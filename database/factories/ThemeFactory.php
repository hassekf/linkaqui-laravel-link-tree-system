<?php

namespace Database\Factories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Theme>
 */
class ThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->optional()->sentence(),
            'preview_image' => null,
            'config' => [
                'background' => fake()->hexColor(),
                'backgroundType' => 'solid',
                'grain' => false,
                'cardBg' => 'rgba(255,255,255,0.05)',
                'cardBorder' => 'rgba(255,255,255,0.1)',
                'cardBlur' => true,
                'textPrimary' => '#ffffff',
                'textSecondary' => '#9ca3af',
                'fontFamily' => 'Inter',
                'animations' => 'staggered',
            ],
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
