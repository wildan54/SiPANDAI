@extends('layouts.app')

@section('title', 'Bidang')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Bidang</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Bidang</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<section id="bidang" class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Form Tambah Bidang -->
      <div class="col-md-4">
        <div class="card border-success">
          <div class="card-header bg-warning">
            Tambah Bidang
          </div>
          <form action="{{ route('bidang.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama<span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama bidang" required>
              </div>
              <div class="form-group">
                <label for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug-bidang">
                <small class="form-text text-muted">
                  “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung.
                </small>
              </div>
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Deskripsi bidang"></textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-warning btn-block">Tambah</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Bidang -->
      <div class="col-md-8">
        <div class="card border-primary">
          <div class="card-header bg-warning">
              Daftar Bidang
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered table-hover data-table">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Deskripsi</th>
                      <th>Slug</th>
                      <th>Jumlah Dokumen</th> <!-- kolom baru -->
                      <th>Dibuat</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($units as $unit)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $unit->name }}</td>
                      <td data-tippy-content="{{ $unit->description }}">
                        {{ \Illuminate\Support\Str::words($unit->description, 5, '...') }}
                      </td>
                      <td>{{ $unit->slug }}</td>
                      <td>
                        {{ $unit->documents_count }} <!-- ambil dari withCount -->
                      </td>
                      <td>{{ $unit->created_at->format('d-m-Y') }}</td>
                      <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $unit->id }}">
                          Edit
                        </button>
                        <!-- Tombol Hapus -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUnit{{ $unit->id }}">
                            Hapus
                        </button>

                        <!-- Modal konfirmasi hapus -->
                        <x-confirm-delete-modal 
                            :id="'deleteUnit' . $unit->id"
                            title="Konfirmasi Hapus Bidang"
                            text="Perhatian! Menghapus bidang akan menghapus semua Dokumen di dalamnya."
                            :name="$unit->name"
                            :action="route('bidang.destroy', $unit->id)"
                        />
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <p class="mt-3 text-muted">
              Menghapus bidang akan menghapus data terkait di dalamnya.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Import modal edit -->
@include('bidang.modal_edit')
@endsection
