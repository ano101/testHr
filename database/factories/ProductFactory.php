<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(rand(2, 4), true)),
            'description' => fake()->sentences(rand(2, 4), true),
            'price' => fake()->randomFloat(2, 100, 50000),
            'category_id' => Category::factory(),
        ];
    }
}
