<?php

namespace App\Models;

use App\Concerns\BelongsToUser;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'name', 'code', 'image_path', 'original_price', 'sale_price', 'url', 'is_active'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use BelongsToUser, HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'is_active' => 'boolean',
            'searches_count' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->original_price || ! $this->sale_price || $this->original_price <= 0) {
            return null;
        }

        return (int) round((1 - $this->sale_price / $this->original_price) * 100);
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(str_replace(['0', 'O', 'I', 'L'], ['X', 'P', 'J', 'K'], bin2hex(random_bytes(3))), 0, 6));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
