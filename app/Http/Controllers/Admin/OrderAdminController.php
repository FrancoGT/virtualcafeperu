<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $category = $request->filled('category_id')
            ? $categories->firstWhere('id', (int) $request->input('category_id'))
            : null;

        // Pagina los pedidos y ordena por created_at descendente
        $orders = Order::with('orderDetails.product.subcategory')
                       // Pedidos con al menos un producto de la categoría (mismo criterio que el dashboard)
                       ->when($category, function ($query) use ($category) {
                           $query->whereHas('orderDetails.product.subcategory', function ($query) use ($category) {
                               $query->where('category_id', $category->id);
                           });
                       })
                       // Busca por número de pedido (#12 o 12) o por nombre del cliente
                       ->when($search !== '', function ($query) use ($search) {
                           $query->where(function ($query) use ($search) {
                               $id = ltrim($search, '#');
                               if (ctype_digit($id)) {
                                   $query->where('id', $id);
                               }
                               $query->orWhereHas('user', function ($query) use ($search) {
                                   $query->where('name', 'like', '%' . $search . '%');
                               });
                           });
                       })
                       ->when(in_array($request->input('status'), ['pending', 'paid', 'served', 'refused'], true), function ($query) use ($request) {
                           $query->where('status', $request->input('status'));
                       })
                       ->orderBy('created_at', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        return view('admin.orders.index', compact('orders', 'categories', 'category'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validar el request
        $request->validate([
            'status' => 'required|in:pending,paid,served,refused',
        ]);

        // Encontrar el pedido
        $order = Order::findOrFail($id);

        // Obtener el nuevo estado del pedido
        $newStatus = $request->input('status');

        // El stock está descontado mientras el pedido está pagado (o servido, que viene después del pago)
        $stockDiscounted = in_array($order->status, ['paid', 'served']);

        DB::transaction(function () use ($order, $newStatus, $stockDiscounted) {
            // Al pasar a 'paid', descontar la cantidad de los productos
            if ($newStatus == 'paid' && !$stockDiscounted) {
                foreach ($order->orderDetails as $detail) {
                    $product = Product::findOrFail($detail->product_id);
                    $newQuantity = $product->quantity - $detail->quantity;

                    // Asegurarse de que la cantidad del producto no sea negativa
                    $product->quantity = max(0, $newQuantity);
                    $product->save();
                }
            }

            // Al salir del pago (vuelve a pendiente o se rechaza), devolver el stock
            if ($stockDiscounted && in_array($newStatus, ['pending', 'refused'])) {
                foreach ($order->orderDetails as $detail) {
                    $product = Product::find($detail->product_id);

                    // Si el producto fue eliminado no hay stock que devolver
                    if ($product) {
                        $product->quantity = $product->quantity + $detail->quantity;
                        $product->save();
                    }
                }
            }

            // Actualizar el estado del pedido
            $order->status = $newStatus;
            $order->save();
        });

        // Redireccionar con un mensaje de éxito
        return redirect()->route('admin.orders.index')->with('swal', [
            'title' => 'Éxito',
            'text' => 'El estado del pedido ha sido actualizado.',
        ]);
    }
}
