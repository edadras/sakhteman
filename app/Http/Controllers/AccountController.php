<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.account.index', [
            'user' => $request->user(),
            'orders' => $request->user()->orders()->withCount('items')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $request->merge(['mobile' => en_num($request->input('mobile'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:190', Rule::unique('users')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:1000'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], ['mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.'], [
            'name' => 'نام', 'mobile' => 'موبایل', 'email' => 'ایمیل', 'address' => 'آدرس',
            'current_password' => 'رمز عبور فعلی', 'password' => 'رمز عبور جدید',
        ]);

        $user->fill(collect($data)->only(['name', 'mobile', 'email', 'address'])->all());
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return back()->with('success', 'اطلاعات حساب به‌روزرسانی شد.');
    }

    public function order(Request $request, string $code)
    {
        $order = $request->user()->orders()->with('items.product')->where('code', $code)->firstOrFail();

        return view('pages.account.order', compact('order'));
    }
}
