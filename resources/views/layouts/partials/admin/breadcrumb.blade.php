@if (count($breadcrumbs) > 1)
    <nav class="mb-3" aria-label="Ruta de navegación">
        <ol class="flex flex-wrap items-center gap-1.5 text-xs text-ink-muted">
            @foreach ($breadcrumbs as $item)
                <li class="flex items-center gap-1.5">
                    @unless ($loop->first)
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300" aria-hidden="true"></i>
                    @endunless

                    @if (isset($item['route']) && !$loop->last)
                        <a href="{{ $item['route'] }}" class="hover:text-ink hover:underline">{{ $item['name'] }}</a>
                    @else
                        <span class="font-semibold text-slate-600" @if ($loop->last) aria-current="page" @endif>{{ $item['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
