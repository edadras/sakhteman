@extends('layouts.app')

@section('content')

{{-- ============ هیرو: پروژه‌های ویژه ============ --}}
<section class="hero grid-bg" data-grid-spot>
    <div class="container">
        <div class="swiper">
            <div class="swiper-wrapper">
                @forelse ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="hero-slide">
                            <div class="hero-slide__content">
                                @if ($slide->tags)
                                    <div class="hero-tags">
                                        @foreach (array_filter(array_map('trim', preg_split('/[,،]/u', $slide->tags))) as $tag)
                                            <span>{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <h{{ $loop->first ? '1' : '2' }} class="hero-slide__title">
                                    @foreach (explode('|', $slide->title) as $line)
                                        <span class="line"><span>{{ trim($line) }}</span></span>
                                    @endforeach
                                </h{{ $loop->first ? '1' : '2' }}>
                                @if ($slide->designer_name)
                                    <div class="hero-designer">
                                        @if ($slide->designer_avatar)<img src="{{ media_url($slide->designer_avatar) }}" alt="{{ $slide->designer_name }}">@endif
                                        <strong>{{ $slide->designer_name }}</strong>
                                        @if ($slide->designer_role)<span>{{ $slide->designer_role }}</span>@endif
                                    </div>
                                @endif
                                @if ($slide->description)<p class="hero-slide__desc">{{ fa_num($slide->description) }}</p>@endif
                                <div class="hero-slide__actions">
                                    @if ($slide->button_text)
                                        <a href="{{ $slide->button_link ?: route('projects.index') }}" class="btn" data-magnetic=".2">{{ $slide->button_text }}</a>
                                    @endif
                                    <a href="{{ route('contact') }}" class="btn btn--ghost">مشاوره رایگان <i class="ri-phone-line"></i></a>
                                </div>
                            </div>
                            <div class="hero-slide__media">
                                <span class="hero-ring hero-ring--1"></span>
                                <span class="hero-ring hero-ring--2"></span>
                                <span class="hero-diamond"></span>
                                <div class="hero-arch"><img src="{{ media_url($slide->image, asset('assets/img/demo/hero-2.jpg')) }}" alt="{{ str_replace('|', ' ', $slide->title) }}"></div>
                                <span class="hero-leaf hero-leaf--r"></span>
                                <span class="hero-leaf hero-leaf--l"></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="hero-slide">
                            <div class="hero-slide__content">
                                <h1 class="hero-slide__title"><span class="line"><span>{{ setting('site_title', 'طهورنیان') }}</span></span></h1>
                                <p class="hero-slide__desc">{{ setting('site_tagline') }}</p>
                            </div>
                            <div class="hero-slide__media"><span class="hero-diamond"></span><div class="hero-arch"><img src="{{ asset('assets/img/demo/hero-2.jpg') }}" alt=""></div></div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="hero-ui">
        <div class="container">
            <div class="hero-controls">
                <div class="hero-dots">
                    @foreach ($slides as $i => $slide)
                        <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-slide="{{ $i }}" aria-label="اسلاید {{ fa_num($i + 1) }}"></button>
                    @endforeach
                </div>
                @if ($slides->count() > 1)
                    <div class="hero-nav">
                        <button class="hero-prev" type="button" aria-label="قبلی"><i class="ri-arrow-right-line"></i></button>
                        <button class="hero-next" type="button" aria-label="بعدی"><i class="ri-arrow-left-line"></i></button>
                    </div>
                @endif
            </div>
            <a href="#services" class="scroll-down"><span class="scroll-down__mouse"></span> اسکرول کنید</a>
        </div>
    </div>
</section>

@include('partials.stats')

{{-- ============ خدمات ============ --}}
@if ($services->count())
<section class="section" id="services">
    <div class="container">
        @include('partials.hang-head', ['title' => 'خدمات', 'en' => 'Services', 'icon' => 'ri-building-3-line'])
        <div class="services-grid" data-stagger=".12">
            @foreach ($services as $service)
                @include('partials.service-card', ['service' => $service])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ پروژه‌ها ============ --}}
@if ($projects->count())
<section class="projects-h">
    <div class="container">
        @include('partials.sec-head', ['title' => 'پروژه ها', 'en' => 'Projects', 'icon' => 'ri-hammer-line', 'button' => ['همه پروژه ها', route('projects.index')]])
    </div>
    <div class="projects-h__track">
        @foreach ($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @endforeach
        <div class="projects-h__end">
            <a href="{{ route('projects.index') }}" class="circle-btn" data-magnetic=".4"><span><i class="ri-arrow-left-up-line"></i>همه پروژه‌ها</span></a>
        </div>
    </div>
    <div class="projects-h__progress"><span></span></div>
</section>
@endif

{{-- ============ تایم‌لاین ============ --}}
@include('partials.timeline')

{{-- ============ محصولات ============ --}}
@if ($products->count())
<section class="section">
    <div class="container">
        @include('partials.sec-head', ['title' => 'محصولات', 'en' => 'Products', 'icon' => 'ri-shopping-bag-3-fill', 'button' => ['همه محصولات', route('shop.index')]])
        <div class="products-grid" data-stagger=".1">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ کاتالوگ و مشاوره ============ --}}
@include('partials.promo')

{{-- ============ همکاران ============ --}}
@include('partials.partners')

{{-- ============ تیم ============ --}}
@include('partials.team')

{{-- ============ نظرات ============ --}}
@include('partials.testimonials')

{{-- ============ مقالات ============ --}}
@if ($posts->count())
<section class="section" style="padding-top:0">
    <div class="container">
        @include('partials.sec-head', ['title' => 'مجله ساختمان', 'en' => 'Blog', 'icon' => 'ri-article-line', 'button' => ['همه مقالات', route('blog.index')]])
        <div class="posts-grid" data-stagger=".12">
            @foreach ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.faq', ['class' => ''])
@include('partials.cta')

@endsection
