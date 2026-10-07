@php
    $isDrawer = $mode === 'drawer';
@endphp

<div @if ($isDrawer)
        x-data="{ open: false }"
        x-on:open-cart.window="open = true"
        x-on:keydown.escape.window="open = false"
    @endif>

    @if ($isDrawer && $count > 0)
        {{-- Barra flotante en móvil --}}
        <div class="fixed inset-x-0 bottom-0 z-30 p-3 lg:hidden" x-show="!open" x-transition.opacity>
            <button type="button" x-on:click="open = true"
                class="btn-primary w-full justify-between !rounded-2xl !px-5 !py-3.5 text-base shadow-card-hover">
                <span class="flex items-center gap-2">
                    <span class="flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-white/20 px-1.5 text-sm">{{ $count }}</span>
                    Ver mi pedido
                </span>
                <span>S/ {{ number_format($total, 2) }}</span>
            </button>
        </div>
    @endif

    @if ($isDrawer)
        {{-- Fondo --}}
        <div x-show="open" x-cloak x-transition.opacity x-on:click="open = false"
            class="fixed inset-0 z-50 bg-ink/50 backdrop-blur-sm" aria-hidden="true"></div>
    @endif

    <aside
        @if ($isDrawer)
            x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            x-trap.noscroll="open"
            role="dialog" aria-modal="true" aria-labelledby="cart-title-{{ $mode }}"
            class="fixed inset-y-0 right-0 z-50 flex w-full max-w-md flex-col bg-white shadow-2xl"
        @else
            class="card flex max-h-[calc(100vh-6rem)] flex-col overflow-hidden"
            aria-labelledby="cart-title-{{ $mode }}"
        @endif>

        {{-- Encabezado --}}
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 id="cart-title-{{ $mode }}" class="flex items-center gap-2 text-lg font-black">
                <i class="fa-solid fa-bag-shopping text-orange-500" aria-hidden="true"></i>
                Tu pedido
                @if ($count > 0)
                    <span class="badge bg-orange-100 text-orange-700">{{ $count }}</span>
                @endif
            </h2>
            @if ($isDrawer)
                <button type="button" x-on:click="open = false" class="qty-btn" aria-label="Cerrar carrito">
                    <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
                </button>
            @endif
        </div>

        @if (count($items) > 0)
            {{-- Productos --}}
            <ul class="flex-1 divide-y divide-slate-100 overflow-y-auto px-5" wire:loading.class="opacity-60">
                @foreach ($items as $productId => $item)
                    @php $max = (int) ($stock[$productId] ?? 0); @endphp
                    <li class="flex gap-3 py-4" wire:key="cart-{{ $mode }}-{{ $productId }}">
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-bold">{{ $item['name'] }}</p>
                            <p class="text-sm text-ink-muted">S/ {{ number_format($item['price'], 2) }} c/u</p>

                            <div class="mt-2 inline-flex items-center rounded-full border border-slate-200">
                                <button type="button" class="qty-btn"
                                    wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                    aria-label="Quitar una unidad de {{ $item['name'] }}">
                                    <i class="fa-solid {{ $item['quantity'] > 1 ? 'fa-minus' : 'fa-trash-can' }} text-xs" aria-hidden="true"></i>
                                </button>
                                <span class="w-7 text-center text-sm font-black" aria-label="Cantidad">{{ $item['quantity'] }}</span>
                                <button type="button" class="qty-btn"
                                    wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                    @if ($item['quantity'] >= $max) disabled title="No hay más unidades disponibles" @endif
                                    aria-label="Agregar una unidad de {{ $item['name'] }}">
                                    <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col items-end justify-between">
                            <p class="font-black">S/ {{ number_format($item['subtotal'], 2) }}</p>
                            <button type="button" wire:click="removeFromCart({{ $productId }})"
                                class="text-xs font-bold text-ink-muted underline-offset-2 hover:text-red-600 hover:underline">
                                Quitar
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Totales --}}
            <div class="border-t border-slate-100 bg-slate-50/70 px-5 py-4">
                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between text-ink-muted">
                        <dt>Subtotal ({{ $count }} {{ $count === 1 ? 'producto' : 'productos' }})</dt>
                        <dd>S/ {{ number_format($total, 2) }}</dd>
                    </div>
                    <div class="flex justify-between pt-1 text-lg font-black">
                        <dt>Total</dt>
                        <dd>S/ {{ number_format($total, 2) }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('checkout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn-primary w-full !py-3 text-base">
                        Confirmar pedido
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <p class="mt-3 flex items-center justify-center gap-2 text-xs text-ink-muted">
                    <i class="fa-solid fa-mobile-screen" aria-hidden="true"></i>
                    Pagas con Yape y lo confirmas por WhatsApp
                </p>
            </div>
        @else
            {{-- Vacío --}}
            <div class="flex flex-1 flex-col items-center justify-center px-6 py-10 text-center">
                <span class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-orange-50 text-2xl text-orange-500">
                    <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>
                </span>
                <p class="font-black">Tu pedido está vacío</p>
                <p class="mt-1 text-sm text-ink-muted">Agrega productos del menú para comenzar.</p>
                @if ($isDrawer)
                    <a href="{{ route('home') }}#menu" x-on:click="open = false" class="btn-outline mt-5">Ver el menú</a>
                @endif
            </div>
        @endif
    </aside>
</div>
