@extends('layouts.app')

@section('title', 'SiPANDAI - Tipe Dokumen')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tipe Dokumen</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Tipe Dokumen</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section id="tipe-dokumen" class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Form Tambah Tipe Dokumen -->
      <div class="col-md-4">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            Tambah Tipe Dokumen
          </div>
          <form action="{{ route('documents.types.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama tipe dokumen" required>
              </div>
              <div class="form-group">
                <label for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug-tipe">
                <small class="form-text text-muted">
                  “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung.
                </small>
              </div>
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Deskripsi tipe dokumen"></textarea>
              </div>
              <div class="form-group">
                <label for="document_category_id">Kategori Dokumen</label>
                <select name="document_category_id" id="document_category_id" class="form-control">
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success btn-block">Tambah</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Tipe Dokumen -->
      <div class="col-md-8">
        <div class="card border-primary">
          <div class="card-header bg-success text-white">
              Daftar Tipe Dokumen
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered table-hover data-table">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Slug</th>
                      <th>Deskripsi</th>
                      <th>Kategori</th>
                      <th>Dibuat</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($types as $type)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $type->name }}</td>
                      <td>{{ $type->slug }}</td>
                      <td data-tippy-content="{{ $type->description }}">
                        {{ \Illuminate\Support\Str::words($type->description ?? '—', 8, '...') }}
                      </td>
                      <td>{{ $type->category->name ?? '—' }}</td>
                      <td>{{ $type->created_at->format('d-m-Y') }}</td>
                      <td>
                          <!-- Tombol Edit -->
                          <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $type->id }}">
                            Edit
                          </button>
                          <form action="{{ route('documents.types.destroy', $type->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tipe dokumen ini?')">
                                  Hapus
                              </button>
                          </form>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>

            <p class="mt-3 text-muted">
              Menghapus tipe dokumen tidak menghapus dokumen yang terkait.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('documents.type.modal_edit')
@endsection