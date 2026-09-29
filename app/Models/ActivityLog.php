<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'action', 'section', 'subject', 'url', 'ip'];

    public const ACTIONS = [
        'login' => ['ورود به پنل', 'ri-login-circle-line', 'blue'],
        'logout' => ['خروج از پنل', 'ri-logout-circle-line', 'muted'],
        'login_failed' => ['تلاش ناموفق ورود', 'ri-error-warning-line', 'rose'],
        'create' => ['افزودن', 'ri-add-circle-line', 'green'],
        'update' => ['ویرایش', 'ri-edit-2-line', 'amber'],
        'delete' => ['حذف', 'ri-delete-bin-6-line', 'rose'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return self::ACTIONS[$this->action][0] ?? $this->action;
    }
}
