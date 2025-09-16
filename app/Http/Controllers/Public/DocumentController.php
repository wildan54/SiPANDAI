<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query()->with(['type', 'unit']);

        // 🔍 Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('document_category_id', $request->category);
            });
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

        // Sorting
        if ($request->filled('sort')) {
            $query->orderBy('upload_date', $request->sort === 'oldest' ? 'asc' : 'desc');
        } else {
            $query->latest('upload_date');
        }

        $documents = $query->paginate(12)->withQueryString();

        $categories = DocumentCategory::all();
        $types      = DocumentType::all();
        $units      = Unit::all();
        $years      = Document::select('year')->distinct()->pluck('year');

        return view('public.home', compact('documents', 'categories', 'types', 'units', 'years'));
    }

    public function show($slug)
    {
        $document = Document::with(['unit', 'type.category'])
            ->where('slug', $slug)
            ->firstOrFail();

        $otherDocuments = Document::where('id', '!=', $document->id)
            ->where('document_type_id', $document->document_type_id)
            ->latest('upload_date')
            ->take(5)
            ->get();

        $sameCategoryTypes = DocumentType::with('category')
            ->where('document_category_id', $document->type->document_category_id)
            ->get();

        return view('public.documents.show', [
            'document' => $document,
            'sameCategoryTypes' => $sameCategoryTypes,
            'otherDocuments' => $otherDocuments
        ]);
    }

    public function download($slug)
    {
        $document = Document::where('slug', $slug)->firstOrFail();

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

    // 🔹 By Type
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

    // 🔹 By Category
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

    // 🔹 By Unit
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