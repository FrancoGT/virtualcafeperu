<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getPendingOrders()
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::check() ? Auth::id() : config('app.anonymous_user_id');

        // Obtener las órdenes pendientes del usuario específico
        $pendingOrders = Order::where('user_id', $userId)
            ->where('status', 'pending')
            ->with('orderDetails') // Incluir detalles de la orden
            ->get();

        // Formatear las órdenes con subtotales y total
        $formattedOrders = $pendingOrders->map(function ($order) {
            $subtotal = $order->orderDetails->sum(function ($detail) {
                return $detail->quantity * $detail->price;
            });
        
            return [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'subtotal' => 'S/. ' . number_format($subtotal, 2),
                'details' => $order->orderDetails->map(function ($detail) {
                    return [
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => 'S/. ' . number_format($detail->price, 2),
                        'total' => 'S/. ' . number_format($detail->quantity * $detail->price, 2),
                    ];
                }),
            ];
        });
        

        // Retornar las órdenes en formato JSON
        return response()->json($formattedOrders);
    }
}
