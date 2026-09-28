@extends('layouts.app')

@section('title', $project->title)
@section('description', $project->summary)
@section('og_image', media_url($project->cover))

@section('content')
<section class="project-hero">
    <div class="project-hero__bg"><img src="{{ media_url($project->cover) }}" alt="{{ $project->title }}"></div>
    <div class="container">
        <span class="eyebrow">{{ $project->category?->name ?? 'پروژه' }} — {{ $project->status_label }}</span>
        <h1>{{ $project->title }}</h1>
        <nav class="breadcrumb" aria-label="مسیر">
            <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> خانه</a>
            <i class="ri-arrow-left-s-line"></i>
            <a href="{{ route('projects.index') }}">پروژه‌ها</a>
            <i class="ri-arrow-left-s-line"></i>
            <span>{{ $project->title }}</span>
        </nav>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        @php
            $info = array_filter([
                ['ri-user-3-line', 'کارفرما', $project->client],
                ['ri-map-pin-line', 'موقعیت', $project->location],
                ['ri-ruler-2-line', 'متراژ', $project->area],
                ['ri-building-line', 'طبقات', $project->floors],
                ['ri-calendar-line', 'سال اجرا', $project->year],
                ['ri-progress-5-line', 'وضعیت', $project->status_label],
            ], fn ($row) => filled($row[2]));
        @endphp
        <div class="project-info" data-stagger=".08" style="grid-template-columns:repeat({{ max(1, min(6, count($info))) }},1fr)">
            @foreach ($info as [$icon, $label, $value])
                <div><small><i class="{{ $icon }}"></i>{{ $label }}</small><strong>{{ fa_num($value) }}</strong></div>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:40px">
    <div class="container content-grid">
        <article>
                        @if ($project->summary)
                <h2 style="font-size:clamp(22px,2.4vw,32px);line-height:1.7" data-split>{{ $project->summary }}</h2>
            @endif
            <div class="prose" data-reveal>{!! $project->body !!}</div>
        </article>
        <aside class="sidebar">
            <div class="widget widget--cta grid-bg" data-reveal>
                <i class="ri-building-2-line big"></i>
                <h3>پروژه‌ای مشابه در نظر دارید؟</h3>
                <p>برای دریافت مشاوره رایگان و برآورد هزینه با ما تماس بگیرید.</p>
                @if (setting('phone'))
                    <a class="phone" href="tel:{{ preg_replace('/[^\d+]/', '', en_num(setting('phone'))) }}">{{ fa_num(setting('phone')) }}</a>
                @endif
                <a href="{{ route('contact') }}" class="btn"><span>درخواست مشاوره</span><i class="ri-arrow-left-up-line"></i></a>
            </div>
        </aside>
    </div>
</section>

@if (! empty($project->gallery))
<section class="section" style="padding-top:0">
    <div class="container">
        @include('partials.sec-head', ['title' => 'گالری تصاویر', 'en' => 'Gallery', 'icon' => 'ri-gallery-line'])
        <div class="gallery-grid" data-stagger=".08">
            @foreach ($project->gallery as $image)
                <a href="{{ media_url($image) }}" data-lightbox data-cursor="بزرگنمایی">
                    <img src="{{ media_url($image) }}" alt="{{ $project->title }}" loading="lazy">
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($prev || $next)
<nav class="project-nav" aria-label="پروژه‌های دیگر">
    @if ($prev)
        <a href="{{ $prev->url }}"><i class="ri-arrow-right-line"></i><div><small>پروژه قبلی</small><strong>{{ $prev->title }}</strong></div></a>
    @else
        <span></span>
    @endif
    @if ($next)
        <a href="{{ $next->url }}"><div><small>پروژه بعدی</small><strong>{{ $next->title }}</strong></div><i class="ri-arrow-left-line"></i></a>
    @endif
</nav>
@endif

@if ($related->count())
<section class="section">
    <div class="container">
        @include('partials.sec-head', ['title' => 'پروژه‌های مشابه', 'en' => 'Related projects', 'icon' => 'ri-building-line', 'button' => ['همه پروژه ها', route('projects.index')]])
        <div class="projects-grid" data-stagger=".12">
            @foreach ($related as $item)
                @include('partials.project-card', ['project' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')
@endsection
