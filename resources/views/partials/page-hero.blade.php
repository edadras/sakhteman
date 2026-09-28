{{-- بنر صفحات داخلی: $title, $crumbs (آرایه label => url), $image اختیاری --}}
<section class="page-hero">
    <div class="page-hero__bg" data-parallax=".25" style="background-image:url('{{ $image ?? media_url(setting('page_banner'), asset('assets/img/demo/banner.jpg')) }}')"></div>
    <div class="page-hero__grid"></div>
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
