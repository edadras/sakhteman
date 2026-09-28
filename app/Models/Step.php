<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use Publishable;

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];
}
