@extends('layouts.app')

@section('title', 'SiPANDAI - Tambah Pengguna')

@section('content')
    <div class="container-fluid">
        <h1 class="my-3">Tambah Pengguna</h1>
        <div class="row">
            <div class="col-md-12"><!-- Full width -->
                <div class="card card-primary">
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
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Ulangi password" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
