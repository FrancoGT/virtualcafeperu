<div>
    @if ($orders->isNotEmpty())
        <section id="pedidos" class="scroll-mt-20 bg-white py-12" aria-labelledby="pedidos-title">
            <div class="container-site">
                <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="eyebrow">Seguimiento</p>
                        <h2 id="pedidos-title" class="section-title mt-1">Mis pedidos pendientes</h2>
                        <p class="mt-2 max-w-xl text-sm text-ink-muted">
                            Envíanos tu pedido por WhatsApp para confirmarlo. Si pagas por Yape, lo reservamos para ti.
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($orders as $order)
                        <article class="card flex flex-col p-5" wire:key="order-{{ $order->id }}">
                            <header class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-black">Pedido #{{ $order->id }}</h3>
                                    <p class="text-xs text-ink-muted">
                                        <time datetime="{{ $order->created_at->toIso8601String() }}">
                                            {{ $order->created_at->format('d/m/Y · H:i') }}
                                        </time>
                                    </p>
                                </div>
                                <span class="badge bg-amber-100 text-amber-800">
                                    <i class="fa-regular fa-clock" aria-hidden="true"></i> Pendiente
                                </span>
                            </header>

                            <ul class="my-4 flex-1 space-y-2 border-y border-dashed border-slate-200 py-4 text-sm">
                                @foreach ($order->orderDetails as $detail)
                                    <li class="flex justify-between gap-3">
                                        <span class="text-slate-600">
                                            <span class="font-bold text-ink">{{ $detail->quantity }}×</span>
                                            {{ $detail->product->name ?? 'Producto' }}
                                        </span>
                                        <span class="font-bold">S/ {{ number_format($detail->quantity * $detail->price, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="flex items-center justify-between">
                                <p class="text-sm text-ink-muted">Total</p>
                                <p class="text-lg font-black">S/ {{ number_format($order->total, 2) }}</p>
                            </div>

                            <a href="{{ \App\Http\Livewire\PedidosPendientes::whatsappLink($order) }}" target="_blank" rel="noopener"
                                class="btn mt-4 w-full bg-[#25D366] text-white hover:bg-[#1ebe5a] focus-visible:ring-[#25D366]">
                                <i class="fa-brands fa-whatsapp text-lg" aria-hidden="true"></i>
                                Notificar por WhatsApp
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
