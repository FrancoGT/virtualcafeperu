@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-xl border-slate-300 py-2.5 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 disabled:bg-slate-100']) !!}>
