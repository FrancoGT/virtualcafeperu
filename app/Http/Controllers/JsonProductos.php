<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Subcategory;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JsonProductos extends Controller
{
    public function index()
    {
        $products = Product::visible()->get();
        return response()->json($products);
    }
    public function search(Request $request)
    {
        // Validar que el campo findproduct está presente en la solicitud
        $request->validate([
            'findproduct' => 'required|string|max:255',
        ]);

        // Obtener el valor del input findproduct
        $findProduct = $request->input('findproduct');

        // Especificar el número de elementos por página
        $perPage = 10;

        // Buscar productos visibles en la tienda cuyos nombres contengan el valor del input, con paginación
        $products = Product::visible()
            ->where('name', 'LIKE', '%' . $findProduct . '%')
            ->paginate($perPage);

        // Retornar los productos encontrados en formato JSON
        return response()->json($products);
    }

    public function getPopularProducts()
    {
        // Obtener los productos más pedidos con al menos 3 pedidos
        $popularProducts = OrderDetail::select('product_id', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('product_id')
            ->having('total_orders', '>=', 3)
            ->orderBy('total_orders', 'desc')
            ->paginate(5);

        $productIds = $popularProducts->pluck('product_id');

        // Obtener los detalles paginados de los productos más pedidos visibles en la tienda
        $products = Product::visible()
            ->whereIn('id', $productIds)
            ->paginate(5);

        return response()->json($products);
    }

    public function getProductsBySubcategory(Request $request)
    {
        // Validar que el campo subcategory-selected está presente en la solicitud
        $request->validate([
            'subcategory-selected' => 'required|string|max:255',
        ]);

        // Obtener el valor del input subcategory-selected
        $subcategoryName = $request->input('subcategory-selected');

        // Especificar el número de elementos por página
        $perPage = 10;

        // Buscar la subcategoría por su nombre (solo si está activa y su categoría también)
        $subcategory = Subcategory::visible()->where('name', $subcategoryName)->first();

        if (!$subcategory) 
        {
            // Retornar una respuesta de error si no se encuentra la subcategoría
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        // Recuperar los productos activos de la subcategoría encontrada, con paginación
        $products = $subcategory->products()->where('status', 1)->paginate($perPage);

        // Retornar los productos encontrados en formato JSON
        return response()->json($products);
    }
}
