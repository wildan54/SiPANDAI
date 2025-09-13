<div class="card card-custom p-3 mb-3">
    <h5 class="fw-bold mb-2">{{ $title_1 ?? 'Dokumen Serupa' }}</h5>
    <ul class="list-group list-group-flush">
        @forelse($documents as $doc)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('public.documents.show', $doc->slug) }}" 
                   class="fw-semibold text-dark link-doc">
                    {{ $doc->title }}
                </a>
                <a href="{{ route('public.documents.show', $doc->slug) }}" 
                   class="btn btn-sm btn-custom">
                    Lihat
                </a>
            </li>
        @empty
            <li class="list-group-item text-muted">Tidak ada dokumen lain.</li>
        @endforelse
    </ul>
</div>

<div class="card card-custom p-3 mb-3">
    <h5 class="fw-bold mb-2">{{ $document->type->category->name }}</h5>
    <ul class="list-group list-group-flush">
        @forelse($sameCategoryTypes as $type)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('public.documents.show', $type->slug) }}" 
                   class="fw-semibold text-dark link-doc">
                    {{ $type->name}}
                </a>
                <a href="{{ route('public.documents.by-type', $type->slug) }}" 
                   class="btn btn-sm btn-custom">
                    Lihat
                </a>
            </li>
        @empty
            <li class="list-group-item text-muted">Tidak ada dokumen lain dalam kategori ini.</li>
        @endforelse
    </ul>
</div>

<style>
    /* Hover untuk judul dokumen */
    a.link-doc {
        transition: color 0.2s ease, text-decoration 0.2s ease;
        color: #212529;
        text-decoration: none;
    }
    a.link-doc:hover {
        color: #030f6b !important; /* Use !important to override Bootstrap */
    }

    /* Tombol custom */
    .btn-custom {
        background-color: #febc2f;
        border: none;
        color: #000; /* teks hitam biar kontras */
        font-weight: 600;
    }
    .btn-custom:hover {
        background-color: #e6a900; /* sedikit lebih gelap saat hover */
        color: #000;
    }
</style>
