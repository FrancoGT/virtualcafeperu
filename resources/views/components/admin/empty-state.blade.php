@props(['icon' => 'fa-solid fa-inbox', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'card flex flex-col items-center px-6 py-14 text-center']) }}>
    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-orange-50 text-xl text-orange-500">
        <i class="{{ $icon }}" aria-hidden="true"></i>
    </span>
    <h2 class="mt-4 text-base font-bold text-ink">{{ $title }}</h2>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-ink-muted">{{ $description }}</p>
    @endif
    @if ($slot->isNotEmpty())
        <div class="mt-5">{{ $slot }}</div>
    @endif
</div>
