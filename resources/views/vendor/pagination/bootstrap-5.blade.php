@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        {{-- Mobile View (sm kebawah) --}}
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Tombol Sebelumnya --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Sebelumnya</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                    </li>
                @endif

                {{-- Tombol Berikutnya --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Berikutnya</span>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Desktop View --}}
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div class="px-4">
                <p class="small text-muted">
                    Menampilkan
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    dari total
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <ul class="pagination">
                    {{-- Tombol Sebelumnya --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Nomor Halaman Short --}}
                    @php
                        $start = max(1, $paginator->currentPage() - 1);
                        $end = min($paginator->lastPage(), $paginator->currentPage() + 1);
                    @endphp

                    {{-- Halaman pertama --}}
                    @if ($start > 1)
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                        @if ($start > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    {{-- Halaman sekitar current --}}
                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active" st><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor

                    {{-- Halaman terakhir --}}
                    @if ($end < $paginator->lastPage())
                        @if ($end < $paginator->lastPage() - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
                    @endif

                    {{-- Tombol Berikutnya --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif

<style>
    .pagination .page-item.active .page-link {
        background-color: #030F6B; /* warna background aktif */
        border-color: #030F6B;     /* warna border aktif */
        color: #fff;               /* warna teks aktif */
    }

    .pagination .page-link {
        color: #333; /* warna default */
    }

    .pagination .page-link:hover {
        background-color: #f1f1f1; /* warna hover */
        border-color: #ddd;
    }
</style>
