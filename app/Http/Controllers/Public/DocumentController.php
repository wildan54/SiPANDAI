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
        // Query awal + relasi
        $query = Document::query()->with(['type', 'unit']);

        // 🔍 Search (judul, keywords, deskripsi)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter kategori (via relasi type → category)
        if ($request->filled('category')) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('document_category_id', $request->category);
            });
        }

        // Filter tipe dokumen
        if ($request->filled('type')) {
            $query->where('document_type_id', $request->type);
        }

        // Filter unit / bidang
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

        // Ambil data dengan pagination
        $documents = $query->paginate(10)->withQueryString();

        // Data untuk filter
        $categories = DocumentCategory::all();
        $types      = DocumentType::all();
        $units      = Unit::all();
        $years      = Document::select('year')->distinct()->pluck('year');

        return view('public.home', compact('documents', 'categories', 'types', 'units', 'years'));
    }

    public function show($slug)
    {
        // Ambil dokumen utama beserta relasi unit, type, dan category
        $document = Document::with(['unit', 'type.category'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Ambil dokumen lain dengan tipe yang sama (Quick Access, max 5)
        $otherDocuments = Document::where('id', '!=', $document->id)
            ->where('document_type_id', $document->document_type_id)
            ->latest('upload_date')
            ->take(5)
            ->get();

        // Ambil semua dokumen dalam kategori yang sama
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
        // Cari dokumen berdasarkan slug
        $document = Document::where('slug', $slug)->firstOrFail();

        // Pastikan ada link file
        if (!$document->file_embed) {
            return back()->with('error', 'Link dokumen tidak tersedia.');
        }

        // Jika link Google Drive → ubah ke link download langsung
        if (str_contains($document->file_embed, 'drive.google.com')) {
            if (preg_match('/\/d\/(.*?)\//', $document->file_embed, $match)) {
                $fileId = $match[1];
                $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                return redirect()->away($downloadUrl);
            }
        }

        // Jika link Nextcloud → tambahkan /download kalau belum ada
        if (str_contains($document->file_embed, 'nextcloud')) {
            $downloadUrl = rtrim($document->file_embed, '/') . '/download';
            return redirect()->away($downloadUrl);
        }

        // Default → langsung redirect ke link yang disimpan
        return redirect()->away($document->file_embed);
    }


    public function types($typeslug)
    {
        // Cari tipe dokumen berdasarkan slug
        $type = DocumentType::where('slug', $typeslug)->firstOrFail();

        // Ambil semua dokumen dengan tipe tersebut
        $documents = Document::with(['unit', 'type.category'])
            ->where('document_type_id', $type->id)
            ->latest('upload_date')
            ->paginate(10);

        return view('public.documents.by-type', compact('type', 'documents'));
    }
}