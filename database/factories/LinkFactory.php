<?php

namespace Database\Factories;

use App\Enums\LinkType;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
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
            'type' => LinkType::Link,
            'title' => fake()->sentence(3),
            'url' => fake()->url(),
            'icon' => null,
            'image_path' => null,
            'bg_color' => null,
            'text_color' => null,
            'is_active' => true,
            'position' => fake()->numberBetween(0, 100),
            'clicks_count' => 0,
        ];
    }

    public function heading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => LinkType::Heading,
            'url' => null,
        ]);
    }

    public function divider(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => LinkType::Divider,
            'url' => null,
            'title' => '',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
