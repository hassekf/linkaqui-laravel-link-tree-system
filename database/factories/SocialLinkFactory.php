<?php

namespace Database\Factories;

use App\Enums\SocialPlatform;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $platform = fake()->randomElement(SocialPlatform::cases());

        return [
            'user_id' => User::factory(),
            'platform' => $platform,
            'url' => fake()->url(),
            'position' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
