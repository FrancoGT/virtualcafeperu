@php
    $links = [
        ['label' => 'Inicio', 'href' => route('home')],
        ['label' => 'Menú', 'href' => route('home') . '#menu'],
        ['label' => 'Cómo pedir', 'href' => route('home') . '#como-pedir'],
        ['label' => 'Mis pedidos', 'href' => route('home') . '#pedidos'],
    ];
@endphp

<header x-data="{ open: false }" x-on:keydown.escape.window="open = false"
    class="sticky top-0 z-40 bg-ink/95 text-white backdrop-blur supports-[backdrop-filter]:bg-ink/85">
    <div class="container-site flex h-16 items-center gap-4">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 rounded-lg" aria-label="{{ config('app.name') }}, ir al inicio">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white">
                <img src="{{ asset('img/caramellalogo.png') }}" alt="" class="h-8 w-8 object-contain">
            </span>
            <span class="hidden text-lg font-black tracking-tight sm:block">{{ config('app.name') }}</span>
        </a>

        {{-- Navegación escritorio --}}
        <nav class="ml-6 hidden items-center gap-1 lg:flex" aria-label="Principal">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                    class="rounded-full px-4 py-2 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="ml-auto flex items-center gap-2">
            {{-- Cuenta --}}
            @auth
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="flex items-center gap-2 rounded-full p-1 pr-3 transition hover:bg-white/10"
                            aria-label="Menú de cuenta">
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-orange-500"
                                src="{{ Auth::user()->profile_photo_url }}" alt="">
                            <span class="hidden max-w-[8rem] truncate text-sm font-bold md:block">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down hidden text-xs text-slate-400 md:block" aria-hidden="true"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="block px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                            {{ __('Cuenta') }}
                        </div>

                        <x-jet-dropdown-link href="{{ route('profile.show') }}">
                            <i class="fa-regular fa-user mr-2 w-4 text-slate-400" aria-hidden="true"></i>{{ __('Perfil') }}
                        </x-jet-dropdown-link>

                        <x-jet-dropdown-link href="{{ route('home') }}#pedidos">
                            <i class="fa-solid fa-receipt mr-2 w-4 text-slate-400" aria-hidden="true"></i>{{ __('Mis pedidos') }}
                        </x-jet-dropdown-link>

                        @role('admin')
                            <x-jet-dropdown-link href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-gauge mr-2 w-4 text-slate-400" aria-hidden="true"></i>{{ __('Administración') }}
                            </x-jet-dropdown-link>
                        @endrole

                        <div class="border-t border-slate-100"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-jet-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2 w-4 text-slate-400" aria-hidden="true"></i>{{ __('Cerrar Sesión') }}
                            </x-jet-dropdown-link>
                        </form>
                    </x-slot>
                </x-jet-dropdown>
            @else
                <a href="{{ route('login') }}"
                    class="hidden rounded-full px-4 py-2 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white sm:inline-flex">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="btn-ghost-light hidden !py-2 md:inline-flex">
                    Registrarse
                </a>
            @endauth

            {{-- Carrito --}}
            <button type="button" x-on:click="$dispatch('open-cart')"
                class="btn-primary relative !px-4 !py-2"
                aria-label="Ver mi pedido, {{ $cartCount }} {{ $cartCount === 1 ? 'producto' : 'productos' }}">
                <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                <span class="hidden sm:inline">Mi pedido</span>
                @if ($cartCount > 0)
                    <span class="flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-white px-1.5 text-xs font-black text-orange-600">
                        {{ $cartCount }}
                    </span>
                @endif
            </button>

            {{-- Menú móvil --}}
            <button type="button" x-on:click="open = !open"
                class="flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-white/10 lg:hidden"
                :aria-expanded="open.toString()" aria-controls="mobile-menu" aria-label="Abrir menú">
                <i class="fa-solid text-lg" :class="open ? 'fa-xmark' : 'fa-bars'" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    {{-- Panel móvil --}}
    <nav id="mobile-menu" x-show="open" x-cloak x-transition.origin.top
        x-on:click.outside="open = false"
        class="border-t border-white/10 bg-ink lg:hidden" aria-label="Principal móvil">
        <div class="container-site flex flex-col gap-1 py-4">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}" x-on:click="open = false"
                    class="rounded-xl px-4 py-3 font-bold text-slate-200 hover:bg-white/10">
                    {{ $link['label'] }}
                </a>
            @endforeach

            @guest
                <div class="mt-2 grid grid-cols-2 gap-2 border-t border-white/10 pt-4">
                    <a href="{{ route('login') }}" class="btn-ghost-light">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn-primary">Registrarse</a>
                </div>
            @endguest
        </div>
    </nav>
</header>
