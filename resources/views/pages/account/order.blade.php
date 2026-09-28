@extends('layouts.app')

@section('title', 'سفارش '.$order->code)

@section('content')
@include('partials.page-hero', ['title' => 'سفارش '.$order->code, 'eyebrow' => $order->status_label, 'crumbs' => ['حساب کاربری' => route('account'), $order->code => null]])

<section class="section">
    <div class="container" style="max-width:900px">
        <div class="account-card" data-reveal>
            <div class="sec-head" style="margin-bottom:20px">
                <h3 style="margin:0">جزئیات سفارش</h3>
                <span class="status status--{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>محصول</th><th>تعداد</th><th>قیمت واحد</th><th>جمع (تومان)</th></tr></thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>@if ($item->product)<a href="{{ $item->product->url }}" class="text-green">{{ $item->title }}</a>@else{{ $item->title }}@endif</td>
                                <td>{{ fa_num($item->quantity) }}</td>
                                <td>{{ fa_num(number_format($item->price)) }}</td>
                                <td>{{ fa_num(number_format($item->price * $item->quantity)) }}</td>
                            </tr>
                        @endforeach
                        <tr><th colspan="3">جمع کل</th><th style="color:var(--ink)">{{ fa_num(number_format($order->total)) }}</th></tr>
                    </tbody>
                </table>
            </div>
            <div class="summary-row"><span class="muted">تاریخ ثبت</span><span>{{ jdate($order->created_at, 'j F Y - H:i') }}</span></div>
            <div class="summary-row"><span class="muted">گیرنده</span><span>{{ $order->name }} — <span class="ltr">{{ fa_num($order->mobile) }}</span></span></div>
            <div class="summary-row"><span class="muted">آدرس</span><span>{{ $order->city }}، {{ $order->address }}</span></div>
            @if ($order->admin_note)
                <div class="alert alert--success" style="margin-top:20px"><i class="ri-information-line"></i>{{ $order->admin_note }}</div>
            @endif
            <a href="{{ route('account') }}" class="btn btn--gray" style="margin-top:20px">بازگشت</a>
        </div>
    </div>
</section>
@endsection
