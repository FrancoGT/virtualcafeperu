<?php

namespace App\Http\Livewire;

use App\Services\CartService;
use Livewire\Component;

class Navigation extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render(CartService $cart)
    {
        return view('livewire.navigation', [
            'cartCount' => $cart->count(),
        ]);
    }
}
