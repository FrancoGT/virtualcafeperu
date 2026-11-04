<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PedidosPendientes extends Component
{
    public function render()
    {
        $userId = Auth::check() ? Auth::id() : config('app.anonymous_user_id');

        $orders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->with('orderDetails.product')
            ->latest()
            ->get();

        return view('livewire.pedidos-pendientes', compact('orders'));
    }

    public static function whatsappLink(Order $order): string
    {
        $message = "Buen día, acabo de hacer un Pedido \nDetalles del Pedido:\n\nID de Orden: {$order->id}\n\n";

        foreach ($order->orderDetails as $detail) {
            $message .= ($detail->product->name ?? 'Producto') . " (Cantidad: {$detail->quantity}) - Precio: S/. "
                . number_format($detail->price, 2) . "\n";
        }

        $message .= "\nTotal a Pagar: S/. " . number_format($order->total, 2)
            . " \n Si transfiero el pago por Yape, reserveme el pedido";

        $phone = preg_replace('/\D/', '', config('app.whatsapp_number'));

        return 'https://api.whatsapp.com/send?phone=' . $phone . '&text=' . rawurlencode($message);
    }
}
