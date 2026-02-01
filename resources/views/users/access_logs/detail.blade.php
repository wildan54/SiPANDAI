@extends('layouts.app')

@section('title', 'Statistik Penggunaan Portal - Detail')

@section('content')
    <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Detail Statistik</h1>
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

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <td>{{ $log->id }}</td>
                </tr>
                <tr>
                    <th>User</th>
                    <td>{{ $log->user->name ?? 'Guest' }}</td>
                </tr>
                <tr>
                    <th>Document ID</th>
                    <td>{{ $log->document_id }}</td>
                </tr>
                <tr>
                    <th>Document Title</th>
                    <td>{{ $log->document_title }}</td>
                </tr>
                <tr>
                    <th>Access Type</th>
                    <td>{{ $log->access_type }}</td>
                </tr>
                <tr>
                    <th>IP Address</th>
                    <td>{{ $log->ip_address }}</td>
                </tr>
                <tr>
                    <th>User Agent</th>
                    <td>{{ $log->user_agent }}</td>
                </tr>
                <tr>
                    <th>Referrer</th>
                    <td>{{ $log->referrer }}</td>
                </tr>
                <tr>
                    <th>Access Datetime</th>
                    <td>{{ $log->access_datetime }}</td>
                </tr>
            </table>

            <div class="mt-3">
            <a href="{{ route('users.access_logs.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            </div>

        </div>
    </div>
@endsection
