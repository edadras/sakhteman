<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->user()?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], ['email' => 'ایمیل', 'password' => 'رمز عبور']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // تلاش ناموفق روی حساب‌های مدیر در گزارش فعالیت ثبت می‌شود
            $target = User::where('email', $credentials['email'])->where('is_admin', true)->first();
            if ($target) {
                Activity::log('login_failed', 'auth', $credentials['email'], $target->id);
            }
            throw ValidationException::withMessages([
                'email' => 'ایمیل یا رمز عبور اشتباه است.',
            ]);
        }

        $user = Auth::user();

        if (! $user->is_admin) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'این حساب دسترسی به پنل مدیریت ندارد.',
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'حساب کاربری شما غیرفعال شده است. با مدیر سایت تماس بگیرید.',
            ]);
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now(), 'last_login_ip' => $request->ip()])->saveQuietly();
        Activity::log('login', 'auth', $user->name);

        // فقط به آدرس‌های داخل پنل بازگردانده می‌شود (نه صفحات مشتری)
        $intended = (string) $request->session()->pull('url.intended', '');
        $target = str_starts_with(parse_url($intended, PHP_URL_PATH) ?? '', '/admin') ? $intended : route('admin.dashboard');

        return redirect()->to($target)->with('success', 'خوش آمدید، '.Auth::user()->name);
    }

    public function logout(Request $request)
    {
        Activity::log('logout', 'auth', $request->user()?->name);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
