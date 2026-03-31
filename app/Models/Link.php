<?php

namespace App\Models;

use App\Concerns\BelongsToUser;
use App\Enums\LinkType;
use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'type', 'title', 'url', 'icon', 'image_path', 'bg_color', 'text_color', 'is_active', 'position'])]
class Link extends Model
{
    use BelongsToUser;

    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => LinkType::class,
            'is_active' => 'boolean',
            'position' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    /** @return HasMany<Click, $this> */
    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    /** @param Builder<Link> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param Builder<Link> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position');
    }
}
