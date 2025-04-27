<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

   
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = fake()->text(100);
        return [
            'title' => fake()->text(100),
            'slug' => Str::slug($title),
            'description' => fake()->realText(1000),
            'price' => fake()->randomFloat(2, 20, 500),
            'quantity' => fake()->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
            'published' => 1,
        ];
    }
}
