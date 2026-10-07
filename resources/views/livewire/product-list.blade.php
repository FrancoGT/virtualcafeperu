<div x-data x-on:menu-filtered.window="$el.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="scroll-mt-20">

    {{--
        Explorar por categoría.
        - Móvil/tablet: chips de 36px (miniatura + nombre + nº de productos), en varias líneas si no caben.
        - Escritorio (lg): tarjetas grandes con la foto de fondo y el texto sobre un degradado.
    --}}
    @if ($categories->count() > 1)
        <div class="mb-4 lg:mb-6" role="group" aria-labelledby="explore-categories">
            <div class="mb-2 flex items-center justify-between gap-2 lg:mb-3">
                <h3 id="explore-categories" class="text-xs font-black uppercase tracking-widest text-ink-muted">Explora por categoría</h3>
                @if ($selectedCategory)
                    <button type="button" wire:click="clearFilters" class="text-sm font-bold text-orange-600 hover:underline">
                        Ver todo el menú
                    </button>
                @endif
            </div>

            {{-- Móvil / tablet: chips --}}
            <div class="flex flex-wrap gap-2 lg:hidden">
                @foreach ($categories as $category)
                    @php
                        $active = $selectedCategory && $selectedCategory->id === $category->id;
                        $image = $category->image_url;
                    @endphp
                    <button type="button" wire:key="category-chip-{{ $category->id }}"
                        wire:click="selectCategory({{ $category->id }})" x-on:click="$dispatch('menu-filtered')"
                        aria-pressed="{{ $active ? 'true' : 'false' }}"
                        class="flex h-9 items-center gap-1.5 rounded-full pl-1.5 pr-2 text-xs font-bold transition
                            {{ $active ? 'bg-orange-500 text-white shadow-sm' : 'bg-white text-ink ring-1 ring-slate-200' }}">
                        <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-gradient-to-br from-orange-400 to-orange-600"
                            x-data="{ broken: false }">
                            @if ($image)
                                <img src="{{ $image }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover"
                                    x-show="!broken" x-on:error="broken = true">
                            @endif
                            <i class="fa-solid fa-utensils text-[10px] text-white" x-show="{{ $image ? 'broken' : 'true' }}" aria-hidden="true"></i>
                        </span>
                        {{ $category->name }}
                        <x-chip-count :count="$category->subcategories->sum('products_count')" :active="$active" />
                    </button>
                @endforeach
            </div>

            {{-- Escritorio: tarjetas con foto --}}
            <div class="hidden gap-3 lg:grid lg:grid-cols-[repeat(auto-fill,minmax(13rem,1fr))]">
                @foreach ($categories as $category)
                    @php
                        $active = $selectedCategory && $selectedCategory->id === $category->id;
                        $count = $category->subcategories->sum('products_count');
                        $image = $category->image_url;
                    @endphp
                    <button type="button" wire:key="category-card-{{ $category->id }}"
                        wire:click="selectCategory({{ $category->id }})" x-on:click="$dispatch('menu-filtered')"
                        aria-pressed="{{ $active ? 'true' : 'false' }}"
                        x-data="{ broken: false }"
                        class="group relative h-32 overflow-hidden rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-left shadow-card outline-none transition
                            focus-visible:ring-4 focus-visible:ring-orange-500/50
                            {{ $active ? 'ring-[3px] ring-orange-500 ring-offset-2 ring-offset-slate-50' : 'hover:-translate-y-0.5 hover:shadow-card-hover motion-reduce:hover:translate-y-0' }}">

                        {{-- Respaldo sin foto (o si la URL externa falla): degradado de marca con icono --}}
                        <i class="fa-solid fa-utensils absolute -right-3 -top-3 text-7xl text-white/15" aria-hidden="true"></i>

                        @if ($image)
                            <img src="{{ $image }}" alt="" loading="lazy" decoding="async"
                                x-show="!broken" x-on:error="broken = true"
                                class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100">
                        @endif

                        {{-- Degradado para que el texto se lea sobre cualquier foto --}}
                        <span class="absolute inset-0 bg-gradient-to-t from-ink/85 via-ink/35 to-transparent" aria-hidden="true"></span>

                        <span class="absolute inset-x-0 bottom-0 p-4">
                            <span class="block truncate text-xl font-black leading-tight text-white drop-shadow">{{ $category->name }}</span>
                            <span class="mt-0.5 block text-xs font-semibold text-white/80">
                                {{ $count }} {{ $count === 1 ? 'producto' : 'productos' }}
                            </span>
                        </span>

                        @if ($active)
                            <span class="absolute right-2.5 top-2.5 flex h-7 w-7 items-center justify-center rounded-full bg-orange-500 text-xs text-white shadow ring-2 ring-white">
                                <i class="fa-solid fa-check" aria-hidden="true"></i>
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="lg:grid lg:grid-cols-[13rem_1fr] lg:gap-6">

        {{-- Sidebar de categorías (escritorio) --}}
        <nav class="hidden lg:block" aria-label="Categorías del menú">
            <div class="sticky top-24 space-y-6">
                @php $all = !$selected && !$selectedCategory; @endphp
                <button type="button" wire:click="clearFilters"
                    class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-sm font-black transition
                        {{ $all ? 'bg-orange-500 text-white shadow-sm' : 'text-ink hover:bg-white' }}"
                    @if ($all) aria-current="true" @endif>
                    <span><i class="fa-solid fa-utensils mr-2 w-4" aria-hidden="true"></i>Todo el menú</span>
                    <span class="text-xs {{ $all ? 'text-orange-100' : 'text-ink-muted' }}">{{ $totalProducts }}</span>
                </button>

                @foreach ($categories as $category)
                    @if ($category->subcategories->isNotEmpty())
                        @php
                            $categoryActive = !$selected && $selectedCategory && $selectedCategory->id === $category->id;
                            $thumb = $category->image_url;
                        @endphp
                        <div>
                            <button type="button" wire:click="selectCategory({{ $category->id }})" x-on:click="$dispatch('menu-filtered')"
                                class="mb-1.5 flex w-full items-center gap-2.5 rounded-xl px-3 py-1.5 text-left text-xs font-black uppercase tracking-widest transition
                                    {{ $categoryActive ? 'bg-orange-500 text-white shadow-sm' : 'text-ink-muted hover:bg-white hover:text-ink' }}"
                                @if ($categoryActive) aria-current="true" @endif>
                                <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-orange-100 text-orange-500 ring-1 ring-slate-900/5"
                                    x-data="{ broken: false }">
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover"
                                            x-show="!broken" x-on:error="broken = true">
                                    @endif
                                    <i class="fa-solid fa-utensils text-[11px]" x-show="{{ $thumb ? 'broken' : 'true' }}" aria-hidden="true"></i>
                                </span>
                                <span class="truncate">{{ $category->name }}</span>
                                @php $categoryCount = $category->subcategories->sum('products_count'); @endphp
                                <span class="ml-auto pl-1 text-xs font-bold normal-case tracking-normal {{ $categoryActive ? 'text-orange-100' : 'text-slate-400' }}">
                                    {{ $categoryCount }}<span class="sr-only"> {{ $categoryCount === 1 ? 'producto' : 'productos' }}</span>
                                </span>
                            </button>
                            <ul class="space-y-0.5">
                                @foreach ($category->subcategories as $sub)
                                    @php $active = $selected && $selected->id === $sub->id; @endphp
                                    <li>
                                        <button type="button" wire:click="selectSubcategory({{ $sub->id }})"
                                            x-on:click="$dispatch('menu-filtered')"
                                            class="flex w-full items-center justify-between gap-2 rounded-xl px-3 py-2 text-left text-sm font-bold transition
                                                {{ $active ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-600 hover:bg-white hover:text-ink' }}"
                                            @if ($active) aria-current="true" @endif>
                                            <span class="flex min-w-0 items-center gap-2">
                                                <x-thumb :src="$subImages[$sub->id] ?? null" class="h-5 w-5 rounded-md ring-1 ring-slate-900/5" />
                                                <span class="truncate">{{ $sub->name }}</span>
                                            </span>
                                            <span class="text-xs {{ $active ? 'text-orange-100' : 'text-slate-400' }}">{{ $sub->products_count }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        </nav>

        <div class="min-w-0">
            {{-- Barra de búsqueda (sticky en móvil) --}}
            <div class="sticky top-16 z-20 -mx-4 bg-slate-50/95 px-4 pb-3 pt-3 backdrop-blur sm:-mx-6 sm:px-6 lg:static lg:mx-0 lg:bg-transparent lg:p-0 lg:pb-4 lg:backdrop-blur-none">
                <label for="menu-search" class="sr-only">Buscar en el menú</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
                    <input id="menu-search" type="search" wire:model.debounce.350ms="search" autocomplete="off"
                        placeholder="Busca empanadas, capuchino, postres…"
                        class="w-full rounded-full border-0 bg-white py-3 pl-11 pr-11 text-sm shadow-card ring-1 ring-slate-900/5 placeholder:text-slate-400 focus:ring-2 focus:ring-orange-500">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-orange-500" wire:loading.delay aria-hidden="true">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                    </span>
                </div>
            </div>

            {{--
                Chips de subcategorías (móvil / tablet): 36px de alto (cómodos al tocar), en varias líneas si no caben (sin scroll
                horizontal) y fuera de la barra fija para no tapar el menú al bajar. Sólo aparecen al elegir
                una categoría (con las fichas de arriba) y muestran sus subcategorías.
            --}}
            @if ($selectedCategory)
                <div class="mb-4 flex flex-wrap gap-2 lg:hidden" role="list" aria-label="Subcategorías de {{ $selectedCategory->name }}">
                    <button type="button" role="listitem" wire:click="showCategory({{ $selectedCategory->id }})"
                        class="flex h-9 items-center gap-1.5 rounded-full pl-3.5 pr-2 text-xs font-bold transition
                            {{ !$selected ? 'bg-ink text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200' }}">
                        Todo
                        <x-chip-count :count="$selectedCategory->subcategories->sum('products_count')" :active="!$selected" />
                    </button>
                    @foreach ($selectedCategory->subcategories as $sub)
                        @php
                            $subImage = $subImages[$sub->id] ?? null;
                            $chipActive = $selected && $selected->id === $sub->id;
                        @endphp
                        <button type="button" wire:click="selectSubcategory({{ $sub->id }})" role="listitem"
                            class="flex h-9 items-center gap-1.5 rounded-full pr-2 text-xs font-bold transition {{ $subImage ? 'pl-1.5' : 'pl-3.5' }}
                                {{ $chipActive ? 'bg-ink text-white' : 'bg-white text-slate-600 ring-1 ring-slate-200' }}">
                            <x-thumb :src="$subImage" class="h-6 w-6 rounded-full" />
                            {{ $sub->name }}
                            <x-chip-count :count="$sub->products_count" :active="$chipActive" />
                        </button>
                    @endforeach
                </div>
            @endif

            <div wire:loading.class.delay="opacity-50" class="transition-opacity">
                @if ($filtering)
                    {{-- Resultado filtrado --}}
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-sm text-ink-muted" aria-live="polite">
                            <span class="font-black text-ink">{{ $products->total() }}</span>
                            {{ $products->total() === 1 ? 'producto' : 'productos' }}
                            @if ($selected) en <span class="font-bold text-ink">{{ $selected->name }}</span>
                            @elseif ($selectedCategory) en <span class="font-bold text-ink">{{ $selectedCategory->name }}</span>@endif
                            @if (trim($search) !== '') para “<span class="font-bold text-ink">{{ $search }}</span>”@endif
                        </p>
                        <button type="button" wire:click="clearFilters" class="text-sm font-bold text-orange-600 hover:underline">
                            <i class="fa-solid fa-xmark mr-1" aria-hidden="true"></i>Limpiar filtros
                        </button>
                    </div>

                    @if ($products->isEmpty())
                        <div class="card flex flex-col items-center px-6 py-14 text-center">
                            <span class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-orange-50 text-xl text-orange-500">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            </span>
                            <p class="font-black">No encontramos productos</p>
                            <p class="mt-1 max-w-xs text-sm text-ink-muted">Prueba con otra palabra o explora todas las categorías del menú.</p>
                            <button type="button" wire:click="clearFilters" class="btn-outline mt-5">Ver todo el menú</button>
                        </div>
                    @else
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" show-subcategory
                                    :sub-image="$subImages[$product->subcategory_id] ?? null" />
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    {{-- Menú completo agrupado --}}
                    @forelse ($groups as $group)
                        <section class="mb-10 last:mb-0" aria-labelledby="sub-{{ $group['subcategory']->id }}">
                            <div class="mb-4 flex items-center justify-between gap-3 border-b border-slate-200 pb-2">
                                <div class="flex min-w-0 items-center gap-3">
                                    <x-thumb :src="$subImages[$group['subcategory']->id] ?? null" class="h-9 w-9 rounded-xl ring-1 ring-slate-900/5" />
                                    <h3 id="sub-{{ $group['subcategory']->id }}" class="truncate text-xl font-black">{{ $group['subcategory']->name }}</h3>
                                </div>
                                <span class="text-xs font-bold text-ink-muted">{{ $group['products']->count() }} {{ $group['products']->count() === 1 ? 'opción' : 'opciones' }}</span>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                @foreach ($group['products'] as $product)
                                    <x-product-card :product="$product" :sub-image="$subImages[$product->subcategory_id] ?? null" />
                                @endforeach
                            </div>
                        </section>
                    @empty
                        <div class="card px-6 py-14 text-center">
                            <p class="font-black">Estamos preparando nuestro menú</p>
                            <p class="mt-1 text-sm text-ink-muted">Vuelve pronto para ver nuestros productos.</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
