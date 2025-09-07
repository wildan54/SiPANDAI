<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::with('category')->latest()->get();
        $categories = DocumentCategory::all();

        return view('documents.type.types', compact('types', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:document_types,name',
            'slug' => 'nullable|string|max:255|unique:document_types,slug',
            'description' => 'nullable|string',
            'document_category_id' => 'nullable|exists:document_categories,id',
        ]);

        // jika slug kosong, buat otomatis dari name
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        DocumentType::create($validated);

        return redirect()->route('documents.type.types')
                         ->with('success', 'Tipe dokumen berhasil ditambahkan.');
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:document_types,name,' . $documentType->id,
            'slug' => 'nullable|string|max:255|unique:document_types,slug,' . $documentType->id,
            'description' => 'nullable|string',
            'document_category_id' => 'nullable|exists:document_categories,id',
        ]);

        // jika slug kosong, buat otomatis dari name
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $documentType->update($validated);

        return redirect()->route('documents.type.types')
                         ->with('success', 'Tipe dokumen berhasil diperbarui.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('documents.type.types')
                         ->with('success', 'Tipe dokumen berhasil dihapus.');
    }
}