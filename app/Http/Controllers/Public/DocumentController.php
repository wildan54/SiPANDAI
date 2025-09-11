<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        // Query awal
        $query = Document::query();

        // Filter kategori / type
        if ($request->filled('category')) {
            $query->where('type_id', $request->category);
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

        // Untuk select filter
        $types = DocumentType::all();
        $units = Unit::all();
        $years = Document::select('year')->distinct()->pluck('year');

        return view('public.home', compact('documents', 'types', 'units', 'years'));
    }

    public function show($slug)
    {
        $document = Document::where('slug', $slug)->firstOrFail();

        return view('public.documents.show', compact('document'));
    }
}