@extends('layouts.app')

@section('title', 'Backup Data')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Backup Data</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Backup</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- CARD AKSI --}}
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>Backup Manual</strong>
          </div>
          <div class="card-body">

          <form action="{{ route('backup.run') }}" method="POST" style="display:inline;" onsubmit="handleBackup(this)">
              @csrf
              <button type="submit" id="btnBackup" class="btn btn-primary">
                  <i class="fas fa-database"></i> Backup Sekarang
              </button>
          </form>

            <span class="ml-3 text-muted">
              Klik tombol untuk menjalankan backup manual.
            </span>

          </div>
        </div>
      </div>
    </div>

    {{-- LIST BACKUP --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white border-bottom-0">
            <strong>Repository File Backup</strong>
          </div>

          <div class="card-body p-0">
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>Nama File</th>
                  <th>Ukuran</th>
                  <th>Tanggal</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($files as $file)
                  <tr>
                    <td>{{ basename($file) }}</td>

                    <td>
                      {{ number_format(Storage::size($file) / 1024 / 1024, 2) }} MB
                    </td>

                    <td>
                      {{ \Carbon\Carbon::createFromTimestamp(Storage::lastModified($file))
                        ->setTimezone('Asia/Jakarta')
                        ->format('d/m/Y H:i') }}
                    </td>

                    <td class="text-center">
                      <a href="{{ route('backup.download', basename($file)) }}" 
                         class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i>
                      </a>

                      <form action="{{ route('backup.delete', basename($file)) }}" 
                          method="POST" 
                          style="display:inline;"
                          onsubmit="return confirm('Hapus backup ini?')">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted">
                      Belum ada file backup
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
function handleBackup(form) {
    if (!confirm('Jalankan backup sekarang?')) {
        return false;
    }

    const btn = document.getElementById('btnBackup');

    // disable button
    btn.disabled = true;

    // ganti isi tombol
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Memproses Backup...
    `;

    return true;
}

// 🔥 AUTO REFRESH SAAT BACKUP BERJALAN
@if(session('success') == 'Backup sedang diproses...')
  <div class="alert alert-info">
    <span class="spinner-border spinner-border-sm"></span>
    Backup sedang diproses...
  </div>
@endif
</script>
@endpush