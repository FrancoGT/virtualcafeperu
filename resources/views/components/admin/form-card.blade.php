{{-- Formulario estándar del panel: tarjeta con título, campos y pie con Cancelar / Guardar --}}
@props([
    'action',
    'method' => 'POST',
    'title',
    'description' => null,
    'cancel' => null,
    'files' => false,
    'submit' => 'Guardar',
])

<x-admin.form-errors />

<form action="{{ $action }}" method="POST" @if ($files) enctype="multipart/form-data" @endif
    {{ $attributes->merge(['class' => 'card']) }}>
    @csrf
    @unless (strtoupper($method) === 'POST')
        @method($method)
    @endunless

    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <h2 class="text-base font-bold text-ink">{{ $title }}</h2>
        @if ($description)
            <p class="mt-0.5 text-sm text-ink-muted">{{ $description }}</p>
        @endif
    </div>

    <div class="space-y-5 px-5 py-5 sm:px-6">
        {{ $slot }}
    </div>

    <div class="flex flex-col-reverse gap-2 rounded-b-2xl border-t border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
        @if ($cancel)
            <a href="{{ $cancel }}" class="btn-secondary">Cancelar</a>
        @endif
        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
            {{ $submit }}
        </button>
    </div>
</form>
