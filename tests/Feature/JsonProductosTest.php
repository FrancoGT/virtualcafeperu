<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JsonProductosTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $attributes = []): Product
    {
        $category = Category::forceCreate(['name' => 'Bebidas', 'slug' => 'bebidas', 'icon' => '', 'status' => 1]);
        $subcategory = Subcategory::forceCreate([
            'category_id' => $category->id, 'name' => 'Cafés', 'slug' => 'cafes', 'image' => ' ', 'status' => 1,
        ]);

        return Product::forceCreate($attributes + [
            'subcategory_id' => $subcategory->id,
            'name' => 'Café Expreso Americano',
            'slug' => 'cafe-expreso-americano',
            'description' => 'Un café expreso americano.',
            'price' => 6,
            'quantity' => 7,
            'status' => 1,
        ]);
    }

    #[Test]
    public function search_requires_findproduct()
    {
        $this->getJson('/products/search')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['findproduct' => 'El campo findproduct es obligatorio.']);
    }

    #[Test]
    public function it_searches_visible_products_by_name()
    {
        $product = $this->product();
        $this->product(['name' => 'Café oculto', 'slug' => 'cafe-oculto', 'status' => 0]);

        $this->getJson('/products/search?findproduct=Expreso')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('per_page', 10)
            ->assertJsonPath('data.0.id', $product->id)
            ->assertJsonPath('data.0.name', 'Café Expreso Americano');
    }

    #[Test]
    public function it_returns_popular_products()
    {
        $this->getJson('/products/popular')
            ->assertOk()
            ->assertJsonPath('data', []);
    }

    #[Test]
    public function it_gets_products_by_subcategory()
    {
        $this->getJson('/products/products-by-subcategory?subcategory-selected=Nonexistent')
            ->assertStatus(404)
            ->assertJson(['message' => 'Subcategory not found']);
    }

    #[Test]
    public function it_validates_subcategory_selected_in_get_products_by_subcategory()
    {
        $this->getJson('/products/products-by-subcategory')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['subcategory-selected']);
    }
}
