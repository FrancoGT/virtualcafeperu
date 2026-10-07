{{--
    Select de filtro: envía el formulario al cambiar. $options = [valor => etiqueta]
    $clears: nombre de otro filtro que se vacía al cambiar éste (p. ej. la subcategoría al cambiar de categoría)
--}}
@props(['name', 'label', 'options' => [], 'clears' => null])

{{-- En móvil (2 columnas) el último select queda a todo el ancho si quedaría solo en su fila --}}
<div class="min-w-0 sm:w-48 [&:nth-child(even):nth-last-child(2)]:col-span-2">
    {{-- (posición par = columna izquierda, porque el buscador ocupa la primera fila) --}}
    <label for="filter-{{ $name }}" class="sr-only">{{ $label }}</label>
    <select name="{{ $name }}" id="filter-{{ $name }}" class="form-input" onchange="@if ($clears) if (this.form.elements['{{ $clears }}']) this.form.elements['{{ $clears }}'].value = ''; @endif this.form.submit()">
        <option value="">{{ $label }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" {{ (string) request($name) === (string) $value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
</div>
