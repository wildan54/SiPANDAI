<div class="card card-custom p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"> {{ $title }}</h5>
        <button class="btn btn-sm btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse show" id="{{ $id }}">
        <ul class="list-group list-group-flush mt-2">
            @forelse($types as $type)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('public.documents.types', $type->slug) }}" 
                       class="fw-semibold link-doc" style="color:#030F6B;">
                        {{ $type->name }}
                    </a>
                    <a href="{{ route('public.documents.types', $type->slug) }}" 
                    class="btn btn-sm btn-outline-dark btn-custom-outline">
                        Lihat
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">Tidak ada tipe dokumen lain.</li>
            @endforelse
        </ul>
    </div>
</div>

<style>

    .link-doc {
        text-decoration: none;
        color: #030F6B;
    }
    
    .btn-custom-outline {
        background-color: white;
        border: 1px solid #030F6B;
        color: #030F6B;
    }
    .btn-custom-outline:hover {
        background-color: #030F6B;;
        color: white;
        border: 1px solid #030F6B;
    }
</style>