<?php

namespace Database\Factories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subcategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'image' => 'subcategories/'.$this->faker->image('public/storage/subcategories', 640, 480, null, false),
            'category_id' => \App\Models\Category::factory(), // Asociación con una categoría existente
            'status' => 1,
        ];
    }
}