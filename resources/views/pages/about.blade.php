@extends('layouts.app')

@section('title', 'درباره ما')

@section('content')
@include('partials.page-hero', ['title' => 'درباره ما', 'eyebrow' => setting('site_title'), 'crumbs' => ['درباره ما' => null]])

<section class="section">
    <div class="container about-grid">
        <div class="about-text">
            <span class="eyebrow">داستان ما</span>
            <h2 class="section-title" data-split>{{ setting('about_title') }}</h2>
            <div class="prose" data-reveal>{!! setting('about_page_text', '<p>'.e(setting('about_text')).'</p>') !!}</div>
            <div class="about-sign" data-reveal style="margin-top:30px">
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
        <div class="about-media">
            <span class="about-media__line" aria-hidden="true"></span>
            <div class="img-reveal about-media__main" data-img-reveal="up">
                <img src="{{ media_url(setting('about_image'), asset('assets/img/demo/about-1.jpg')) }}" alt="" loading="lazy">
            </div>
            <div class="img-reveal about-media__second" data-img-reveal="left" data-parallax=".15">
                <img src="{{ media_url(setting('about_image_2'), asset('assets/img/demo/about-2.jpg')) }}" alt="" loading="lazy">
            </div>
            <div class="about-media__badge" data-reveal="scale">
                <svg viewBox="0 0 160 160" aria-hidden="true">
                    <defs><path id="circlePath2" d="M80,80 m-62,0 a62,62 0 1,1 124,0 a62,62 0 1,1 -124,0"/></defs>
                    <text><textPath href="#circlePath2">• از سال {{ fa_num(setting('established_year', '۱۳۸۴')) }} • همراه شما در ساخت • </textPath></text>
                </svg>
                <div class="about-media__badge-core"><div><strong>{{ fa_num(setting('experience_years', 20)) }}</strong><span>سال تجربه</span></div></div>
            </div>
        </div>
    </div>
</section>

<section class="section section--dark2">
    <div class="container">
        <div class="mv-grid" data-stagger=".15">
            <div class="mv-card"><i class="ri-focus-3-line"></i><h3>مأموریت ما</h3><p>{{ setting('mission') }}</p></div>
            <div class="mv-card"><i class="ri-eye-line"></i><h3>چشم‌انداز</h3><p>{{ setting('vision') }}</p></div>
            <div class="mv-card"><i class="ri-medal-line"></i><h3>ارزش‌های ما</h3><p>{{ setting('values') }}</p></div>
        </div>
    </div>
</section>

@include('partials.stats')
@include('partials.process', ['class' => 'section--dark2'])
@include('partials.team')
@include('partials.testimonials')
@include('partials.faq')
@include('partials.partners')
@include('partials.cta')
@endsection
