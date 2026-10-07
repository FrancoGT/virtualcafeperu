<x-guest-layout>
    <x-jet-authentication-card>
        <h1 class="text-2xl font-black">Crea tu cuenta</h1>
        <p class="mt-1 text-sm text-ink-muted">Guarda tus datos y sigue tus pedidos fácilmente.</p>

        <x-jet-validation-errors class="mt-6" />

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Nombre completo') }}" />
                <x-jet-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div>
                <x-jet-label for="email" value="{{ __('Correo Electrónico') }}" />
                <x-jet-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tucorreo@ejemplo.com" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                    <x-jet-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div>
                    <x-jet-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                    <x-jet-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <x-jet-label for="terms">
                    <div class="flex items-start">
                        <x-jet-checkbox name="terms" id="terms" class="mt-0.5" required />

                        <div class="ml-2 font-normal text-slate-600">
                            {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="font-bold text-orange-600 hover:underline">'.__('Términos del servicio').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="font-bold text-orange-600 hover:underline">'.__('Política de privacidad').'</a>',
                            ]) !!}
                        </div>
                    </div>
                </x-jet-label>
            @endif

            <x-jet-button class="w-full !py-3 text-base">
                {{ __('Crear cuenta') }}
            </x-jet-button>
        </form>

        <p class="mt-6 border-t border-slate-100 pt-6 text-center text-sm text-ink-muted">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:underline">Inicia sesión</a>
        </p>
    </x-jet-authentication-card>
</x-guest-layout>
