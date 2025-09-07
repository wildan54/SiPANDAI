@extends('layouts.app')

@section('title', 'SiPANDAI - Bidang')

@section('content')
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
                      <td>{{ $unit->created_at->format('d-m-Y') }}</td>
                      <td>
                          <!-- Tombol Edit -->
                          <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $unit->id }}">
                            Edit
                          </button>
                          <!-- Tombol Hapus -->
                          <form action="{{ route('bidang.destroy', $unit->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus bidang ini?')">
                                  Hapus
                              </button>
                          </form>
                      </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Import modal edit -->
@include('bidang.modal_edit')
@endsection
