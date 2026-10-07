<x-guest-layout>
    <x-jet-authentication-card>
        <h1 class="text-2xl font-black">Bienvenido de nuevo</h1>
        <p class="mt-1 text-sm text-ink-muted">Ingresa para ver y seguir tus pedidos.</p>

        <x-jet-validation-errors class="mt-6" />

        @if (session('status'))
            <div class="mt-6 rounded-xl bg-emerald-50 p-4 text-sm font-bold text-emerald-700 ring-1 ring-emerald-200" role="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-jet-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="tucorreo@ejemplo.com" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-orange-600 hover:underline" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>
                <x-jet-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <label for="remember_me" class="flex items-center">
                <x-jet-checkbox id="remember_me" name="remember" />
                <span class="ml-2 text-sm text-slate-600">{{ __('Mantener la sesión iniciada') }}</span>
            </label>

            <x-jet-button class="w-full !py-3 text-base">
                {{ __('Ingresar') }}
            </x-jet-button>
        </form>

        @if (Route::has('register'))
            <p class="mt-6 border-t border-slate-100 pt-6 text-center text-sm text-ink-muted">
                ¿Aún no tienes cuenta?
                <a href="{{ route('register') }}" class="font-bold text-orange-600 hover:underline">Regístrate gratis</a>
            </p>
        @endif
    </x-jet-authentication-card>
</x-guest-layout>
