@if ($product->final_price)
    @if ($product->discount_percent)
        <div class="price-old"><del>{{ fa_num(number_format($product->price)) }}</del><span class="price-off">٪{{ fa_num($product->discount_percent) }}</span></div>
    @elseif ($product->price_from)
        <span class="price-from">از</span>
    @endif
    <div class="price-now">{{ fa_num(number_format($product->final_price)) }}<small>تومان</small></div>
@else
    <span class="price-call">{{ $product->in_stock ? 'برای استعلام قیمت تماس بگیرید' : 'ناموجود' }}</span>
@endif
