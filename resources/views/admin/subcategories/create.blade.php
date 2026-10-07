<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Subcategorías', 'route' => route('admin.subcategories.index')],
    ['name' => 'Nueva subcategoría'],
]">

    <x-admin.page-header title="Nueva subcategoría" />

    <div class="max-w-3xl">
        <x-admin.form-card :action="route('admin.subcategories.store')" title="Información de la subcategoría"
            :cancel="route('admin.subcategories.index')" files>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.field label="Nombre" for="name" name="name" required>
                    <input name="name" type="text" id="name" class="form-input" value="{{ old('name') }}"
                        placeholder="Ingrese el nombre de la subcategoría" autocomplete="off" required>
                </x-admin.field>

                <x-admin.field label="Categoría" for="category" name="category_id" required>
                    <select name="category_id" id="category" class="form-input" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </x-admin.field>
            </div>

            <x-admin.field label="Imagen de la subcategoría" for="image" name="image">
                <x-admin.image-input :model="null" />
            </x-admin.field>

        </x-admin.form-card>
    </div>

</x-admin-layout>
