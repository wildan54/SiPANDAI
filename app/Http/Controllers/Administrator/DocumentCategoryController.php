<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
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
        return view('documents.categories.index', compact('categories'));
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

        return redirect()
            ->route('documents.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update kategori (pakai modal edit).
     */
    public function update(Request $request, DocumentCategory $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:document_categories,name,' . $category->id,
            'slug'        => 'nullable|string|max:255|unique:document_categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'slug'        => $request->slug ?? Str::slug($request->name),
        ]);

        return redirect()
            ->route('documents.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(DocumentCategory $category)
    {
        $category->delete();

        return redirect()
            ->route('documents.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}