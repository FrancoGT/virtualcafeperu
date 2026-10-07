<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\InteractsWithCart;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PopularProducts extends Component
{
    use InteractsWithCart;

    public function render()
    {
        // Productos con al menos 3 pedidos, del más al menos pedido
        $ranking = OrderDetail::select('product_id', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('product_id')
            ->having('total_orders', '>=', 3)
            ->orderBy('total_orders', 'desc')
            ->limit(6)
            ->pluck('product_id');

        $products = Product::visible()
            ->with('subcategory')
            ->whereIn('id', $ranking)
            ->get()
            ->sortBy(fn ($product) => $ranking->search($product->id))
            ->values();

        return view('livewire.popular-products', compact('products'));
    }
}
