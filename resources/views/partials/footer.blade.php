<footer class="site-footer grid-bg" data-grid-spot>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about" data-reveal>
                <a href="{{ route('home') }}" class="brand">
                    @if (setting('logo'))
                        <img src="{{ media_url(setting('logo')) }}" alt="{{ setting('site_title') }}">
                    @else
                        <img class="brand__mark" src="{{ asset('assets/img/logo-mark.svg') }}" alt="">
                        <span class="brand__text">
                            <span class="brand__name">{{ setting('site_title', 'طاهورنیان') }}</span>
                            <span class="brand__tag">{{ setting('site_tagline') }}</span>
                        </span>
                    @endif
                </a>
                <p>{{ setting('footer_text') }}</p>
                @include('partials.social')
            </div>

            <div data-reveal data-delay=".1">
                <h3 class="footer-title">دسترسی سریع</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">صفحه اصلی</a></li>
                    <li><a href="{{ route('about') }}">درباره ما</a></li>
                    <li><a href="{{ route('projects.index') }}">پروژه‌ها</a></li>
                    <li><a href="{{ route('blog.index') }}">مقالات</a></li>
                    <li><a href="{{ route('contact') }}">تماس با ما</a></li>
                </ul>
            </div>

            <div data-reveal data-delay=".2">
                <h3 class="footer-title">خدمات ما</h3>
                <ul class="footer-links">
                    @foreach (($menuServices ?? collect())->take(6) as $service)
                        <li><a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div data-reveal data-delay=".3">
                <h3 class="footer-title">اطلاعات تماس</h3>
                <ul class="footer-contact">
                    @if (setting('address'))
                        <li><i class="ri-map-pin-2-line"></i><div><small>آدرس</small>{{ setting('address') }}</div></li>
                    @endif
                    @if (setting('phone') || setting('mobile'))
                        <li><i class="ri-phone-line"></i><div><small>تلفن</small>
                            @if (setting('phone'))<a class="ltr" href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}">{{ fa_num(setting('phone')) }}</a>@endif
                            @if (setting('mobile'))<br><a class="ltr" href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('mobile'))) }}">{{ fa_num(setting('mobile')) }}</a>@endif
                        </div></li>
                    @endif
                    @if (setting('email'))
                        <li><i class="ri-mail-line"></i><div><small>ایمیل</small><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></div></li>
                    @endif
                    @if (setting('working_hours'))
                        <li><i class="ri-time-line"></i><div><small>ساعات کاری</small>{{ fa_num(setting('working_hours')) }}</div></li>
                    @endif
                </ul>
                @if (setting('enamad'))
                    <div class="enamad">{!! setting('enamad') !!}</div>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ jdate(now(), 'Y') }} — {{ setting('copyright', 'تمامی حقوق محفوظ است.') }}</span>
            <span>طراحی و توسعه با <i class="ri-heart-3-fill text-gold"></i> برای {{ setting('site_title') }}</span>
        </div>
    </div>
    <div class="footer-big" aria-hidden="true">{{ \Illuminate\Support\Str::of(setting('site_title', 'طاهورنیان'))->explode(' ')->last() }}</div>
</footer>
