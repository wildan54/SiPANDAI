<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Unit;
use App\Models\DocumentApproval;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\AccessLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
        $user = auth()->user();

        // =========================
        // QUERY DOKUMEN
        // =========================
        $documents = Document::with(['type', 'unit', 'uploader'])

            // 🔐 Editor WAJIB hanya unit sendiri
            ->when($user->role === 'editor', function ($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            })

            // =========================
            // FILTER TIPE (SEMUA ROLE)
            // =========================
            ->when(
                $request->filled('type'),
                fn ($q) => $q->whereHas('type', function ($qq) use ($request) {
                    $qq->where('slug', $request->type);
                })
            )
            


            // =========================
            // FILTER KHUSUS ADMIN
            // =========================
            ->when(
                $user->role === 'administrator' && $request->filled('unit'),
                fn ($q) => $q->whereHas('unit', function ($qq) use ($request) {
                    $qq->where('slug', $request->unit);
                })
            )

            ->when(
                $user->role === 'administrator' && $request->filled('year'),
                fn ($q) => $q->where('year', $request->year)
            )

            // =========================
            // SEARCH
            // =========================
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('title', 'like', "%{$request->q}%")
                    ->orWhere('description', 'like', "%{$request->q}%")
                    ->orWhere('slug', 'like', "%{$request->q}%");
                });
            })

            ->latest('upload_date')
            ->paginate(10);


        // =========================
        // DATA FILTER
        // =========================
        $documentTypes = DocumentType::orderBy('name')->get();
        $units = $user->role === 'administrator'
            ? Unit::orderBy('name')->get()
            : collect();

        $years = $user->role === 'administrator'
            ? Document::whereNotNull('year')->select('year')->distinct()->orderByDesc('year')->pluck('year')
            : collect();

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }



    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $documentTypes  = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        $user = auth()->user();

        AccessLogService::log('view'); // tanpa dokumen

        return view('documents.create', compact('documentTypes', 'units', 'user'));
    }

        /**
     * Store a newly created document in storage (embed / upload).
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // ==========================
        // VALIDATION RULES
        // ==========================
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'file_source' => 'required|in:embed,upload',
            'file_embed'  => 'required_if:file_source,embed|url',
            'file_upload' => 'required_if:file_source,upload|file|mimes:pdf,doc,docx|max:5120',

            'document_type_id' => 'required|integer|exists:document_types,id',
            'slug' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ];

        // unit khusus admin
        if ($user->role === 'administrator') {
            $rules['unit_id'] = 'required|integer|exists:units,id';
        }

        // ==========================
        // VALIDATION MESSAGE
        // ==========================
        $messages = [
            'file_upload.required_if' => 'Silakan upload file dokumen.',
            'file_upload.mimes'       => 'Format file harus PDF, DOC, atau DOCX.',
            'file_upload.max'         => 'Ukuran file maksimal 5 MB.',
            'file_embed.required_if'  => 'Link embed wajib diisi.',
            'file_source.required'    => 'Silakan pilih sumber dokumen.',
        ];

        $validated = $request->validate($rules, $messages);

        // ==========================
        // PAKSA UNIT EDITOR
        // ==========================
        if ($user->role === 'editor') {
            $validated['unit_id'] = $user->unit_id;
        }

        // ==========================
        // TRANSACTION
        // ==========================
        DB::transaction(function () use ($validated, $request, $user) {

            // 1️⃣ SIMPAN DOCUMENT
            $document = Document::create([
                'title'            => $validated['title'],
                'description'      => $validated['description'] ?? null,
                'document_type_id' => $validated['document_type_id'],
                'unit_id'          => $validated['unit_id'],
                'year'             => $validated['year'],

                'slug'             => $validated['slug'] ?? null,
                'meta_title'       => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,

                'file_source' => $validated['file_source'],
                'uploaded_by' => $user->id,
                'upload_date' => now(),
                'status'      => Document::STATUS_DRAFT,
                'visibility'  => Document::VISIBILITY_PRIVATE,
            ]);

            // 2️⃣ FILE HANDLING
            if ($validated['file_source'] === 'upload') {

                $file = $request->file('file_upload');

                $unit = Unit::findOrFail($validated['unit_id']);
                $folder = 'dokumen/bidang-' . Str::slug($unit->name);

                $filename = $document->slug . '.' . $file->extension();

                $path = $file->storeAs($folder, $filename, 'public');

                $document->update([
                    'file_path'  => $path,
                    'file_size'  => $file->getSize(),
                    'file_type'  => $file->extension(),
                    'file_embed' => null,
                ]);

            } else {
                // embed
                $document->update([
                    'file_embed' => $validated['file_embed'],
                    'file_path'  => null,
                    'file_size'  => null,
                    'file_type'  => null,
                ]);
            }

            // 3️⃣ LOG
            AccessLogService::log('upload', $document);
        });

        return redirect()
            ->route('documents.index')
            ->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Form edit dokumen.
     */
    public function edit(Document $document)
    {
        $document->load('uploader');

        $documentTypes = DocumentType::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        AccessLogService::log('view', $document);

        return view('documents.edit', compact('document', 'documentTypes', 'units'));
    }

    /**
     * Update dokumen.
     */
    public function update(Request $request, Document $document)
    {
        // ==========================
        // VALIDATION
        // ==========================
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',

            'file_source' => 'required|in:embed,upload',

            'file_upload' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
                function ($attribute, $value, $fail) use ($request, $document) {
                    if (
                        $request->file_source === 'upload' &&
                        !$document->file_path &&
                        !$request->hasFile('file_upload')
                    ) {
                        $fail('Silakan upload file PDF.');
                    }
                },
            ],

            'file_embed' => [
                'nullable',
                'url',
                function ($attribute, $value, $fail) use ($request, $document) {
                    if (
                        $request->file_source === 'embed' &&
                        !$document->file_embed &&
                        empty($value)
                    ) {
                        $fail('URL dokumen wajib diisi.');
                    }
                },
            ],

            'document_type_id' => 'required|exists:document_types,id',
            'unit_id'          => 'required|exists:units,id',
            'slug'             => 'nullable|string|unique:documents,slug,' . $document->id,
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'year'             => 'required|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        // ==========================
        // SLUG AUTO
        // ==========================
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        // ==========================
        // FILL BASIC DATA (AMAN)
        // ==========================
        $document->fill(
            collect($validated)->except(['file_upload'])->toArray()
        );

        // ==========================
        // FILE HANDLING
        // ==========================
        if ($validated['file_source'] === 'upload') {

            // reset embed
            $document->file_embed = null;

            if ($request->hasFile('file_upload')) {

                // hapus file lama
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                $file = $request->file('file_upload');
                $filename = $validated['slug'] . '.' . $file->extension();

                $unit = Unit::findOrFail($validated['unit_id']);
                $folder = 'dokumen/bidang-' . Str::slug($unit->name);

                $path = $file->storeAs($folder, $filename, 'public');

                $document->file_path = $path;
                $document->file_size = $file->getSize();
                $document->file_type = $file->extension();
            }
        }

        if ($validated['file_source'] === 'embed') {

            // hapus file upload lama
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->file_embed = $validated['file_embed'];
            $document->file_path = null;
            $document->file_size = null;
            $document->file_type = null;
        }

        // ==========================
        // SAVE
        // ==========================
        $document->save();

        AccessLogService::log('update', $document);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }


    public function checkSlug(Request $request)
    {
        $slug = $request->query('slug');
        $id   = $request->query('id'); // opsional, dipakai saat edit

        if (!$slug) {
            return response()->json(['exists' => false]);
        }

        $exists = Document::where('slug', $slug)
            ->when($id, fn($q) => $q->where('id', '!=', $id))
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Show dokumen (detail / preview).
     */
    public function show(Document $document)
    {
        AccessLogService::log('view', $document);

        $document->load(['type.category', 'unit', 'uploader']);

        $embedLink = $document->file_embed;

        if ($embedLink && Str::contains($embedLink, 'drive.google.com')) {
            preg_match('/\/d\/(.*?)\//', $embedLink, $matches);
            if (isset($matches[1])) {
                $embedLink = "https://drive.google.com/file/d/{$matches[1]}/preview";
            }
        }

        $filePath = $document->file_path
            ? asset('storage/' . $document->file_path)
            : null;

        return response()->json([
            'id' => $document->id,
            'slug' => $document->slug,
            'title' => $document->title,

            // sumber dokumen
            'embed_link' => $embedLink,
            'file_path' => $filePath,
            'has_file' => !is_null($document->file_path),

            // meta
            'type' => $document->type,
            'unit' => $document->unit,
            'uploader' => $document->uploader,
            'status' => $document->status,
            'description' => $document->description,
            'upload_date_year' => $document->year ?? '-',
            'upload_date_formatted' => optional($document->created_at)->format('d/m/Y'),
        ]);
    }


    public function view($slug)
    {
        $doc = Document::where('slug', $slug)->firstOrFail();

        $path = storage_path('app/public/' . $doc->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->title.'.pdf"',
        ]);
    }



    /**
     * Hapus dokumen.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        AccessLogService::log('delete', $document);

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Document $document)
    {
        // OPSIONAL: policy
        // $this->authorize('download', $document);

        // ===== EMBED LINK (CLOUD) =====
        if ($document->source_type === 'embed') {
            return redirect()->away($document->embed_url);
        }

        // ===== UPLOAD LANGSUNG (LOCAL STORAGE) =====
        if ($document->source_type === 'upload') {

            if (!$document->file_path || !Storage::exists($document->file_path)) {
                abort(404, 'File tidak ditemukan di server');
            }

            return Storage::download(
                $document->file_path,
                $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION)
            );
        }

        abort(400, 'Sumber dokumen tidak valid');
    }

    /**
     * Submit dokumen (Role EDITOR)
     */
    public function submit(Document $document)
    {
        // VALIDASI WAJIB (PALING ATAS)
        abort_unless(auth()->user()->role === 'editor', 403);
        abort_unless(in_array($document->status, ['draft', 'rejected']), 403);

        AccessLogService::log('submit', $document);

        // Update status dokumen
        $document->update([
            'status' => 'submitted',
        ]);

        // Simpan riwayat approval
        DocumentApproval::create([
            'document_id' => $document->id,
            'reviewed_by' => auth()->id(),
            'status' => 'submitted',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Dokumen berhasil disubmit.');
    }


    public function bulkSubmit(Request $request)
    {
        abort_unless(Auth::user()->role === 'editor', 403);

        $ids = $request->document_ids;

        if (!$ids) {
            return back()->with('error', 'Pilih minimal satu dokumen.');
        }

        $documents = Document::whereIn('id', $ids)
            ->whereIn('status', ['draft', 'rejected'])
            ->get();

        foreach ($documents as $document) {

            // update status
            $document->update([
                'status' => 'submitted',
            ]);

            // access log (PER DOKUMEN)
            AccessLogService::log('submit', $document);

            // approval history (konsisten dengan submit single)
            DocumentApproval::create([
                'document_id' => $document->id,
                'reviewed_by' => auth()->id(),
                'status'      => 'submitted',
                'reviewed_at' => now(),
            ]);
        }

        return back()->with('success', "{$documents->count()} dokumen berhasil disubmit.");
    }


    /**
     * Approve dokumen (Role ADMIN)
     */
    public function approve(Document $document)
    {
        // VALIDASI
        abort_unless(auth()->user()->role === 'administrator', 403);
        abort_unless($document->status === 'submitted', 403);

        AccessLogService::log('approve', $document);

        // Update dokumen
        $document->update([
            'status' => 'approved',
            'visibility' => 'public',
        ]);

        // Riwayat approval
        DocumentApproval::create([
            'document_id' => $document->id,
            'reviewed_by' => auth()->id(),
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function bulkApprove(Request $request)
    {
        abort_unless(auth()->user()->role === 'administrator', 403);

        $request->validate([
            'document_ids'   => 'required|array|min:1',
            'document_ids.*' => 'exists:documents,id',
        ]);

        $documents = Document::whereIn('id', $request->document_ids)
            ->where('status', 'submitted')
            ->get();

        foreach ($documents as $document) {

            $document->update([
                'status'     => 'approved',
                'visibility' => 'public',
            ]);

            AccessLogService::log('approve', $document);

            DocumentApproval::create([
                'document_id' => $document->id,
                'reviewed_by' => auth()->id(),
                'status'      => 'approved',
                'reviewed_at' => now(),
            ]);
        }

        $total = count($request->document_ids);
        $approved = $documents->count();

        return back()->with(
            'success',
            "$approved dari $total dokumen berhasil di-approve."
        );
    }


    /**
     * Reject dokumen (ADMIN)
     */
    public function reject(Document $document)
    {
        // VALIDASI
        abort_unless(auth()->user()->role === 'administrator', 403);
        abort_unless($document->status === 'submitted', 403);

        AccessLogService::log('reject', $document);

        // Update dokumen
        $document->update([
            'status' => 'rejected',
            'visibility' => 'private',
        ]);

        // Riwayat approval
        DocumentApproval::create([
            'document_id' => $document->id,
            'reviewed_by' => auth()->id(),
            'status' => 'rejected',
            'note' => request('note'),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Dokumen berhasil ditolak.');
    }

    public function bulkReject(Request $request)
    {
        abort_unless(Auth::user()->role === 'administrator', 403);

        $ids = $request->document_ids;

        if (!$ids) {
            return back()->with('error', 'Pilih minimal satu dokumen.');
        }

        $documents = Document::whereIn('id', $ids)
            ->where('status', 'submitted')
            ->get();

        foreach ($documents as $document) {

            $document->update([
                'status'     => 'rejected',
                'visibility' => 'private',
            ]);

            AccessLogService::log('reject', $document);

            DocumentApproval::create([
                'document_id' => $document->id,
                'reviewed_by' => auth()->id(),
                'status'      => 'rejected',
                'note'        => $request->note, // opsional massal
                'reviewed_at' => now(),
            ]);
        }

        return back()->with('success', "{$documents->count()} dokumen berhasil direject.");
    }

    public function approvedDocuments(Request $request)
    {
        AccessLogService::log('view_approved_documents', null);

        $documentTypes  = DocumentType::orderBy('name')->get();
        $user = auth()->user();
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->where('status', 'approved')
            // 🔐 Batasi editor hanya unit sendiri
            ->when(auth()->user()->role === 'editor', function ($q) {
                $q->where('unit_id', auth()->user()->unit_id);
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


        return view('documents.index', compact('documents','documentTypes', 'units', 'years'));
    }

    public function rejectedDocuments(Request $request)
    {
        AccessLogService::log('view_rejected_documents', null);

        $documentTypes  = DocumentType::orderBy('name')->get();
        $user = auth()->user();
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->where('status', 'rejected')
            // 🔐 Batasi editor hanya unit sendiri
            ->when(auth()->user()->role === 'editor', function ($q) {
                $q->where('unit_id', auth()->user()->unit_id);
            })

            ->when(auth()->user()->role === 'editor', function ($q) {
            $q->where('unit_id', auth()->user()->unit_id);
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

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }

    public function draftDocuments(Request $request)
    {
        AccessLogService::log('view_draft_documents', null);

        $documentTypes  = DocumentType::orderBy('name')->get();
        $user = auth()->user();
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->where('status', 'draft')
            // 🔐 Batasi editor hanya unit sendiri
            ->when(auth()->user()->role === 'editor', function ($q) {
                $q->where('unit_id', auth()->user()->unit_id);
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

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }

    public function submittedDocuments(Request $request)
    {
        AccessLogService::log('view_submitted_documents', null);

        $documentTypes  = DocumentType::orderBy('name')->get();
        $user = auth()->user();
        $documents = Document::with(['type', 'unit', 'uploader'])
            ->where('status', 'submitted')
            // 🔐 Batasi editor hanya unit sendiri
            ->when(auth()->user()->role === 'editor', function ($q) {
                $q->where('unit_id', auth()->user()->unit_id);
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

        return view('documents.index', compact('documents', 'documentTypes', 'units', 'years'));
    }
}