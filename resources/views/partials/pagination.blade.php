@if ($paginator->hasPages())
    <nav class="pagination" aria-label="صفحه‌بندی">
        @if ($paginator->onFirstPage())
            <span class="is-disabled"><i class="ri-arrow-right-s-line"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="قبلی"><i class="ri-arrow-right-s-line"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="is-disabled">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="is-active">{{ fa_num($page) }}</span>
                    @else
                        <a href="{{ $url }}">{{ fa_num($page) }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="بعدی"><i class="ri-arrow-left-s-line"></i></a>
        @else
            <span class="is-disabled"><i class="ri-arrow-left-s-line"></i></span>
        @endif
    </nav>
@endif
