@extends('layouts.app')

@section('title', 'SiPANDAI - Kategori Dokumen')

@section('content')
<section id="kategori-dokumen" class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- Form Tambah Kategori -->
      <div class="col-md-4">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
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
                <input type="text" name="slug" id="slug" class="form-control" placeholder="slug-kategori" required>
                <small class="form-text text-muted">
                  “Slug” adalah versi nama yang ramah URL. Biasanya semuanya huruf kecil dan hanya mengandung huruf, angka, serta tanda hubung.
                </small>
              </div>
              <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Deskripsi kategori"></textarea>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success btn-block">Tambah</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Tabel Kategori -->
      <div class="col-md-8">
        <div class="card border-primary">
          <div class="card-header bg-success text-white">
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
                      <th>Dibuat</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($categories as $category)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $category->name }}</td>
                      <td>{{ $category->description ?? '—' }}</td>
                      <td>{{ $category->slug }}</td>
                      <td>{{ $category->documents_count ?? 0 }}</td>
                      <td>{{ $category->created_at->format('d-m-Y') }}</td>
                      <td>
                          <a href="#" class="btn btn-sm btn-info">Edit</a>
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
@endsection
