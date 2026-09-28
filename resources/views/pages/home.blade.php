@extends('layouts.app')

@section('content')

{{-- ============ هیرو ============ --}}
<section class="hero">
    <canvas class="hero-canvas" aria-hidden="true"></canvas>
    <div class="swiper">
        <div class="swiper-wrapper">
            @forelse ($slides as $slide)
                <div class="swiper-slide">
                    <div class="hero-slide">
                        <div class="hero-slide__bg" style="background-image:url('{{ media_url($slide->image, asset('assets/img/demo/hero-1.jpg')) }}')"></div>
                        <div class="container">
                            <div class="hero-slide__content">
                                @if ($slide->subtitle)<span class="hero-slide__sub">{{ $slide->subtitle }}</span>@endif
                                <h{{ $loop->first ? '1' : '2' }} class="hero-slide__title">
                                    @foreach (explode('|', $slide->title) as $line)
                                        <span class="line"><span>{{ trim($line) }}</span></span>
                                    @endforeach
                                </h{{ $loop->first ? '1' : '2' }}>
                                @if ($slide->description)<p class="hero-slide__desc">{{ $slide->description }}</p>@endif
                                <div class="hero-slide__actions">
                                    @if ($slide->button_text)
                                        <a href="{{ $slide->button_link ?: route('projects.index') }}" class="btn" data-magnetic=".2"><span>{{ $slide->button_text }}</span><i class="ri-arrow-left-up-line"></i></a>
                                    @endif
                                    <a href="{{ route('contact') }}" class="btn btn--outline"><span>درخواست مشاوره</span><i class="ri-phone-line"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide">
                    <div class="hero-slide">
                        <div class="hero-slide__bg" style="background-image:url('{{ asset('assets/img/demo/hero-1.jpg') }}')"></div>
                        <div class="container">
                            <div class="hero-slide__content">
                                <span class="hero-slide__sub">{{ setting('site_tagline') }}</span>
                                <h1 class="hero-slide__title"><span class="line"><span>{{ setting('site_title', 'طاهورنیان') }}</span></span></h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="hero-ui">
        <div class="container" style="display:flex;justify-content:space-between;align-items:center;gap:20px">
            <div class="hero-counter">
                <span class="hero-counter__current">{{ fa_num('01') }}</span>
                <span class="hero-counter__bar"><span></span></span>
                <span class="hero-counter__total">{{ fa_num(str_pad(max(1, $slides->count()), 2, '0', STR_PAD_LEFT)) }}</span>
            </div>
            @if ($slides->count() > 1)
                <div class="hero-nav">
                    <button class="hero-prev" type="button" aria-label="اسلاید قبلی" data-magnetic=".3"><i class="ri-arrow-right-line"></i></button>
                    <button class="hero-next" type="button" aria-label="اسلاید بعدی" data-magnetic=".3"><i class="ri-arrow-left-line"></i></button>
                </div>
            @endif
        </div>
    </div>

    @include('partials.social', ['class' => 'hero-social'])

    <a href="#about" class="scroll-down" aria-label="اسکرول به پایین">
        <span class="scroll-down__mouse"></span>
        <span>اسکرول کنید</span>
    </a>
</section>

{{-- ============ نوار متحرک ============ --}}
@php $marquee = array_filter(array_map('trim', explode(',', str_replace('،', ',', setting('marquee_text', 'طراحی, ساخت, اجرا'))))); @endphp
<div class="marquee" data-marquee="60">
    <div class="marquee__track">
        <div class="marquee__group">
            @foreach ($marquee as $word)
                <span class="marquee__item">{{ $word }} <i class="ri-asterisk"></i></span>
            @endforeach
        </div>
    </div>
</div>

{{-- ============ درباره ما ============ --}}
<section class="section" id="about">
    <div class="container about-grid">
        <div class="about-media">
            <span class="about-media__line" aria-hidden="true"></span>
            <div class="img-reveal about-media__main" data-img-reveal="up">
                <img src="{{ media_url(setting('about_image'), asset('assets/img/demo/about-1.jpg')) }}" alt="{{ setting('about_title') }}" loading="lazy">
            </div>
            <div class="img-reveal about-media__second" data-img-reveal="right" data-parallax=".15">
                <img src="{{ media_url(setting('about_image_2'), asset('assets/img/demo/about-2.jpg')) }}" alt="" loading="lazy">
            </div>
            <div class="about-media__badge" data-reveal="scale">
                <svg viewBox="0 0 160 160" aria-hidden="true">
                    <defs><path id="circlePath" d="M80,80 m-62,0 a62,62 0 1,1 124,0 a62,62 0 1,1 -124,0"/></defs>
                    <text><textPath href="#circlePath">• کیفیت • تعهد • نوآوری • اعتماد • تجربه </textPath></text>
                </svg>
                <div class="about-media__badge-core">
                    <div><strong>{{ fa_num(setting('experience_years', 20)) }}</strong><span>سال تجربه</span></div>
                </div>
            </div>
        </div>

        <div class="about-text">
            <span class="eyebrow">{{ setting('about_subtitle', 'درباره ما') }}</span>
            <h2 class="section-title" data-split>{{ setting('about_title') }}</h2>
            <p data-highlight>{{ setting('about_text') }}</p>
            @php $features = array_filter(array_map('trim', preg_split('/\r?\n/', (string) setting('about_features')))); @endphp
            @if ($features)
                <ul class="check-list" data-stagger=".08">
                    @foreach ($features as $feature)
                        <li><i class="ri-check-line"></i>{{ $feature }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="about-sign" data-reveal>
                <a href="{{ route('about') }}" class="btn" data-magnetic=".2"><span>بیشتر درباره ما</span><i class="ri-arrow-left-up-line"></i></a>
                @if (setting('ceo_name'))
                    <div class="about-sign__person">
                        <strong>{{ setting('ceo_name') }}</strong>
                        <span>بنیان‌گذار و مدیرعامل</span>
                    </div>
                @endif
                @if (setting('ceo_signature'))
                    <img src="{{ media_url(setting('ceo_signature')) }}" alt="امضا">
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ============ آمار ============ --}}
@include('partials.stats')

{{-- ============ خدمات ============ --}}
@if ($services->count())
<section class="section section--dark2">
    <div class="container">
        <div class="section-head">
            <div class="section-head__text">
                <span class="eyebrow">خدمات ما</span>
                <h2 class="section-title" data-split>راهکارهای <em>جامع</em> برای هر پروژه ساختمانی</h2>
            </div>
            <a href="{{ route('services.index') }}" class="btn btn--outline" data-reveal><span>همه خدمات</span><i class="ri-arrow-left-up-line"></i></a>
        </div>
        <div class="services-grid" data-stagger=".1">
            @foreach ($services as $i => $service)
                @include('partials.service-card', ['service' => $service, 'index' => $i])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ پروژه‌های منتخب (اسکرول افقی) ============ --}}
@if ($projects->count())
<section class="projects-h">
    <div class="container projects-h__head">
        <div class="section-head">
            <div class="section-head__text">
                <span class="eyebrow">نمونه کارها</span>
                <h2 class="section-title" data-split>پروژه‌های <em>شاخص</em> ما</h2>
            </div>
            <p class="muted" style="max-width:420px" data-reveal>هر پروژه، داستانی از خلاقیت، دقت و تعهد است. برای مشاهده بیشتر اسکرول کنید.</p>
        </div>
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

{{-- ============ مراحل کار ============ --}}
@include('partials.process', ['class' => 'section--dark2'])

{{-- ============ ویدیو ============ --}}
<section class="video-band">
    <div class="video-band__bg" data-parallax=".3" style="background-image:url('{{ media_url(setting('video_cover'), asset('assets/img/demo/video.jpg')) }}')"></div>
    <div class="video-band__content">
        @if (setting('video_url'))
            <a href="#" class="play-btn" data-video="{{ setting('video_url') }}" data-magnetic=".5" aria-label="پخش ویدیو"><i class="ri-play-fill"></i></a>
        @else
            <a href="{{ route('projects.index') }}" class="play-btn" data-magnetic=".5" aria-label="مشاهده پروژه‌ها"><i class="ri-building-2-line"></i></a>
        @endif
        <h2 data-split>{{ setting('video_title', 'ساختن با عشق و دقت') }}</h2>
    </div>
</section>

<div style="overflow:hidden;padding:20px 0">
    <div class="marquee marquee--tilt" data-marquee="45" data-marquee-reverse>
        <div class="marquee__track">
            <div class="marquee__group">
                @foreach ($marquee as $word)
                    <span class="marquee__item">{{ $word }} <i class="ri-star-s-fill"></i></span>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ============ نظرات ============ --}}
@include('partials.testimonials')

{{-- ============ تیم ============ --}}
@include('partials.team')

{{-- ============ سوالات ============ --}}
@include('partials.faq', ['class' => 'section--dark2'])

{{-- ============ مقالات ============ --}}
@if ($posts->count())
<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="section-head__text">
                <span class="eyebrow">مجله ساختمان</span>
                <h2 class="section-title" data-split>آخرین <em>مقالات</em> و اخبار</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn--outline" data-reveal><span>همه مقالات</span><i class="ri-arrow-left-up-line"></i></a>
        </div>
        <div class="posts-grid" data-stagger=".12">
            @foreach ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.partners')
@include('partials.cta')

@endsection
