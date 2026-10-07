<header class="fixed inset-x-0 top-0 z-50 h-16 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex h-full items-center justify-between gap-3 px-3 sm:px-4">
        <div class="flex min-w-0 items-center gap-2">
            <button type="button" class="icon-btn" x-on:click="toggle()" aria-controls="admin-sidebar"
                x-bind:aria-expanded="open.toString()">
                <span class="sr-only">Mostrar u ocultar menú</span>
                <i class="fa-solid fa-bars text-lg" aria-hidden="true"></i>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-2.5 rounded-lg px-1 py-1">
                <img src="{{ asset('img/caramellalogo.svg') }}" alt="" class="h-9 w-9 flex-shrink-0">
                <span class="truncate text-base font-extrabold text-ink">{{ config('app.name') }}</span>
                <span class="badge-neutral hidden sm:inline-flex">Admin</span>
            </a>
        </div>

        <div class="flex items-center gap-1 sm:gap-2">
            <a href="{{ route('home') }}" class="btn-action hidden sm:inline-flex" target="_blank" rel="noopener">
                <i class="fa-solid fa-store" aria-hidden="true"></i>
                Ver tienda
            </a>

            <div class="relative" x-data="{ menu: false }" x-on:click.outside="menu = false"
                x-on:keydown.escape.stop="menu = false">
                <button type="button" x-on:click="menu = !menu" x-bind:aria-expanded="menu.toString()"
                    class="flex items-center gap-2 rounded-full p-1 transition hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 sm:pr-3">
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="">
                    <span class="hidden max-w-[10rem] truncate text-sm font-semibold text-ink sm:block">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down hidden text-xs text-ink-muted sm:block" aria-hidden="true"></i>
                    <span class="sr-only">Menú de usuario</span>
                </button>

                <div x-cloak x-show="menu" x-transition.origin.top.right
                    class="absolute right-0 mt-2 w-60 overflow-hidden rounded-xl bg-white shadow-card-hover ring-1 ring-slate-900/5">
                    <div class="border-b border-slate-100 px-4 py-3">
                        <p class="truncate text-sm font-bold text-ink">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-ink-muted">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fa-solid fa-user w-4 text-ink-muted" aria-hidden="true"></i> Perfil
                        </a>
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 sm:hidden">
                            <i class="fa-solid fa-store w-4 text-ink-muted" aria-hidden="true"></i> Ver tienda
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket w-4" aria-hidden="true"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
