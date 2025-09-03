<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Tampilkan form create dokumen
     */
    public function create()
    {
        $types = DocumentType::all();
        $units = Unit::all();

        return view('dokumen.create', compact('types', 'units'));
    }

    /**
     * Simpan dokumen baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|mimes:pdf,doc,docx,xlsx,ppt,pptx,jpg,png|max:5120',
            'id_type'     => 'required|integer|exists:document_types,id_type',
            'id_unit'     => 'required|integer|exists:units,id_unit',
            'year'        => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        ]);

        // Simpan file
        $path = $request->file('file')->store('documents', 'public');
        $fileSize = $request->file('file')->getSize();
        $fileType = $request->file('file')->getClientOriginalExtension();

        // Simpan data ke DB
        Document::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'file_path'        => $path,
            'file_size'        => $fileSize,
            'file_type'        => $fileType,
            'id_type'          => $request->id_type,
            'id_unit'          => $request->id_unit,
            'year'             => $request->year,
            'upload_date'      => now(),
            'uploaded_by'      => Auth::id(),
            'slug'             => \Str::slug($request->title),
            'meta_title'       => $request->meta_title ?? $request->title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil ditambahkan');
    }
}