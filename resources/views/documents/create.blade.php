@extends('layouts.app')

@section('title', 'Tambah Dokumen')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tambah Dokumen</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Tembah Dokumen</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Dokumen</h3>
                </div>
                <form action="{{ route('documents.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- Judul --}}
                        <div class="form-group">
                            <label for="title">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" 
                                class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" 
                                value="{{ old('title') }}" 
                                placeholder="Masukkan judul dokumen" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="3" placeholder="Masukkan deskripsi">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- File Embed --}}
                        <div class="form-group">
                            <label for="file_embed">Link/Embed File <span class="text-danger">*</span></label>
                            <input type="url" 
                                class="form-control @error('file_embed') is-invalid @enderror" 
                                id="file_embed" name="file_embed" 
                                value="{{ old('file_embed') }}" 
                                placeholder="Masukkan URL/embed link file dari Nextcloud (share publik)" required>
                            @error('file_embed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Dokumen harus diupload terlebih dahulu ke <strong>Nextcloud</strong>, kemudian di-share secara publik. 
                                Salin link publik dan tempelkan di kolom ini.
                            </small>
							
							<a href="https://dinz.ddns.net/nextcloud/index.php/login" target="_blank" class="btn btn-sm btn-info mt-2">
								<i class="fas fa-cloud-upload-alt"></i> Buka Nextcloud
							</a>
                        </div>

                        {{-- Tipe Dokumen --}}
                        <div class="form-group">
                            <label for="document_type_id">Tipe Dokumen <span class="text-danger">*</span></label>
                            <select id="document_type_id" name="document_type_id" 
                                    class="form-control @error('document_type_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe Dokumen --</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('document_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('document_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Unit --}}
                        <div class="form-group">
                            <label for="unit_id">Unit <span class="text-danger">*</span></label>
                            <select id="unit_id" name="unit_id" 
                                    class="form-control @error('unit_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tahun --}}
                        <div class="form-group">
                            <label for="year">Tahun <span class="text-danger">*</span></label>
                            <input type="number" 
                                class="form-control @error('year') is-invalid @enderror" 
                                id="year" name="year" 
                                value="{{ old('year') }}" 
                                placeholder="contoh: 2025" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" 
                                class="form-control @error('slug') is-invalid @enderror" 
                                id="slug" name="slug" 
                                value="{{ old('slug') }}" 
                                placeholder="contoh: surat-keputusan-2025">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Slug diisi otomatis berdasarkan judul, 
                                tapi bisa juga diubah sesuai kebutuhan. Hanya boleh mengandung huruf kecil, angka, dan strip (-).
                            </small>
                        </div>

                        {{-- Meta Title --}}
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" 
                                class="form-control @error('meta_title') is-invalid @enderror" 
                                id="meta_title" name="meta_title" 
                                value="{{ old('meta_title') }}" 
                                placeholder="Masukkan meta title (SEO)" 
                                maxlength="60">
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
                                    placeholder="Masukkan meta description (SEO)" 
                                    maxlength="160">{{ old('meta_description') }}</textarea>
                            <small class="form-text text-muted">
                                Versi singkat dari deskripsi maksimal 160 karakter.
                            </small>
                            <small id="meta_description_count" class="text-muted"></small>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Hidden file_source --}}
                        <input type="hidden" name="file_source" value="embed">

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
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
    // Slug Generator
    // ----------------------------
    let titleInput = document.getElementById('title');
    let slugInput  = document.getElementById('slug');
    let feedback   = document.createElement('small');
    feedback.classList.add('text-danger');
    slugInput.parentNode.appendChild(feedback);

    let slugEdited = false; // flag apakah user sudah edit slug manual?

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')        // ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')    // hapus karakter non-word
            .replace(/\-\-+/g, '-')      // ganti -- jadi -
            .replace(/^-+/, '')          // hapus - di awal
            .replace(/-+$/, '');         // hapus - di akhir
    }

    // Isi slug otomatis saat judul diisi, kecuali kalau user sudah edit slug
    titleInput.addEventListener('input', function() {
        if (!slugEdited) {
            slugInput.value = slugify(this.value);
            checkSlug(slugInput.value);
        }
    });

    // Kalau user edit slug manual, set flag = true
    slugInput.addEventListener('input', function() {
        slugEdited = true;
        checkSlug(this.value);
    });

    // Cek slug ke server (tanpa id exclude karena create)
    function checkSlug(slug) {
        fetch(`{{ route('documents.checkSlug') }}?slug=${slug}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    feedback.textContent = "⚠️ Slug sudah dipakai dokumen lain.";
                } else {
                    feedback.textContent = "";
                }
            });
    }

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
});
</script>
@endpush