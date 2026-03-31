<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visit>
 */
class VisitFactory extends Factory
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
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'user_agent' => fake()->userAgent(),
            'referer' => fake()->optional()->url(),
            'country' => fake()->countryCode(),
            'visited_at' => fake()->dateTimeBetween('-30 days'),
        ];
    }
}
