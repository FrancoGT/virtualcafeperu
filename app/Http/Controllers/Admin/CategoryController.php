<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageInput;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use HandlesImageInput;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            // Contadores para el listado (los productos incluyen los inactivos)
            ->withCount([
                'subcategories',
                'products',
                'products as active_products_count' => fn ($query) => $query->where('products.status', 1),
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->when(in_array($request->input('status'), ['0', '1'], true), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->paginate()
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:categories,name',
            ] + $this->imageRules($request, false), [], $this->imageAttributes());
            $name = $validatedData['name'];
            $slug = strtolower($name);

            $category = new Category([
                'name' => $name,
                'slug' => $slug,
                'icon' => '<i class="fa-solid fa-pie"></i>',
                'status' => 1
            ]);

            $category->save();

            // Imagen: archivo subido o URL externa
            $this->saveImageInput($request, $category);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Bien hecho',
                'text' => 'Categoría creada correctamente.'
            ]);

            return redirect()->route('admin.categories.index');
        } catch (ValidationException $e) {
            // Redirigir de vuelta con los errores y los datos antiguos
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos guardar la categoría',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validatedData = $request->validate([
                // Ignora la propia categoría para poder guardar sin cambiar el nombre
                'name' => 'required|unique:categories,name,' . $category->id,
            ] + $this->imageRules($request, false), [], $this->imageAttributes());

            $name = $validatedData['name'];
            $slug = strtolower($name);

            $category->name = $name;
            $category->slug = $slug;
            $category->icon = '<i class="fa-solid fa-pie"></i>';

            $category->save();

            // Imagen: archivo subido o URL externa
            $this->saveImageInput($request, $category);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Categoría actualizada correctamente.'
            ]);

            return redirect()->route('admin.categories.index');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos actualizar la categoría',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request, Category $category)
    {
        $category->status = $request->input('status');
        $category->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estado Actualizado',
            'text' => 'El estado de la categoría ha sido actualizado con éxito.'
        ]);

        return redirect()->route('admin.categories.index');
    }
}