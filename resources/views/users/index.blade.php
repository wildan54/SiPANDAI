@extends('layouts.app')

@section('title', 'SiPANDAI - Pengguna')

@section('content')
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
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Terakhir Aktif</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $index => $user)
              <tr>
                <td>{{ $users->firstItem() + $index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username ? $user->username : '-' }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @if ($user->last_active && $user->last_active->isAfter(now()->subMinutes(10)))
                    <span class="badge badge-success">Aktif</span>
                  @else
                    <span class="badge badge-secondary">Nonaktif</span>
                  @endif
                </td>
                <td>{{ $user->last_active ? $user->last_active->format('Y-m-d H:i') : '-' }}</td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-info edit-user-btn" 
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-username="{{ $user->username }}"
                    data-email="{{ $user->email }}">
                    Edit
                  </button>
                  <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">
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
</section>

<!-- Modal edit (1x saja) -->
@include('users.modal_edit')
@endsection