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
                <form action="{{ route('documents.update', $document->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        {{-- Judul --}}
                        <div class="form-group">
                            <label for="title">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title', $document->title) }}" 
                                   placeholder="Masukkan judul dokumen" required>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan deskripsi">{{ old('description', $document->description) }}</textarea>
                        </div>

                        {{-- File Embed --}}
                        <div class="form-group">
                            <label for="file_embed">Link/Embed File <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="file_embed" name="file_embed" 
                                   value="{{ old('file_embed', $document->file_embed) }}" 
                                   placeholder="Masukkan URL/embed link file dari cloud" required>
                        </div>

                        {{-- Tipe Dokumen --}}
                        <div class="form-group">
                            <label for="document_type_id">Tipe Dokumen <span class="text-danger">*</span></label>
                            <select id="document_type_id" name="document_type_id" class="form-control" required>
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
                            <select id="unit_id" name="unit_id" class="form-control" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" 
                                        {{ old('unit_id', $document->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun --}}
                        <div class="form-group">
                            <label for="year">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="year" name="year" 
                                   value="{{ old('year', $document->year) }}" 
                                   placeholder="contoh: 2025" required>
                        </div>

                        {{-- Slug --}}
                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" 
                                   value="{{ old('slug', $document->slug) }}" 
                                   placeholder="contoh: surat-keputusan-2025">
                            <small id="slug-feedback" class="text-danger"></small>
                            <small class="form-text text-muted">
                                Slug diisi otomatis dari judul tapi bisa diubah manual. Hanya boleh huruf kecil, angka, dan strip (-).
                            </small>
                        </div>

                        {{-- Meta Title --}}
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="{{ old('meta_title', $document->meta_title) }}" 
                                   placeholder="Masukkan meta title (SEO)">
                        </div>

                        {{-- Meta Description --}}
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3" placeholder="Masukkan meta description (SEO)">{{ old('meta_description', $document->meta_description) }}</textarea>
                        </div>

                        <input type="hidden" name="file_source" value="embed">
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
    let titleInput = document.getElementById('title');
    let slugInput  = document.getElementById('slug');
    let feedback   = document.getElementById('slug-feedback');
    let slugEdited = false;

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    // Jika slug sama dengan slugify dari judul, berarti belum diedit manual
    if (slugInput.value !== slugify(titleInput.value)) {
        slugEdited = false; // masih bisa auto-generate jika judul diganti
    }

    // Event saat judul diubah
    titleInput.addEventListener('input', function() {
        if (!slugEdited) {
            slugInput.value = slugify(this.value);
            checkSlug(slugInput.value);
        }
    });

    // Event saat slug diubah manual
    slugInput.addEventListener('input', function() {
        slugEdited = true; // menandai user sudah mengedit slug
        checkSlug(this.value);
    });

    function checkSlug(slug) {
        let docId = "{{ $document->id }}";
        fetch(`{{ route('documents.checkSlug') }}?slug=${encodeURIComponent(slug)}&id=${docId}`)
            .then(res => res.json())
            .then(data => {
                feedback.textContent = data.exists ? "⚠️ Slug sudah dipakai dokumen lain." : "";
            });
    }

    // cek awal saat load halaman
    checkSlug(slugInput.value);
});
</script>
@endpush