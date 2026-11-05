@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-xl bg-red-50 p-4 ring-1 ring-red-200']) }} role="alert">
        <div class="flex items-center gap-2 font-bold text-red-700">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
            {{ __('Revisa los siguientes datos:') }}
        </div>

        <ul class="mt-2 list-inside list-disc text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
