@extends('public.layouts.app')

@section('title', 'Home')

@section('content')

  @include('public.partials.filter')

  <!-- Judul Halaman -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4 class="fw-bold mb-3">
              DOKUMEN <span class="text-warning">PUBLIK</span>
          </h4>
        </div><!-- /.col -->
        <div class="col-sm-6 text-sm-end">
        <ol class="breadcrumb float-sm-end mb-0">
            <li class="breadcrumb-item">
            <a href="{{ route('public.home') }}" class="text-dark text-decoration-none fw-bold">
                <i class="bi bi-house-door"></i> Home
            </a>
        </ol>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Grid Dokumen -->  
  <div class="row">
    @forelse($documents as $doc)
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card card-custom p-3 h-100 d-flex flex-column">

          <!-- Icon + Konten -->
          <div class="d-flex mb-2">
            <i class="bi bi-file-earmark-text fs-2 me-3" style="color: #030F6B"></i>
            <div class="flex-grow-1">
              <!-- Judul -->
              <h6 class="mb-1 fw-bold text-truncate-2" title="{{ $doc->title }}">
                  {{ $doc->title }}
                  {{-- {{ $doc->year ? ' - ' . $doc->year : '' }} --}}
              </h6>
              <!-- Deskripsi -->
              <p class="small text-muted mb-2 text-truncate-3">
                {{ $doc->description }}
              </p>
              <!-- Badge -->
              <div class="mb-2">
                <span class="badge bg-light text-dark">{{ $doc->year }}</span>
                <span class="badge bg-light text-dark">{{ $doc->unit->name ?? '-' }}</span>
                <span class="badge bg-light text-dark">{{ $doc->type->name ?? '-' }}</span>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="mt-auto d-flex justify-content-between align-items-center">
            <small class="text-muted">Diunggah: {{ $doc->upload_date->format('d/m/Y') }}</small>
            <div class="d-flex gap-2">
              <a href="{{ route('public.documents.download', $doc->slug) }}" class="btn btn-sm btn-download">
                <i class="bi bi-download"></i> Unduh
              </a>
              <a href="{{ route('public.documents.show', $doc->slug) }}" class="btn btn-sm btn-view">
                <i class="bi bi-eye"></i> Lihat
              </a>
            </div>
          </div>

        </div>
      </div>
    @empty
      <p class="text-muted">Tidak ada dokumen ditemukan.</p>
    @endforelse
  </div>


  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center mt-4 flex-column flex-md-row gap-2">
    <small class="text-muted">
      Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
    </small>
    {{ $documents->links() }}
  </div>

  <!-- Custom CSS -->
<style>
/* Batasi tinggi card & isi flex agar tombol selalu di bawah */
.card-custom {
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card-custom:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

/* Atur teks supaya tidak melewati card */
.card-custom h6,
.card-custom p,
.card-custom span {
  word-wrap: break-word;
  overflow-wrap: break-word;
  white-space: normal;
}

/* Judul max 2 baris */
.text-truncate-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Deskripsi max 3 baris */
.text-truncate-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Tombol */
.btn-download {
  background-color: #FEBC2F;
  color: #000;
  border-radius: 8px;
  padding: 4px 10px;
  font-size: 0.85rem;
  font-weight: 500;
}

.btn-download:hover {
  background-color: #e0a800;
  color: #fff;
}

.btn-view {
  background-color: #030F6B;
  color: #fff;
  border-radius: 8px;
  padding: 4px 10px;
  font-size: 0.85rem;
  font-weight: 500;
}

.btn-view:hover {
  background-color: #04127a;
}
</style>

@endsection
