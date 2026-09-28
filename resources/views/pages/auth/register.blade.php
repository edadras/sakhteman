@extends('layouts.app')

@section('title', 'عضویت')
@section('body_class', 'header-solid')

@section('content')
@component('pages.auth._layout')
    <h1>ساخت حساب کاربری</h1>
    <p class="muted">تنها در چند ثانیه عضو شوید.</p>
    @if ($errors->any())
        <div class="alert alert--error"><i class="ri-error-warning-line"></i>{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('register') }}" data-once>
        @csrf
        <div class="form-grid">
            <div class="field full">
                <input type="text" id="r-name" name="name" value="{{ old('name') }}" placeholder=" " required>
                <label for="r-name">نام و نام خانوادگی</label>
            </div>
            <div class="field">
                <input type="tel" id="r-mobile" name="mobile" value="{{ old('mobile') }}" placeholder=" " required dir="ltr" style="text-align:right">
                <label for="r-mobile">شماره موبایل</label>
            </div>
            <div class="field">
                <input type="email" id="r-email" name="email" value="{{ old('email') }}" placeholder=" " dir="ltr" style="text-align:right">
                <label for="r-email">ایمیل (اختیاری)</label>
            </div>
            <div class="field">
                <input type="password" id="r-pass" name="password" placeholder=" " required dir="ltr" style="text-align:right">
                <label for="r-pass">رمز عبور (حداقل ۸ کاراکتر)</label>
            </div>
            <div class="field">
                <input type="password" id="r-pass2" name="password_confirmation" placeholder=" " required dir="ltr" style="text-align:right">
                <label for="r-pass2">تکرار رمز عبور</label>
            </div>
            <button type="submit" class="btn btn--block full">ساخت حساب <i class="ri-user-add-line"></i></button>
        </div>
    </form>
    <p class="muted" style="margin-top:20px;text-align:center">قبلا ثبت‌نام کرده‌اید؟ <a href="{{ route('login') }}" class="text-green">وارد شوید</a></p>
@endcomponent
@endsection
