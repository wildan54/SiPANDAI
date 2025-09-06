<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::with('category')->latest()->get();
        $categories = DocumentCategory::all();

        return view('documents.types', compact('types', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:document_types,name',
            'slug' => 'required|string|max:255|unique:document_types,slug',
            'description' => 'nullable|string',
            'document_category_id' => 'nullable|exists:document_categories,id',
        ]);

        DocumentType::create($validated);

        return redirect()->route('documents.types')
                         ->with('success', 'Tipe dokumen berhasil ditambahkan.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('documents.types')
                         ->with('success', 'Tipe dokumen berhasil dihapus.');
    }
}
