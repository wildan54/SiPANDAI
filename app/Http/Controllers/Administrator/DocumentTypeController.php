<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\DocumentCategory;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::with('category')
                    ->withCount('documents')
                    ->latest()
                    ->get();

        $categories = DocumentCategory::withCount('documents')
                        ->latest()
                        ->get();

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

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        DocumentType::create($validated);

        return redirect()->route('documents.types.index')
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

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $documentType->update($validated);

        return redirect()->route('documents.types.index')
                         ->with('success', 'Tipe dokumen berhasil diperbarui.');
    }

    public function destroy(Request $request, DocumentType $documentType)
    {
        $action = $request->input('action');
        $targetTypeId = $request->input('target_id');

        if ($action === 'delete') {
            $documentType->documents()->delete();
            $documentType->delete();

            return redirect()->route('documents.types.index')
                ->with('success', 'Tipe dokumen dan semua dokumennya berhasil dihapus.');
        }

        if ($action === 'move' && $targetTypeId) {
            Document::where('document_type_id', $documentType->id)
                    ->update(['document_type_id' => $targetTypeId]);

            $documentType->delete();

            return redirect()->route('documents.types.index')
                ->with('success', 'Tipe dokumen berhasil dihapus dan semua dokumen dipindahkan ke tipe lain.');
        }

        return back()->with('error', 'Anda harus memilih aksi yang valid (hapus semua atau pindah ke tipe lain).');
    }
}