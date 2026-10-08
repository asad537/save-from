@if ($paginator->hasPages())
    <nav class="blog-pagination" role="navigation" aria-label="Blog pagination">
        <p class="pagination-summary">Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} guides</p>
        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Previous page"><i class="bi bi-chevron-left"></i></span>
            @else
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-gap" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-link page-number is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-link page-number" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="pagination-link is-disabled" aria-disabled="true" aria-label="Next page"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </nav>
@endif
