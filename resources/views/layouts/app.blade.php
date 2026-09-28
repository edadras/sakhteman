@php
    $siteTitle = setting('site_title', config('app.name'));
    $pageTitle = trim($__env->yieldContent('title'));
    $metaDescription = trim($__env->yieldContent('description')) ?: setting('meta_description');
    $ogImage = trim($__env->yieldContent('og_image')) ?: media_url(setting('about_image'));
@endphp
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="is-loading">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ? $pageTitle.' | '.$siteTitle : $siteTitle.' | '.setting('site_tagline') }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ setting('meta_keywords') }}">
    <meta name="theme-color" content="#0c0d0f">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="fa_IR">
    <meta property="og:site_name" content="{{ $siteTitle }}">
    <meta property="og:title" content="{{ $pageTitle ?: $siteTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">

    @if (setting('favicon'))
        <link rel="icon" href="{{ media_url(setting('favicon')) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/fonts/vazirmatn/vazirmatn.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/remixicon/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ filemtime(public_path('assets/css/app.css')) }}">
    @stack('head')

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'HomeAndConstructionBusiness',
            'name' => $siteTitle,
            'url' => url('/'),
            'telephone' => setting('phone'),
            'email' => setting('email'),
            'address' => setting('address'),
            'image' => $ogImage,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>
<body class="@yield('body_class')">

    {{-- پیش‌بارگذار --}}
    <div class="preloader" aria-hidden="true">
        <div class="preloader__inner">
            <svg class="preloader__logo" viewBox="0 0 64 64" fill="none">
                <path d="M8 56V26L32 8l24 18v30H40V36H24v20z" stroke="#c9a15b" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M32 8v48" stroke="#e8c887" stroke-width="1.5"/>
            </svg>
            <div class="preloader__count">۰</div>
            <div class="preloader__title">{{ $siteTitle }}</div>
            <div class="preloader__bar"><span></span></div>
        </div>
        <div class="preloader__panel"></div>
    </div>

    <div class="page-transition" aria-hidden="true"><span></span><span></span><span></span></div>
    <div class="cursor" aria-hidden="true"><span class="cursor__text"></span></div>
    <div class="cursor-dot" aria-hidden="true"></div>
    @stack('before_header')

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- دکمه‌های شناور --}}
    <button class="to-top" type="button" aria-label="بازگشت به بالا">
        <svg viewBox="0 0 56 56"><circle cx="28" cy="28" r="26"/></svg>
        <i class="ri-arrow-up-line"></i>
    </button>
    @if (setting('whatsapp'))
        @php $wa = setting('whatsapp'); @endphp
        <a class="whatsapp-float" href="{{ str_starts_with($wa, 'http') ? $wa : 'https://wa.me/'.preg_replace('/\D/', '', en_num($wa)) }}" target="_blank" rel="noopener" aria-label="واتس‌اپ">
            <i class="ri-whatsapp-line"></i>
        </a>
    @endif

    {{-- مودال ویدیو --}}
    <div class="modal" id="videoModal" role="dialog" aria-modal="true" aria-label="ویدیو">
        <button class="modal__close" type="button" aria-label="بستن"><i class="ri-close-line"></i></button>
        <div class="modal__box"></div>
    </div>

    {{-- لایت‌باکس --}}
    <div class="modal" id="lightbox" role="dialog" aria-modal="true" aria-label="گالری تصاویر">
        <button class="modal__close" type="button" aria-label="بستن"><i class="ri-close-line"></i></button>
        <button class="lightbox__nav lightbox__prev" type="button" aria-label="قبلی"><i class="ri-arrow-right-s-line"></i></button>
        <img class="lightbox__img" src="data:image/gif;base64,R0lGODlhAQABAAAAACw=" alt="">
        <button class="lightbox__nav lightbox__next" type="button" aria-label="بعدی"><i class="ri-arrow-left-s-line"></i></button>
    </div>

    <script src="{{ asset('assets/vendor/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/gsap/ScrollTrigger.min.js') }}"></script>
    @stack('vendor_scripts')
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/lenis/lenis.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
    @stack('scripts')
</body>
</html>
