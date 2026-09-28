<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Publishable;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->published_at ??= now();
        });
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', $this->slug);
    }
}
