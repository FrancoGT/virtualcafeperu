<?php

namespace App\Livewire\Concerns;

use App\Models\Product;
use App\Services\CartService;

trait InteractsWithCart
{
    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::visible()->find($productId);

        if (!$product) {
            return $this->notify('error', 'Este producto ya no está disponible.');
        }

        $error = app(CartService::class)->add($product, (int) $quantity);

        if ($error) {
            return $this->notify('error', $error);
        }

        $this->dispatch('cartUpdated');
        $this->notify('success', $product->name . ' se añadió a tu pedido.');
    }

    public function updateQuantity($productId, $quantity)
    {
        $product = Product::find($productId);

        if (!$product) {
            app(CartService::class)->remove($productId);
            $this->dispatch('cartUpdated');

            return;
        }

        $error = app(CartService::class)->update($product, (int) $quantity);

        if ($error) {
            $this->notify('error', $error);
        }

        $this->dispatch('cartUpdated');
    }

    public function removeFromCart($productId)
    {
        app(CartService::class)->remove($productId);

        $this->dispatch('cartUpdated');
        $this->notify('info', 'Producto quitado de tu pedido.');
    }

    protected function notify(string $type, string $message)
    {
        $this->dispatch('notify', type: $type, message: $message);
    }
}
