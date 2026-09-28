<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->merge(['username' => en_num(trim((string) $request->input('username')))]);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:190'],
            'password' => ['required', 'string'],
        ], [], ['username' => 'موبایل یا ایمیل', 'password' => 'رمز عبور']);

        $field = filter_var($data['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$field => $data['username'], 'password' => $data['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['username' => 'اطلاعات ورود صحیح نیست.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('account'))->with('success', 'خوش آمدید، '.Auth::user()->name);
    }

    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->merge(['mobile' => en_num($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'regex:/^09\d{9}$/', 'unique:users,mobile'],
            'email' => ['nullable', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'mobile.unique' => 'با این شماره قبلا ثبت‌نام شده است.',
        ], ['name' => 'نام', 'mobile' => 'موبایل', 'email' => 'ایمیل', 'password' => 'رمز عبور']);

        $user = User::create($data);
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('account'))->with('success', 'حساب کاربری شما با موفقیت ساخته شد.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
