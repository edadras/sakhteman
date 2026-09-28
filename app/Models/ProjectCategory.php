<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    use Publishable;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'category_id');
    }
}
