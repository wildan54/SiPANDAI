@extends('layouts.app')

@section('title', 'Semua Dokumen')

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dokumen</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Dokumen</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid py-2">

  <!-- Header Actions -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
    <div class="d-flex flex-wrap align-items-center gap-2">

      <a href="{{ route('documents.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Tambah Dokumen
      </a>

      @if(auth()->user()->role === 'administrator')
        <span id="selectedCount" class="text-muted ml-2">(0 dokumen dipilih)</span>

        <button id="btnApproveSelected" class="btn btn-success" disabled>
          <i class="fas fa-check mr-1"></i> Setujui
        </button>

        <button id="btnRejectSelected" class="btn btn-danger" disabled>
          <i class="fas fa-times mr-1"></i> Tolak
        </button>
      @endif

      @if(auth()->user()->role === 'editor')
        <span id="selectedCount" class="text-muted ml-2">(0 dokumen dipilih)</span>
        <button id="btnSubmitSelected" class="btn btn-warning text-white" disabled>
          <i class="fas fa-paper-plane mr-1"></i> Submit
        </button>
      @endif
    </div>
  </div>

  <!-- Card -->
  <div class="card shadow-sm border-0">

    <!-- Filter -->
    <div class="card-header bg-primary">
      <form method="GET" action="{{ route('documents.index') }}" class="row g-2">

        <div class="col-12 col-md-3 mt-2">
          <select name="type" class="form-control form-control-sm">
            <option value="">Tipe Dokumen</option>
            @foreach($documentTypes as $type)
              <option value="{{ $type->slug }}" {{ request('type') == $type->slug ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>

        @if(auth()->user()->role === 'administrator')
        <div class="col-12 col-md-3 mt-2">
          <select name="unit" class="form-control form-control-sm">
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
              <option value="{{ $unit->slug }}" {{ request('unit') == $unit->slug ? 'selected' : '' }}>
                {{ $unit->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12 col-md-2 mt-2">
          <select name="year" class="form-control form-control-sm">
            <option value="">Tahun</option>
            @foreach($years as $year)
              <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                {{ $year }}
              </option>
            @endforeach
          </select>
        </div>
        @endif

        <div class="col-12 col-md-4 mt-2">
          <div class="input-group input-group-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari dokumen...">
            <div class="input-group-append">
              <button class="btn btn-light"><i class="fas fa-search"></i></button>
            </div>
          </div>
        </div>

      </form>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
          <thead class="thead-light">
            <tr>
              <th class="text-center" style="width:3%">
                <input type="checkbox" id="checkAll">
              </th>
              <th style="width:5%">No</th>
              <th>Judul Dokumen</th>
              <th style="width:15%">Tipe</th>
              <th style="width:15%">Unit</th>
              <th style="width:12%">Uploader</th>
              <th style="width:15%">Status</th>
              <th style="width:10%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>

          @forelse($documents as $doc)
            <tr>
              <td class="text-center">
                <input type="checkbox" class="document-checkbox" value="{{ $doc->id }}">
              </td>
              <td>{{ $loop->iteration + ($documents->currentPage() - 1) * $documents->perPage() }}</td>
              <td class="font-weight-semibold">{{ $doc->title }}</td>
              <td><span class="badge badge-info">{{ $doc->type->name ?? '-' }}</span></td>
              <td>{{ $doc->unit->name ?? '-' }}</td>
              <td>{{ $doc->uploader->name ?? '-' }}</td>

              <td>
                @php
                  $map = [
                    'approved' => ['Disetujui','success','check-circle'],
                    'rejected' => ['Ditolak','danger','times-circle'],
                    'submitted'=> ['Menunggu','warning','clock'],
                    'draft'    => ['Draf','secondary','file-alt'],
                  ];
                  [$label,$class,$icon] = $map[$doc->status] ?? $map['draft'];
                  $visibility = $doc->visibility === 'public' ? 'Publik' : 'Privat';
                @endphp
                <span class="badge badge-{{ $class }}">
                  <i class="fas fa-{{ $icon }} mr-1"></i>{{ $label }} · {{ $visibility }}
                </span>
              </td>

              <td class="text-center">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">Aksi</button>
                  <div class="dropdown-menu dropdown-menu-right">

                    <a class="dropdown-item showDocument" href="javascript:void(0)" data-id="{{ $doc->id }}">
                      <i class="fas fa-eye mr-2"></i>Lihat
                    </a>
                @if($doc->file_path)
                    <a href="{{ route('public.documents.download.file', $doc->slug) }}"
                        class="dropdown-item">
                        <i class="fas fa-download mr-2"></i>Unduh
                    </a>
                @elseif($doc->file_embed)
                    <a href="{{ route('public.documents.download.embed', $doc->slug) }}"
                        class="dropdown-item">
                        <i class="fas fa-download mr-2"></i>Unduh
                    </a>
                @endif
                    <a class="dropdown-item" href="{{ route('documents.edit',$doc->id) }}">
                      <i class="fas fa-edit mr-2"></i>Edit
                    </a>

                    <div class="dropdown-divider"></div>

                    <form action="{{ route('documents.destroy',$doc->id) }}" method="POST">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger" onclick="return confirm('Hapus dokumen ini?')">
                        <i class="fas fa-trash mr-2"></i>Hapus
                      </button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted">Belum ada dokumen</td>
            </tr>
          @endforelse

          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <div class="card-footer d-flex justify-content-between align-items-center">
      <div>
        Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
      </div>
      <div>
        {{ $documents->links('pagination::bootstrap-4') }}
      </div>
    </div>
  </div>
</div>

@include('documents.modal_detail')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.document-checkbox');
    const checked = document.querySelectorAll('.document-checkbox:checked');
    const count = checked.length;

    const counter = document.getElementById('selectedCount');
    if (counter) counter.innerText = `(${count} dokumen dipilih)`;

    ['btnApproveSelected','btnRejectSelected','btnSubmitSelected'].forEach(id => {
      const btn = document.getElementById(id);
      if (btn) btn.disabled = count === 0;
    });

    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
      checkAll.checked = checkboxes.length > 0 && count === checkboxes.length;
    }
  }

  // CHECK ALL
  const checkAll = document.getElementById('checkAll');
  if (checkAll) {
    checkAll.addEventListener('change', function () {
      document.querySelectorAll('.document-checkbox').forEach(cb => {
        cb.checked = this.checked;
      });
      updateSelectedCount();
    });
  }

  // SINGLE CHECK
  document.querySelectorAll('.document-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
  });

  function getSelected() {
    return Array.from(document.querySelectorAll('.document-checkbox:checked'))
      .map(cb => cb.value);
  }

  function submitBulk(ids, url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    ids.forEach(id => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'document_ids[]';
      input.value = id;
      form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
  }

  // BULK ACTION BUTTONS
  document.getElementById('btnApproveSelected')?.addEventListener('click', () => {
    const ids = getSelected();
    if (!ids.length) return alert('Pilih minimal satu dokumen');
    if (!confirm('Setujui dokumen terpilih?')) return;
    submitBulk(ids, '{{ route('documents.bulkApprove') }}');
  });

  document.getElementById('btnRejectSelected')?.addEventListener('click', () => {
    const ids = getSelected();
    if (!ids.length) return alert('Pilih minimal satu dokumen');
    if (!confirm('Tolak dokumen terpilih?')) return;
    submitBulk(ids, '{{ route('documents.bulkReject') }}');
  });

  document.getElementById('btnSubmitSelected')?.addEventListener('click', () => {
    const ids = getSelected();
    if (!ids.length) return alert('Pilih minimal satu dokumen');
    if (!confirm('Submit dokumen terpilih?')) return;
    submitBulk(ids, '{{ route('documents.bulkSubmit') }}');
  });

  // init
  updateSelectedCount();
});
</script>
@endpush
@endsection