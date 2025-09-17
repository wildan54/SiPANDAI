@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <!-- Statistik: 4 card penuh lebar -->
    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h6 class="text-muted">Total Dokumen</h6>
            <h3 class="mb-0">{{ $totalDocuments ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h6 class="text-muted">Kategori</h6>
            <h3 class="mb-0">{{ $totalCategories ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h6 class="text-muted">Tipe Dokumen</h6>
            <h3 class="mb-0">{{ $totalTypes ?? 0 }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center">
            <h6 class="text-muted">Bidang</h6>
            <h3 class="mb-0">{{ $totalUnits ?? 0 }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Baris 2: Chart kategori & daftar kategori -->
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>Dokumen per Kategori</strong>
          </div>
          <div class="card-body">
            <canvas id="chartKategori" height="180"></canvas>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>Daftar Kategori</strong>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($latestCategories ?? [] as $cat)
              <li class="list-group-item">{{ $cat->name }}</li>
            @empty
              <li class="list-group-item text-muted">Belum ada kategori</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <!-- Baris 3: 5 Dokumen Terbaru -->
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>5 Dokumen Terbaru</strong>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($latestDocuments ?? [] as $doc)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $doc->title }}
                <span class="badge badge-light">{{ $doc->upload_date->format('d/m/Y') }}</span>
              </li>
            @empty
              <li class="list-group-item text-muted">Belum ada dokumen</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <!-- Baris 4: Quick Actions -->
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>Quick Actions</strong>
          </div>
          <div class="card-body">
            <a href="{{ route('documents.create') }}" class="btn btn-outline-success mr-2">
              <i class="fas fa-file-upload"></i> Upload Dokumen
            </a>
            <a href="{{ route('documents.categories.index') }}" class="btn btn-outline-primary mr-2">
              <i class="fas fa-folder-plus"></i> Tambah Kategori
            </a>
            <a href="{{ route('documents.types.index') }}" class="btn btn-outline-info">
              <i class="fas fa-tags"></i> Tambah Tipe
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  new Chart(document.getElementById("chartKategori"), {
    type: 'pie',
    data: {
      labels: {!! json_encode($chartKategoriLabels ?? []) !!},
      datasets: [{
        data: {!! json_encode($chartKategoriData ?? []) !!},
        backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545", "#17a2b8"]
      }]
    }
  });
</script>
@endpush