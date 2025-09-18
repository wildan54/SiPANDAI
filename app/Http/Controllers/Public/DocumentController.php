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

        // Filter kategori
        if ($request->filled('category')) {
            $validTypes = DocumentType::where('document_category_id', $request->category)
                ->pluck('id')
                ->toArray();

            // Reset type jika tidak sesuai dengan kategori terpilih
            if ($request->filled('type') && !in_array($request->type, $validTypes)) {
                $request->merge(['type' => null]);
            }

            $query->whereHas('type', function ($q) use ($request) {
                $q->where('document_category_id', $request->category);
            });

            // Dropdown type hanya milik kategori ini
            $types = DocumentType::where('document_category_id', $request->category)
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $types = DocumentType::orderBy('name', 'asc')->get();
        }

        // Filter tipe
        if ($request->filled('type')) {
            $query->where('document_type_id', $request->type);
        }

        // Filter unit
        if ($request->filled('unit')) {
            $query->where('unit_id', $request->unit);
        }

        // Filter tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Urutan sort
        if ($request->filled('sort')) {
            $query->orderBy('year', $request->sort === 'oldest' ? 'asc' : 'desc');
        } else {
            $query->latest('year');
        }

        // Pagination hasil
        $documents = $query->paginate(12)->withQueryString();

        // Data untuk dropdown
        $categories = DocumentCategory::orderBy('name', 'asc')->get();
        $units      = Unit::orderBy('name', 'asc')->get();
        $years      = Document::select('year')->distinct()->orderBy('year', 'asc')->pluck('year');

        return view('public.home', compact('documents', 'categories', 'types', 'units', 'years'));
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