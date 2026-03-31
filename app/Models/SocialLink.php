<?php

namespace App\Models;

use App\Concerns\BelongsToUser;
use App\Enums\SocialPlatform;
use Database\Factories\SocialLinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'platform', 'url', 'position', 'is_active'])]
class SocialLink extends Model
{
    use BelongsToUser;

    /** @use HasFactory<SocialLinkFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'platform' => SocialPlatform::class,
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /** @param Builder<SocialLink> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param Builder<SocialLink> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position');
    }
}
