{{-- Badge del estado de un pedido (valores aceptados por OrderAdminController::updateStatus) --}}
@props(['status'])

@php
    $map = [
        'pending' => ['badge-warning', 'Pendiente'],
        'paid'    => ['badge-success', 'Pagado'],
        'served'  => ['badge-info', 'Servido'],
        'refused' => ['badge-danger', 'Rechazado'],
    ];
    [$class, $label] = $map[$status] ?? ['badge-neutral', ucfirst($status)];
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
    {{ $label }}
</span>
