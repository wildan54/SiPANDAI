<!-- Tag Kategori -->
<div class="nav-tag mb-4">
  <label class="form-label small">Pilih Kategori </label>
  <a href="{{ route('public.home') }}" 
     class="btn btn-sm {{ request('category') ? 'btn-outline-secondary' : 'text-dark' }}" 
     style="{{ request('category') ? '' : 'background-color:#FEBC2F; border-color:#FEBC2F;' }}">
     Semua
  </a>
  @foreach($categories as $category)
    <a href="{{ route('public.home', array_merge(request()->except('page'), ['category' => $category->id])) }}" 
       class="btn btn-sm {{ request('category') == $category->id ? 'text-dark' : 'btn-outline-secondary' }}"
       style="{{ request('category') == $category->id ? 'background-color:#FEBC2F; border-color:#FEBC2F;' : '' }}">
       {{ $category->name }}
    </a>
  @endforeach
</div>

<!-- Form Filter -->
<form action="{{ route('public.home') }}" method="GET" class="mb-3">
  <div class="row g-2 align-items-end">

    <!-- Tipe Dokumen -->
    <div class="col-md-3">
      <label class="form-label small">Tipe Dokumen</label>
      <select name="type" class="form-select">
        <option value="">Semua Tipe</option>
        @forelse($types as $type)
          <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
            {{ $type->name }}
          </option>
        @empty
          <option disabled>-- Tidak ada tipe --</option>
        @endforelse
      </select>
    </div>

    <!-- Unit -->
    <div class="col-md-3">
      <label class="form-label small">Bidang / Unit</label>
      <select name="unit" class="form-select">
        <option value="">Semua Bidang</option>
        @foreach($units as $unit)
          <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
            {{ $unit->name }}
          </option>
        @endforeach
      </select>
    </div>

    <!-- Tahun -->
    <div class="col-md-2">
      <label class="form-label small">Tahun</label>
      <select name="year" class="form-select">
        <option value="">Semua Tahun</option>
        @foreach($years as $year)
          <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
            {{ $year }}
          </option>
        @endforeach
      </select>
    </div>

    <!-- Sort -->
    <div class="col-md-2">
      <label class="form-label small">Urutkan</label>
      <select name="sort" class="form-select">
        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
      </select>
    </div>

    <!-- Tombol -->
    <div class="col-md-2 d-flex gap-2">
      <button class="btn text-dark w-100" style="background-color:#FEBC2F; border-color:#FEBC2F;">
        Filter
      </button>
      <a href="{{ route('public.home') }}" class="btn btn-outline-secondary w-100">
        Reset
      </a>
    </div>

  </div>
</form>

<!-- Indikator Filter Aktif -->
@if(request()->hasAny(['type', 'unit', 'year', 'sort', 'category']) && collect(request()->only(['type','unit','year','sort','category']))->filter()->isNotEmpty())
  <div class="alert alert-warning mt-3 p-2">
    <small><i class="bi bi-funnel"></i> Filter sedang aktif, Klik Tombol Reset Untuk menampilkan semua Dokumen</small>
  </div>
@endif