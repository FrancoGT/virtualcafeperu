{{-- Resumen de errores de validación (y del error genérico que los controladores envían en la clave "error") --}}
@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-5 flex items-start gap-3 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-inset ring-red-600/20']) }} role="alert">
        <i class="fa-solid fa-circle-exclamation mt-0.5" aria-hidden="true"></i>
        <div>
            <p class="font-bold">No se pudo guardar. Revisa los campos marcados.</p>
            @if ($errors->has('error'))
                <p class="mt-1">{{ $errors->first('error') }}</p>
            @endif
        </div>
    </div>
@endif
