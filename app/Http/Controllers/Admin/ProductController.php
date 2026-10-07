<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageInput;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use HandlesImageInput;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('subcategory')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->whereHas('subcategory', function ($query) use ($request) {
                    $query->where('category_id', $request->input('category_id'));
                });
            })
            ->when($request->filled('subcategory_id'), function ($query) use ($request) {
                $query->where('subcategory_id', $request->input('subcategory_id'));
            })
            ->when(in_array($request->input('status'), ['0', '1'], true), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            // Mismo criterio que Product::getIsAvailableAttribute (quantity > 0)
            ->when($request->input('stock') === 'available', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->when($request->input('stock') === 'out', function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('quantity')->orWhere('quantity', '<=', 0);
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        // Con una categoría elegida, el filtro de subcategoría sólo ofrece las suyas
        $subcategories = Subcategory::orderBy('name')
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->input('category_id'));
            })
            ->get(['id', 'name', 'category_id']);

        return view('admin.products.index', compact('products', 'categories', 'subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = Subcategory::all();
        return view('admin.products.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'subcategory_id' => 'required|exists:subcategories,id',
                'name' => 'required|unique:products',
                'description' => 'required',
                'price' => 'required|numeric|min:1',
                'quantity' => 'nullable|integer|min:1',
            ] + $this->imageRules($request, true), [], $this->imageAttributes());

            $name = $validatedData['name'];
            $slug = Str::slug($name);
            $description = $validatedData['description'];
            $price = $validatedData['price'];
            $quantity = $request->input('quantity', 1); // Valor por defecto para quantity

            $product = new Product();
            $product->name = $name;
            $product->slug = $slug;
            $product->description = $description;
            $product->price = $price;
            $product->quantity = $quantity;
            $product->subcategory_id = $validatedData['subcategory_id'];
            $product->save();

            // Imagen: archivo subido o URL externa
            $this->saveImageInput($request, $product);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Producto creado correctamente.'
            ]);

            return redirect()->route('admin.products.index');
        } catch (ValidationException $e) {
            // Redirigir de vuelta con los errores de validación y los datos ingresados
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos guardar el producto',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $subcategories = Subcategory::all();
        return view('admin.products.edit', compact('product', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'subcategory_id' => 'required|exists:subcategories,id',
                'name' => 'required|unique:products,name,' . $id,
                'description' => 'required',
                'price' => 'required|numeric|min:1',
                'quantity' => 'nullable|integer|min:1',
            ] + $this->imageRules($request, false), [], $this->imageAttributes());

            $product = Product::findOrFail($id);
            $product->name = $validatedData['name'];
            $product->description = $validatedData['description'];
            $product->price = $validatedData['price'];
            $product->quantity = $request->input('quantity', 1);
            // El estado se conserva: sólo se cambia desde el interruptor (updateStatus)
            $product->subcategory_id = $validatedData['subcategory_id'];
            $product->save();

            // Imagen: archivo subido o URL externa
            $this->saveImageInput($request, $product);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Producto actualizado correctamente.'
            ]);

            return redirect()->route('admin.products.index');
        } catch (ValidationException $e) {
            // Redirigir de vuelta con los errores de validación y los datos ingresados
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos actualizar el producto',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request, Product $product)
    {
        $product->status = $request->input('status');
        $product->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estado Actualizado',
            'text' => 'El estado del producto ha sido actualizado con éxito.'
        ]);

        return redirect()->route('admin.products.index');
    }
}
