<?php

namespace App\Http\Controllers\Publik;

use App\Models\Unit;
use App\Models\Document;
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

        return view('public.home', compact(
            'documents', 'categories', 'types', 'units', 'years', 'activeFilters'
        ));
    }

    public function landingPage()
    {
        // Data dummy untuk testing tampilan
        $total_documents = 1250;
        $total_units = 42;
        $total_downloads = "8.2k";

        // Mockup Kategori
        $featured_categories = collect([
            (object)['name' => 'Informasi Berkala', 'slug' => 'berkala', 'documents_count' => 150],
            (object)['name' => 'Informasi Serta Merta', 'slug' => 'serta-merta', 'documents_count' => 45],
            (object)['name' => 'Tersedia Setiap Saat', 'slug' => 'setiap-saat', 'documents_count' => 320],
            (object)['name' => 'Regulasi & Hukum', 'slug' => 'regulasi', 'documents_count' => 88],
        ]);

        // Mockup Dokumen Terbaru
        $latest_documents = collect([
            (object)[
                'title' => 'Laporan Realisasi Anggaran Triwulan III Tahun 2025',
                'slug' => 'laporan-anggaran-q3-2025',
                'created_at' => now()->subHours(2),
                'unit' => (object)['name' => 'Sekretariat']
            ],
            (object)[
                'title' => 'Rencana Strategis (Renstra) Instansi Periode 2024-2029',
                'slug' => 'renstra-2024-2029',
                'created_at' => now()->subDay(),
                'unit' => (object)['name' => 'Perencanaan']
            ],
            (object)[
                'title' => 'SOP Standar Pelayanan Informasi Publik v.2.0',
                'slug' => 'sop-ppid-2025',
                'created_at' => now()->subDays(3),
                'unit' => (object)['name' => 'Humas']
            ],
        ]);

        return view('public.landing-page', compact(
            'total_documents', 
            'total_units', 
            'total_downloads', 
            'featured_categories', 
            'latest_documents'
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