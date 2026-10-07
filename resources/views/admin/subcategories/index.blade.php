<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Subcategorías', 'route' => route('admin.subcategories.index')],
]">

    <x-admin.page-header title="Subcategorías" description="Secciones dentro de cada categoría donde se ubican los productos.">
        <a href="{{ route('admin.subcategories.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Nueva subcategoría
        </a>
    </x-admin.page-header>

    @php $filtering = request()->filled('search') || request()->filled('category_id') || request()->filled('status'); @endphp

    @if ($subcategories->count() || $filtering)
        <x-admin.filters placeholder="Buscar subcategoría..." :keys="['search', 'category_id', 'status']">
            <x-admin.filter-select name="category_id" label="Categoría" :options="$categories->pluck('name', 'id')->all()" />
            <x-admin.filter-select name="status" label="Estado" :options="['1' => 'Activo', '0' => 'Inactivo']" />
        </x-admin.filters>
    @endif

    @if ($subcategories->count())
        <x-admin.table :paginator="$subcategories">
            <thead>
                <tr>
                    <th scope="col">Subcategoría</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Productos</th>
                    <th scope="col">Estado</th>
                    <th scope="col" class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategories as $subcategory)
                    @php $imageUrl = $subcategory->image_url; @endphp
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
                                <span class="font-semibold text-ink">{{ $subcategory->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge-neutral">{{ $subcategory->category->name }}</span></td>
                        <td>
                            @if (!$subcategory->products_count)
                                <a href="{{ route('admin.products.index', ['subcategory_id' => $subcategory->id]) }}"
                                    class="font-bold tabular-nums text-slate-400 hover:text-orange-600 hover:underline"
                                    title="Sin productos: ver y agregar productos a {{ $subcategory->name }}">0</a>
                            @else
                            <a href="{{ route('admin.products.index', ['subcategory_id' => $subcategory->id]) }}"
                                class="font-bold tabular-nums text-ink hover:text-orange-600 hover:underline"
                                title="Ver los productos de {{ $subcategory->name }}">{{ $subcategory->products_count }}</a>
                            @if ($subcategory->products_count && $subcategory->active_products_count !== $subcategory->products_count)
                                <span class="ml-1 text-xs text-ink-muted">({{ $subcategory->active_products_count }} {{ $subcategory->active_products_count === 1 ? 'activo' : 'activos' }})</span>
                            @endif
                            @endif
                        </td>
                        <td>
                            <x-admin.status-toggle :action="route('admin.subcategories.updateStatus', $subcategory->id)"
                                :active="$subcategory->status == '1'" :name="$subcategory->name" type="la subcategoría" />
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn-action">
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
        <x-admin.empty-state icon="fa-solid fa-tag" title="Todavía no hay subcategorías registradas"
            description="Crea una subcategoría y asígnala a una categoría existente.">
            <a href="{{ route('admin.subcategories.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Nueva subcategoría
            </a>
        </x-admin.empty-state>
    @endif

</x-admin-layout>
