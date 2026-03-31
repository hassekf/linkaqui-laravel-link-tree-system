<?php

namespace App\Models;

use App\Concerns\BelongsToUser;
use App\Enums\EmbedType;
use Database\Factories\EmbedFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'type', 'embed_url', 'title', 'position', 'is_active'])]
class Embed extends Model
{
    use BelongsToUser;

    /** @use HasFactory<EmbedFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => EmbedType::class,
            'is_active' => 'boolean',
            'position' => 'integer',
        ];
    }

    /** @param Builder<Embed> $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param Builder<Embed> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position');
    }
}
