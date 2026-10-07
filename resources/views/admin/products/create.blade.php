<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Productos', 'route' => route('admin.products.index')],
    ['name' => 'Nuevo producto'],
]">

    <x-admin.page-header title="Nuevo producto" />

    <div class="max-w-4xl">
        <x-admin.form-card :action="route('admin.products.store')" title="Información del producto"
            description="Los campos marcados con * son obligatorios." :cancel="route('admin.products.index')" files>
            @include('admin.products.partials.form-fields')
        </x-admin.form-card>
    </div>

</x-admin-layout>
