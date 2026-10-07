<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Productos', 'route' => route('admin.products.index')],
]">

    <x-admin.page-header title="Productos" description="Catálogo de productos con su precio, stock y estado de publicación.">
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Nuevo producto
        </a>
    </x-admin.page-header>

    @php $filtering = request()->filled('search') || request()->filled('category_id') || request()->filled('subcategory_id') || request()->filled('status') || request()->filled('stock'); @endphp
    @php
        // Filtrado sólo por categoría y/o subcategoría (sin búsqueda, estado ni stock) y sin resultados:
        // la subcategoría (o la categoría) está vacía y se muestra un aviso propio para agregar productos
        $onlyPlaceFilter = (request()->filled('category_id') || request()->filled('subcategory_id'))
            && !request()->filled('search') && !request()->filled('status') && !request()->filled('stock')
            && !$products->count();
        $emptySubcategory = $onlyPlaceFilter && request()->filled('subcategory_id')
            ? $subcategories->firstWhere('id', (int) request('subcategory_id'))
            : null;
        $emptyCategory = $onlyPlaceFilter && !$emptySubcategory && request()->filled('category_id')
            ? $categories->firstWhere('id', (int) request('category_id'))
            : null;
    @endphp

    @if ($products->count() || $filtering)
        <x-admin.filters placeholder="Buscar producto..." :keys="['search', 'category_id', 'subcategory_id', 'status', 'stock']">
            <x-admin.filter-select name="category_id" label="Categoría" :options="$categories->pluck('name', 'id')->all()" clears="subcategory_id" />
            <x-admin.filter-select name="subcategory_id" label="Subcategoría" :options="$subcategories->pluck('name', 'id')->all()" />
            <x-admin.filter-select name="status" label="Estado" :options="['1' => 'Activo', '0' => 'Inactivo']" />
            <x-admin.filter-select name="stock" label="Stock" :options="['available' => 'Con stock', 'out' => 'Agotado']" />
        </x-admin.filters>
    @endif

    @if ($products->count())
        <x-admin.table :paginator="$products">
            <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Subcategoría</th>
                    <th scope="col" class="text-right">Precio</th>
                    <th scope="col" class="text-right">Stock</th>
                    <th scope="col">Estado</th>
                    <th scope="col" class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php $imageUrl = $product->image_url; @endphp
                    <tr>
                        <td>
                            <div class="flex min-w-[12rem] items-center gap-3">
                                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <i class="fa-regular fa-image text-slate-300" aria-hidden="true"></i>
                                    @endif
                                </span>
                                <span class="font-semibold text-ink">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge-neutral whitespace-nowrap">{{ $product->subcategory->name }}</span></td>
                        <td class="whitespace-nowrap text-right font-semibold tabular-nums text-ink">S/. {{ number_format($product->price, 2) }}</td>
                        <td class="text-right">
                            @if ($product->is_available)
                                <span class="tabular-nums">{{ $product->quantity }}</span>
                            @else
                                <span class="badge-danger">Agotado</span>
                            @endif
                        </td>
                        <td>
                            <x-admin.status-toggle :action="route('admin.products.updateStatus', $product->id)"
                                :active="$product->status == '1'" :name="$product->name" type="el producto" />
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-action">
                                <i class="fa-solid fa-pen" aria-hidden="true"></i> Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>
    @elseif ($emptySubcategory)
        {{-- Filtrado sólo por una subcategoría que no tiene productos (p. ej. desde el 0 del listado de subcategorías) --}}
        <x-admin.empty-state icon="fa-solid fa-box-open" title="{{ $emptySubcategory->name }} todavía no tiene productos"
            description="Agrega el primero; en la tienda la subcategoría se mostrará cuando tenga productos activos.">
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('admin.products.create', ['subcategory_id' => $emptySubcategory->id]) }}" class="btn-primary">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Nuevo producto en {{ $emptySubcategory->name }}
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Ver todos los productos</a>
            </div>
        </x-admin.empty-state>
    @elseif ($emptyCategory)
        {{-- Filtrado sólo por una categoría que no tiene productos (p. ej. desde el 0 del listado de categorías) --}}
        <x-admin.empty-state icon="fa-solid fa-box-open" title="{{ $emptyCategory->name }} todavía no tiene productos"
            description="Agrega el primero en alguna de sus subcategorías; en la tienda la categoría se mostrará cuando tenga productos activos.">
            <div class="flex flex-wrap justify-center gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Nuevo producto
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Ver todos los productos</a>
            </div>
        </x-admin.empty-state>
    @elseif ($filtering)
        @include('admin.partials.no-results')
    @else
        <x-admin.empty-state icon="fa-solid fa-box" title="Todavía no hay productos registrados"
            description="Agrega tu primer producto con su precio, stock e imagen.">
            <a href="{{ route('admin.products.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Nuevo producto
            </a>
        </x-admin.empty-state>
    @endif

</x-admin-layout>
