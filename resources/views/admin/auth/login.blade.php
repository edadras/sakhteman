<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>ورود به پنل مدیریت | {{ setting('site_title') }}</title>
    <script>try { document.documentElement.dataset.theme = localStorage.getItem('admin-theme') || 'light'; } catch (e) {}</script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/vazirmatn/vazirmatn.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/admin.css') }}?v={{ filemtime(public_path('assets/admin/admin.css')) }}">
</head>
<body class="admin">
<div class="login-page">
    <div class="login-form">
        <div class="login-form__box">
            <img src="{{ asset('assets/img/logo-mark.svg') }}" alt="">
            <h1>خوش آمدید 👋</h1>
            <p>برای مدیریت محتوای سایت {{ setting('site_title') }} وارد شوید.</p>

            @if ($errors->any())
                <div class="alert-box">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" data-once>
                @csrf
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label" for="email">ایمیل</label>
                    <div class="input-icon">
                        <i class="ri-mail-line"></i>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus dir="ltr" style="text-align:right">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label" for="password">رمز عبور</label>
                    <div class="input-icon">
                        <i class="ri-lock-2-line"></i>
                        <input class="form-control" type="password" id="password" name="password" required dir="ltr" style="text-align:right">
                    </div>
                </div>
                <label class="switch" style="margin-bottom:22px">
                    <input type="checkbox" name="remember" value="1">
                    <span class="switch__track"></span>
                    <span>مرا به خاطر بسپار</span>
                </label>
                <button type="submit" class="btn btn-primary btn-block"><i class="ri-login-box-line"></i>ورود به پنل</button>
            </form>
            <p style="margin-top:24px;text-align:center"><a href="{{ route('home') }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به سایت</a></p>
        </div>
    </div>
    <div class="login-visual">
        <div>
            <h2>{{ setting('site_title') }}</h2>
            <p>مدیریت آسان اسلایدر، خدمات، پروژه‌ها، مقالات، تیم و تمام بخش‌های سایت از یک پنل فارسی و ساده.</p>
        </div>
    </div>
</div>
<script src="{{ asset('assets/admin/admin.js') }}"></script>
</body>
</html>
