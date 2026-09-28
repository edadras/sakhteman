{{-- بنر صفحات داخلی: $title, $crumbs (label => url), $image و $eyebrow اختیاری --}}
<section class="page-hero grid-bg" data-grid-spot>
    <div class="page-hero__img"><img src="{{ $image ?? media_url(setting('page_banner'), asset('assets/img/demo/banner.jpg')) }}" alt="" data-parallax=".2"></div>
    <div class="container">
        @isset($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endisset
        <h1>{{ $title }}</h1>
        <nav class="breadcrumb" aria-label="مسیر">
            <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> خانه</a>
            @foreach ($crumbs ?? [] as $label => $url)
                <i class="ri-arrow-left-s-line"></i>
                @if ($url)<a href="{{ $url }}">{{ $label }}</a>@else<span>{{ $label }}</span>@endif
            @endforeach
        </nav>
    </div>
</section>
