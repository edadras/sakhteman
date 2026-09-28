@extends('layouts.app')

@section('title', 'سبد خرید')

@section('content')
@include('partials.page-hero', ['title' => 'سبد خرید', 'eyebrow' => 'فروشگاه', 'crumbs' => ['فروشگاه' => route('shop.index'), 'سبد خرید' => null]])

<section class="section">
    <div class="container">
        <div class="steps-bar" data-reveal>
            <span class="is-active"><b>{{ fa_num(1) }}</b> سبد خرید</span>
            <span class="{{ $items->count() ? 'is-active' : '' }}"><b>{{ fa_num(2) }}</b> اطلاعات ارسال</span>
            <span><b>{{ fa_num(3) }}</b> ثبت سفارش</span>
        </div>

        @if ($items->isEmpty())
            <div class="empty-state" data-reveal>
                <i class="ri-shopping-cart-2-line"></i>
                <h3>سبد خرید شما خالی است</h3>
                <p>از فروشگاه ما دیدن کنید و محصولات مورد علاقه خود را انتخاب کنید.</p>
                <a href="{{ route('shop.index') }}" class="btn">رفتن به فروشگاه</a>
            </div>
        @else
            <div class="cart-layout">
                <div>
                    @foreach ($items as $item)
                        <div class="cart-item" data-reveal>
                            <img src="{{ media_url($item->product->image) }}" alt="">
                            <div>
                                <h3><a href="{{ $item->product->url }}">{{ $item->product->title }}</a></h3>
                                <span class="muted">{{ fa_num(number_format($item->product->final_price)) }} تومان</span>
                            </div>
                            <div class="cart-item__actions">
                                <form action="{{ route('cart.update', $item->product) }}" method="POST" class="qty" data-qty data-autosubmit>
                                    @csrf @method('PATCH')
                                    <button type="button" data-step="1" aria-label="افزایش">+</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="99" aria-label="تعداد">
                                    <button type="button" data-step="-1" aria-label="کاهش">−</button>
                                </form>
                                <strong style="color:var(--ink);min-width:120px;text-align:left">{{ fa_num(number_format($item->subtotal)) }} <small class="muted">تومان</small></strong>
                                <form action="{{ route('cart.remove', $item->product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="remove-btn" type="submit" aria-label="حذف"><i class="ri-delete-bin-6-line"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-box" style="margin-top:30px" data-reveal>
                        <h3>اطلاعات ارسال</h3>
                        <p class="muted">پس از ثبت سفارش، کارشناسان ما برای هماهنگی نهایی و نحوه پرداخت با شما تماس می‌گیرند.</p>
                        <form action="{{ route('cart.checkout') }}" method="POST" id="checkoutForm" data-once>
                            @csrf
                            <div class="form-grid">
                                <div class="field">
                                    <input type="text" id="c-name" name="name" value="{{ old('name', $user?->name) }}" placeholder=" " required>
                                    <label for="c-name">نام و نام خانوادگی *</label>
                                    @error('name')<span class="error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <input type="tel" id="c-mobile" name="mobile" value="{{ old('mobile', $user?->mobile) }}" placeholder=" " required dir="ltr" style="text-align:right">
                                    <label for="c-mobile">شماره موبایل *</label>
                                    @error('mobile')<span class="error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <input type="text" id="c-city" name="city" value="{{ old('city') }}" placeholder=" " required>
                                    <label for="c-city">شهر *</label>
                                    @error('city')<span class="error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <input type="text" id="c-postal" name="postal_code" value="{{ old('postal_code') }}" placeholder=" " dir="ltr" style="text-align:right">
                                    <label for="c-postal">کد پستی</label>
                                    @error('postal_code')<span class="error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field full">
                                    <textarea id="c-address" name="address" placeholder=" " required style="min-height:100px">{{ old('address', $user?->address) }}</textarea>
                                    <label for="c-address">آدرس کامل *</label>
                                    @error('address')<span class="error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field full">
                                    <textarea id="c-note" name="note" placeholder=" " style="min-height:90px">{{ old('note') }}</textarea>
                                    <label for="c-note">توضیحات سفارش (اختیاری)</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="summary" data-reveal="left">
                    <h3>خلاصه سفارش</h3>
                    <div class="summary-row"><span class="muted">تعداد اقلام</span><span>{{ fa_num($items->sum('quantity')) }}</span></div>
                    <div class="summary-row"><span class="muted">هزینه ارسال</span><span>پس از هماهنگی</span></div>
                    <div class="summary-row total"><span>مبلغ قابل پرداخت</span><span>{{ fa_num(number_format($total)) }} <small class="muted">تومان</small></span></div>
                    <button type="submit" form="checkoutForm" class="btn btn--block" style="margin-top:16px">ثبت سفارش <i class="ri-check-double-line"></i></button>
                    @guest
                        <p class="muted" style="margin:14px 0 0;font-size:14px">برای پیگیری آسان سفارش‌ها <a href="{{ route('login') }}" class="text-green">وارد شوید</a> یا <a href="{{ route('register') }}" class="text-green">ثبت‌نام کنید</a>.</p>
                    @endguest
                </aside>
            </div>
        @endif
    </div>
</section>
@endsection
