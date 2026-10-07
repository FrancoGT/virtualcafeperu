{{-- Envoltura de campo: etiqueta, control (slot), ayuda y error de validación del campo $name --}}
@props(['label', 'for', 'name' => null, 'hint' => null, 'required' => false])

@php $error = $name ? $errors->first($name) : null; @endphp

<div {{ $attributes->merge(['class' => 'form-field']) }} @if ($error) data-invalid @endif>
    <label for="{{ $for }}" class="form-label">
        {{ $label }}
        @if ($required)<span class="text-red-500" aria-hidden="true">*</span>@endif
    </label>

    {{ $slot }}

    @if ($error)
        <p class="form-error">{{ $error }}</p>
    @elseif ($hint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
</div>
