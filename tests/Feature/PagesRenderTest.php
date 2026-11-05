<?php

namespace Tests\Feature;

use App\Livewire\CartPanel;
use App\Livewire\ProductList;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Comprueba que las páginas principales (tienda, admin y perfil) se renderizan.
 */
class PagesRenderTest extends TestCase
{
    use RefreshDatabase;

    private function product(): Product
    {
        $category = Category::forceCreate(['name' => 'Bebidas', 'slug' => 'bebidas', 'icon' => '', 'status' => 1]);
        $subcategory = Subcategory::forceCreate([
            'category_id' => $category->id, 'name' => 'Cafés', 'slug' => 'cafes', 'image' => ' ', 'status' => 1,
        ]);

        return Product::forceCreate([
            'subcategory_id' => $subcategory->id, 'name' => 'Café', 'slug' => 'cafe',
            'description' => 'Café pasado', 'price' => 6, 'quantity' => 7, 'status' => 1,
        ]);
    }

    public function test_store_pages_render(): void
    {
        $this->product();

        $this->get('/')->assertOk()->assertSee('Café');
        $this->get('/cart')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_cart_can_be_used_through_livewire(): void
    {
        $product = $this->product();

        Livewire::test(ProductList::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('cartUpdated')
            ->assertDispatched('notify', type: 'success');

        Livewire::test(CartPanel::class)->assertSee('Café');

        Livewire::test(ProductList::class)
            ->set('search', 'zzz')
            ->assertSee('Ver todo el menú');
    }

    public function test_admin_pages_render(): void
    {
        $product = $this->product();
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        foreach ([
            '/admin',
            '/admin/categories', '/admin/categories/create', "/admin/categories/{$product->subcategory->category_id}/edit",
            '/admin/subcategories', '/admin/subcategories/create', "/admin/subcategories/{$product->subcategory_id}/edit",
            '/admin/products', '/admin/products/create', "/admin/products/{$product->id}/edit",
            '/admin/orders',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_upload_a_category_image_saved_as_png(): void
    {
        Storage::fake('public');
        Storage::disk('public')->makeDirectory('categories');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->post('/admin/categories', [
                'name' => 'Postres',
                'image_source' => 'file',
                'image' => UploadedFile::fake()->image('postres.jpg', 40, 40),
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Postres')->firstOrFail();
        $path = "categories/{$category->id}.png";

        Storage::disk('public')->assertExists($path);
        $this->assertSame('image/png', mime_content_type(Storage::disk('public')->path($path)));
    }

    public function test_admin_requires_admin_role(): void
    {
        $client = User::factory()->create(['role_id' => 2]);
        $client->assignRole('client');

        $this->actingAs($client)->get('/admin')->assertForbidden();
    }

    public function test_profile_page_renders(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/user/profile')
            ->assertOk();
    }
}
