<?php

namespace Database\Seeders;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product1 = Product::create([
            'name' => 'Empanada Triángulo',
            'slug' => Str::slug('Empanada Triángulo'),
            'description' => 'Empanada Triángulo',
            'price' => 8.00,
            'subcategory_id' => 4,
            'quantity' => 6,
            'status' => '1',
        ]);

        Image::create([
            'url' => 'storage/products/' . $product1->id . '.png',
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class,
        ]);

        $product2 = Product::create([
            'name' => 'Empanada de queso',
            'slug' => Str::slug('Empanada de queso'),
            'description' => 'Una empanada rellena de queso.',
            'price' => 5.00,
            'subcategory_id' => 4,
            'quantity' => 15,
            'status' => '1',
        ]);

        Image::create([
            'url' => 'storage/products/' . $product2->id . '.png',
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class,
            // Agrega más campos de la imagen si es necesario
        ]);

        $product3 = Product::create([
            'name' => 'Empanada argentina',
            'slug' => Str::slug('Empanada argentina'),
            'description' => 'Empanada argentina.',
            'price' => 8.00,
            'subcategory_id' => 4,
            'quantity' => 3,
            'status' => '1',
        ]);

        Image::create([
            'url' => 'storage/products/' . $product3->id . '.png',
            'imageable_id' => $product3->id,
            'imageable_type' => Product::class,
        ]);

        $product4 = Product::create([
            'name' => 'Rollos de canela',
            'slug' => Str::slug('Rollos de canela'),
            'description' => 'Rollo de canela.',
            'price' => 7.50,
            'subcategory_id' => 4,
            'quantity' => 3,
            'status' => '1',
        ]);

        Image::create([
            'url' => 'storage/products/' . $product4->id . '.png',
            'imageable_id' => $product4->id,
            'imageable_type' => Product::class,
        ]);

        $product5 = Product::create([
            'name' => 'Capuchino  Matcha Lata',
            'slug' => Str::slug('Capucchino  Matcha Lata'),
            'description' => 'Capucchino  Matcha Lata',
            'price' => 12.00,
            'subcategory_id' => 9,
            'quantity' => 3,
            'status' => '1',
        ]);
        Image::create([
            'url' => 'storage/products/' . $product5->id . '.png',
            'imageable_id' => $product5->id,
            'imageable_type' => Product::class,
        ]);
        $product6 = Product::create([
            'name' => 'Ice caramelo',
            'slug' => Str::slug('Capucchino  Matcha Lata'),
            'description' => 'Capucchino  Matcha Lata',
            'price' => 12.00,
            'subcategory_id' => 8,
            'quantity' => 3,
            'status' => '1',
        ]);
        Image::create([
            'url' => 'storage/products/' . $product6->id . '.png',
            'imageable_id' => $product6->id,
            'imageable_type' => Product::class,
        ]);
    }
}
