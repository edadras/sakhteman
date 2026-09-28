@extends('layouts.app')

@section('title', 'درباره ما')

@section('content')
@include('partials.page-hero', ['title' => 'درباره ما', 'eyebrow' => setting('site_title'), 'crumbs' => ['درباره ما' => null]])

<section class="section">
    <div class="container about-grid">
        <div>
            @include('partials.sec-head', ['title' => 'داستان ما', 'en' => 'About us', 'icon' => 'ri-building-4-line'])
            <h3 data-reveal style="font-size:clamp(22px,2.4vw,30px);line-height:1.7">{{ setting('about_title') }}</h3>
            <div class="prose" data-reveal>{!! setting('about_page_text', '<p>'.e(setting('about_text')).'</p>') !!}</div>
            @php $features = array_filter(array_map('trim', preg_split('/\r?\n/', (string) setting('about_features')))); @endphp
            @if ($features)
                <div class="feature-grid" data-stagger=".08">
                    @foreach ($features as $feature)
                        <div class="feature-item"><i class="ri-checkbox-circle-fill"></i>{{ $feature }}</div>
                    @endforeach
                </div>
            @endif
            @if (setting('ceo_name'))
                <p data-reveal><strong style="color:var(--ink)">{{ setting('ceo_name') }}</strong> <span class="muted">— بنیان‌گذار و مدیرعامل</span></p>
            @endif
        </div>
        <div class="about-img" data-reveal="scale">
            <div class="arch"><img src="{{ media_url(setting('about_image'), asset('assets/img/demo/about-1.jpg')) }}" alt="" data-parallax=".12"></div>
            <div class="about-img__badge"><strong>{{ fa_num(setting('experience_years', 20)) }}+</strong>سال تجربه</div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="container">
        <div class="mv-grid" data-stagger=".15">
            <div class="mv-card"><i class="ri-focus-3-line"></i><h3>مأموریت ما</h3><p>{{ setting('mission') }}</p></div>
            <div class="mv-card"><i class="ri-eye-line"></i><h3>چشم‌انداز</h3><p>{{ setting('vision') }}</p></div>
            <div class="mv-card"><i class="ri-medal-line"></i><h3>ارزش‌های ما</h3><p>{{ setting('values') }}</p></div>
        </div>
    </div>
</section>

@include('partials.stats', ['class' => 'section--tight'])
@include('partials.timeline')
@include('partials.team')
@include('partials.testimonials')
@include('partials.faq')
@include('partials.partners')
@include('partials.cta')
@endsection
