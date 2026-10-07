{{--
    Barra de búsqueda y filtros (GET sobre la misma página). Los selects van en el slot
    usando <x-admin.filter-select>. $keys lista los parámetros que cuentan como filtro activo.
--}}
@props(['placeholder' => 'Buscar...', 'keys' => ['search']])

@php $active = collect($keys)->contains(function ($key) { return request()->filled($key); }); @endphp

<form method="GET" action="{{ url()->current() }}" role="search"
    class="mb-4 grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center">
    <div class="relative col-span-2 sm:w-72">
        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
        <label for="filter-search" class="sr-only">{{ $placeholder }}</label>
        <input type="search" name="search" id="filter-search" value="{{ request('search') }}" placeholder="{{ $placeholder }}"
            class="form-input pl-9" autocomplete="off">
    </div>

    {{ $slot }}

    <div class="col-span-2 flex items-center gap-2">
        <button type="submit" class="btn-secondary flex-1 py-2.5 sm:flex-none">Buscar</button>
        @if ($active)
            <a href="{{ url()->current() }}" class="btn-action">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i> Limpiar
            </a>
        @endif
    </div>
</form>
