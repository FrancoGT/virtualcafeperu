<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subcategories = [
            [
                'category_id' => 1,
                'name' => 'Postres',
                'slug' => Str::slug('Postres'),
                'status' => '1',
            ],
            [
                'category_id' => 1,
                'name' => 'Postres con Helado',
                'slug' => Str::slug('Tortas'),
                'status' => '1',
            ],
            [
                'category_id' => 2,
                'name' => 'Jugos',
                'slug' => Str::slug('Jugos'),
                'status' => '1',
            ],
            [
                'category_id' => 1,
                'name' => 'Empanadas',
                'slug' => Str::slug('Empanadas'),
                'status' => '1',
            ],
            [
                'category_id' => 1,
                'name' => 'Sandwiches frios',
                'slug' => Str::slug('Sandwiches frios'),
                'status' => '1',
            ],
            [
                'category_id' => 1,
                'name' => 'Pizzetas',
                'slug' => Str::slug('Pizzetas'),
                'status' => '1',
            ],
            [
                'category_id' => 2,
                'name' => 'Bebidas con Licor',
                'slug' => Str::slug('Bebidas con Licor'),
                'status' => '1',
            ],
            [
                'category_id' => 2,
                'name' => 'Bebidas frias',
                'slug' => Str::slug('Bebidas frias'),
                'status' => '1',
            ],
            [
                'category_id' => 2,
                'name' => 'Bebidas calientes',
                'slug' => Str::slug('Bebidas calientes'),
                'status' => '1',
            ]
        ];
        foreach ($subcategories as $subcategory)
        {
            Subcategory::factory(1)->create($subcategory);
        }
    }
}
