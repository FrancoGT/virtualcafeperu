<?php

namespace App\Services;

use App\Models\Product;

/**
 * Carrito guardado en sesión con la estructura:
 * [productId => ['name', 'quantity', 'price', 'subtotal']]
 */
class CartService
{
    const SESSION_KEY = 'cart';

    public function items(): array
    {
        $cart = session(self::SESSION_KEY, []);

        return is_array($cart) ? array_filter($cart, 'is_array') : [];
    }

    public function count(): int
    {
        return (int) array_sum(array_column($this->items(), 'quantity'));
    }

    public function total(): float
    {
        return (float) array_sum(array_column($this->items(), 'subtotal'));
    }

    public function isEmpty(): bool
    {
        return empty($this->items());
    }

    /**
     * Agrega una cantidad del producto respetando el stock disponible.
     * Devuelve null si se agregó, o el mensaje de error.
     */
    public function add(Product $product, int $quantity = 1): ?string
    {
        $quantity = max(1, $quantity);
        $cart = $this->items();
        $current = $cart[$product->id]['quantity'] ?? 0;

        if ($current + $quantity > (int) $product->quantity) {
            return 'No hay suficiente cantidad disponible para el producto.';
        }

        $this->put($cart, $product, $current + $quantity);

        return null;
    }

    /**
     * Fija la cantidad exacta de un producto del carrito (0 lo elimina).
     */
    public function update(Product $product, int $quantity): ?string
    {
        if ($quantity <= 0) {
            $this->remove($product->id);

            return null;
        }

        if ($quantity > (int) $product->quantity) {
            return 'Solo quedan ' . (int) $product->quantity . ' unidades de ' . $product->name . '.';
        }

        $this->put($this->items(), $product, $quantity);

        return null;
    }

    public function remove($productId): void
    {
        $cart = $this->items();
        unset($cart[$productId]);
        session()->put(self::SESSION_KEY, $cart);
    }

    /**
     * Quita del carrito los productos que ya no se muestran en la tienda
     * (producto, subcategoría o categoría inactivos, o producto eliminado).
     * Devuelve los nombres de los productos quitados.
     */
    public function removeUnavailable(): array
    {
        $cart = $this->items();
        $visible = Product::visible()->whereIn('id', array_keys($cart))->pluck('id')->all();
        $removed = [];

        foreach ($cart as $productId => $item) {
            if (!in_array($productId, $visible)) {
                $removed[] = $item['name'] ?? 'Producto';
                unset($cart[$productId]);
            }
        }

        if ($removed) {
            session()->put(self::SESSION_KEY, $cart);
        }

        return $removed;
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    private function put(array $cart, Product $product, int $quantity): void
    {
        $cart[$product->id] = [
            'name' => $product->name,
            'quantity' => $quantity,
            'price' => $product->price,
            'subtotal' => $product->price * $quantity,
        ];

        session()->put(self::SESSION_KEY, $cart);
    }
}
