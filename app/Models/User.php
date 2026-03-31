<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'username', 'email', 'password', 'bio', 'avatar_path', 'is_admin', 'is_active', 'settings'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    /** @return HasMany<Link, $this> */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->ordered();
    }

    /** @return HasMany<SocialLink, $this> */
    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->ordered();
    }

    /** @return HasMany<Embed, $this> */
    public function embeds(): HasMany
    {
        return $this->hasMany(Embed::class)->ordered();
    }

    /** @return HasMany<Product, $this> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** @return HasMany<Visit, $this> */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/'.$this->avatar_path);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=7c5cfc&color=fff&size=220';
    }

    public function getThemeSlugAttribute(): string
    {
        return $this->settings['theme_slug'] ?? 'midnight-glow';
    }

    public function getSocialPositionAttribute(): string
    {
        return $this->settings['social_position'] ?? 'bottom';
    }

    public function getFontFamilyAttribute(): ?string
    {
        return $this->settings['font_family'] ?? null;
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
