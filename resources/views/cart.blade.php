<x-app-layout>
    <x-slot name="title">Mi pedido</x-slot>

    <section class="container-site py-10">
        <a href="{{ route('home') }}#menu" class="inline-flex items-center gap-2 text-sm font-bold text-ink-muted hover:text-orange-600">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Seguir comprando
        </a>

        <div class="mx-auto mt-6 max-w-xl">
            @livewire('cart-panel', ['mode' => 'sidebar'])
        </div>
    </section>
</x-app-layout>
