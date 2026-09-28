@php
    $currentResource = request()->route('resource');
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'داشبورد') | پنل مدیریت {{ setting('site_title') }}</title>
    <script>try { document.documentElement.dataset.theme = localStorage.getItem('admin-theme') || 'light'; } catch (e) {}</script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/vazirmatn/vazirmatn.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/remixicon.css') }}">
    @stack('head')
    <link rel="stylesheet" href="{{ asset('assets/admin/admin.css') }}?v={{ filemtime(public_path('assets/admin/admin.css')) }}">
</head>
<body class="admin">
<div class="a-layout">
    <aside class="a-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="a-brand">
            <img src="{{ asset('assets/img/logo-mark.svg') }}" alt="">
            <div><strong>{{ setting('site_title', 'طهورنیان') }}</strong><small>پنل مدیریت محتوا</small></div>
        </a>
        <nav class="a-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="ri-dashboard-3-line"></i>داشبورد</a>
            <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="ri-mail-line"></i>پیام‌ها و درخواست‌ها
                @if ($unreadCount)<span class="badge badge-danger">{{ fa_num($unreadCount) }}</span>@endif
            </a>

            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="ri-shopping-cart-2-line"></i>سفارش‌ها
                @if ($pendingOrders)<span class="badge badge-danger">{{ fa_num($pendingOrders) }}</span>@endif
            </a>
            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"><i class="ri-group-line"></i>مشتریان</a>

            @foreach (\App\Admin\Resources::grouped() as $group => $items)
                <div class="a-nav__group">{{ $group }}</div>
                @foreach ($items as $key => $def)
                    <a href="{{ route('admin.resources.index', $key) }}" class="{{ $currentResource === $key ? 'active' : '' }}"><i class="{{ $def['icon'] }}"></i>{{ $def['label'] }}</a>
                @endforeach
            @endforeach

            <div class="a-nav__group">تنظیمات</div>
            <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="ri-settings-4-line"></i>تنظیمات سایت</a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"><i class="ri-user-settings-line"></i>حساب کاربری</a>
        </nav>
        <div class="a-sidebar__foot">
            <a href="{{ route('home') }}" target="_blank"><i class="ri-external-link-line"></i>مشاهده سایت</a>
        </div>
    </aside>
    <div class="overlay"></div>

    <div class="a-main">
        <header class="a-topbar">
            <button class="icon-btn a-burger" id="aBurger" type="button" aria-label="منو"><i class="ri-menu-3-line"></i></button>
            <div class="a-topbar__title">@yield('title', 'داشبورد')</div>
            <div class="a-topbar__actions">
                <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" class="icon-btn" title="پیام‌های جدید">
                    <i class="ri-notification-3-line"></i>
                    @if ($unreadCount)<span class="dot"></span>@endif
                </a>
                <button class="icon-btn" id="themeToggle" type="button" title="حالت تیره / روشن"><i class="ri-moon-line"></i></button>
                <div class="a-user">
                    <button class="a-user__btn" type="button">
                        <span class="a-user__avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="a-user__menu">
                        <a href="{{ route('admin.profile.edit') }}"><i class="ri-user-line"></i>حساب کاربری</a>
                        <a href="{{ route('admin.settings.edit') }}"><i class="ri-settings-3-line"></i>تنظیمات</a>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" style="color:var(--a-danger)"><i class="ri-logout-box-r-line"></i>خروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="a-content">
            @if ($errors->any())
                <div class="alert-box">
                    <strong><i class="ri-error-warning-line"></i> لطفا خطاهای زیر را بررسی کنید:</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="a-footer">پنل مدیریت {{ setting('site_title') }} — {{ jdate(now(), 'l j F Y') }}</footer>
    </div>
</div>

<div class="toast-stack">
    @if (session('success'))
        <div class="toast"><i class="ri-checkbox-circle-fill"></i><span>{{ session('success') }}</span></div>
    @endif
    @if (session('error'))
        <div class="toast error"><i class="ri-error-warning-fill"></i><span>{{ session('error') }}</span></div>
    @endif
</div>

<script src="{{ asset('assets/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
@stack('scripts')
<script src="{{ asset('assets/admin/admin.js') }}?v={{ filemtime(public_path('assets/admin/admin.js')) }}"></script>
</body>
</html>
