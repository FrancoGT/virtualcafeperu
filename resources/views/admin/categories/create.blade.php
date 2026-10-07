<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Categorías', 'route' => route('admin.categories.index')],
    ['name' => 'Nueva categoría'],
]">

    <x-admin.page-header title="Nueva categoría" />

    <div class="max-w-3xl">
        <x-admin.form-card :action="route('admin.categories.store')" title="Información de la categoría"
            :cancel="route('admin.categories.index')" files>

            <x-admin.field label="Nombre" for="name" name="name" required>
                <input name="name" type="text" id="name" class="form-input" value="{{ old('name') }}"
                    placeholder="Ingrese el nombre de la categoría" autocomplete="off" required>
            </x-admin.field>

            <x-admin.field label="Imagen de la categoría" for="image" name="image" required>
                <x-admin.image-input required />
            </x-admin.field>

        </x-admin.form-card>
    </div>

</x-admin-layout>
