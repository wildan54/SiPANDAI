@extends('public.layouts.app')

@section('title', "Detail Dokumen - {$document->title}")

@section('content')

<!-- ================= HEADER ================= -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h4 class="fw-bold mb-3">
                    DETAIL <span class="text-warning">DOKUMEN</span>
                </h4>
            </div>
            <div class="col-sm-6 text-sm-end">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('public.home') }}" class="text-dark fw-bold text-decoration-none">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-bold">Detail Dokumen</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- ================= CONTENT ================= -->
<div class="container-fluid">
<div class="row">

    <!-- ===== KOLOM UTAMA ===== -->
    <div class="col-lg-8 mb-3">
        <div class="card card-custom p-4">

            {{-- ================= HEADER DOKUMEN ================= --}}
            < 
            div class="d-flex align-items-start mb-3">
                <i class="bi bi-file-earmark-text fs-2 me-3 text-primary"></i>

                <div class="flex-grow-1">
                    <h3 class="fw-bold text-break mb-2">
                        {{ $document->title }}
                    </h3>

                    <div class="mb-2 d-flex flex-wrap gap-1">
                        <span class="badge bg-light text-dark">{{ $document->unit->name ?? '-' }}</span>
                        <span class="badge bg-light text-dark">{{ $document->type->name ?? '-' }}</span>
                        <span class="badge bg-light text-dark">{{ $document->year }}</span>
                    </div>
                </div>
            </div>

            {{-- ================= PREVIEW DOKUMEN (FULL WIDTH) ================= --}}
            @if ($has_local_file)
                <div class="document-preview-wrapper mb-4">
                    <iframe
                        class="document-preview"
                        src="{{ $file_path }}#toolbar=0&navpanes=0&scrollbar=1&zoom=page-width"
                        frameborder="0"
                        loading="lazy">
                    </iframe>
                </div>
            @endif

            {{-- ================= DETAIL ================= --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th width="30%">Tipe Dokumen</th>
                            <td>{{ $document->type->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bidang</th>
                            <td>{{ $document->unit->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $document->year }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ================= DESKRIPSI ================= --}}
            <p class="small text-muted text-break">
                {{ $document->description }}
            </p>

            {{-- ================= FOOTER ================= --}}
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <small class="text-muted uploaded-text">
                    Diunggah:
                    {{ optional($document->created_at)->format('d/m/Y') }}
                </small>

                <div class="d-flex gap-2 flex-wrap">
                    @if($document->file_path)
                        <a href="{{ route('public.documents.download.file', $document->slug) }}"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-download"></i> Unduh
                        </a>
                    @elseif($document->file_embed)
                        <a href="{{ route('public.documents.download.embed', $document->slug) }}"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-download"></i> Unduh
                        </a>
                    @endif

                    @if($document->file_embed)
                        <a href="{{ $document->file_embed }}" target="_blank"
                           class="btn btn-secondary btn-sm">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- ===== SIDEBAR ===== -->
    <div class="col-lg-4">
        @include('components.quick-access-documents-card', [
            'documents' => $otherDocuments,
            'title_1' => 'Tipe Dokumen '.$document->type->name.' Lainnya',
            'dropdown' => true,
            'id' => 'collapseSimilarDocs'
        ])

        @include('components.quick-access-types-card', [
            'types' => $sameCategoryTypes,
            'title' => 'Kategori '.$document->type->category->name.' Lainnya',
            'id' => 'collapseCategoryDocs'
        ])
    </div>

</div>
</div>
@endsection

@push('styles')
<style>
.document-preview-wrapper {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    background: #f9fafb;
}

.document-preview {
    display: block;
    width: 100%;
    height: 100vh;      /* iframe benar-benar besar */
    min-height: 900px; /* aman untuk dokumen panjang */
}

.card-custom * {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

@media (max-width: 576px) {
    .uploaded-text {
        font-size: 0.75rem;
    }

    .document-preview {
        height: 80vh;
        min-height: 600px;
    }
}
</style>
@endpush
