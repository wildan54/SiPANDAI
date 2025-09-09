@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg rounded-lg">
    <div class="card-body">
      <div class="row">
        <!-- Kolom Kiri: Preview Dokumen -->
        <div class="col-md-8">
          <h5 class="font-weight-bold mb-3">{{ strtoupper($document->title) }}</h5>
          <div class="border rounded-lg" style="height: 500px;">
            {{-- @php
              // pastikan link dari Nextcloud jadi direct link
              $embedLink = Str::endsWith($document->file_embed, '/download')
                ? $document->file_embed
                : rtrim($document->file_embed, '/') . '/download';
            @endphp
            @php
                $embedLink = $document->file_embed;

                // Kalau link dari Google Drive
                if (Str::contains($embedLink, 'drive.google.com')) {
                    // Ambil FILE_ID dari link
                    preg_match('/\/d\/(.*?)\//', $embedLink, $matches);
                    if (isset($matches[1])) {
                        $fileId = $matches[1];
                        $embedLink = "https://drive.google.com/file/d/{$fileId}/preview";
                    }
                }
            @endphp

            <embed src="{{ $embedLink }}#toolbar=0"
                    type="application/pdf" width="100%" height="100%"> --}}

            <embed src="{{ route('documents.preview', $document->id) }}#toolbar=0"
                  type="application/pdf" width="100%" height="100%">

          </div>
        </div>

        <!-- Kolom Kanan: Detail Dokumen -->
        <div class="col-md-4 position-relative">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge badge-warning px-3 py-2">{{ $document->type->name ?? '-' }}</span>
                <a href="{{ route('documents.index') }}" class="close">&times;</a>
            </div>
            
            <ul class="list-unstyled">
                <li><strong>Kategori</strong> : {{ $document->type->name ?? '-' }}</li>
                <li><strong>Bidang</strong> : {{ $document->unit->name ?? '-' }}</li>
                <li><strong>Tahun</strong> : {{ $document->upload_date ? $document->upload_date->format('Y') : '-' }}</li>
                <li><strong>Diunggah</strong> : {{ $document->upload_date ? $document->upload_date->format('d/m/Y') : '-' }}</li>
                <li><strong>Tipe</strong> : PDF</li>
                <li><strong>Ukuran</strong> : - </li>
            </ul>

            <p class="text-muted small">
                {{ $document->description ?? 'Tidak ada deskripsi' }}
            </p>

            <!-- Tombol pojok kanan bawah -->
            <div class="position-absolute" style="bottom: 15px; right: 15px;">
                <div class="d-flex">
                    <a href="#" class="btn btn-warning mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
