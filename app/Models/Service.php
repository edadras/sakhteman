<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Publishable;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function getUrlAttribute(): string
    {
        return route('services.show', $this->slug);
    }

    /**
     * ویژگی‌ها به صورت متن چندخطی ذخیره می‌شوند (هر خط یک ویژگی).
     */
    public function getFeatureListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $this->features))));
    }
}
