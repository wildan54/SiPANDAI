@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ================= HERO ================= --}}
<section class="hero-section text-white d-flex align-items-center position-relative">
    <div class="container text-center position-relative z-2">
        <h1 class="display-5 fw-bold mb-3">
            Akses Informasi Publik dengan Mudah
        </h1>
        <p class="lead opacity-75 mb-4 mx-auto" style="max-width:600px">
            Cari dan unduh dokumen resmi, regulasi, serta laporan kinerja secara cepat dan transparan.
        </p>

        <form method="GET" action="{{ route('public.documents.search') }}">
            <input type="hidden" name="from" value="hero">
            <div class="search-wrapper mx-auto shadow">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-0 ps-4">
                        <i class="bi bi-search text-muted"></i>
                    </span>

                    <input type="text"
                        name="keyword"
                        class="form-control border-0 shadow-none"
                        placeholder="Cari judul dokumen atau kata kunci…">


                    <button class="btn btn-search fw-bold px-4">
                        Cari
                    </button>
                </div>
            </div>
        </form>


        {{-- Popular --}}
        <div class="mt-3 small">
            <span class="opacity-75 me-2">
                <i class="bi bi-fire"></i> Populer:
            </span>

            @forelse($popularTypes as $type)
                <a href="{{ route('public.documents.index', ['type' => $type->slug]) }}"
                class="badge badge-popular">
                    {{ $type->name }}
                </a>
            @empty
                <span class="text-white-50">Belum ada data</span>
            @endforelse
        </div>
    </div>

    <div class="hero-icon">
        <i class="bi bi-file-earmark-text"></i>
    </div>
</section>

{{-- ================= STATISTIK ================= --}}
<section class="stat-section shadow-sm">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <i class="bi bi-file-earmark-text stat-icon"></i>
                <h3 class="fw-bold mb-0">{{ $total_documents }}</h3>
                <small class="text-muted">Dokumen Publik</small>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <i class="bi bi-diagram-3 stat-icon"></i>
                <h3 class="fw-bold mb-0">{{ $total_units }}</h3>
                <small class="text-muted">Unit / Bidang</small>
            </div>
            <div class="col-md-4">
                <i class="bi bi-download stat-icon"></i>
                <h3 class="fw-bold mb-0">{{ $total_downloads }}</h3>
                <small class="text-muted">Total Unduhan</small>
            </div>
        </div>
    </div>
</section>

{{-- ================= KATEGORI DOKUMEN ================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <small class="text-uppercase text-muted fw-bold">Jelajahi</small>
            <h2 class="fw-bold text-primary">Kategori Dokumen</h2>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($featured_categories as $cat)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('public.documents.categories', $cat->slug) }}" class="text-decoration-none text-dark">
                        <div class="card card-custom p-3 h-100 text-center">

                            <div class="mb-3">
                                <i class="bi {{ $icons[$cat->name] ?? 'bi-folder2-open' }} fs-2"
                                style="color:#030F6B"></i>
                            </div>

                            <h6 class="fw-bold text-truncate-2 mb-1">
                                {{ $cat->name }}
                            </h6>

                            <p class="small text-muted mb-2">
                                {{ $cat->documents_count }} Dokumen
                            </p>

                            @if(isset($legalBases[$cat->id]))
                                <span class="badge bg-light text-dark"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    title="{!! implode('<br>', $legalBases[$cat->id]) !!}">
                                    <i class="bi bi-info-circle"></i> Dasar Hukum
                                </span>
                            @endif


                        </div>
                    </a>
                </div>

            @endforeach
        </div>
    </div>
</section>

{{-- ================= DOKUMEN TERBARU ================= --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold text-primary">
                    Terbaru Ditambahkan
                </h2>
                <p class="text-muted mb-0">
                    Dokumen publik yang baru diunggah
                </p>
            </div>
            <a href="" class="btn btn-outline-primary rounded-pill">
                Lihat Semua →
            </a>
        </div>

        {{-- <div class="row">
            @foreach($latest_documents as $doc)
            <div class="col-md-4 mb-4">
                <div class="card doc-card h-100">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger">PDF</span>
                            <span class="badge bg-light text-muted">
                                {{ $doc->year }}
                            </span>
                        </div>

                        <h6 class="fw-bold text-truncate-2">
                            <a href="{{ route('public.documents.show', $doc->slug) }}"
                               class="text-decoration-none text-dark">
                                {{ $doc->title }}
                            </a>
                        </h6>

                        <small class="text-muted">
                            {{ $doc->upload_date->diffForHumans() }}
                        </small>

                        <div class="mt-3">
                            <span class="badge bg-white border text-dark">
                                {{ $doc->unit->name ?? 'Umum' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div> --}}

         <!-- Grid Dokumen -->  
        <div class="row">
            @forelse($latest_documents as $doc)
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card card-custom p-3 h-100 d-flex flex-column">

                <!-- Icon + Konten -->
                <div class="d-flex mb-2">
                    <i class="bi bi-file-earmark-text fs-2 me-3" style="color: #030F6B"></i>
                    <div class="flex-grow-1">
                    <!-- Judul -->
                    <h6 class="mb-1 fw-bold text-truncate-2" title="{{ $doc->title }}">
                        {{ $doc->title }}
                        {{-- {{ $doc->year ? ' - ' . $doc->year : '' }} --}}
                    </h6>
                    <!-- Deskripsi -->
                    <p class="small text-muted mb-2 text-truncate-3">
                        {{ $doc->description }}
                    </p>
                    <!-- Badge -->
                    <div class="mb-2">
                        <span class="badge bg-light text-dark">{{ $doc->year }}</span>
                        <span class="badge bg-light text-dark">{{ $doc->unit->name ?? '-' }}</span>
                        <span class="badge bg-light text-dark">{{ $doc->type->name ?? '-' }}</span>
                    </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-auto d-flex justify-content-end align-items-center">
                    <div class="d-flex gap-2">
                @if($doc->file_path)
                    <a href="{{ route('public.documents.download.file', $doc->slug) }}"
                        class="btn btn-download">
                        <i class="bi bi-download"></i>Unduh
                    </a>
                @elseif($doc->file_embed)
                    <a href="{{ route('public.documents.download.embed', $doc->slug) }}"
                        class="btn btn-download">
                        <i class="bi bi-download"></i>Unduh
                    </a>
                @endif
                    <a href="{{ route('public.documents.show', $doc->slug) }}" class="btn btn-sm btn-view">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                    </div>
                </div>

                </div>
            </div>
            @empty
            <p class="text-muted">Tidak ada dokumen ditemukan.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ================= CTA ================= --}}
<section class="cta-section text-center text-white">
    <div class="container">
        <h3 class="fw-bold mb-3">
            Masih belum menemukan dokumen yang Anda butuhkan?
        </h3>
        <p class="opacity-75 mb-4">
            Gunakan pencarian lanjutan untuk menyaring berdasarkan tahun, unit, dan jenis dokumen.
        </p>
        <a href="{{ route('public.documents.index') }}" class="btn btn-warning fw-bold px-5 rounded-pill">
            Buka Pencarian Lanjutan
        </a>
    </div>
</section>

{{-- ================= STYLE ================= --}}
<style>
.hero-section {
    background: linear-gradient(135deg,#030F6B,#051680);
    min-height:420px;
}
.hero-icon {
    position:absolute;
    right:5%;
    bottom:10%;
    opacity:.08;
    font-size:18rem;
}
.btn-search {
    background:#FEBC2F;
    color:#030F6B;
}

.btn-outline-primary.rounded-pill {
    border-radius: 50px;
    border: 1px solid #030F6B;
    color: #030F6B;
    background-color: transparent;
    transition: all .3s ease;
}

.btn-outline-primary.rounded-pill:hover,
.btn-outline-primary.rounded-pill:focus {
    background-color: #FEBC2F;
    border-color: #FEBC2F;
    color: #030F6B;
}


.badge-popular {
    background:#FEBC2F;
    color:#030F6B;
    text-decoration:none;
    margin-right:.25rem;
}
.stat-section {
    background:#fff;
    padding:2rem 0;
    margin-top:-30px;
    position:relative;
    z-index:5;
}
.stat-icon {
    font-size:1.5rem;
    color:#030F6B;
}
.category-card {
    border:0;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
    transition:.3s;
}
.category-card:hover {
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.icon-circle {
    width:60px;
    height:60px;
    border-radius:50%;
    background:rgba(3,15,107,.1);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    color:#030F6B;
    font-size:1.5rem;
}
.badge-legal {
    background:#fff;
    border:1px dashed #030F6B;
    color:#030F6B;
    font-weight:500;
}
.doc-card {
    border:1px solid #eee;
    transition:.3s;
}
.doc-card:hover {
    border-color:#030F6B;
}
.cta-section {
    background:#030F6B;
    padding:4rem 1rem;
}
@media(max-width:768px){
    .hero-icon{display:none}
}

.fw-bold.text-primary {
    color: #030F6B !important;
}
</style>

{{-- ================= TOOLTIP INIT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
});
</script>

@endsection
