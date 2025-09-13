<!-- Form Filter -->
<form action="{{ route('public.home') }}" method="GET" class="mb-3">
  <div class="row mb-3">
    <div class="col-md-8 d-flex flex-wrap gap-2">

      <!-- Dropdown Tipe Dokumen -->
      <select name="type" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Tipe</option>
        @foreach($types as $type)
          <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
            {{ $type->name }}
          </option>
        @endforeach
      </select>

      <!-- Unit -->
      <select name="unit" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Bidang</option>
        @foreach($units as $unit)
          <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
            {{ $unit->name }}
          </option>
        @endforeach
      </select>

      <!-- Tahun -->
      <select name="year" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Tahun</option>
        @foreach($years as $year)
          <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
            {{ $year }}
          </option>
        @endforeach
      </select>

      <!-- Sortir -->
      <select name="sort" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
      </select>

      <!-- Tombol -->
      <button class="btn mb-2" style="background-color: #FEBC2F;">Filter</button>
      <a href="{{ route('public.home') }}" class="btn btn-secondary mb-2">Reset</a>
    </div>
  </div>
</form>

<!-- Tag Kategori -->
<div class="nav-tag mb-4">
  <a href="{{ route('public.home') }}" 
     class="btn btn-sm {{ request('category') ? 'btn-outline-secondary' : 'btn-warning' }}">
     Semua
  </a>
  @foreach($categories as $category)
    <a href="{{ route('public.home', array_merge(request()->all(), ['category' => $category->id])) }}" 
       class="btn btn-sm {{ request('category') == $category->id ? 'btn-warning' : 'btn-outline-secondary' }}">
       {{ $category->name }}
    </a>
  @endforeach
</div>