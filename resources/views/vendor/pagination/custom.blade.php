{{-- resources/views/vendor/pagination/custom.blade.php --}}
@if ($paginator->hasPages())
  <nav aria-label="Custom Pagination">
    <ul class="my-custom-pagination">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="disabled"><span>&laquo; Prev</span></li>
      @else
        <li><a href="{{ $paginator->previousPageUrl() }}">&laquo; Prev</a></li>
      @endif

      {{-- Page Numbers --}}
      @foreach ($elements as $element)
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li class="active"><span>{{ $page }}</span></li>
            @else
              <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}">Next &raquo;</a></li>
      @else
        <li class="disabled"><span>Next &raquo;</span></li>
      @endif
    </ul>
  </nav>
@endif
