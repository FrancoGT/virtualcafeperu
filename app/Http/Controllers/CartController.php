<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request, CartService $cart, $productId)
    {
        $product = Product::visible()->find($productId);

        if (!$product) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Producto no disponible',
                'text' => 'Este producto ya no está disponible en la tienda.',
            ]);
        }

        $error = $cart->add($product, (int) $request->input('quantity', 1));

        if ($error) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => $error,
            ]);
        }

        return redirect()->back()->with('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Producto añadido al carrito.'
        ]);
    }

    public function showCart()
    {
        return view('cart');
    }

    public function checkout(CartService $cart)
    {
        if ($cart->isEmpty()) {
            return redirect()->back()->with('swal', [
                'icon' => 'error',
                'title' => 'Tu carrito está vacío',
                'text' => 'Agrega al menos un producto antes de confirmar el pedido.'
            ]);
        }

        // No confirmar productos que dejaron de mostrarse en la tienda desde que se añadieron
        $removed = $cart->removeUnavailable();

        if ($removed) {
            return redirect()->back()->with('swal', [
                'icon' => 'warning',
                'title' => count($removed) === 1 ? 'Un producto ya no está disponible' : 'Algunos productos ya no están disponibles',
                'text' => 'Quitamos de tu pedido: ' . implode(', ', $removed) . '. '
                    . ($cart->isEmpty()
                        ? 'Tu carrito quedó vacío; agrega otros productos para continuar.'
                        : 'Revisa tu pedido y vuelve a confirmarlo.'),
            ]);
        }

        DB::transaction(function () use ($cart) {
            $order = new Order();
            $order->user_id = Auth::check() ? Auth::id() : config('app.anonymous_user_id'); // Usuario anónimo
            $order->status = 'pending'; // Estado inicial de la orden
            $order->save();

            foreach ($cart->items() as $productId => $productDetails) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $productId;
                $orderDetail->quantity = $productDetails['quantity'];
                $orderDetail->price = $productDetails['price'];
                $orderDetail->save();
            }
        });

        $cart->clear();

        return redirect(url('/') . '#pedidos')->with('swal', [
            'icon' => 'success',
            'title' => '¡Pedido registrado!',
            'text' => 'Notifícalo por WhatsApp para que lo preparemos.'
        ]);
    }

    public function removeFromCart(CartService $cart, $productId)
    {
        $cart->remove($productId);

        return redirect()->back()->with('swal', [
            'icon' => 'info',
            'title' => '¡Pedido Eliminado!',
            'text' => 'Pedido quitado del carrito.'
        ]);
    }
}
