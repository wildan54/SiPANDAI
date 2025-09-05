@extends('layouts.app')

@section('content')
<section id="semua-pengguna" class="content tab-pane fade show active">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="my-3">Semua Pengguna</h1>
      <a href="#tambah-pengguna" class="btn btn-primary fw-semibold" data-bs-toggle="tab">
        <i class="fas fa-plus me-1"></i> Tambah Pengguna
      </a>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm border-0">
          <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <form action="{{ route('users.index') }}" method="GET" class="input-group input-group-sm w-25">
              <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Cari pengguna...">
              <div class="input-group-append">
                <button class="btn btn-light" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </form>
          </div>

          <div class="card-body p-0">
            <table class="table table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Username</th>
                  <th scope="col">Email</th>
                  <th scope="col">Status</th>
                  <th scope="col">Terakhir Aktif</th>
                  <th scope="col" class="text-center">Aksi</th>
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
                      <button class="btn btn-sm btn-outline-info" title="Edit">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
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

          <div class="card-footer d-flex justify-content-between">
            <span>
              Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </span>
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection