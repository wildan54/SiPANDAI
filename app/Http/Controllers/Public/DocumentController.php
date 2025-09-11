<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index()
    {
        // ambil semua dokumen untuk publik
        $documents = Document::latest()->paginate(6);

        return view('public.home', compact('documents'));
    }

    public function show($slug)
    {
        $document = Document::where('slug', $slug)->firstOrFail();

        return view('public.documents.show', compact('document'));
    }
}