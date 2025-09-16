@extends('layouts.app')

@section('title', 'Kategori Dokumen')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Kategori Dokumen</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Kategori Dokumen</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section id="kategori-dokumen" class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Form Tambah Kategori -->
      <div class="col-md-4">
        <div class="card border-primary">
          <div class="card-header bg-primary text-white">
            Tambah Kategori
          </div>
          <form action="{{ route('documents.categories.store') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="name">Nama<span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama kategori" required>
              </div>
              <div class="form-group">
                <label for="slug">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug-kategori">
                <small class="form-text text-muted">
                  “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung. Jika dikosongkan, slug akan dibuat otomatis berdasarkan nama kategori.
                </small>
              </div>
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Deskripsi kategori"></textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary btn-block">Tambah</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Kategori -->
      <div class="col-md-8">
        <div class="card border-primary">
          <div class="card-header bg-primary text-white">
              Daftar Kategori
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered table-hover data-table">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Deskripsi</th>
                      <th>Slug</th>
                      <th>Jumlah</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($categories as $category)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $category->name }}</td>
                      <td data-tippy-content="{{ $category->description ?? '—' }}">
                        {{ \Illuminate\Support\Str::words($category->description ?? '—', 5, '...') }}
                      </td>
                      <td>{{ $category->slug }}</td>
                      <td>
                        <a href="{{ route('public.documents.categories', $category->slug) }}" class="text-primary">
                              {{$category->documents_count}}
                        </a>
                      </td>
                      <td>
                          <button type="button" 
                                  class="btn btn-sm btn-info" 
                                  data-toggle="modal" 
                                  data-target="#editCategoryModal{{ $category->id }}">
                            Edit
                          </button>
                          <form action="{{ route('documents.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                  Hapus
                              </button>
                          </form>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
            </table>

            <p class="mt-3 text-muted">
              Menghapus kategori tidak menghapus dokumen di dalamnya.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('documents.category.modal_edit')
@endsection
