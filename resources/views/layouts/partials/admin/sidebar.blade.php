@php
    $links = [
        [
            'icon' => 'fa-solid fa-gauge',
            'name' => 'Dashboard',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard')
        ],
        [
            'icon' => 'fa-solid fa-tags',
            'name' => 'Categorías',
            'route' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.*')
        ],
        [
            'icon' => 'fa-solid fa-tag',
            'name' => 'Subcategorías',
            'route' => route('admin.subcategories.index'),
            'active' => request()->routeIs('admin.subcategories.*')
        ],
        [
            'icon' => 'fa-solid fa-box',
            'name' => 'Productos',
            'route' => route('admin.products.index'),
            'active' => request()->routeIs('admin.products.*')
        ],
        [
            'icon' => 'fa-solid fa-receipt',
            'name' => 'Pedidos',
            'route' => route('admin.orders.index'),
            'active' => request()->routeIs('admin.orders.*')
        ]
    ];
@endphp

<aside id="admin-sidebar" aria-label="Menú de administración"
    class="fixed bottom-0 left-0 top-16 z-40 w-64 -translate-x-full border-r border-slate-200 bg-white lg:translate-x-0"
    x-bind:class="{ '!translate-x-0': open, 'lg:!-translate-x-full': !open, 'transition-transform duration-200': ready }">
    <nav class="h-full overflow-y-auto px-3 py-5">
        <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-[.15em] text-slate-400">Gestión</p>
        <ul class="space-y-1">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['route'] }}" x-on:click="closeOnMobile()"
                        @if ($link['active']) aria-current="page" @endif
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                            {{ $link['active'] ? 'bg-orange-50 text-orange-700' : 'text-slate-600 hover:bg-slate-100 hover:text-ink' }}">
                        <span class="inline-flex w-5 justify-center {{ $link['active'] ? 'text-orange-500' : 'text-slate-400' }}">
                            <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                        </span>
                        {{ $link['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
