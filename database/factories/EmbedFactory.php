<?php

namespace Database\Factories;

use App\Enums\EmbedType;
use App\Models\Embed;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Embed>
 */
class EmbedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(EmbedType::cases());

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'embed_url' => match ($type) {
                EmbedType::YouTube => 'https://www.youtube-nocookie.com/embed/'.fake()->regexify('[a-zA-Z0-9_-]{11}'),
                EmbedType::Spotify => 'https://open.spotify.com/embed/track/'.fake()->regexify('[a-zA-Z0-9]{22}'),
                EmbedType::SoundCloud => 'https://w.soundcloud.com/player/?url='.urlencode('https://soundcloud.com/'.fake()->userName().'/'.fake()->slug()),
                EmbedType::Twitch => 'https://player.twitch.tv/?channel='.fake()->userName().'&parent=localhost&muted=true',
                EmbedType::Vimeo => 'https://player.vimeo.com/video/'.fake()->numberBetween(10000000, 99999999).'?dnt=1',
                EmbedType::TikTok => 'https://www.tiktok.com/embed/v2/'.fake()->numerify('###################'),
                EmbedType::AppleMusic => 'https://embed.music.apple.com/us/album/'.fake()->numberBetween(1000000000, 9999999999),
            },
            'title' => fake()->optional()->sentence(3),
            'position' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }

    public function youtube(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EmbedType::YouTube,
            'embed_url' => 'https://www.youtube.com/watch?v='.fake()->regexify('[a-zA-Z0-9_-]{11}'),
        ]);
    }

    public function spotify(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => EmbedType::Spotify,
            'embed_url' => 'https://open.spotify.com/track/'.fake()->regexify('[a-zA-Z0-9]{22}'),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
