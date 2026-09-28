<article class="product-card" data-product="{{ $product->id }}">
    <div class="card-top">
        <a href="{{ $product->url }}" class="product-card__media" data-cursor="مشاهده">
            <img src="{{ media_url($product->image) }}" alt="{{ $product->title }}" loading="lazy">
        </a>
        @if ($product->category)
            <span class="badge badge--amber card-top__badge card-top__badge--sm">{{ $product->category->name }}</span>
        @endif
    </div>
    <div class="product-card__head">
        <h3 class="product-card__title"><a href="{{ $product->url }}">{{ $product->title }}</a></h3>
        <button type="button" class="fav-btn js-fav" data-id="{{ $product->id }}" aria-label="افزودن به علاقه‌مندی‌ها"><i class="ri-heart-3-line"></i></button>
    </div>
    <div class="product-card__price">
        @include('partials.price', ['product' => $product])
    </div>
    <div class="product-card__foot">
        <div class="product-card__buy">
            @if ($product->purchasable)
                <form action="{{ route('cart.add') }}" method="POST" class="js-add-cart">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="tab-shape">خرید</button>
                </form>
            @else
                <a href="{{ route('contact') }}" class="tab-shape">{{ $product->in_stock ? 'استعلام قیمت' : 'ناموجود' }}</a>
            @endif
        </div>
    </div>
</article>
