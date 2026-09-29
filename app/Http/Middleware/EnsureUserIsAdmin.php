<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * فقط کاربران مدیرِ فعال اجازه ورود به پنل مدیریت را دارند.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->is_admin) {
            return redirect()->route('admin.login')->withErrors(['email' => 'برای ورود به پنل باید با حساب مدیر وارد شوید.']);
        }

        // حسابی که غیرفعال شده، فورا از پنل خارج می‌شود
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors(['email' => 'حساب کاربری شما غیرفعال شده است. با مدیر سایت تماس بگیرید.']);
        }

        return $next($request);
    }
}
