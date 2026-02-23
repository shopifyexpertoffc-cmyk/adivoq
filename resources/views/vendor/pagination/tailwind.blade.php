@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page-item disabled">
                <span class="page-link"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-item">
                <span class="page-link"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-item disabled"><span class="page-link">{{ $element }}</span></span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                    @else
                        <a href="{{ $url }}" class="page-item"><span class="page-link">{{ $page }}</span></a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-item">
                <span class="page-link"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
            </a>
        @else
            <span class="page-item disabled">
                <span class="page-link"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
            </span>
        @endif
    </nav>
@endif