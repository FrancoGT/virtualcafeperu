@props(['label', 'value', 'icon', 'href' => null, 'hint' => null])

@if ($href)
    <a href="{{ $href }}" class="card flex items-center gap-4 p-4 transition hover:shadow-card-hover sm:p-5">
@else
    <div class="card flex items-center gap-4 p-4 sm:p-5">
@endif
    <span class="hidden h-12 w-12 flex-shrink-0 sm:flex items-center justify-center rounded-xl bg-orange-50 text-lg text-orange-500">
        <i class="{{ $icon }}" aria-hidden="true"></i>
    </span>
    <div class="min-w-0">
        <p class="text-sm font-semibold text-ink-muted">{{ $label }}</p>
        <p class="truncate text-2xl font-extrabold tracking-tight text-ink">{{ $value }}</p>
        @if ($hint)
            <p class="truncate text-xs text-ink-muted">{{ $hint }}</p>
        @endif
    </div>
@if ($href)
    </a>
@else
    </div>
@endif
