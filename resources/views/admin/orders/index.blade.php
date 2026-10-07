<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Pedidos', 'route' => route('admin.orders.index')],
]">

    <x-admin.page-header title="Pedidos" description="Pedidos recibidos, del más reciente al más antiguo. Cambia el estado desde la última columna." />

    @php
        // Tono del selector según el estado actual (mismos colores que los badges)
        $statusTone = [
            'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
            'paid'    => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'served'  => 'bg-sky-50 text-sky-800 border-sky-200',
            'refused' => 'bg-red-50 text-red-800 border-red-200',
        ];
    @endphp

    @php $filtering = request()->filled('search') || request()->filled('status') || request()->filled('category_id'); @endphp

    @if ($orders->count() || $filtering)
        <x-admin.filters placeholder="N.º de pedido o cliente..." :keys="['search', 'status', 'category_id']">
            <x-admin.filter-select name="category_id" label="Categoría" :options="$categories->pluck('name', 'id')->all()" />
            <x-admin.filter-select name="status" label="Estado"
                :options="['pending' => 'Pendiente', 'paid' => 'Pagado', 'served' => 'Servido', 'refused' => 'Rechazado']" />
        </x-admin.filters>
    @endif

    @if ($category && $orders->count())
        <p class="mb-4 flex items-start gap-2 rounded-xl bg-orange-50 px-4 py-3 text-sm text-orange-900 ring-1 ring-inset ring-orange-200">
            <i class="fa-solid fa-circle-info mt-0.5 text-orange-500" aria-hidden="true"></i>
            <span>
                Pedidos con al menos un producto de <strong>{{ $category->name }}</strong>. En el detalle se resaltan esos productos;
                el total es el del pedido completo.
            </span>
        </p>
    @endif

    @if ($orders->count())
        <x-admin.table :paginator="$orders">
            <thead>
                <tr>
                    <th scope="col">Pedido</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Detalle</th>
                    <th scope="col" class="text-right">Total</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td class="whitespace-nowrap">
                            <p class="font-bold text-ink">#{{ $order->id }}</p>
                            <p class="text-xs text-ink-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="whitespace-nowrap">{{ $order->user->name ?? 'N/A' }}</td>
                        <td>
                            <ul class="min-w-[14rem] space-y-0.5">
                                @foreach ($order->orderDetails as $detail)
                                    @php
                                        // Con filtro por categoría se atenúan los productos de otras categorías
                                        $outside = $category && optional(optional($detail->product)->subcategory)->category_id !== $category->id;
                                    @endphp
                                    <li class="text-sm {{ $outside ? 'opacity-50' : '' }}">
                                        <span class="font-semibold text-ink">{{ $detail->quantity }}×</span>
                                        {{ $detail->product->name ?? 'Producto Eliminado' }}
                                        <span class="text-xs text-ink-muted">· S/. {{ $detail->price }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="whitespace-nowrap text-right font-bold tabular-nums text-ink">S/. {{ number_format($order->total, 2) }}</td>
                        <td>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="status-{{ $order->id }}" class="sr-only">Estado del pedido #{{ $order->id }}</label>
                                <select name="status" id="status-{{ $order->id }}" data-current="{{ $order->status }}"
                                    onchange="var paid = ['paid', 'served'].indexOf(this.dataset.current) !== -1;
                                        var msg = (this.value === 'paid' && !paid) ? 'Al marcar el pedido #{{ $order->id }} como pagado se descontará el stock de sus productos. ¿Continuar?'
                                            : (paid && this.value !== 'paid' && this.value !== 'served') ? 'El pedido #{{ $order->id }} dejará de estar pagado y se devolverá el stock de sus productos. ¿Continuar?' : null;
                                        var select = this;
                                        if (!msg) { select.form.submit(); return; }
                                        confirmAction({ title: 'Cambiar estado del pedido', text: msg, confirmText: 'Sí, cambiar' })
                                            .then(function (ok) { if (ok) { select.form.submit(); } else { select.value = select.dataset.current; } });"
                                    class="w-36 cursor-pointer rounded-full border py-1.5 pl-3 pr-8 text-xs font-bold focus:border-orange-500 focus:ring-orange-500 {{ $statusTone[$order->status] ?? 'border-slate-200 bg-slate-50 text-slate-700' }}">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Pagado</option>
                                    @if ($order->status == 'served')
                                        {{-- Estado válido en el backend sin opción propia: se muestra para no aparentar "Pendiente" --}}
                                        <option value="served" selected>Servido</option>
                                    @endif
                                    <option value="refused" {{ $order->status == 'refused' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>
    @elseif ($filtering)
        @include('admin.partials.no-results')
    @else
        <x-admin.empty-state icon="fa-solid fa-receipt" title="Todavía no hay pedidos"
            description="Los pedidos que hagan los clientes desde la tienda aparecerán aquí." />
    @endif

</x-admin-layout>
