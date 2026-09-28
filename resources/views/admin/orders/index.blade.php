@extends('admin.layouts.app')

@section('title', 'سفارش‌ها')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-shopping-cart-2-line"></i>سفارش‌های فروشگاه</h1>
        <p>{{ fa_num($orders->total()) }} سفارش</p>
    </div>
</div>

<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="کد سفارش، نام یا موبایل...">
            </div>
            <select class="form-control" name="status" onchange="this.form.submit()">
                <option value="">همه وضعیت‌ها</option>
                @foreach (\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>اعمال</button>
        </form>
    </div>
    @if ($orders->count())
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>کد</th><th>مشتری</th><th>موبایل</th><th>اقلام</th><th>مبلغ (تومان)</th><th>تاریخ</th><th>وضعیت</th><th></th></tr></thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="{{ $order->status === 'pending' ? 'unread' : '' }}">
                            <td dir="ltr" style="text-align:right">{{ $order->code }}</td>
                            <td>{{ $order->name }}</td>
                            <td dir="ltr" style="text-align:right">{{ fa_num($order->mobile) }}</td>
                            <td>{{ fa_num($order->items_count) }}</td>
                            <td><strong>{{ fa_num(number_format($order->total)) }}</strong></td>
                            <td>{{ jdate($order->created_at, 'j F Y - H:i') }}</td>
                            <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                            <td><div class="actions"><a class="btn btn-light btn-sm" href="{{ route('admin.orders.show', $order) }}"><i class="ri-eye-line"></i>مشاهده</a></div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links('admin.partials.pagination') }}
    @else
        <div class="empty"><i class="ri-shopping-cart-2-line"></i>سفارشی یافت نشد.</div>
    @endif
</div>
@endsection
