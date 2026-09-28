<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'site_settings';

    protected $fillable = ['key', 'value'];

    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        try {
            $value = static::allCached()[$key] ?? null;
        } catch (\Throwable) {
            return $default;
        }

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
