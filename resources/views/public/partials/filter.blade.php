<!-- Tag Kategori -->
<div class="nav-tag mb-4">
  <label class="form-label small">Pilih Kategori </label>
  <a href="{{ route('public.documents.index') }}" 
     class="btn btn-sm {{ request('category') ? 'btn-outline-secondary' : 'text-dark' }}" 
     style="{{ request('category') ? '' : 'background-color:#FEBC2F; border-color:#FEBC2F;' }}">
     Semua
  </a>
  @foreach($categories as $category)
    <a href="{{ route('public.documents.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}" 
       class="btn btn-sm {{ request('category') == $category->slug ? 'text-dark' : 'btn-outline-secondary' }}"
       style="{{ request('category') == $category->slug ? 'background-color:#FEBC2F; border-color:#FEBC2F;' : '' }}">
       {{ $category->name }}
    </a>
  @endforeach
</div>

<!-- Form Filter -->
<form action="{{ route('public.documents.index') }}" method="GET" class="mb-3">
  <div class="row g-2 align-items-end">

    <!-- Tipe Dokumen -->
    <div class="col-md-3">
      <label class="form-label small">Tipe Dokumen</label>
      <select name="type" class="form-select">
        <option value="">Semua Tipe</option>
        @forelse($types as $type)
          <option value="{{ $type->slug }}" {{ request('type') == $type->slug ? 'selected' : '' }}>
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
          <option value="{{ $unit->slug }}" {{ request('unit') == $unit->slug ? 'selected' : '' }}>
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
      <a href="{{ route('public.documents.index') }}" class="btn btn-outline-secondary w-100">
        Reset
      </a>
    </div>

  </div>
</form>

<!-- Indikator Filter Aktif -->
@php
    $filters = collect(request()->only(['type','unit','year','sort','category']))->filter();
@endphp

@if($filters->isNotEmpty())
  <div class="alert alert-info mt-3 p-2">
    <small>
      <i class="bi bi-funnel"></i> Filter sedang aktif: 
      @foreach($filters as $key => $value)
        <strong>{{ ucfirst($key) }}:</strong> {{ $value }}@if(!$loop->last), @endif
      @endforeach
      . Klik tombol Reset untuk menampilkan semua dokumen.
    </small>
  </div>
@endif
