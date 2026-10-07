{{-- Campos compartidos por "Nuevo producto" y "Editar producto". $product es null al crear. --}}
@php $product = $product ?? null; @endphp

<div class="grid gap-5 sm:grid-cols-2">
    <x-admin.field label="Nombre" for="name" name="name" required>
        <input name="name" type="text" id="name" class="form-input" value="{{ old('name', optional($product)->name) }}"
            placeholder="Ingrese el nombre del producto" autocomplete="off" required>
    </x-admin.field>

    <x-admin.field label="Subcategoría" for="subcategory" name="subcategory_id" required>
        <select name="subcategory_id" id="subcategory" class="form-input" required>
            @foreach ($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}"
                    {{ old('subcategory_id', optional($product)->subcategory_id ?? request('subcategory_id')) == $subcategory->id ? 'selected' : '' }}>
                    {{ $subcategory->name }}
                </option>
            @endforeach
        </select>
    </x-admin.field>
</div>

<x-admin.field label="Descripción" for="description" name="description" required>
    <textarea name="description" id="description" rows="4" class="form-input" placeholder="Ingrese la descripción del producto"
        autocomplete="off" required>{{ old('description', optional($product)->description) }}</textarea>
</x-admin.field>

<div class="grid gap-5 sm:grid-cols-2">
    <x-admin.field label="Precio" for="price" name="price" required>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-semibold text-ink-muted">S/.</span>
            <input name="price" type="number" step="0.01" id="price" class="form-input pl-10"
                value="{{ old('price', optional($product)->price) }}" placeholder="0.00" autocomplete="off" required>
        </div>
    </x-admin.field>

    <x-admin.field label="Cantidad" for="quantity" name="quantity" hint="Unidades disponibles en stock.">
        <input name="quantity" type="number" id="quantity" class="form-input"
            value="{{ old('quantity', optional($product)->quantity) }}" placeholder="Ingrese la cantidad disponible" autocomplete="off">
    </x-admin.field>
</div>

<x-admin.field label="Imagen del producto" for="image" name="image" :required="!$product">
    <x-admin.image-input :required="!$product" :model="$product" />
</x-admin.field>
