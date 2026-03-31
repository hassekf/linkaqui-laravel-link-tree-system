<?php

namespace App\Models;

use Database\Factories\ClickFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['link_id', 'ip_hash', 'clicked_at'])]
#[Hidden(['ip_hash'])]
class Click extends Model
{
    /** @use HasFactory<ClickFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Link, $this> */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
