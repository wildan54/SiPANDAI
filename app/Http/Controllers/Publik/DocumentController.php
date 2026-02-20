<?php

namespace App\Http\Controllers\Publik;

use App\Models\Unit;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Models\DocumentCategory;
use App\Services\AccessLogService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Tampilkan daftar dokumen (index)
     */
    public function index(Request $request)
    {
        // Log guest atau user melihat daftar dokumen
        AccessLogService::log('view');

        $query = Document::public()->with(['type', 'unit']);

        $from = $request->get('from'); // hero | null

        // 🔍 Pencarian keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Ambil semua data dropdown
        $categories = DocumentCategory::orderBy('name', 'asc')->get();
        $types      = DocumentType::orderBy('name', 'asc')->get();
        $units      = Unit::orderBy('name', 'asc')->get();
        $years      = Document::select('year')->distinct()->orderBy('year', 'asc')->pluck('year');

        $activeFilters = [];

        // Filter kategori berdasarkan slug
        if ($request->filled('category')) {
            $category = DocumentCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->whereHas('type', function ($q) use ($category) {
                    $q->where('document_category_id', $category->id);
                });
                $types = DocumentType::where('document_category_id', $category->id)
                    ->orderBy('name', 'asc')
                    ->get();
                $activeFilters['Kategori'] = $category->name;
            }
        }

        // Filter tipe berdasarkan slug
        if ($request->filled('type')) {
            $type = DocumentType::where('slug', $request->type)->first();
            if ($type) {
                $query->where('document_type_id', $type->id);
                $activeFilters['Tipe'] = $type->name;
            }
        }

        // Filter unit berdasarkan slug
        if ($request->filled('unit')) {
            $unit = Unit::where('slug', $request->unit)->first();
            if ($unit) {
                $query->where('unit_id', $unit->id);
                $activeFilters['Unit'] = $unit->name;
            }
        }

        // Filter tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
            $activeFilters['Tahun'] = $request->year;
        }

        // Urutan sort
        // Urutan sort (TAHUN → HURUF)
        if ($request->filled('sort')) {
            $yearOrder = $request->sort === 'oldest' ? 'asc' : 'desc';
        } else {
            $yearOrder = 'desc'; // default terbaru
        }

        $query->orderBy('year', $yearOrder)
            ->orderBy('title', 'asc'); // urut huruf jika tahun sama


        // Pagination hasil
        $documents = $query->paginate(12)->withQueryString();

        return view('public.documents', compact(
            'documents', 'categories', 'types', 'units', 'years', 'activeFilters'
        ));
    }


    /**
     * Detail dokumen
     */
    public function show($slug)
    {
        $document = Document::with(['unit', 'type.category'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Log view
        AccessLogService::log('view', $document);

        $otherDocuments = Document::where('id', '!=', $document->id)
            ->where('document_type_id', $document->document_type_id)
            ->orderBy('year', 'asc')
            ->take(4)
            ->get();

        $sameCategoryTypes = DocumentType::with('category')
            ->where('document_category_id', $document->type->document_category_id)
            ->orderBy('name', 'asc')
            ->get();
        

        return view('public.documents.show', [
            'document'          => $document,
            'otherDocuments'    => $otherDocuments,
            'sameCategoryTypes' => $sameCategoryTypes,

            // preview flags
            'has_local_file' => !empty($document->file_path),
            'embed_link'     => $document->file_embed,
        ]);
    }

    public function preview($slug)
    {
        $document = Document::where('slug', $slug)->firstOrFail();

        if (!$document->file_path) {
            abort(404, 'File tidak tersedia');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $path = storage_path('app/public/' . $document->file_path);

        return response()->make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->title.'.pdf"',
            'X-Frame-Options' => 'ALLOWALL',
            'Content-Length' => filesize($path),
            'Accept-Ranges' => 'bytes',
        ]);
    }


    /**
     * Download dokumen
     */
    public function downloadEmbed($slug)
    {
        $document = Document::where('slug', $slug)->firstOrFail();

        // Log guest atau user download
        AccessLogService::log('download', $document);

        if (!$document->file_embed) {
            return back()->with('error', 'Link dokumen tidak tersedia.');
        }

        // Google Drive
        if (str_contains($document->file_embed, 'drive.google.com')) {
            if (preg_match('/\/d\/(.*?)\//', $document->file_embed, $match)) {
                $fileId = $match[1];
                $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                return redirect()->away($downloadUrl);
            }
        }

        // Nextcloud
        if (str_contains($document->file_embed, 'nextcloud')) {
            $downloadUrl = rtrim($document->file_embed, '/') . '/download';
            return redirect()->away($downloadUrl);
        }

        // Default
        return redirect()->away($document->file_embed);
    }

    public function downloadFileUpload(string $slug)
{
    $document = Document::where('slug', $slug)->firstOrFail();

    // Log download
    AccessLogService::log('download', $document);

    if (!$document->file_path) {
        abort(404, 'File upload tidak tersedia');
    }

    if (!Storage::disk('public')->exists($document->file_path)) {
        abort(404, 'File tidak ditemukan di storage');
    }

    return Storage::disk('public')->download(
        $document->file_path,
        $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION)
    );
}

    /**
     * Dokumen berdasarkan tipe
     */
    public function types($slug)
    {
        $type = DocumentType::where('slug', $slug)->firstOrFail();

        $documents = Document::with(['unit', 'type.category'])
            ->where('document_type_id', $type->id)
            ->latest('upload_date')
            ->paginate(10);

        return view('public.documents.index', [
            'documents'    => $documents,
            'contextLabel' => 'Tipe',
            'contextName'  => $type->name,
        ]);
    }

    /**
     * Dokumen berdasarkan kategori
     */
    public function categories($slug)
    {
        $category = DocumentCategory::where('slug', $slug)->firstOrFail();

        $documents = Document::with(['unit', 'type.category'])
            ->whereHas('type', function ($q) use ($category) {
                $q->where('document_category_id', $category->id);
            })
            ->latest('upload_date')
            ->paginate(10);

        return view('public.documents.index', [
            'documents'    => $documents,
            'contextLabel' => 'Kategori',
            'contextName'  => $category->name,
        ]);
    }

    /**
     * Dokumen berdasarkan unit
     */
    public function units($slug)
    {
        $unit = Unit::where('slug', $slug)->firstOrFail();

        $documents = Document::with(['unit', 'type.category'])
            ->where('unit_id', $unit->id)
            ->latest('upload_date')
            ->paginate(10);

        return view('public.documents.index', [
            'documents'    => $documents,
            'contextLabel' => ' ',
            'contextName'  => $unit->name,
        ]);
    }
}