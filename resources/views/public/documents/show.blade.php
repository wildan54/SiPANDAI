@extends('public.layouts.app')

@section('title', "Detail Dokumen - {$document->title}")

@section('content')

<!-- Judul Halaman -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h4 class="fw-bold mb-3">
                DETAIL <span class="text-warning">DOKUMEN</span>
            </h4>
        </div><!-- /.col -->
        <div class="col-sm-6 text-sm-end">
        <ol class="breadcrumb float-sm-end mb-0">
            <li class="breadcrumb-item">
            <a href="{{ route('public.home') }}" class="text-dark text-decoration-none fw-bold">
                <i class="bi bi-house-door"></i> Home
            </a>
            </li>
            <li class="breadcrumb-item active fw-bold" aria-current="page">
                Detail Dokumen
            </li>
        </ol>
        </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<div class="row">
    <!-- Kolom Detail Dokumen -->
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <div class="d-flex mb-3 align-items-start">
                <i class="bi bi-file-earmark-text fs-2 me-3" style="color: #030F6B;"></i>
                <div class="flex-grow-1">
                    <!-- Judul -->
                    <h3 class="mb-2 fw-bold text-break">{{ $document->title }}</h3>

                    <!-- Badges: Unit, Tipe, Tahun -->
                    <div class="mb-2 d-flex flex-wrap gap-1">
                        <span class="badge bg-light text-dark text-break">{{ $document->unit->name ?? '-' }}</span>
                        <span class="badge bg-light text-dark text-break">{{ $document->type->name ?? '-' }}</span>
                        <span class="badge bg-light text-dark">{{ $document->year }}</span>
                    </div>

                    <!-- Kolom Detail Dokumen -->
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-sm text-wrap">
                            <tbody>
                                <tr>
                                    <th class="text-break">Tipe Dokumen</th>
                                    <td class="text-break">{{ $document->type->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-break">Bidang</th>
                                    <td class="text-break">{{ $document->unit->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-break">Tahun</th>
                                    <td class="text-break">{{ $document->year }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Deskripsi -->
                    <p class="small text-muted mb-2 text-break">{{ $document->description }}</p>

                    <!-- Tanggal unggah + Tombol Aksi -->
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                        <!-- Tanggal di kiri -->
                        <small class="text-muted uploaded-text">
                            Diunggah: {{ $document->upload_date->format('d/m/Y') }}
                        </small>

                        <!-- Tombol di kanan -->
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('public.documents.download', $document->slug) }}" class="btn btn-download">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                            <a href="{{ $document->file_embed }}" target="_blank" class="btn btn-secondary">
                                <i class="bi bi-eye"></i> Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Preview Dokumen -->
            <div class="border rounded" style="height: 600px; width: 100%;">
                <iframe id="docEmbed" width="100%" height="100%" frameborder="0"></iframe>
            </div> --}}
        </div>
    </div>

    <!-- Kolom Quick Access Dokumen Lainnya -->
    <div class="col-lg-4">
        {{-- Dokumen serupa --}}
        @include('components.quick-access-documents-card', [
            'documents' => $otherDocuments,
            'title_1' => 'Dokumen Serupa',
            'dropdown' => true,
            'id' => 'collapseSimilarDocs'
        ])

        {{-- Tipe dokumen dalam kategori yg sama --}}
        @include('components.quick-access-types-card', [
            'types' => $sameCategoryTypes,
            'title' => $document->type->category->name,
            'id' => 'collapseCategoryDocs'
        ])
    </div>
</div>
</div>

<!-- Script Embed Dokumen -->
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


// <!-- Custom Style untuk card -->
<style>
.card-custom h3,
.card-custom p,
.card-custom span,
.card-custom td,
.card-custom th {
  word-wrap: break-word;
  overflow-wrap: break-word;
  white-space: normal;
}

/* Kecilkan tulisan "Diunggah" di mobile */
@media (max-width: 576px) {
  .uploaded-text {
    font-size: 0.75rem; /* lebih kecil dari small (0.875rem) */
  }
}
</style>
@endsection