<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f172a">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link rel="icon" href="{{ asset('img/caramellalogo.png') }}" type="image/png">

        <!-- Styles -->
        @livewireStyles

        <!-- Scripts -->
        <script src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    </head>
    <body class="bg-slate-50">
        <div class="font-sans text-ink antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
