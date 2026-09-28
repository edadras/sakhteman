@extends('layouts.app')

@section('title', $product->title)
@section('description', $product->summary)
@section('og_image', media_url($product->image))

@section('content')
@include('partials.page-hero', [
    'title' => $product->title,
    'eyebrow' => $product->category?->name ?? 'محصول',
    'image' => media_url($product->image),
    'crumbs' => ['فروشگاه' => route('shop.index'), $product->title => null],
])

<section class="section">
    <div class="container product-detail">
        @php $images = array_values(array_unique(array_filter(array_merge([$product->image], (array) $product->gallery)))); @endphp
        <div class="pd-gallery" data-reveal="right">
            <div class="pd-gallery__main"><img src="{{ media_url($images[0] ?? null) }}" alt="{{ $product->title }}" id="pdMain"></div>
            @if (count($images) > 1)
                <div class="pd-gallery__thumbs">
                    @foreach ($images as $i => $img)
                        <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-src="{{ media_url($img) }}" aria-label="تصویر {{ fa_num($i + 1) }}"><img src="{{ media_url($img) }}" alt=""></button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="pd-info">
            <div class="pd-meta" data-reveal>
                @if ($product->category)<a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="badge badge--amber">{{ $product->category->name }}</a>@endif
                <span class="badge {{ $product->in_stock ? 'badge--green' : 'badge--gray' }}">{{ $product->in_stock ? 'موجود در انبار' : 'ناموجود' }}</span>
                @if ($product->sku)<span class="badge badge--gray ltr">{{ $product->sku }}</span>@endif
            </div>
            <h2 data-split style="font-size:clamp(26px,3vw,40px)">{{ $product->title }}</h2>
            @if ($product->summary)<p class="muted" data-reveal>{{ $product->summary }}</p>@endif

            <div class="pd-price" data-reveal>@include('partials.price', ['product' => $product])</div>

            <div class="pd-buy" data-reveal>
                @if ($product->purchasable)
                    <form action="{{ route('cart.add') }}" method="POST" class="js-add-cart pd-buy">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="qty" data-qty>
                            <button type="button" data-step="1" aria-label="افزایش">+</button>
                            <input type="number" name="quantity" value="1" min="1" max="99" aria-label="تعداد">
                            <button type="button" data-step="-1" aria-label="کاهش">−</button>
                        </div>
                        <button type="submit" class="btn">افزودن به سبد خرید <i class="ri-shopping-cart-2-line"></i></button>
                    </form>
                @else
                    <a href="{{ route('contact') }}" class="btn">استعلام قیمت و موجودی <i class="ri-phone-line"></i></a>
                @endif
                <button type="button" class="fav-btn js-fav" data-id="{{ $product->id }}" style="width:60px;height:60px;border-radius:16px;background:#fff" aria-label="علاقه‌مندی"><i class="ri-heart-3-line"></i></button>
            </div>

            @if ($product->spec_list)
                <h3 style="margin-top:40px" data-reveal>مشخصات محصول</h3>
                <table class="specs" data-reveal>
                    @foreach ($product->spec_list as [$k, $v])
                        <tr><th>{{ $k }}</th><td>{{ fa_num($v) }}</td></tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>

    @if ($product->body)
        <div class="container" style="margin-top:60px">
            @include('partials.sec-head', ['title' => 'توضیحات', 'en' => 'Description', 'icon' => 'ri-file-text-line'])
            <div class="prose" data-reveal>{!! $product->body !!}</div>
        </div>
    @endif
</section>

@if ($related->count())
<section class="section" style="padding-top:0">
    <div class="container">
        @include('partials.sec-head', ['title' => 'محصولات مشابه', 'en' => 'Related products', 'icon' => 'ri-shopping-bag-3-fill', 'button' => ['همه محصولات', route('shop.index')]])
        <div class="products-grid" data-stagger=".1">
            @foreach ($related as $item)
                @include('partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
