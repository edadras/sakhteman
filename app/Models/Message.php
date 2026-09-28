<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'body', 'ip', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];
}
