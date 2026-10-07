<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Productos', 'route' => route('admin.products.index')],
    ['name' => 'Editar producto'],
]">

    <x-admin.page-header title="Editar producto" :description="$product->name" />

    <div class="max-w-4xl">
        <x-admin.form-card :action="route('admin.products.update', $product->id)" method="PUT"
            title="Información del producto" description="Los campos marcados con * son obligatorios."
            :cancel="route('admin.products.index')" files>
            @include('admin.products.partials.form-fields', ['product' => $product])
        </x-admin.form-card>
    </div>

</x-admin-layout>
