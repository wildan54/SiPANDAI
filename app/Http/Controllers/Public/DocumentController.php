<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\AccessLogService;

class DocumentController extends Controller
{
    /**
     * Tampilkan daftar dokumen (index)
     */
 public function index(Request $request)
{
    // Log guest atau user melihat daftar dokumen
    AccessLogService::log('view');

    $query = Document::query()->with(['type', 'unit']);

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
    if ($request->filled('sort')) {
        $query->orderBy('year', $request->sort === 'oldest' ? 'asc' : 'desc');
    } else {
        $query->latest('year');
    }

    // Pagination hasil
    $documents = $query->paginate(12)->withQueryString();

    return view('public.home', compact(
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

        // Log guest atau user melihat dokumen
        AccessLogService::log('view', $document);

        $otherDocuments = Document::where('id', '!=', $document->id)
            ->where('document_type_id', $document->document_type_id)
            ->orderBy('year', 'asc') // urut berdasarkan kolom year
            ->take(4)
            ->get();

        $sameCategoryTypes = DocumentType::with('category')
            ->where('document_category_id', $document->type->document_category_id)
            ->orderBy('name', 'asc')
            ->get();

        return view('public.documents.show', [
            'document' => $document,
            'sameCategoryTypes' => $sameCategoryTypes,
            'otherDocuments' => $otherDocuments
        ]);
    }

    /**
     * Download dokumen
     */
    public function download($slug)
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
            'contextLabel' => 'Unit',
            'contextName'  => $unit->name,
        ]);
    }
}