@extends('public.layouts.app')

@section('title', "Tipe Dokumen - {$type->name}")

@section('content')

  <!-- Judul Halaman -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h4 class="fw-bold mb-3">
                    DOKUMEN <span class="text" style="color: #FEBC2F;">{{ strtoupper($type->name) }}</span>
              </h4>
          </div><!-- /.col -->
          <div class="col-sm-6 text-sm-end">
          <ol class="breadcrumb float-sm-end mb-0">
              <li class="breadcrumb-item">
              <a href="{{ route('dashboard') }}" class="text-dark text-decoration-none fw-bold">
                  <i class="bi bi-house-door"></i> Home
              </a>
              </li>
              <li class="breadcrumb-item active fw-bold" aria-current="page">
                  Dokumen
              </li>
              <li class="breadcrumb-item active fw-bold" aria-current="page">
                  {{ $type->name }}
              </li>
          </ol>
          </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Grid Dokumen -->  
  <div class="row">
    @forelse($documents as $doc)
      <div class="col-md-6 mb-4">
        <div class="card card-custom p-3 h-100">
          <div class="d-flex">
            <i class="bi bi-file-earmark-text fs-2 text-primary me-3"></i>
            <div>
              <h6 class="mb-1">{{ $doc->title }}</h6>
              <p class="small text-muted mb-2">{{ Str::limit($doc->description, 120) }}</p>
              <div class="mb-2">
                <span class="badge bg-light text-dark">{{ $doc->year }}</span>
                <span class="badge bg-light text-dark">{{ $doc->unit->name ?? '-' }}</span>
                <span class="badge bg-light text-dark">{{ $doc->type->name ?? '-' }}</span>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">
              Diunggah: {{ $doc->upload_date->format('d/m/Y') }}
            </small>
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
  <div class="mt-3">
    {{ $documents->links() }}
  </div>
@endsection

@push('styles')
<style>
  .btn-download {
    background-color: #FEBC2F;
    border: none;
    color: #000;
    font-weight: 600;
  }
  .btn-download:hover {
    background-color: #e6a900;
    color: #000;
  }

  .btn-view {
    background-color: #0d6efd;
    border: none;
    color: #fff;
    font-weight: 600;
  }
  .btn-view:hover {
    background-color: #0b5ed7;
    color: #fff;
  }
</style>
@endpush
