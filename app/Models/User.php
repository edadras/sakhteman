<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'mobile', 'address', 'password', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * آیا این کاربر مدیر کل است؟
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_admin && $this->role?->is_super;
    }

    /**
     * بررسی یک دسترسی پنل (مثلا projects.edit)
     */
    public function hasPermission(string $permission): bool
    {
        return $this->is_admin && $this->is_active !== false && (bool) $this->role?->allows($permission);
    }


    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }
}
