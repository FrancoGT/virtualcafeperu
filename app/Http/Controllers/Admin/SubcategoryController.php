<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesImageInput;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    use HandlesImageInput;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subcategories = Subcategory::with('category')
                // Contadores para el listado (los productos incluyen los inactivos)
                ->withCount([
                    'products',
                    'products as active_products_count' => fn ($query) => $query->where('status', 1),
                ])
                ->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('search') . '%');
                })
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    $query->where('category_id', $request->input('category_id'));
                })
                ->when(in_array($request->input('status'), ['0', '1'], true), function ($query) use ($request) {
                    $query->where('status', $request->input('status'));
                })
                ->paginate(10)
                ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $validatedData = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|unique:subcategories,name,NULL,id,category_id,' . $request->input('category_id'),
            ] + $this->imageRules($request, false), [], $this->imageAttributes());

            $name = $validatedData['name'];
            $slug = strtolower($name);
            $subcategory = new Subcategory();
            $subcategory->category_id = $validatedData['category_id'];
            $subcategory->name = $name;
            $subcategory->slug = $slug;
            $subcategory->image = ' '; // Sin archivo todavía (la columna no admite null)
            $subcategory->status = 1;
            $subcategory->save();

            // Imagen: archivo subido o URL externa
            $this->saveImageInput($request, $subcategory);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Subcategoría creada correctamente.'
            ]);

            return redirect()->route('admin.subcategories.index');
        } 
        catch (ValidationException $e) 
        {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } 
        catch (\Exception $e) 
        {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos guardar la subcategoría',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        try 
        {
            $validatedData = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|unique:subcategories,name,' . $subcategory->id . ',id,category_id,' . $request->input('category_id'),
            ] + $this->imageRules($request, false), [], $this->imageAttributes());

            $name = $validatedData['name'];
            $slug = strtolower($name);
            $subcategory->category_id = $validatedData['category_id'];
            $subcategory->name = $name;
            $subcategory->slug = $slug;
            $subcategory->save();

            // Imagen: archivo subido o URL externa (si no se elige ninguna, se conserva la actual)
            $this->saveImageInput($request, $subcategory);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Bien hecho!',
                'text' => 'Subcategoría actualizada correctamente.'
            ]);

            return redirect()->route('admin.subcategories.index');
        } 
        catch (ValidationException $e) 
        {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } 
        catch (\Exception $e) 
        {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No pudimos actualizar la subcategoría',
                'text' => 'Ocurrió un problema inesperado. Revisa los datos e inténtalo de nuevo; tus cambios siguen en el formulario.',
                'detail' => $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request, Subcategory $subcategory)
    {
        $subcategory->status = $request->input('status');
        $subcategory->save();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Estado Actualizado',
            'text' => 'El estado de la subcategoría ha sido actualizado con éxito.'
        ]);

        return redirect()->route('admin.subcategories.index');
    }
}
