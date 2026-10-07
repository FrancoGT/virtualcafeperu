<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Panel principal: sólo lecturas sobre las tablas existentes.
     *
     * Filtro opcional ?category_id: productos, subcategorías, pedidos (los que incluyen algún producto
     * de la categoría), ingresos (sólo la parte de esa categoría) y más pedidos se limitan a la categoría.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $category = $request->filled('category_id')
            ? $categories->firstWhere('id', (int) $request->input('category_id'))
            : null;
        $categoryId = optional($category)->id;

        // Restricciones por categoría (no hacen nada sin filtro)
        $inCategory = function ($query) use ($categoryId) {
            $query->when($categoryId, fn ($q) => $q->where('category_id', $categoryId));
        };
        $productInCategory = function ($query) use ($categoryId) {
            $query->when($categoryId, fn ($q) => $q->whereHas('subcategory', fn ($sub) => $sub->where('category_id', $categoryId)));
        };
        $detailInCategory = function ($query) use ($categoryId) {
            $query->when($categoryId, fn ($q) => $q->whereHas('product.subcategory', fn ($sub) => $sub->where('category_id', $categoryId)));
        };
        $orderInCategory = function ($query) use ($categoryId, $detailInCategory) {
            $query->when($categoryId, fn ($q) => $q->whereHas('orderDetails', $detailInCategory));
        };

        $totals = [
            'products' => Product::where($productInCategory)->count(),
            'categories' => Category::count(),
            'active_categories' => Category::where('status', 1)->count(),
            'subcategories' => Subcategory::where($inCategory)->count(),
            // Subcategorías sin ningún producto (ni activo ni inactivo): la tienda no las muestra
            'empty_subcategories' => Subcategory::where($inCategory)->doesntHave('products')->count(),
            'orders' => Order::where($orderInCategory)->count(),
        ];

        // Pedidos agrupados por estado (pending, paid, served, refused)
        // (alias distinto de "total": Order ya tiene el accessor getTotalAttribute)
        $ordersByStatus = Order::where($orderInCategory)
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // Ingresos = suma de cantidad x precio de los detalles de pedidos pagados
        $revenue = OrderDetail::where($detailInCategory)
            ->whereHas('order', function ($query) {
                $query->where('status', 'paid');
            })->sum(DB::raw('quantity * price'));

        // Mismo criterio que Product::getIsAvailableAttribute (quantity > 0)
        $outOfStock = Product::where($productInCategory)
            ->where(function ($query) {
                $query->whereNull('quantity')->orWhere('quantity', '<=', 0);
            })->count();

        $recentOrders = Order::where($orderInCategory)
            ->with(['user', 'orderDetails'])
            ->latest()
            ->take(5)
            ->get();

        // Productos con más unidades pedidas (excluye pedidos rechazados)
        $topProducts = OrderDetail::where($detailInCategory)
            ->select('product_id', DB::raw('SUM(quantity) as units'))
            ->whereHas('order', function ($query) {
                $query->where('status', '!=', 'refused');
            })
            ->groupBy('product_id')
            ->orderByDesc('units')
            ->with('product')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'categories',
            'category',
            'totals',
            'ordersByStatus',
            'revenue',
            'outOfStock',
            'recentOrders',
            'topProducts'
        ));
    }
}
