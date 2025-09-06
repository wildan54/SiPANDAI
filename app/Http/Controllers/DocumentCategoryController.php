<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentCategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori dokumen.
     */
    public function index()
    {
        $categories = DocumentCategory::withCount('documents')->latest()->get();
        return view('documents.categories', compact('categories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:document_categories,name',
            'slug'        => 'nullable|string|max:255|unique:document_categories,slug',
            'description' => 'nullable|string',
        ]);

        DocumentCategory::create([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => $request->slug ?? Str::slug($request->name),
        ]);

        return redirect()->route('documents.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(DocumentCategory $kategori)
    {
        return view('documents.categories', compact('kategori'));
    }

    /**
     * Update kategori.
     */
    public function update(Request $request, DocumentCategory $kategori)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:document_categories,name,' . $kategori->id,
            'slug'        => 'nullable|string|max:255|unique:document_categories,slug,' . $kategori->id,
            'description' => 'nullable|string',
        ]);

        $kategori->update([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => $request->slug ?? Str::slug($request->name),
        ]);

        return redirect()->route('documents.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(DocumentCategory $kategori)
    {
        $kategori->delete();
        return redirect()->route('documents.categories')->with('success', 'Kategori berhasil dihapus.');
    }
}