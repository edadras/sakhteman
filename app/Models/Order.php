<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'در انتظار بررسی',
        'confirmed' => 'تایید شده',
        'sending' => 'در حال ارسال',
        'done' => 'تحویل شده',
        'canceled' => 'لغو شده',
    ];

    public const STATUS_COLORS = [
        'pending' => 'amber',
        'confirmed' => 'blue',
        'sending' => 'blue',
        'done' => 'green',
        'canceled' => 'rose',
    ];

    protected $guarded = ['id'];

    protected $casts = ['total' => 'integer'];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->code ??= static::generateCode();
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = 'TH-'.random_int(100000, 999999);
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'amber';
    }
}
