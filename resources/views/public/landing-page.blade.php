@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ================= HERO SECTION ================= --}}
<section class="hero-section text-white d-flex align-items-center position-relative">
    <div class="container text-center position-relative z-2">
        <h1 class="display-5 fw-bold mb-3">
            Akses Informasi Publik dengan Mudah
        </h1>
        <p class="lead opacity-75 mb-4 mx-auto" style="max-width:600px">
            Cari dan unduh dokumen resmi, regulasi, serta laporan kinerja secara cepat dan transparan.
        </p>

        {{-- SEARCH --}}
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form method="GET" action="">
                    <div class="input-group input-group-lg rounded-pill overflow-hidden shadow bg-white p-1">
                        <span class="input-group-text bg-white border-0 ps-4">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               name="keyword"
                               class="form-control border-0 shadow-none"
                               placeholder="Cari judul dokumen atau kata kunci..."
                               aria-label="Cari dokumen publik">
                        <button class="btn rounded-pill px-4 fw-bold btn-search">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- POPULAR SEARCH --}}
                <div class="mt-3 small">
                    <span class="opacity-75 me-2">
                        <i class="bi bi-fire"></i> Pencarian Populer:
                    </span>
                    <a href="" class="badge badge-popular">Anggaran</a>
                    <a href="" class="badge badge-popular">Renstra</a>
                    <a href="" class="badge badge-popular">SOP</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative Icon --}}
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
                <h3 class="fw-bold">{{ $total_documents }}</h3>
                <small class="text-muted">Dokumen Publik Tersedia</small>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <i class="bi bi-diagram-3 stat-icon"></i>
                <h3 class="fw-bold">{{ $total_units }}</h3>
                <small class="text-muted">Unit / Bidang</small>
            </div>
            <div class="col-md-4">
                <i class="bi bi-download stat-icon"></i>
                <h3 class="fw-bold">{{ $total_downloads }}</h3>
                <small class="text-muted">Kali Dokumen Diunduh</small>
            </div>
        </div>
    </div>
</section>

{{-- ================= KATEGORI ================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <small class="text-uppercase text-muted fw-bold">Jelajahi</small>
            <h2 class="fw-bold text-primary">Kategori Informasi</h2>
        </div>

        @php
        $icons = [
            'Informasi Berkala' => 'bi-calendar-check',
            'Informasi Serta Merta' => 'bi-lightning-charge',
            'Tersedia Setiap Saat' => 'bi-clock-history',
            'Regulasi & Hukum' => 'bi-scale'
        ];
        @endphp

        <div class="row g-4 justify-content-center">
            @foreach($featured_categories as $cat)
            <div class="col-6 col-md-3">
                <a href="" class="card category-card text-center">
                    <div class="card-body py-4">
                        <div class="icon-circle mb-3">
                            <i class="bi {{ $icons[$cat->name] ?? 'bi-folder2-open' }}"></i>
                        </div>
                        <h6 class="fw-bold">{{ $cat->name }}</h6>
                        <small class="text-muted">{{ $cat->documents_count }} Dokumen</small>
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
                <h2 class="fw-bold text-primary mb-1">Terbaru Ditambahkan</h2>
                <p class="text-muted mb-0">Update informasi publik terkini</p>
            </div>
            <a href="" class="btn btn-outline-primary rounded-pill">
                Lihat Semua →
            </a>
        </div>

        <div class="row">
            @foreach($latest_documents as $doc)
            <div class="col-md-4 mb-4">
                <div class="card doc-card h-100">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger">PDF</span>
                            <span class="badge bg-light text-muted">
                                {{ $doc->created_at->year }}
                            </span>
                        </div>
                        <h6 class="fw-bold text-truncate-2">
                            <a href="{{ route('public.documents.show', $doc->slug) }}"
                               class="text-decoration-none text-dark">
                                {{ $doc->title }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            {{ $doc->created_at->diffForHumans() }}
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
        <a href="" class="btn btn-warning fw-bold px-5 rounded-pill">
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
    z-index:5;
    position:relative;
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
</style>

@endsection
