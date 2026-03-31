<?php

namespace Database\Factories;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Click>
 */
class ClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'clicked_at' => fake()->dateTimeBetween('-30 days'),
        ];
    }
}
