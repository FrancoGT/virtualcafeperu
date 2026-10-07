<div class="grid min-h-screen lg:grid-cols-2">
    {{-- Panel de marca --}}
    <div class="relative hidden overflow-hidden bg-ink lg:flex lg:flex-col lg:justify-between lg:p-12">
        <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-orange-500/20 blur-3xl" aria-hidden="true"></div>

        <a href="{{ route('home') }}" class="relative inline-flex items-center gap-2 text-white">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">
                <img src="{{ asset('img/caramellalogo.png') }}" alt="" class="h-8 w-8 object-contain">
            </span>
            <span class="text-lg font-black">{{ config('app.name') }}</span>
        </a>

        <div class="relative">
            <div class="mx-auto aspect-square max-w-sm overflow-hidden rounded-[2rem] ring-8 ring-white/5">
                <img src="{{ asset('img/vendedora_pasteles.png') }}" alt="" class="h-full w-full object-cover">
            </div>
            <p class="mx-auto mt-8 max-w-sm text-center text-2xl font-black leading-tight text-white">
                Tus antojos favoritos, <span class="text-orange-500">a un clic</span>.
            </p>
            <p class="mx-auto mt-2 max-w-sm text-center text-sm text-slate-400">
                Guarda tus datos y sigue tus pedidos desde cualquier dispositivo.
            </p>
        </div>

        <p class="relative text-xs text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>

    {{-- Formulario --}}
    <div class="flex flex-col items-center justify-center px-4 py-10 sm:px-6">
        <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2 lg:hidden">
            <img src="{{ asset('img/caramellalogo.png') }}" alt="" class="h-12 w-12 object-contain">
            <span class="text-xl font-black">{{ config('app.name') }}</span>
        </a>

        <div class="card w-full max-w-md p-6 sm:p-8">
            {{ $slot }}
        </div>

        <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-ink-muted hover:text-orange-600">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Volver a la tienda
        </a>
    </div>
</div>
