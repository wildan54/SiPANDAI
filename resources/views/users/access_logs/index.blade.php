@extends('layouts.app')

@section('title', 'Statistik Penggunaan Portal')

@section('content')
    <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Statistik Penggunaan Portal</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Statistik Peggunaan Portal</li>
            </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-3">
        <div class="form-row">
            <div class="col-md-3">
                <small>Pilih Pengguna</small>
                <select name="user_id" class="form-control">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <small>Aksi</small>
                <select name="access_type" class="form-control">
                    <option value="">-- Semua Aksi --</option>
                    <option value="view" {{ request('access_type')=='view' ? 'selected': '' }}>View</option>
                    <option value="download" {{ request('access_type')=='download' ? 'selected': '' }}>Download</option>
                    <option value="upload" {{ request('access_type')=='upload' ? 'selected' : '' }}>Upload</option>
                    <option value="update" {{ request('access_type')=='update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('access_type')=='delete' ? 'selected' : '' }}>Delete</option>
                </select>
            </div>

            <div class="col-md-3">
                <small>Tanggal Awal</small>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Tanggal Mulai">
            </div>

            <div class="col-md-3">
                <small>Tanggal Akhir</small>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="Tanggal Akhir">
            </div>
        </div>

        <div class="form-row mt-2">
            <div class="col">
                <button type="submit" class="btn btn-sm" style="background-color: #FEBC2F">Filter</button>
                <a href="{{ route('users.access_logs.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    {{-- Tabel Log --}}
    <div class="card shadow-sm">
        <div class="card-header text-white" style="background-color: #030F6B;">
            Aktivitas Pengelolaan Dokumen
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="logsTable" class="table table-striped table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Dokumen</th>
                            <th>Ip_Address</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->access_datetime }}</td>
                            <td>{{ $log->user->name ?? 'Guest' }}</td>
                            <td>
                                @switch($log->access_type)
                                    @case('upload') <span class="badge badge-success">Upload</span> @break
                                    @case('update') <span class="badge badge-warning">Update</span> @break
                                    @case('delete') <span class="badge badge-danger">Delete</span> @break
                                    @case('download') <span class="badge badge-info">Download</span> @break
                                    @default <span class="badge badge-secondary">View</span>
                                @endswitch
                            </td>
                            <td>{{ $log->document_title ?? '-' }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>
                                <a href="{{ route('users.access_logs.detail', $log->id) }}" class="btn btn-sm btn-primary">
                                    Lihat Detail
                                </a>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination Laravel --}}
            <div class="p-3">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection