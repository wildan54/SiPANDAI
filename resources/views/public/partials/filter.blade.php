<form action="{{ route('public.home') }}" method="GET" class="mb-3">
  <div class="row mb-3">
    <div class="col-md-8 d-flex flex-wrap gap-2">
      <select name="category" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Kategori</option>
        @foreach($types as $type)
          <option value="{{ $type->id }}" {{ request('category') == $type->id ? 'selected' : '' }}>
            {{ $type->name }}
          </option>
        @endforeach
      </select>

      <select name="unit" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Bidang</option>
        @foreach($units as $unit)
          <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
            {{ $unit->name }}
          </option>
        @endforeach
      </select>

      <select name="year" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="">Semua Tahun</option>
        @foreach($years as $year)
          <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
            {{ $year }}
          </option>
        @endforeach
      </select>

      <select name="sort" class="form-select me-2 mb-2" style="max-width: 180px;">
        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
      </select>

      <button class="btn btn-primary mb-2">Filter</button>
      <a href="{{ route('public.home') }}" class="btn btn-secondary mb-2">Reset</a>
    </div>
  </div>
</form>