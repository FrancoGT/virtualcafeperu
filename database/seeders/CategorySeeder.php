<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Repostería y Sándwiches',
                'slug' => Str::slug('Repostería y Sándwiches'),
                'icon' => '<i class="fa-solid fa-pie"></i>',
                'status' => '1',
            ],
            [
                'name' => 'Bebidas',
                'slug' => Str::slug('Bebidas'),
                'icon' => '<i class="fa-solid fa-glass"></i>',
                'status' => '1',
            ]
        ];
        foreach($categories as $category)
        {
            // forceCreate: "status" no está en $fillable y no dependemos de Faker (no se instala con --no-dev)
            Category::forceCreate($category);
        }
    }
}
