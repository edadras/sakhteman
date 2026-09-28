@extends('layouts.app')

@section('title', 'مقالات')

@section('content')
@include('partials.page-hero', ['title' => 'مجله ساختمان', 'eyebrow' => 'مقالات و اخبار', 'crumbs' => ['مقالات' => null]])

<section class="section">
    <div class="container">
        <div class="filter-bar" data-reveal style="align-items:center">
            <a href="{{ route('blog.index') }}" class="filter-btn {{ request('category') ? '' : 'is-active' }}" style="display:inline-flex;align-items:center">همه</a>
            @foreach ($categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat]) }}" class="filter-btn {{ request('category') === $cat ? 'is-active' : '' }}" style="display:inline-flex;align-items:center">{{ $cat }}</a>
            @endforeach
            <form action="{{ route('blog.index') }}" class="search-form" style="min-width:260px">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجو در مقالات..." aria-label="جستجو">
                <button type="submit" aria-label="جستجو"><i class="ri-search-line"></i></button>
            </form>
        </div>

        @if ($posts->count())
            <div class="posts-grid" data-stagger=".1">
                @foreach ($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
            {{ $posts->links() }}
        @else
            <div class="empty-state"><i class="ri-article-line"></i>مقاله‌ای یافت نشد.</div>
        @endif
    </div>
</section>

@include('partials.cta')
@endsection
