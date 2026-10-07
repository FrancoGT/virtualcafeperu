<x-app-layout>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-ink text-white">
        <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-orange-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-40 -left-24 h-96 w-96 rounded-full bg-orange-500/10 blur-3xl" aria-hidden="true"></div>

        <div class="container-site relative grid items-center gap-12 py-14 lg:grid-cols-2 lg:py-20">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-orange-300 ring-1 ring-white/10">
                    <i class="fa-solid fa-mug-hot" aria-hidden="true"></i> Repostería &amp; cafetería
                </p>
                <h1 class="mt-5 text-4xl font-black leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                    Antojos caseros, <span class="text-orange-500">listos para ti</span>
                </h1>
                <p class="mt-5 max-w-lg text-lg text-slate-300">
                    Empanadas, postres y bebidas preparados cada día. Arma tu pedido en minutos, paga con Yape y confírmalo por WhatsApp.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#menu" class="btn-primary !px-7 !py-3.5 text-base">
                        Ver el menú <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="#como-pedir" class="btn-ghost-light !px-7 !py-3.5 text-base">¿Cómo pedir?</a>
                </div>

                <dl class="mt-10 grid max-w-md grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div>
                        <dt class="text-xs text-slate-400">Pedido</dt>
                        <dd class="font-black">100% en línea</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Pago</dt>
                        <dd class="font-black">Yape</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Confirmación</dt>
                        <dd class="font-black">WhatsApp</dd>
                    </div>
                </dl>
            </div>

            <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="aspect-square overflow-hidden rounded-[2rem] ring-8 ring-white/5 lg:ml-auto lg:max-w-md">
                    <img src="{{ asset('img/vendedora_pasteles.png') }}" alt="Vitrina con pasteles y postres de la casa"
                        class="h-full w-full object-cover" width="512" height="512" fetchpriority="high">
                </div>
                <div class="absolute -bottom-5 left-2 flex items-center gap-3 rounded-2xl bg-white p-3 pr-5 text-ink shadow-card-hover sm:-left-6">
                    <img src="{{ asset('img/venta_portada.png') }}" alt="" class="h-12 w-12 rounded-xl object-cover">
                    <div>
                        <p class="text-sm font-black">Hecho cada día</p>
                        <p class="text-xs text-ink-muted">Stock limitado por producto</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cómo pedir --}}
    <section id="como-pedir" class="scroll-mt-20 border-b border-slate-200 bg-white" aria-labelledby="como-pedir-title">
        <h2 id="como-pedir-title" class="sr-only">Cómo pedir</h2>
        <ol class="container-site grid gap-6 py-10 md:grid-cols-3">
            @foreach ([
                ['fa-solid fa-utensils', 'Elige del menú', 'Agrega tus productos favoritos a tu pedido.'],
                ['fa-solid fa-mobile-screen', 'Confirma y paga', 'Confirma el pedido y paga fácilmente con Yape.'],
                ['fa-brands fa-whatsapp', 'Avísanos por WhatsApp', 'Envíanos el detalle con un clic y lo preparamos.'],
            ] as $i => [$icon, $title, $text])
                <li class="flex items-start gap-4">
                    <span class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-xl text-orange-500">
                        <i class="{{ $icon }}" aria-hidden="true"></i>
                        <span class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-ink text-[10px] font-black text-white">{{ $i + 1 }}</span>
                    </span>
                    <div>
                        <h3 class="font-black">{{ $title }}</h3>
                        <p class="mt-0.5 text-sm text-ink-muted">{{ $text }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <livewire:pending-order-notification />

    {{-- Menú + carrito --}}
    <section id="menu" class="container-site scroll-mt-16 py-12" aria-labelledby="menu-title">
        <div class="mb-6">
            <p class="eyebrow">Nuestra carta</p>
            <h2 id="menu-title" class="section-title mt-1">Menú</h2>
        </div>

        <div class="lg:flex lg:items-start lg:gap-8">
            <div class="min-w-0 flex-1">
                <livewire:product-list />
            </div>

            <div class="sticky top-24 hidden w-80 flex-shrink-0 lg:block">
                @livewire('cart-panel', ['mode' => 'sidebar'])
            </div>
        </div>
    </section>

    <livewire:popular-products />

    <livewire:pedidos-pendientes />

</x-app-layout>
