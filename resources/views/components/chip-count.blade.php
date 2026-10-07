{{-- Contador de productos dentro de un chip del menú (el texto "productos" sólo lo leen los lectores de pantalla) --}}
@props(['count', 'active' => false])

<span class="min-w-[1.25rem] rounded-full px-1.5 py-0.5 text-center text-[10px] font-black leading-none tabular-nums {{ $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
    {{ $count }}<span class="sr-only"> {{ $count === 1 ? 'producto' : 'productos' }}</span>
</span>
