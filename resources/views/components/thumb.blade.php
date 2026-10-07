{{--
    Miniatura decorativa (el texto al lado ya nombra el elemento, por eso alt="").
    Sin $src no se pinta nada, y si la imagen falla (p. ej. una URL externa caída) se oculta,
    para no dejar huecos ni iconos rotos.
--}}
@props(['src' => null])

@if ($src)
    <img src="{{ $src }}" alt="" loading="lazy" decoding="async"
        x-data="{ broken: false }" x-show="!broken" x-on:error="broken = true"
        {{ $attributes->merge(['class' => 'flex-shrink-0 object-cover bg-slate-100']) }}>
@endif
