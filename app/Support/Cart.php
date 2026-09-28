<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * سبد خرید مبتنی بر Session: [product_id => quantity]
 */
class Cart
{
    protected const KEY = 'cart';

    public static function raw(): array
    {
        return (array) session(self::KEY, []);
    }

    public static function put(int $productId, int $quantity): void
    {
        $cart = static::raw();
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($quantity, 99);
        }
        session([self::KEY => $cart]);
    }

    public static function add(int $productId, int $quantity = 1): void
    {
        static::put($productId, (static::raw()[$productId] ?? 0) + $quantity);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    public static function count(): int
    {
        return (int) array_sum(static::raw());
    }

    /**
     * @return Collection<int, object{product: Product, quantity: int, subtotal: int}>
     */
    public static function items(): Collection
    {
        $raw = static::raw();
        if (! $raw) {
            return collect();
        }

        $products = Product::active()->whereIn('id', array_keys($raw))->get()->keyBy('id');

        return collect($raw)
            ->filter(fn ($qty, $id) => isset($products[$id]) && $products[$id]->purchasable)
            ->map(fn ($qty, $id) => (object) [
                'product' => $products[$id],
                'quantity' => (int) $qty,
                'subtotal' => $products[$id]->final_price * (int) $qty,
            ])
            ->values();
    }

    public static function total(?Collection $items = null): int
    {
        return (int) ($items ?? static::items())->sum('subtotal');
    }
}
