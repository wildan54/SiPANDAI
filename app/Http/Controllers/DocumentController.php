<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Constructor: pastikan semua method hanya bisa diakses user login
     */

        public function __construct()
    {
        $this->middleware('auth'); // ini sekarang akan bekerja
    }

    /**
     * Tampilkan daftar dokumen (index).
     */
    public function index(Request $request)
    {
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->when($request->document_type_id, fn ($q, $v) => $q->where('document_type_id', $v))
            ->when($request->unit_id, fn ($q, $v) => $q->where('unit_id', $v))
            ->when($request->year, fn ($q, $v) => $q->whereYear('upload_date', $v))
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
        $years = Document::whereNotNull('upload_date')
            ->selectRaw('YEAR(upload_date) as year')
            ->distinct()
            ->orderBy('year','desc')
            ->pluck('year');

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $documentTypes  = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

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
        ]);

        // Generate slug otomatis jika tidak diisi
        $validated['slug'] = $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['title']);

        // Set uploaded_by ke user login
        $validated['uploaded_by'] = \Illuminate\Support\Facades\Auth::id();

        // Set tanggal upload & updated
        $validated['upload_date'] = now();
        $validated['updated_at'] = now();

        // Simpan dokumen
        Document::create($validated);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil disimpan.');
    }

    // public function show($id)
    // {
    //     $document = Document::with(['type', 'unit', 'uploader'])->findOrFail($id);

    //     return view('documents.show', compact('document'));
    // }
    public function show(Document $document)
    {
        if (request()->ajax()) {
            // bikin link embed
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
                'upload_date_year' => $document->upload_date ? $document->upload_date->format('Y') : '-',
                'upload_date_formatted' => $document->upload_date ? $document->upload_date->format('d/m/Y') : '-',
                'description' => $document->description,
            ]);
        }

        return view('documents.show', compact('document'));
    }
    

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }


}
