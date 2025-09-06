@extends('layouts.app')

@section('title', 'SiPANDAI - Pengguna')

@section('content')
<section id="semua-pengguna" class="content">
  <div class="container-fluid">
    <div class="row">

      <!-- Tabel Semua Pengguna -->
      <div class="col-md-12">
        <!-- Tombol Tambah Pengguna di pojok kanan -->
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
                  @forelse ($users as $index => $user)
                    <tr>
                      <td>{{ $users->firstItem() + $index }}</td>
                      <td>{{ $user->name }}</td>
                      <td>{{ $user->username ? '@'.$user->username : '-' }}</td>
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
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">
                            Hapus
                          </button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center p-3">Tidak ada pengguna</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection