<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Categorías', 'route' => route('admin.categories.index')],
]">

    <x-admin.page-header title="Categorías" description="Agrupan las subcategorías y los productos de la tienda.">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Nueva categoría
        </a>
    </x-admin.page-header>

    @php $filtering = request()->filled('search') || request()->filled('status'); @endphp

    @if ($categories->count() || $filtering)
        <x-admin.filters placeholder="Buscar categoría..." :keys="['search', 'status']">
            <x-admin.filter-select name="status" label="Estado" :options="['1' => 'Activo', '0' => 'Inactivo']" />
        </x-admin.filters>
    @endif

    @if ($categories->count())
        <x-admin.table :paginator="$categories">
            <thead>
                <tr>
                    <th scope="col">Categoría</th>
                    <th scope="col">Subcategorías</th>
                    <th scope="col">Productos</th>
                    <th scope="col">Estado</th>
                    <th scope="col" class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    @php $imageUrl = $category->image_url; @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <i class="fa-regular fa-image text-slate-300" aria-hidden="true"></i>
                                    @endif
                                </span>
                                <span class="font-semibold text-ink">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if (!$category->subcategories_count)
                                <span class="font-bold tabular-nums text-slate-400" title="Sin subcategorías">0</span>
                            @else
                                <a href="{{ route('admin.subcategories.index', ['category_id' => $category->id]) }}"
                                    class="font-bold tabular-nums text-ink hover:text-orange-600 hover:underline"
                                    title="Ver las subcategorías de {{ $category->name }}">{{ $category->subcategories_count }}</a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}"
                                class="font-bold tabular-nums hover:text-orange-600 hover:underline {{ $category->products_count ? 'text-ink' : 'text-slate-400' }}"
                                title="{{ $category->products_count ? 'Ver los productos de ' . $category->name : 'Sin productos: ver y agregar productos a ' . $category->name }}">{{ $category->products_count }}</a>
                            @if ($category->products_count && $category->active_products_count !== $category->products_count)
                                <span class="ml-1 text-xs text-ink-muted">({{ $category->active_products_count }} {{ $category->active_products_count === 1 ? 'activo' : 'activos' }})</span>
                            @endif
                        </td>
                        <td>
                            <x-admin.status-toggle :action="route('admin.categories.updateStatus', $category->id)"
                                :active="$category->status == '1'" :name="$category->name" type="la categoría" />
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-action">
                                <i class="fa-solid fa-pen" aria-hidden="true"></i> Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-admin.table>
    @elseif ($filtering)
        @include('admin.partials.no-results')
    @else
        <x-admin.empty-state icon="fa-solid fa-tags" title="Todavía no hay categorías registradas"
            description="Crea la primera categoría para empezar a organizar el catálogo.">
            <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Nueva categoría
            </a>
        </x-admin.empty-state>
    @endif

</x-admin-layout>
