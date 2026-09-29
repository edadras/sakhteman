<?php

namespace App\Support;

use App\Models\ActivityLog;

/**
 * ثبت فعالیت کاربران پنل مدیریت
 */
class Activity
{
    public static function log(string $action, ?string $section = null, ?string $subject = null, ?int $userId = null): void
    {
        rescue(fn () => ActivityLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'section' => $section,
            'subject' => $subject !== null ? mb_substr(strip_tags($subject), 0, 190) : null,
            'url' => mb_substr(request()->fullUrl(), 0, 250),
            'ip' => request()->ip(),
        ]), null, false);

        // پاک‌سازی گاه‌به‌گاه رویدادهای قدیمی‌تر از یک سال
        if (random_int(1, 200) === 1) {
            rescue(fn () => ActivityLog::where('created_at', '<', now()->subYear())->delete(), null, false);
        }
    }
}
