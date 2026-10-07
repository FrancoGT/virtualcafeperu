<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\InteractsWithCart;
use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class CartPanel extends Component
{
    use InteractsWithCart;

    /** 'sidebar' (columna fija del menú) o 'drawer' (panel deslizante) */
    public $mode = 'sidebar';

    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render(CartService $cart)
    {
        $items = $cart->items();

        return view('livewire.cart-panel', [
            'items' => $items,
            'stock' => Product::whereIn('id', array_keys($items))->pluck('quantity', 'id'),
            'count' => $cart->count(),
            'total' => $cart->total(),
        ]);
    }
}
