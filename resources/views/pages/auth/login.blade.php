@extends('layouts.app')

@section('title', 'ورود به حساب کاربری')
@section('body_class', 'header-solid')

@section('content')
@component('pages.auth._layout')
    <h1>ورود به حساب</h1>
    <p class="muted">با شماره موبایل یا ایمیل خود وارد شوید.</p>
    @if ($errors->any())<div class="alert alert--error"><i class="ri-error-warning-line"></i>{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('login') }}" data-once>
        @csrf
        <div class="form-grid">
            <div class="field full">
                <input type="text" id="l-user" name="username" value="{{ old('username') }}" placeholder=" " required autofocus dir="ltr" style="text-align:right">
                <label for="l-user">شماره موبایل یا ایمیل</label>
            </div>
            <div class="field full">
                <input type="password" id="l-pass" name="password" placeholder=" " required dir="ltr" style="text-align:right">
                <label for="l-pass">رمز عبور</label>
            </div>
            <label class="check full"><input type="checkbox" name="remember" value="1"> مرا به خاطر بسپار</label>
            <button type="submit" class="btn btn--block full">ورود <i class="ri-login-box-line"></i></button>
        </div>
    </form>
    <p class="muted" style="margin-top:20px;text-align:center">حساب ندارید؟ <a href="{{ route('register') }}" class="text-green">ثبت‌نام کنید</a></p>
@endcomponent
@endsection
