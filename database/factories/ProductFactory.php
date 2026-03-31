<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'code' => strtoupper(fake()->unique()->bothify('??####')),
            'original_price' => fake()->randomFloat(2, 50, 500),
            'sale_price' => fake()->randomFloat(2, 20, 400),
            'url' => fake()->url(),
            'is_active' => true,
        ];
    }
}
