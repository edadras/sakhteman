{{-- نوار ناوبری پایین (فقط موبایل و تبلت) --}}
@php
    $shopOn = setting('shop_enabled', '1') === '1';
    $cartCount = $shopOn ? \App\Support\Cart::count() : 0;
@endphp
<nav class="bottom-nav" aria-label="ناوبری پایین">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">
        <i class="ri-home-5-line"></i><i class="ri-home-5-fill"></i><span>خانه</span>
    </a>
    <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'is-active' : '' }}">
        <i class="ri-building-2-line"></i><i class="ri-building-2-fill"></i><span>پروژه‌ها</span>
    </a>
    @if ($shopOn)
        <a href="{{ route('shop.index') }}" class="bottom-nav__main {{ request()->routeIs('shop.*') ? 'is-active' : '' }}" aria-label="فروشگاه">
            <span class="bottom-nav__fab"><i class="ri-shopping-bag-3-fill"></i></span><span>فروشگاه</span>
        </a>
        <a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'is-active' : '' }}">
            <span class="bottom-nav__icon">
                <i class="ri-shopping-cart-2-line"></i><i class="ri-shopping-cart-2-fill"></i>
                <span class="cart-count" data-count="{{ $cartCount }}">{{ fa_num($cartCount) }}</span>
            </span>
            <span>سبد خرید</span>
        </a>
    @else
        <a href="{{ route('contact') }}" class="bottom-nav__main {{ request()->routeIs('contact') ? 'is-active' : '' }}" aria-label="مشاوره">
            <span class="bottom-nav__fab"><i class="ri-customer-service-2-fill"></i></span><span>مشاوره</span>
        </a>
        <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'is-active' : '' }}">
            <i class="ri-tools-line"></i><i class="ri-tools-fill"></i><span>خدمات</span>
        </a>
    @endif
    <button type="button" class="js-menu-toggle" aria-controls="menuOverlay" aria-expanded="false">
        <span class="bottom-nav__burger"><span></span><span></span><span></span></span><span>منو</span>
    </button>
</nav>

{{-- پیشنهاد نصب اپلیکیشن --}}
<div class="install-banner" id="installBanner" hidden>
    <img src="{{ asset('assets/pwa/icon-192.png') }}" alt="">
    <div class="install-banner__text">
        <strong>نصب اپلیکیشن {{ \Illuminate\Support\Str::of(setting('site_title', 'طهورنیان'))->explode(' ')->last() }}</strong>
        <span data-install-hint>دسترسی سریع‌تر، حتی بدون اینترنت</span>
    </div>
    <button type="button" class="btn btn--sm" id="installBtn">نصب</button>
    <button type="button" class="install-banner__close" id="installClose" aria-label="بستن"><i class="ri-close-line"></i></button>
</div>
