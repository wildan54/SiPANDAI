@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tambah Pengguna</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Tambah Pengguna</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"><!-- Full width -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Pengguna</h3>
                    </div>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan nama lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" id="role" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="administrator">Administrator</option>
                                    <option value="editor">Editor</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="unit-wrapper">
                                <label for="unit_id">Unit / Bidang</label>
                                <select name="unit_id" class="form-control" id="unit_id">
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Ulangi password" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Simpan Pengguna</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const roleSelect = document.getElementById('role');
    const unitWrapper = document.getElementById('unit-wrapper');
    const unitSelect = document.getElementById('unit_id');

    roleSelect.addEventListener('change', function () {
        if (this.value === 'editor') {
            unitWrapper.classList.remove('d-none');
            unitSelect.setAttribute('required', 'required');
        } else {
            unitWrapper.classList.add('d-none');
            unitSelect.removeAttribute('required');
            unitSelect.value = '';
        }
    });
</script>
@endpush
