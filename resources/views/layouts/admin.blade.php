<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' · ' : '' }}Admin · {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('img/caramellalogo.png') }}" type="image/png">

    @livewireStyles

    <!-- Alpine (misma versión que la tienda) controla sidebar, menú de usuario, toasts y previews -->
    <script src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    <script>
        // Estado del sidebar: abierto por defecto en escritorio (recuerda si el usuario lo ocultó), cerrado en móvil
        function adminShell() {
            const desktop = window.matchMedia('(min-width: 1024px)');
            const read = () => { try { return localStorage.getItem('admin.sidebar'); } catch (e) { return null; } };
            const write = (v) => { try { localStorage.setItem('admin.sidebar', v); } catch (e) {} };

            return {
                open: false,
                ready: false,
                init() {
                    this.open = desktop.matches && read() !== 'closed';
                    desktop.addEventListener('change', (e) => { this.open = e.matches && read() !== 'closed'; });
                    this.$nextTick(() => this.ready = true);
                },
                toggle() {
                    this.open = !this.open;
                    if (desktop.matches) write(this.open ? 'open' : 'closed');
                },
                closeOnMobile() {
                    if (!desktop.matches) this.open = false;
                },
            };
        }
    </script>
</head>

<body class="bg-slate-50 font-sans antialiased" x-data="adminShell()"
    x-bind:class="{ 'overflow-hidden lg:overflow-auto': open }"
    x-on:keydown.escape.window="closeOnMobile()">

    @include('layouts.partials.admin.navigation')

    @include('layouts.partials.admin.sidebar')

    <!-- Fondo oscuro del menú en móvil -->
    <div x-cloak x-show="open" x-transition.opacity x-on:click="open = false"
        class="fixed inset-0 z-30 bg-ink/40 lg:hidden" aria-hidden="true"></div>

    <div class="pt-16 lg:pl-64" x-bind:class="{ 'lg:!pl-0': !open, 'transition-[padding] duration-200': ready }">
        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            @include('layouts.partials.admin.breadcrumb')

            {{ $slot }}
        </main>
    </div>

    <x-toasts />

    @livewireScripts
</body>

</html>
