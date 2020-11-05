
@php
    $paginator -> appends(request() -> input());
@endphp

@if ($paginator -> lastPage() > 1)
    <ul class="pagination">
        @if ($paginator -> currentPage() != 1)
            <li>
                <a href="{{ $paginator -> url(1) }}">
                    <i class="material-icons dp48">chevron_left</i>
                </a>
            </li>
        @endif

        @for ($i = 1; $i <= $paginator -> lastPage(); $i ++)
            <li class="waves-effect {{ ($paginator -> currentPage() == $i) ? ' active' : '' }}">
                <a href="{{ $paginator -> url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($paginator -> currentPage() != $paginator -> lastPage())
            <li>
                <a href="{{ $paginator -> url($paginator -> currentPage() + 1) }}">
                    <i class="material-icons dp48">chevron_right</i>
                </a>
            </li>
        @endif
    </ul>
@endif
