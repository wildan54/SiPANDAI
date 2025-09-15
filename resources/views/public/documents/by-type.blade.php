@extends('public.layouts.app')

@section('title', "Tipe Dokumen - {$type->name}")

@section('content')
<div class="container py-4">
  <!-- Judul -->
  <h4 class="fw-bold mb-3">
    DOKUMEN <span class="text" style="color: #FEBC2F;">{{ strtoupper($type->name) }}</span>
  </h4>

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
