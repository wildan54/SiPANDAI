@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="container-fluid py-4">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <a href="{{ route('documents.create') }}" class="btn btn-primary font-weight-semibold">
      <i class="fas fa-plus mr-1"></i> Tambah Dokumen
    </a>
  </div>

  <!-- Card -->
  <div class="card shadow-sm border-0 rounded">
    <!-- Card Header (Filter + Search) -->
    <div class="card-header bg-primary text-white">
      <form method="GET" action="{{ route('documents.index') }}" class="row g-2 align-items-center">
        <!-- Filter Tipe -->
        <div class="col-12 col-md-auto">
          <select name="document_type_id" class="form-control form-control-sm w-100">
            <option value="">Tipe</option>
            @foreach($documentTypes as $type)
              <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Unit -->
        <div class="col-12 col-md-auto">
          <select name="unit_id" class="form-control form-control-sm w-100">
            <option value="">Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                {{ $unit->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Tahun -->
        <div class="col-12 col-md-auto">
          <select name="year" class="form-control form-control-sm w-100">
            <option value="">Tahun Dokumen</option>
            @foreach($years as $year)
              <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                {{ $year }}
              </option>
            @endforeach
          </select>
        </div>
        <!-- Search -->
        <div class="col-12 col-md-4 ms-md-auto">
          <div class="input-group input-group-sm w-100">
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
    <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
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
