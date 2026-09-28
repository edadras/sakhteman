@extends('admin.layouts.app')

@section('title', 'مشتریان')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-group-line"></i>مشتریان سایت</h1>
        <p>کاربرانی که در سایت ثبت‌نام کرده‌اند — {{ fa_num($customers->total()) }} نفر</p>
    </div>
</div>
<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            <div class="search-box"><i class="ri-search-line"></i><input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="نام، موبایل یا ایمیل..."></div>
            <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>جستجو</button>
        </form>
    </div>
    @if ($customers->count())
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>نام</th><th>موبایل</th><th>ایمیل</th><th>سفارش‌ها</th><th>مجموع خرید (تومان)</th><th>تاریخ عضویت</th><th></th></tr></thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="title-cell">{{ $customer->name }}</td>
                            <td dir="ltr" style="text-align:right">{{ fa_num($customer->mobile ?: '—') }}</td>
                            <td>{{ $customer->email ?: '—' }}</td>
                            <td>{{ fa_num($customer->orders_count) }}</td>
                            <td>{{ fa_num(number_format((int) $customer->orders_sum_total)) }}</td>
                            <td>{{ jdate($customer->created_at) }}</td>
                            <td>
                                <div class="actions">
                                    @if ($customer->orders_count)<a href="{{ route('admin.orders.index', ['q' => $customer->mobile]) }}" class="btn btn-light btn-sm"><i class="ri-shopping-cart-2-line"></i>سفارش‌ها</a>@endif
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" data-confirm="حساب «{{ $customer->name }}» حذف شود؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm btn-icon" type="submit"><i class="ri-delete-bin-6-line"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $customers->links('admin.partials.pagination') }}
    @else
        <div class="empty"><i class="ri-group-line"></i>هنوز مشتری ثبت‌نام نکرده است.</div>
    @endif
</div>
@endsection
