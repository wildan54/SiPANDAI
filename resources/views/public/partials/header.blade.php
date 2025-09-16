<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 p-3 px-5 sticky-top" style="background-color: #030F6B;">
  <!-- Kiri: Logo + Judul -->
  <div class="d-flex align-items-center mb-3 mb-md-0">
    <i class="bi bi-file-earmark-text fs-1 text-white me-2"></i>
    <div>
      <a href="{{ route('public.home') }}" class="text-decoration-none">
        <h2 class="mb-0 fw-bold" style="color: #FEBC2F;">Portal SiPANDAI</h2>
        <small class="text-light">Sistem Informasi Publikasi dan Arsip Dokumen Informasi</small>
      </a>
    </div>
  </div>

  <!-- Kanan: Search -->
  <form action="{{ route('public.home') }}" method="GET" class="input-group search-box w-100 w-md-auto">
      <input type="text" name="q" class="form-control" 
            placeholder="Cari Dokumen, Kata Kunci" value="{{ request('q') }}">

      <!-- Hidden fields -->
      @if(request('type'))
          <input type="hidden" name="type" value="{{ request('type') }}">
      @endif
      @if(request('unit'))
          <input type="hidden" name="unit" value="{{ request('unit') }}">
      @endif
      @if(request('year'))
          <input type="hidden" name="year" value="{{ request('year') }}">
      @endif
      @if(request('sort'))
          <input type="hidden" name="sort" value="{{ request('sort') }}">
      @endif
      @if(request('category'))
          <input type="hidden" name="category" value="{{ request('category') }}">
      @endif

      <button class="btn btn-outline-light" type="submit">
          <i class="bi bi-search"></i> Cari
      </button>
  </form>
</div>