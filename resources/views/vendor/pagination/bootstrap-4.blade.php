@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a></li>
            @endif

            {{-- Pages --}}
            @php
                $total = $paginator->lastPage();
                $display = 4; // jumlah halaman awal yang ditampilkan
                $current = $paginator->currentPage();
            @endphp

            {{-- Tampilkan halaman pertama sampai $display --}}
            @for ($i = 1; $i <= min($display, $total); $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Jika halaman terakhir belum ditampilkan --}}
            @if ($total > $display)
                @if($current > $display && $current < $total)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    {{-- Tampilkan halaman aktif jika di tengah --}}
                    <li class="page-item active"><span class="page-link">{{ $current }}</span></li>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @else
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif
                {{-- Halaman terakhir --}}
                <li class="page-item {{ $current == $total ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($total) }}">{{ $total }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
