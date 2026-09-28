<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use Publishable;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'in_stock' => 'boolean',
        'price_from' => 'boolean',
        'gallery' => 'array',
        'price' => 'integer',
        'sale_price' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function getUrlAttribute(): string
    {
        return route('shop.show', $this->slug);
    }

    /** قیمت نهایی (با احتساب تخفیف) */
    public function getFinalPriceAttribute(): ?int
    {
        if ($this->sale_price && $this->price && $this->sale_price < $this->price) {
            return $this->sale_price;
        }

        return $this->price ?: null;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->sale_price && $this->price && $this->sale_price < $this->price) {
            return (int) round(100 - ($this->sale_price * 100 / $this->price));
        }

        return 0;
    }

    public function getPurchasableAttribute(): bool
    {
        return $this->in_stock && $this->final_price > 0;
    }

    public function getSpecListAttribute(): array
    {
        $rows = [];
        foreach (preg_split('/\r?\n/', (string) $this->specs) as $line) {
            if (trim($line) === '') {
                continue;
            }
            [$k, $v] = array_pad(array_map('trim', explode(':', $line, 2)), 2, '');
            $rows[] = [$k, $v];
        }

        return $rows;
    }
}
