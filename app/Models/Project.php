<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use Publishable;

    public const STATUSES = [
        'completed' => 'تکمیل شده',
        'in_progress' => 'در حال اجرا',
        'design' => 'در مرحله طراحی',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'gallery' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function getUrlAttribute(): string
    {
        return route('projects.show', $this->slug);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? '—';
    }
}
