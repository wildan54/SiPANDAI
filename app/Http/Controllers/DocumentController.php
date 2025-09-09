<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\AccessLogService;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar dokumen (index).
     */
    public function index(Request $request)
    {
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->when($request->document_type_id, fn ($q, $v) => $q->where('document_type_id', $v))
            ->when($request->unit_id, fn ($q, $v) => $q->where('unit_id', $v))
            ->when($request->year, fn ($q, $v) => $q->where('year', $v))
            ->when($request->q, function ($q, $v) {
                $q->where(function ($qq) use ($v) {
                    $qq->where('title', 'like', "%{$v}%")
                       ->orWhere('description', 'like', "%{$v}%")
                       ->orWhere('slug', 'like', "%{$v}%");
                });
            })
            ->latest('upload_date')
            ->paginate(10)
            ->withQueryString();

        $documentTypes  = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $years = Document::whereNotNull('year')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        AccessLogService::log('view');

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $documentTypes  = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        AccessLogService::log('view');

        return view('documents.create', compact('documentTypes', 'units'));
    }

    /**
     * Store a newly created document in storage (embed-only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_source' => 'required|in:embed',
            'file_embed' => 'required|url',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'unit_id' => 'required|integer|exists:units,id',
            'slug' => 'nullable|string|unique:documents,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1), // ✅ validasi year
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['uploaded_by'] = Auth::id();
        $validated['upload_date'] = now();
        $validated['updated_at'] = now();

        $document = Document::create($validated);

        AccessLogService::log('upload', $document->id);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Form edit dokumen.
     */
    public function edit(Document $document)
    {
        $document->load('uploader');

        $documentTypes = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        AccessLogService::log('view', $document->id);

        return view('documents.edit', compact('document', 'documentTypes', 'units'));
    }

    /**
     * Update dokumen.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_source' => 'required|in:embed',
            'file_embed' => 'required|url',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'unit_id' => 'required|integer|exists:units,id',
            'slug' => 'nullable|string|unique:documents,slug,' . $document->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['updated_at'] = now();

        $document->update($validated);

        AccessLogService::log('update', $document->id);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Show dokumen (detail / preview).
     */
    public function show(Document $document)
    {
        AccessLogService::log('view', $document->id);

        if (request()->ajax()) {
            $embedLink = $document->file_embed;
            if (Str::contains($embedLink, 'drive.google.com')) {
                preg_match('/\/d\/(.*?)\//', $embedLink, $matches);
                if (isset($matches[1])) {
                    $fileId = $matches[1];
                    $embedLink = "https://drive.google.com/file/d/{$fileId}/preview";
                }
            }

            return response()->json([
                'id' => $document->id,
                'title' => $document->title,
                'embed_link' => $embedLink,
                'type' => $document->type,
                'unit' => $document->unit,
                'upload_date_year' => $document->year ?? '-',
                'upload_date_formatted' => $document->upload_date ? $document->upload_date->format('d/m/Y') : '-',
                'description' => $document->description,
            ]);
        }

        return view('documents.show', compact('document'));
    }

    /**
     * Hapus dokumen.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        AccessLogService::log('delete', $document->id);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}