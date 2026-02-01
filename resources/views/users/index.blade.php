@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pengguna</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Pengguna</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
  <div class="container-fluid">
    <!-- Tombol tambah pengguna -->
    <div class="d-flex justify-content-end mb-3">
      <a href="{{ route('users.create') }}" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Tambah Pengguna
      </a>
    </div>

    <div class="card border-primary">
      <div class="card-header bg-success text-white">
        Daftar Pengguna
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover data-table">
            <thead>
            <tr>
              <th>No</th>
              <th>Pengguna</th> <!-- Gabungan foto + nama -->
              <th>Username</th>
              <th>Email</th>
              <th>Unit</th>
              <th>Role</th>
              <th>Terakhir Aktif</th>
              <th>Dokumen</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $index => $user)
            <tr>
              <td>{{ $users->firstItem() + $index }}</td>
              <td class="d-flex align-items-center">
                  <div class="user-photo-wrapper mr-2" style="position: relative; display: inline-block;">
                      <img src="{{ asset('images/default-avatar.png') }}" 
                          alt="Foto {{ $user->username }}" 
                          class="rounded-circle" 
                          width="30" height="30">
                      <span class="status-indicator" 
                            style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white; background-color: {{ $user->last_active && $user->last_active->isAfter(now()->subMinutes(10)) ? 'green' : 'gray' }};"
                            title="{{ $user->last_active && $user->last_active->isAfter(now()->subMinutes(10)) ? 'Aktif' : 'Nonaktif' }}">
                      </span>
                  </div>
                  {{ $user->name }}
              </td>

              <td>{{ $user->username ?? '-' }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->unit ? $units->firstWhere('id', $user->unit_id)->name : '-' }}</td>
              <td>{{ ucfirst($user->role) }}</td>
              <td>{{ $user->last_active ? $user->last_active->format('Y-m-d H:i') : '-' }}</td>
              <td>{{ $user->documents_count ?? 0 }}</td>
              <td class="text-center">
              <button type="button" class="btn btn-sm btn-info edit-user-btn" 
                  data-id="{{ $user->id }}"
                  data-name="{{ $user->name }}"
                  data-username="{{ $user->username }}"
                  data-role="{{ $user->role }}"
                  data-email="{{ $user->email }}"
                  data-unit="{{ $user->unit_id ?? '-' }}">
                  Edit
              </button>

              <!-- Tombol Hapus -->
              <button type="button" 
                      class="btn btn-sm btn-danger"
                      data-toggle="modal" data-target="#deleteUser{{ $user->id }}">
                  Hapus
              </button>

              <!-- Modal Konfirmasi Hapus -->
              <x-confirm-delete-modal 
                  :id="'deleteUser'.$user->id"
                  title="Hapus Pengguna"
                  :name="$user->name"
                  :action="route('users.destroy', $user->id)"
                  :hasMoveOption="$user->documents_count > 0"
                  :moveOptions="$users->where('id', '!=', $user->id)"
              />
          </td>
            </tr>
            @endforeach
          </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal edit (1x saja) -->
@include('users.modal_edit')
@endsection