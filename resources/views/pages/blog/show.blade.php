@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->excerpt)
@section('og_image', media_url($post->cover))

@push('before_header')
    <div class="reading-progress" aria-hidden="true"></div>
@endpush

@section('content')
<section class="page-hero">
    <div class="page-hero__bg" data-parallax=".25" style="background-image:url('{{ media_url($post->cover) }}')"></div>
    <div class="page-hero__grid"></div>
    <div class="container">
        @if ($post->category)<span class="eyebrow">{{ $post->category }}</span>@endif
        <h1 style="font-size:clamp(30px,4.6vw,60px)">{{ $post->title }}</h1>
        <div class="post-head-meta">
            @if ($post->author)<span><i class="ri-user-3-line"></i>{{ $post->author }}</span>@endif
            <span><i class="ri-calendar-line"></i>{{ jdate($post->published_at) }}</span>
            <span><i class="ri-time-line"></i>{{ fa_num(reading_time($post->body)) }} دقیقه مطالعه</span>
            <span><i class="ri-eye-line"></i>{{ fa_num(number_format($post->views)) }} بازدید</span>
        </div>
    </div>
</section>

<section class="section" style="padding-top:60px">
    <div class="container content-grid">
        <article data-article>
            @if ($post->excerpt)
                <p class="section-title" style="font-size:22px;font-weight:600;line-height:2;color:var(--text)" data-reveal>{{ $post->excerpt }}</p>
            @endif
            <div class="prose" data-reveal>{!! $post->body !!}</div>

            <div class="share">
                <strong>اشتراک‌گذاری:</strong>
                <div class="social">
                    <a href="https://t.me/share/url?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" aria-label="تلگرام"><i class="ri-telegram-line"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($post->title.' '.$post->url) }}" target="_blank" rel="noopener" aria-label="واتس‌اپ"><i class="ri-whatsapp-line"></i></a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($post->url) }}" target="_blank" rel="noopener" aria-label="لینکدین"><i class="ri-linkedin-line"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" aria-label="ایکس"><i class="ri-twitter-x-line"></i></a>
                </div>
            </div>
        </article>

        <aside class="sidebar">
            <div class="widget" data-reveal>
                <h3 class="widget__title">جستجو</h3>
                <form action="{{ route('blog.index') }}" class="search-form">
                    <input type="search" name="q" placeholder="عبارت مورد نظر..." aria-label="جستجو">
                    <button type="submit" aria-label="جستجو"><i class="ri-search-line"></i></button>
                </form>
            </div>
            @if ($recent->count())
                <div class="widget" data-reveal data-delay=".1">
                    <h3 class="widget__title">مقالات اخیر</h3>
                    @foreach ($recent as $item)
                        <a href="{{ $item->url }}" class="recent-post">
                            <img src="{{ media_url($item->cover) }}" alt="" loading="lazy">
                            <div><strong>{{ $item->title }}</strong><small>{{ jdate($item->published_at) }}</small></div>
                        </a>
                    @endforeach
                </div>
            @endif
            @if ($categories->count())
                <div class="widget" data-reveal data-delay=".2">
                    <h3 class="widget__title">دسته‌بندی‌ها</h3>
                    <div class="tags">
                        @foreach ($categories as $cat)
                            <a href="{{ route('blog.index', ['category' => $cat]) }}" class="{{ $post->category === $cat ? 'is-active' : '' }}">{{ $cat }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</section>

@include('partials.cta')
@endsection
