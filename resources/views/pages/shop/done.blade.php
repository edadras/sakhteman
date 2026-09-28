@extends('layouts.app')

@section('title', 'سفارش ثبت شد')

@section('content')
@include('partials.page-hero', ['title' => 'سفارش شما ثبت شد', 'eyebrow' => 'فروشگاه', 'crumbs' => ['سبد خرید' => route('cart.index'), 'ثبت سفارش' => null]])

<section class="section">
    <div class="container" style="max-width:820px">
        <div class="empty-state" data-reveal="scale" style="padding:50px 30px">
            <i class="ri-checkbox-circle-fill" style="color:var(--green)"></i>
            <h2>با تشکر از خرید شما!</h2>
            <p>کد پیگیری سفارش: <strong class="ltr" style="color:var(--ink);font-size:22px">{{ $order->code }}</strong></p>
            <p>کارشناسان ما به‌زودی برای هماهنگی ارسال و پرداخت با شماره <span class="ltr">{{ fa_num($order->mobile) }}</span> تماس می‌گیرند.</p>
            <div class="table-wrap" style="margin:30px 0;text-align:right">
                <table class="table">
                    <thead><tr><th>محصول</th><th>تعداد</th><th>قیمت واحد</th><th>جمع</th></tr></thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr><td>{{ $item->title }}</td><td>{{ fa_num($item->quantity) }}</td><td>{{ fa_num(number_format($item->price)) }}</td><td>{{ fa_num(number_format($item->price * $item->quantity)) }}</td></tr>
                        @endforeach
                        <tr><th colspan="3">جمع کل (تومان)</th><th style="color:var(--ink)">{{ fa_num(number_format($order->total)) }}</th></tr>
                    </tbody>
                </table>
            </div>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('shop.index') }}" class="btn">ادامه خرید</a>
                @auth<a href="{{ route('account') }}" class="btn btn--gray">سفارش‌های من</a>@endauth
            </div>
        </div>
    </div>
</section>
@endsection
