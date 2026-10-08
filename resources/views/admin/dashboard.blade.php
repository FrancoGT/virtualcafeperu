<x-admin-layout title="Dashboard" :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
]">

    <x-admin.page-header title="Hola, {{ auth()->user()->name }}" description="Resumen general de la tienda.">
        
    </x-admin.page-header>

    {{-- Filtro por categoría (GET ?category_id) --}}
    @php $filter = $category ? ['category_id' => $category->id] : []; @endphp
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4 flex flex-wrap items-center gap-2">
        <label for="dashboard-category" class="text-sm font-semibold text-ink-muted">
            <i class="fa-solid fa-filter mr-1 text-xs" aria-hidden="true"></i> Categoría
        </label>
        <select name="category_id" id="dashboard-category" class="form-input w-auto min-w-[12rem]" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach ($categories as $option)
                <option value="{{ $option->id }}" {{ optional($category)->id === $option->id ? 'selected' : '' }}>{{ $option->name }}</option>
            @endforeach
        </select>
        <noscript><button type="submit" class="btn-secondary py-2.5">Aplicar</button></noscript>
        @if ($category)
            <a href="{{ route('admin.dashboard') }}" class="btn-action">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i> Quitar filtro
            </a>
        @endif
    </form>

    @if ($category)
        <p class="mb-4 flex items-start gap-2 rounded-xl bg-orange-50 px-4 py-3 text-sm text-orange-900 ring-1 ring-inset ring-orange-200">
            <i class="fa-solid fa-circle-info mt-0.5 text-orange-500" aria-hidden="true"></i>
            <span>
                Mostrando datos de <strong>{{ $category->name }}</strong>. Los pedidos son los que incluyen al menos un producto
                de esta categoría (su total es el del pedido completo); los ingresos cuentan sólo esos productos.
            </span>
        </p>
    @endif

    {{-- Resumen --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
        <x-admin.stat-card label="Productos" :value="$totals['products']" icon="fa-solid fa-box"
            :href="route('admin.products.index', $filter)"
            :hint="$outOfStock ? $outOfStock . ' sin stock' : 'Todos con stock'" />
        @php $inactiveCategories = $totals['categories'] - $totals['active_categories']; @endphp
        <x-admin.stat-card label="Categorías" :value="$totals['categories']" icon="fa-solid fa-tags"
            :href="route('admin.categories.index')"
            :hint="!$totals['categories'] ? 'Ninguna registrada'
                : (!$inactiveCategories ? 'Todas activas'
                : $totals['active_categories'] . ' ' . ($totals['active_categories'] === 1 ? 'activa' : 'activas') . ' · ' . $inactiveCategories . ' ' . ($inactiveCategories === 1 ? 'inactiva' : 'inactivas'))" />
        @php $emptySubcategories = $totals['empty_subcategories']; @endphp
        <x-admin.stat-card label="Subcategorías" :value="$totals['subcategories']" icon="fa-solid fa-tag"
            :href="route('admin.subcategories.index', $filter)"
            :hint="!$totals['subcategories'] ? 'Ninguna registrada'
                : (!$emptySubcategories ? 'Todas con productos'
                : $emptySubcategories . ' sin productos')" />
        <x-admin.stat-card label="Pedidos" :value="$totals['orders']" icon="fa-solid fa-receipt"
            :href="route('admin.orders.index', $filter)"
            :hint="($ordersByStatus['pending'] ?? 0) . ' pendientes'" />
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Pedidos recientes --}}
        <section class="lg:col-span-2" aria-labelledby="recent-orders">
            <div class="mb-3 flex items-center justify-between">
                <h2 id="recent-orders" class="text-base font-bold text-ink">Pedidos recientes</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn-action">Ver todos <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i></a>
            </div>

            @if ($recentOrders->count())
                <x-admin.table>
                    <thead>
                        <tr>
                            <th scope="col">Pedido</th>
                            <th scope="col">Cliente</th>
                            <th scope="col" class="text-right">Total</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <p class="font-bold text-ink">#{{ $order->id }}</p>
                                    <p class="text-xs text-ink-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="whitespace-nowrap">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap text-right font-semibold tabular-nums text-ink">S/. {{ number_format($order->total, 2) }}</td>
                                <td><x-admin.order-status :status="$order->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-admin.table>
            @else
                <x-admin.empty-state icon="fa-solid fa-receipt"
                    :title="$category ? 'No hay pedidos con productos de ' . $category->name : 'Todavía no hay pedidos'" />
            @endif
        </section>

        <div class="space-y-6">
            {{-- Ingresos --}}
            <div class="rounded-2xl bg-ink p-5 text-white shadow-card">
                <p class="text-sm font-semibold text-slate-300">
                    {{ $category ? 'Ingresos de ' . $category->name . ' en pedidos pagados' : 'Ingresos de pedidos pagados' }}
                </p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight tabular-nums">S/. {{ number_format($revenue, 2) }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $ordersByStatus['paid'] ?? 0 }} pedidos con estado «Pagado»</p>
            </div>

            {{-- Pedidos por estado --}}
            <section class="card p-5" aria-labelledby="orders-by-status">
                <h2 id="orders-by-status" class="text-base font-bold text-ink">Pedidos por estado</h2>
                @php
                    $statusRows = [
                        'pending' => ['Pendientes', 'bg-amber-400'],
                        'paid' => ['Pagados', 'bg-emerald-500'],
                        'served' => ['Servidos', 'bg-sky-500'],
                        'refused' => ['Rechazados', 'bg-red-500'],
                    ];
                @endphp
                <ul class="mt-4 space-y-3">
                    @foreach ($statusRows as $key => [$label, $bar])
                        @php $count = $ordersByStatus[$key] ?? 0; @endphp
                        @continue($key === 'served' && !$count)
                        <li>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">{{ $label }}</span>
                                <span class="font-bold tabular-nums text-ink">{{ $count }}</span>
                            </div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $bar }}"
                                    style="width: {{ $totals['orders'] ? round($count / $totals['orders'] * 100) : 0 }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Más pedidos --}}
            <section class="card p-5" aria-labelledby="top-products">
                <h2 id="top-products" class="text-base font-bold text-ink">
                    Productos más pedidos @if ($category)<span class="font-semibold text-ink-muted">· {{ $category->name }}</span>@endif
                </h2>
                @if ($topProducts->count())
                    <ol class="mt-4 space-y-3">
                        @foreach ($topProducts as $row)
                            <li class="flex items-center gap-3 text-sm">
                                <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-orange-50 text-xs font-bold text-orange-600">{{ $loop->iteration }}</span>
                                <span class="min-w-0 flex-1 truncate text-slate-700">{{ $row->product->name ?? 'Producto Eliminado' }}</span>
                                <span class="font-bold tabular-nums text-ink">{{ $row->units }} u.</span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="mt-3 text-sm text-ink-muted">Aún no hay productos pedidos.</p>
                @endif
            </section>
        </div>
    </div>

</x-admin-layout>
