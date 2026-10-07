{{--
    Tarjeta de producto.
    - $showSubcategory: etiqueta con miniatura + nombre de la subcategoría (útil en búsquedas y
      "Populares"; en el menú agrupado sobra porque ya lo dice el título de la sección).
    - $subImage: URL de la imagen de la subcategoría si ya se resolvió (evita comprobar el disco
      por cada tarjeta); si no se pasa, se toma de la relación subcategory (cárguela con with()).
    Si el producto no tiene foto, se usa la de su subcategoría y, si tampoco hay, un icono.
--}}
@props(['product', 'showSubcategory' => false, 'subImage' => null])

@php
    $stock = (int) $product->quantity;
    $subcategory = $product->relationLoaded('subcategory') ? $product->subcategory : null;
    $ownImage = $product->image_url;
    // La imagen de la subcategoría sólo hace falta para la etiqueta o como respaldo
    $subImage = $subImage ?? ($subcategory && ($showSubcategory || !$ownImage) ? $subcategory->image_url : null);
    $image = $ownImage ?: $subImage;
@endphp

<article class="group flex gap-3 rounded-2xl bg-white p-3 ring-1 ring-slate-900/5 transition hover:shadow-card-hover hover:ring-orange-200 sm:gap-4 sm:p-4"
    wire:key="product-{{ $product->id }}">

    {{-- Foto (propia o, como respaldo, la de la subcategoría): 128px en móvil, 112px desde sm (2 columnas) --}}
    <div class="relative h-32 w-32 flex-shrink-0 overflow-hidden rounded-xl bg-orange-50 sm:h-28 sm:w-28" x-data="{ broken: false }">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy" decoding="async"
                x-show="!broken" x-on:error="broken = true"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100 {{ $stock > 0 ? '' : 'grayscale' }}">
        @endif
        <div class="flex h-full w-full items-center justify-center text-3xl text-orange-300" aria-hidden="true"
            @if ($image) x-show="broken" x-cloak @endif>
            <i class="fa-solid fa-mug-hot"></i>
        </div>
    </div>

    {{-- Información --}}
    <div class="flex min-w-0 flex-1 flex-col">
        @if ($showSubcategory && $subcategory)
            <p class="mb-1 flex min-w-0 items-center gap-1.5 text-xs font-bold text-ink-muted">
                <x-thumb :src="$subImage" class="h-4 w-4 rounded" />
                <span class="truncate">{{ $subcategory->name }}</span>
            </p>
        @endif

        <div class="flex items-start justify-between gap-2">
            <h3 class="font-black leading-snug">{{ $product->name }}</h3>
            @if ($stock <= 0)
                <span class="badge flex-shrink-0 bg-slate-100 text-slate-500">Agotado</span>
            @elseif ($stock <= 3)
                <span class="badge flex-shrink-0 bg-orange-50 text-orange-700">¡Quedan {{ $stock }}!</span>
            @endif
        </div>

        @if ($product->description && $product->description !== $product->name)
            <p class="mt-1 line-clamp-2 text-sm text-ink-muted">{{ $product->description }}</p>
        @endif

        <div class="mt-auto flex flex-wrap items-center justify-between gap-x-2 gap-y-2 pt-3">
            <p class="text-lg font-black text-ink">
                <span class="text-sm font-bold text-ink-muted">S/</span> {{ number_format($product->price, 2) }}
            </p>

            @if ($stock > 0)
                <button type="button" wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled"
                    wire:target="addToCart({{ $product->id }})"
                    class="btn-primary !px-4 !py-2" aria-label="Agregar {{ $product->name }} a mi pedido">
                    <i class="fa-solid fa-plus" wire:loading.remove wire:target="addToCart({{ $product->id }})" aria-hidden="true"></i>
                    <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="addToCart({{ $product->id }})" aria-hidden="true"></i>
                    <span>Agregar</span>
                </button>
            @else
                <button type="button" disabled class="btn !px-4 !py-2 bg-slate-100 text-slate-400">No disponible</button>
            @endif
        </div>
    </div>
</article>
