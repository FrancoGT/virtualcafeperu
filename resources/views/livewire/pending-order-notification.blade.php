<div>
    @if ($hasPendingOrders)
        <div class="container-site pt-6">
            <div class="flex flex-col gap-3 rounded-2xl bg-orange-50 p-4 ring-1 ring-orange-200 sm:flex-row sm:items-center sm:justify-between" role="status">
                <p class="flex items-center gap-3 text-sm font-bold text-orange-900">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-orange-500 text-white">
                        <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                    </span>
                    Tienes pedidos pendientes. Notifícalos por WhatsApp para que los preparemos.
                </p>
                <a href="#pedidos" class="btn-dark !py-2 flex-shrink-0">Ver mis pedidos</a>
            </div>
        </div>
    @endif
</div>
