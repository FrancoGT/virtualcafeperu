<x-admin.empty-state icon="fa-solid fa-magnifying-glass" title="No se encontraron resultados"
    description="Ningún registro coincide con la búsqueda o los filtros aplicados.">
    <a href="{{ url()->current() }}" class="btn-secondary">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i> Limpiar filtros
    </a>
</x-admin.empty-state>
