<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where($this->getTable().'.is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy($this->getTable().'.sort')->orderByDesc($this->getTable().'.id');
    }
}
