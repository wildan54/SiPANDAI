@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Dokumen</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Edit Dokumen</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Form Edit Dokumen</h3></div>
                <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        {{-- Judul --}}
                        <div class="form-group">
                            <label for="title">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" 
                                   value="{{ old('title', $document->title) }}" 
                                   placeholder="Masukkan judul dokumen" required maxlength="255">
                            <small id="title-feedback" class="form-text"></small>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Masukkan deskripsi">{{ old('description', $document->description) }}</textarea>
                        </div>

                        {{-- Sumber Dokumen --}}
                        <fieldset class="form-group">
                            <label><span>Sumber Dokumen <span class="text-danger">*</span></span></label><br>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="radio"
                                    name="file_source"
                                    id="source_upload"
                                    value="upload"
                                    {{ old('file_source', $document->file_source) === 'upload' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_upload">Upload File</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                    type="radio"
                                    name="file_source"
                                    id="source_embed"
                                    value="embed"
                                    {{ old('file_source', $document->file_source) === 'embed' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_embed">Link / Embed</label>
                            </div>

                            @error('file_source')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>


                        {{-- Upload --}}
                        <div class="form-group"
                            id="upload-wrapper"
                            style="{{ old('file_source', $document->file_source) === 'upload' ? '' : 'display:none;' }}">

                            <label>
                                File saat ini:
                                    <a href="{{ asset('storage/'.$document->file_path) }}" target="_blank">
                                        {{ basename($document->file_path) }}
                                    </a>
                            </label>

                            <input type="file"
                                id="file_upload"
                                name="file_upload"
                                class="form-control @error('file_upload') is-invalid @enderror"
                                accept="application/pdf">

                            {{-- ERROR UPLOAD --}}
                            @error('file_upload')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                            @if($document->file_path)
                                <small class="form-text text-muted mt-1">
                                Format diperbolehkan: PDF (maks 5MB)
                                </small>
                            @endif
                        </div>


                        {{-- Embed --}}
                        <div class="form-group"
                            id="embed-wrapper"
                            style="{{ old('file_source', $document->file_source) === 'embed' ? '' : 'display:none;' }}">

                            <label for="file_embed">Link / Embed File</label>

                            <input type="url"
                                id="file_embed"
                                name="file_embed"
                                class="form-control @error('file_embed') is-invalid @enderror"
                                value="{{ old('file_embed', $document->file_embed) }}"
                                placeholder="https://...">

                            @error('file_embed')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Tipe Dokumen --}}
                        <div class="form-group">
                            <label for="document_type_id">Tipe Dokumen <span class="text-danger">*</span></label>
                            <select id="document_type_id" name="document_type_id" 
                                    class="form-control @error('document_type_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe Dokumen --</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('document_type_id', $document->document_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Unit --}}
                        <div class="form-group">
                            <label for="unit_id">Unit <span class="text-danger">*</span></label>

                            @if(auth()->user()->role === 'editor')
                                {{-- Editor: unit otomatis & terkunci --}}

                                <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">

                                <input type="text"
                                    class="form-control"
                                    value="{{ optional($units->firstWhere('id', auth()->user()->unit_id))->name }}"
                                    readonly>
                            @else
                                {{-- Administrator: pilih manual --}}
                                <select id="unit_id"
                                        name="unit_id"
                                        class="form-control @error('unit_id') is-invalid @enderror"
                                        required>

                                    <option value="">-- Pilih Unit --</option>

                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $document->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        {{-- Tahun --}}
                        <div class="form-group">
                            <label for="year">Tahun <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('year') is-invalid @enderror" 
                                   id="year" name="year" 
                                   value="{{ old('year', $document->year) }}" 
                                   placeholder="contoh: 2025" required min="1900" max="{{ date('Y')+1 }}">
                        </div>

                        {{-- Slug --}}
                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" 
                                   value="{{ old('slug', $document->slug) }}" 
                                   placeholder="contoh: surat-keputusan-2025" maxlength="255">
                            <small id="slug-feedback" class="text-danger"></small>
                            <small class="form-text text-muted">
                                Slug diisi otomatis dari judul tapi bisa diubah manual. Hanya huruf kecil, angka, dan strip (-).
                            </small>
                        </div>

                        {{-- Meta Title --}}
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" 
                                   class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" 
                                   value="{{ old('meta_title', $document->meta_title) }}" 
                                   placeholder="Masukkan meta title (SEO)" maxlength="60">
                            <small class="form-text text-muted">
                                Ambil dari judul dokumen disarankan maksimal 60 karakter.
                            </small>
                            <small  id="meta_title_count" class="text-muted"></small>
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Meta Description --}}
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="3" 
                                      placeholder="Masukkan meta description (SEO)" maxlength="160">{{ old('meta_description', $document->meta_description) }}</textarea>
                            <small class="form-text text-muted">
                                Versi singkat dari deskripsi maksimal 160 karakter.
                            </small>
                            <small id="meta_description_count" class="text-muted"></small>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Dokumen</button>
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------
    // Slug Generator & Validasi
    // ----------------------------
    let titleInput = document.getElementById('title');
    let slugInput  = document.getElementById('slug');
    let titleFeedback = document.getElementById('title-feedback');
    let slugFeedback  = document.getElementById('slug-feedback');
    let slugEdited = false;

    const uploadRadio = document.getElementById('source_upload');
    const embedRadio  = document.getElementById('source_embed');
    const uploadBox   = document.getElementById('upload-wrapper');
    const embedBox    = document.getElementById('embed-wrapper');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    titleInput.addEventListener('input', function() {
        if (this.value.length > 255) {
            titleFeedback.textContent = "⚠️ Judul maksimal 255 karakter.";
            titleFeedback.classList.add('text-danger');
        } else {
            titleFeedback.textContent = "";
            titleFeedback.classList.remove('text-danger');
        }

        if (!slugEdited) {
            slugInput.value = slugify(this.value);
            checkSlug(slugInput.value);
        }
    });

    if (slugInput.value !== slugify(titleInput.value)) {
        slugEdited = true;
    }

    slugInput.addEventListener('input', function() {
        slugEdited = true;
        checkSlug(this.value);
    });

    function checkSlug(slug) {
        let docId = "{{ $document->id }}";
        fetch(`{{ route('documents.checkSlug') }}?slug=${encodeURIComponent(slug)}&id=${docId}`)
            .then(res => res.json())
            .then(data => {
                slugFeedback.textContent = data.exists ? "⚠️ Slug sudah dipakai dokumen lain." : "";
            });
    }

    checkSlug(slugInput.value);

    // ----------------------------
    // Meta Title & Meta Description Counter
    // ----------------------------
    function updateCount(input, counterId, ideal, max) {
        const counter = document.getElementById(counterId);
        if (!counter) return;

        function refresh() {
            const length = input.value.length;
            counter.textContent = length + " / " + max + " karakter";
            if (length > ideal) {
                counter.classList.remove('text-muted');
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
                counter.classList.add('text-muted');
            }
        }

        input.addEventListener('input', refresh);
        refresh(); // jalankan sekali saat load
    }

    // Meta Title → ideal 60, max 255
    const metaTitleInput = document.getElementById('meta_title');
    if (metaTitleInput) {
        updateCount(metaTitleInput, 'meta_title_count', 60, 60);
    }

    // Meta Description → ideal 160, max 500
    const metaDescInput = document.getElementById('meta_description');
    if (metaDescInput) {
        updateCount(metaDescInput, 'meta_description_count', 160, 160);
    }

    // Toggle Sumber Dokumen
    function toggleSource() {
        uploadBox.style.display = uploadRadio.checked ? '' : 'none';
        embedBox.style.display  = embedRadio.checked ? '' : 'none';
    }

    uploadRadio.addEventListener('change', toggleSource);
    embedRadio.addEventListener('change', toggleSource);

    toggleSource(); // init
});
</script>
@endpush
