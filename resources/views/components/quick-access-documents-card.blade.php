<div class="card card-custom p-3 mt-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">{{ $title_1 ?? 'Dokumen Serupa' }}</h5>
        @if(!empty($dropdown) && !empty($id))
            <button class="btn btn-sm btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}">
                <i class="bi bi-chevron-down"></i>
            </button>
        @endif
    </div>
    <div class="collapse show" id="{{ $id ?? '' }}">
        <ul class="list-group list-group-flush mt-2">
            @forelse($documents as $doc)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('public.documents.show', $doc->slug) }}" 
                       class="fw-semibold link-doc">
                        {{ $doc->title }}
                    </a>
                    <a href="{{ route('public.documents.show', $doc->slug) }}" 
                    class="btn btn-sm btn-outline-dark btn-custom-outline">
                        Lihat
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">Tidak ada dokumen lain.</li>
            @endforelse
        </ul>
    </div>
</div>