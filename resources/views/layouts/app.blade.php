<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Repostería, bebidas y café artesanal. Arma tu pedido en línea, paga con Yape y confírmalo por WhatsApp.">
        <meta name="theme-color" content="#0f172a">

        <title>{{ isset($title) ? $title . ' · ' : '' }}{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

        <!---- Favicon ---->
        <link rel="icon" href="{{ asset('img/caramellalogo.png') }}" type="image/png">

        <!-- Styles -->
        @livewireStyles

        <!-- Scripts -->
        <script src="https://unpkg.com/@alpinejs/focus@3.14.1/dist/cdn.min.js" defer></script>
        <script src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] btn-primary">
            Saltar al contenido
        </a>

        <x-jet-banner />

        <div class="flex min-h-screen flex-col">
            @livewire('navigation')

            @isset($header)
                <header class="border-b border-slate-200 bg-white">
                    <div class="container-site py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main id="main" class="flex-1">
                {{ $slot }}
            </main>

            <x-site-footer />
        </div>

        @livewire('cart-panel', ['mode' => 'drawer'])

        <x-toasts />

        @stack('modals')

        @livewireScripts
        @stack('scripts')
    </body>
</html>
