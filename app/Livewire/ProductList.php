<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\InteractsWithCart;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination, InteractsWithCart;

    public $search = '';
    public $subcategory = '';
    public $category = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'subcategory' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function selectSubcategory($id = '')
    {
        $this->subcategory = $id;
        $this->category = '';
        $this->resetPage();
    }

    // Pulsar la categoría activa la desmarca
    public function selectCategory($id = '')
    {
        $this->category = (string) $this->category === (string) $id ? '' : $id;
        $this->subcategory = '';
        $this->resetPage();
    }

    // Toda la categoría, sin alternar (chip "Todo ..." en móvil)
    public function showCategory($id)
    {
        $this->category = $id;
        $this->subcategory = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'subcategory', 'category']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('status', 1)
            ->with(['subcategories' => function ($query) {
                $query->where('status', 1)
                    ->withCount(['products' => fn ($q) => $q->where('status', 1)])
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get()
            ->each(fn ($category) => $category->setRelation(
                'subcategories',
                $category->subcategories->where('products_count', '>', 0)->values()
            ))
            // Sólo categorías con productos que mostrar
            ->filter(fn ($category) => $category->subcategories->isNotEmpty())
            ->values();

        $subcategories = $categories->pluck('subcategories')->flatten();
        $selected = $subcategories->firstWhere('id', (int) $this->subcategory);
        $selectedCategory = $selected
            ? $categories->firstWhere('id', $selected->category_id)
            : $categories->firstWhere('id', (int) $this->category);
        $filtering = trim($this->search) !== '' || $selected || ($selectedCategory && !$selected);

        $query = Product::where('status', 1)
            ->with('subcategory')
            ->whereIn('subcategory_id', $subcategories->pluck('id'))
            ->orderBy('name');

        if ($filtering) {
            // Resultado filtrado: lista paginada
            $products = $query
                ->when($selected, fn ($q) => $q->where('subcategory_id', $selected->id))
                ->when(!$selected && $selectedCategory, fn ($q) => $q->whereIn('subcategory_id', $selectedCategory->subcategories->pluck('id')))
                ->when(trim($this->search) !== '', function ($q) {
                    $term = '%' . trim($this->search) . '%';
                    $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('description', 'like', $term));
                })
                ->paginate(12);
            $groups = collect();
        } else {
            // Menú completo agrupado por subcategoría, en el orden del sidebar
            $products = null;
            $all = $query->get()->groupBy('subcategory_id');
            $groups = $subcategories
                ->filter(fn ($sub) => $all->has($sub->id))
                ->map(fn ($sub) => ['subcategory' => $sub, 'products' => $all[$sub->id]]);
        }

        return view('livewire.product-list', [
            'categories' => $categories,
            'selected' => $selected,
            'selectedCategory' => $selectedCategory,
            // URL de la imagen de cada subcategoría (o null), resuelta una sola vez por render
            'subImages' => $subcategories->mapWithKeys(fn ($sub) => [$sub->id => $sub->image_url]),
            'filtering' => $filtering,
            'products' => $products,
            'groups' => $groups,
            'totalProducts' => $subcategories->sum('products_count'),
        ]);
    }
}
