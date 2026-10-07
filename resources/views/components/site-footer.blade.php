@php
    $whatsapp = config('app.whatsapp_number');
    $whatsappDigits = preg_replace('/\D/', '', $whatsapp);
@endphp

<footer class="bg-ink text-slate-300">
    <div class="container-site grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">
                    <img src="{{ asset('img/caramellalogo.png') }}" alt="" class="h-8 w-8 object-contain">
                </span>
                <span class="text-lg font-black text-white">{{ config('app.name') }}</span>
            </a>
            <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                Repostería, bebidas y café preparados con cariño. Arma tu pedido en línea y recógelo o recíbelo sin complicaciones.
            </p>
        </div>

        <nav aria-label="Enlaces del pie de página">
            <h2 class="text-sm font-black uppercase tracking-widest text-white">Explora</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('home') }}#menu" class="hover:text-orange-400">Menú</a></li>
                <li><a href="{{ route('home') }}#como-pedir" class="hover:text-orange-400">Cómo pedir</a></li>
                <li><a href="{{ route('home') }}#pedidos" class="hover:text-orange-400">Mis pedidos</a></li>
                @if (Route::has('terms.show'))
                    <li><a href="{{ route('terms.show') }}" class="hover:text-orange-400">Términos y condiciones</a></li>
                @endif
                @if (Route::has('policy.show'))
                    <li><a href="{{ route('policy.show') }}" class="hover:text-orange-400">Política de privacidad</a></li>
                @endif
            </ul>
        </nav>

        <div>
            <h2 class="text-sm font-black uppercase tracking-widest text-white">Contacto</h2>
            <ul class="mt-4 space-y-3 text-sm">
                <li>
                    <a href="https://api.whatsapp.com/send?phone={{ $whatsappDigits }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 hover:text-orange-400">
                        <i class="fa-brands fa-whatsapp w-4 text-lg text-[#25D366]" aria-hidden="true"></i>
                        {{ $whatsapp }}
                    </a>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen w-4 text-orange-400" aria-hidden="true"></i>
                    Aceptamos pagos con Yape
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-site flex flex-col gap-2 pb-24 pt-5 text-xs text-slate-500 sm:flex-row sm:justify-between lg:pb-5">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            <p>Hecho en Perú <span aria-hidden="true">🇵🇪</span></p>
        </div>
    </div>
</footer>
