@extends('public.layouts.app')

@section('title', 'Detail Dokumen')

@section('content')

  <!-- Judul Halaman -->
  <h4 class="fw-bold mb-3">
    DETAIL <span class="text-warning">DOKUMEN</span>
  </h4>

  <div class="card card-custom p-4">
    <div class="d-flex mb-3">
      <i class="bi bi-file-earmark-text fs-2 text-primary me-3"></i>
      <div>
        <h5 class="mb-1">{{ $document->title }}</h5>
        <p class="small text-muted mb-2">{{ $document->description }}</p>
        <div class="mb-2">
          <span class="badge bg-light text-dark">{{ $document->year }}</span>
          <span class="badge bg-light text-dark">{{ $document->unit->name ?? '-' }}</span>
          <span class="badge bg-light text-dark">{{ $document->type->name ?? '-' }}</span>
        </div>
        <small class="text-muted">Diunggah: {{ $document->upload_date->format('d/m/Y') }}</small>
      </div>
    </div>

    <!-- Preview Dokumen (opsional, jika PDF atau gambar) -->
    <div class="border rounded" style="height: 600px; width: 100%;">
      <iframe id="docEmbed" width="100%" height="100%" frameborder="0"></iframe>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex gap-2 mt-4 mb-4">
      <a href="{{ route('public.documents.download', $document->id) }}" class="btn btn-download">
        <i class="bi bi-download"></i> Unduh
      </a>
      <a href="{{ route('public.home') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

  </div>

  <script>
      document.addEventListener("DOMContentLoaded", function () {
    let embedLink = @json($document->file_embed);

    // Jika link dari Google Drive → ubah ke /preview
    if (embedLink.includes("drive.google.com")) {
      const match = embedLink.match(/\/d\/(.*?)\//);
      if (match && match[1]) {
        const fileId = match[1];
        embedLink = `https://drive.google.com/file/d/${fileId}/preview`;
      }
    }

    // Jika dari Nextcloud → pastikan pakai /download
    if (embedLink.includes("nextcloud")) {
      if (!embedLink.endsWith("/download")) {
        embedLink = embedLink.replace(/\/+$/, "") + "/download";
      }
    }

    // Tambahkan toolbar=0 supaya minimalis
    document.getElementById("docEmbed").src = embedLink + "#toolbar=0";
  });
</script>

@endsection