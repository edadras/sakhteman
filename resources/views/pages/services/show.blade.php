@extends('layouts.app')

@section('title', $service->title)
@section('description', $service->summary)
@section('og_image', media_url($service->image))

@section('content')
@include('partials.page-hero', [
    'title' => $service->title,
    'eyebrow' => 'خدمات',
    'image' => $service->image ? media_url($service->image) : null,
    'crumbs' => ['خدمات' => route('services.index'), $service->title => null],
])

<section class="section">
    <div class="container content-grid">
        <article>
            @if ($service->image)
                <div class="service-hero-img img-reveal" data-img-reveal="up"><img src="{{ media_url($service->image) }}" alt="{{ $service->title }}"></div>
            @endif
            <span class="badge badge--amber" style="margin-bottom:14px"><i class="{{ $service->icon }}"></i> {{ $service->title }}</span>
            <h2 style="font-size:clamp(24px,2.8vw,36px);line-height:1.6" data-split>{{ $service->summary }}</h2>

            @if ($service->feature_list)
                <div class="feature-grid" data-stagger=".08">
                    @foreach ($service->feature_list as $feature)
                        <div class="feature-item"><i class="ri-checkbox-circle-fill"></i>{{ $feature }}</div>
                    @endforeach
                </div>
            @endif

            <div class="prose" data-reveal>{!! $service->body !!}</div>
        </article>

        <aside class="sidebar">
            <div class="widget" data-reveal>
                <h3 class="widget__title">همه خدمات</h3>
                <div class="widget-links">
                    @foreach ($others as $other)
                        <a href="{{ $other->url }}" class="{{ $other->id === $service->id ? 'is-active' : '' }}">
                            <span>{{ $other->title }}</span><i class="ri-arrow-left-line"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="widget widget--cta grid-bg" data-reveal data-delay=".1">
                <i class="ri-customer-service-2-line big"></i>
                <h3>نیاز به مشاوره دارید؟</h3>
                <p>کارشناسان ما آماده پاسخگویی به سوالات شما هستند.</p>
                @if (setting('phone'))
                    <a class="phone" href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}">{{ fa_num(setting('phone')) }}</a>
                @endif
                <a href="{{ route('contact') }}" class="btn"><span>ارسال پیام</span><i class="ri-arrow-left-up-line"></i></a>
            </div>
        </aside>
    </div>
</section>

@if ($projects->count())
<section class="section" style="padding-top:0">
    <div class="container">
        @include('partials.sec-head', ['title' => 'پروژه‌های مرتبط', 'en' => 'Projects', 'icon' => 'ri-building-line', 'button' => ['همه پروژه ها', route('projects.index')]])
        <div class="projects-grid" data-stagger=".12">
            @foreach ($projects as $project)
                @include('partials.project-card', ['project' => $project])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')
@endsection
