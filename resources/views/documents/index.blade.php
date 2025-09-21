@extends('layouts.app')

@section('title', 'Semua Dokumen')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dokumen</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Dokumen</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="container-fluid py-4">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <a href="{{ route('documents.create') }}" class="btn btn-primary font-weight-semibold">
      <i class="fas fa-plus mr-1"></i> Tambah Dokumen
    </a>
  </div>

  <!-- Card -->
  <div class="card shadow-sm border-0 rounded">
    <div class="card-header bg-primary text-white">
      <form method="GET" action="{{ route('documents.index') }}" 
            class="d-flex flex-wrap align-items-center gap-2">

        <!-- Filter Tipe -->
        <div style="min-width: 180px;">
          <select name="document_type_id" class="form-control form-control-sm">
            <option value="">Tipe</option>
            @foreach($documentTypes as $type)
              <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Unit -->
        <div style="min-width: 180px;">
          <select name="unit_id" class="form-control form-control-sm">
            <option value="">Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                {{ $unit->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Tahun -->
        <div style="min-width: 150px;">
          <select name="year" class="form-control form-control-sm">
            <option value="">Tahun Dokumen</option>
            @foreach($years as $year)
              <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                {{ $year }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Search -->
        <div class="flex-grow-1">
          <div class="input-group input-group-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari Dokumen...">
            <button class="btn btn-light btn-sm" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>

      </form>
    </div>
    

    <!-- Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="thead-light">
            <tr>
              <th style="width: 5%">No</th>
              <th>Judul Dokumen</th>
              <th style="width: 15%">Tipe Dokumen</th>
              <th style="width: 15%">Unit</th>
              <th style="width: 10%">Tahun</th>
              <th style="width: 12%">Uploader</th>
              <th style="width: 15%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($documents as $doc)
              <tr>
                <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
                <td class="font-weight-semibold">{{ $doc->title }}</td>
                <td>
                  <span class="badge badge-info">{{ $doc->type->name ?? '-' }}</span>
                </td>
                <td>{{ $doc->unit->name ?? '-' }}</td>
                <td>
                  <span class="badge badge-secondary">{{ $doc->upload_date->format('Y') }}</span>
                </td>
                <td>
                  <span class="badge">{{ $doc->uploader->name ?? '-' }}</span>
                </td>
                

                <td class="text-center">
                    {{-- Tombol View --}}
                    <a href="javascript:void(0)" 
                      class="btn btn-sm btn-outline-primary mr-1 showDocument" 
                      data-id="{{ $doc->id }}" 
                      title="Lihat Dokumen">
                        <i class="fas fa-eye"></i>
                    </a>

                    {{-- Tombol Edit --}}
                    <a href="{{ route('documents.edit', $doc->id) }}" 
                      class="btn btn-sm btn-outline-warning mr-1" 
                      title="Edit Dokumen">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- Tombol Delete --}}
                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="return confirm('Yakin hapus dokumen ini?')" 
                                title="Hapus Dokumen">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Belum ada dokumen</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <div class="card-footer d-flex flex-column flex-md-row justify-content-end align-items-center gap-2">
      <div>
        Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
      </div>
      <div>
        {{ $documents->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@include('documents.modal_detail')
@endsection
