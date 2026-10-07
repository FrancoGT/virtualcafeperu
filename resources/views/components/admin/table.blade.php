{{-- Tabla del panel: tarjeta + scroll horizontal propio + pie con paginación --}}
@props(['paginator' => null])

<div {{ $attributes->merge(['class' => 'card overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="table-admin">
            {{ $slot }}
        </table>
    </div>

    @if ($paginator)
        <div class="border-t border-slate-100 px-4 py-3">
            {{ $paginator->links('admin.partials.pagination') }}
        </div>
    @endif
</div>
