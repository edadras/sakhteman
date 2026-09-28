<section class="auth-wrap">
    <div class="auth-form">
        <div class="auth-form__box" data-reveal>
            <div class="auth-tabs">
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'is-active' : '' }}">ورود</a>
                <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'is-active' : '' }}">عضویت</a>
            </div>
            {{ $slot }}
        </div>
    </div>
    <div class="auth-side grid-bg" data-grid-spot>
        <span class="hero-diamond" style="bottom:auto;top:240px;width:320px;margin-left:-160px"></span>
        <div class="hero-arch"><img src="{{ asset('assets/img/demo/p2.jpg') }}" alt=""></div>
        <h2>به خانواده {{ \Illuminate\Support\Str::of(setting('site_title', 'طاهورنیان'))->explode(' ')->last() }} بپیوندید</h2>
        <p>با ساخت حساب کاربری، سفارش‌های فروشگاه را پیگیری کنید و از پیشنهادهای ویژه پروژه‌های جدید باخبر شوید.</p>
    </div>
</section>
