{{--
    Interruptor Activo/Inactivo. Envía el mismo PATCH {status: 0|1} a la misma ruta que antes
    usaba el <select>, con el valor contrario al estado actual.
    Pide confirmación con SweetAlert (data-confirm, ver components/toasts).
--}}
@props(['action', 'active', 'name' => null, 'type' => 'el registro'])

@php
    $subject = $name ? $type . ' «' . $name . '»' : $type;
    $confirm = $active
        ? ucfirst($subject) . ' dejará de mostrarse en la tienda.'
        : ucfirst($subject) . ' volverá a mostrarse en la tienda.';
@endphp

<form method="POST" action="{{ $action }}" class="inline-flex"
    data-confirm="{{ $confirm }}"
    data-confirm-title="{{ $active ? '¿Desactivar ' . $type . '?' : '¿Activar ' . $type . '?' }}"
    data-confirm-button="{{ $active ? 'Sí, desactivar' : 'Sí, activar' }}"
    data-confirm-icon="{{ $active ? 'warning' : 'question' }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $active ? 0 : 1 }}">

    <button type="submit" role="switch" aria-checked="{{ $active ? 'true' : 'false' }}"
        title="{{ $active ? 'Clic para desactivar' : 'Clic para activar' }}"
        class="group inline-flex items-center gap-2 rounded-full py-1 pr-1 text-sm font-semibold focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2">
        <span class="relative inline-flex h-5 w-9 flex-shrink-0 rounded-full transition-colors {{ $active ? 'bg-emerald-500 group-hover:bg-emerald-600' : 'bg-slate-300 group-hover:bg-slate-400' }}">
            <span class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform {{ $active ? 'translate-x-4' : '' }}"></span>
        </span>
        <span class="{{ $active ? 'text-emerald-700' : 'text-slate-500' }}">{{ $active ? 'Activo' : 'Inactivo' }}</span>
    </button>
</form>
