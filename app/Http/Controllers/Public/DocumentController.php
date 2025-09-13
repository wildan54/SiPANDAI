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
        // Query awal
        $query = Document::query()->with(['type', 'unit']);

        // Filter kategori (lewat join document_types)
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
            if ($request->sort == 'latest') {
                $query->orderBy('upload_date', 'desc');
            } else {
                $query->orderBy('upload_date', 'asc');
            }
        } else {
            $query->latest('upload_date');
        }

        // Ambil data dengan pagination
        $documents = $query->paginate(6)->withQueryString();

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
        $sameCategoryTypes = \App\Models\DocumentType::with('category')
            ->where('document_category_id', $document->type->document_category_id)
            ->get();

        return view('public.documents.show', [
            'document' => $document,
            'sameCategoryTypes' => $sameCategoryTypes,
            'otherDocuments' => $otherDocuments
        ]);
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


