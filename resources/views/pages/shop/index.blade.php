@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')
@include('partials.page-hero', ['title' => 'فروشگاه لوازم خانه لوکس', 'eyebrow' => 'محصولات', 'crumbs' => ['فروشگاه' => null], 'image' => asset('assets/img/demo/pr-5.jpg')])

<section class="section">
    <div class="container">
        <div class="filter-bar" data-reveal>
            <a href="{{ route('shop.index', request()->except('category', 'page')) }}" class="filter-btn {{ $currentCategory ? '' : 'is-active' }}">همه</a>
            @foreach ($categories as $cat)
                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="filter-btn {{ $currentCategory === $cat->slug ? 'is-active' : '' }}">{{ $cat->name }}<sup>{{ fa_num($cat->products_count) }}</sup></a>
            @endforeach
            <form action="{{ route('shop.index') }}" class="search-form" style="min-width:240px">
                @if ($currentCategory)<input type="hidden" name="category" value="{{ $currentCategory }}">@endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی محصول..." aria-label="جستجو">
                <button type="submit" aria-label="جستجو"><i class="ri-search-line"></i></button>
            </form>
            <form action="{{ route('shop.index') }}">
                @foreach (request()->except('sort', 'page') as $k => $v)<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endforeach
                <select name="sort" class="select-sort" onchange="this.form.submit()" aria-label="مرتب‌سازی">
                    <option value="">پیشنهاد ما</option>
                    <option value="new" @selected(request('sort') === 'new')>جدیدترین</option>
                    <option value="cheap" @selected(request('sort') === 'cheap')>ارزان‌ترین</option>
                    <option value="expensive" @selected(request('sort') === 'expensive')>گران‌ترین</option>
                </select>
            </form>
        </div>

        @if ($products->count())
            <div class="products-grid" data-stagger=".08">
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            {{ $products->links() }}
        @else
            <div class="empty-state"><i class="ri-shopping-bag-3-line"></i>محصولی یافت نشد.</div>
        @endif
    </div>
</section>

@include('partials.promo')
@endsection
