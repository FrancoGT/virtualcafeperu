{{-- Paginación del panel (basada en la vista tailwind de Laravel 8) --}}
<nav role="navigation" aria-label="Paginación" class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">
    <p class="text-sm text-ink-muted">
        @if ($paginator->total())
            Mostrando <span class="font-semibold text-ink">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-ink">{{ $paginator->lastItem() }}</span>
            de <span class="font-semibold text-ink">{{ $paginator->total() }}</span>
        @else
            Sin resultados
        @endif
    </p>

    @if ($paginator->hasPages())
        @php
            $base = 'inline-flex h-9 min-w-[2.25rem] items-center justify-center rounded-lg px-2 text-sm font-semibold transition';
            $link = $base . ' text-slate-600 hover:bg-slate-100 hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500';
            $off = $base . ' cursor-default text-slate-300';
        @endphp

        <ul class="flex flex-wrap items-center justify-center gap-1">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $off }}" aria-disabled="true" aria-label="Anterior"><i class="fa-solid fa-chevron-left text-xs"></i></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $link }}" aria-label="Anterior"><i class="fa-solid fa-chevron-left text-xs"></i></a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="{{ $off }}">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $base }} bg-ink text-white" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $link }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $link }}" aria-label="Siguiente"><i class="fa-solid fa-chevron-right text-xs"></i></a>
                @else
                    <span class="{{ $off }}" aria-disabled="true" aria-label="Siguiente"><i class="fa-solid fa-chevron-right text-xs"></i></span>
                @endif
            </li>
        </ul>
    @endif
</nav>
