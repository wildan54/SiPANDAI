@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="container-fluid py-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('documents.create') }}" class="btn btn-primary font-weight-semibold">
      <i class="fas fa-plus mr-1"></i> Tambah Dokumen
    </a>
  </div>

  <!-- Card -->
  <div class="card shadow-sm border-0 rounded">
    <!-- Card Header (Filter + Search) -->
    <div class="card-header bg-primary text-white">
      <form method="GET" action="{{ route('documents.index') }}" class="form-inline w-100">
        <!-- Filter Tipe -->
        <select name="document_type_id" class="form-control form-control-sm mr-2" style="max-width: 150px;">
          <option value="">Tipe</option>
          @foreach($documentTypes  as $type)
            <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
              {{ $type->name }}
            </option>
          @endforeach
        </select>

        <!-- Filter Unit -->
        <select name="unit_id" class="form-control form-control-sm mr-2" style="max-width: 200px;">
          <option value="">Unit</option>
          @foreach($units as $unit)
            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
              {{ $unit->name }}
            </option>
          @endforeach
        </select>

        <!-- Filter Tahun -->
        <select name="year" class="form-control form-control-sm mr-2" style="max-width: 200px;">
          <option value="">Tahun Dokumen</option>
          @foreach($years as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
              {{ $year }}
            </option>
          @endforeach
        </select>

        <!-- Search -->
        <div class="ml-auto input-group input-group-sm" style="width: 250px;">
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari Dokumen...">
          <div class="input-group-append">
            <button class="btn btn-light" type="submit">
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
              <th style="width: 5%">#</th>
              <th>Judul Dokumen</th>
              <th style="width: 15%">Tipe Dokumen</th>
              <th style="width: 15%">Unit</th>
              <th style="width: 10%">Tahun</th>
              <th style="width: 12%">Sumber File</th>
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
                  @if($doc->isUpload())
                    <span class="badge badge-primary">Upload File</span>
                  @elseif($doc->isEmbed())
                    <span class="badge badge-success">Cloud Embed</span>
                  @else
                    <span class="badge badge-secondary">Tidak Ada</span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="javascript:void(0)" 
                    class="btn btn-sm btn-outline-primary mr-1 showDocument" 
                    data-id="{{ $doc->id }}">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="#" class="btn btn-sm btn-outline-warning mr-1" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus dokumen ini?')" title="Hapus">
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
    <div class="card-footer d-flex justify-content-between">
      <span>
        Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
      </span>
      <div>
        {{ $documents->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>
@include('documents.modal_detail')
@endsection