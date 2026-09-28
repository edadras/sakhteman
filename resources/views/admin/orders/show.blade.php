@extends('admin.layouts.app')

@section('title', 'سفارش '.$order->code)

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-file-list-3-line"></i>سفارش <span dir="ltr">{{ $order->code }}</span></h1>
        <p><a href="{{ route('admin.orders.index') }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به سفارش‌ها</a></p>
    </div>
    <div class="toolbar">
        <a href="tel:{{ $order->mobile }}" class="btn btn-primary"><i class="ri-phone-line"></i>تماس با مشتری</a>
        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" data-confirm="این سفارش حذف شود؟">
            @csrf @method('DELETE')
            <button class="btn btn-danger" type="submit"><i class="ri-delete-bin-6-line"></i>حذف</button>
        </form>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card__head"><h2 class="card__title"><i class="ri-shopping-bag-3-line"></i>اقلام سفارش</h2></div>
        <div class="table-wrap">
            <table class="table order-items">
                <thead><tr><th>محصول</th><th>قیمت واحد</th><th>تعداد</th><th>جمع (تومان)</th></tr></thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="title-cell">
                                @if ($item->product)
                                    <a href="{{ route('admin.resources.edit', ['products', $item->product_id]) }}" style="display:flex;align-items:center;gap:10px"><img class="thumb" src="{{ media_url($item->product->image) }}" alt="">{{ $item->title }}</a>
                                @else
                                    {{ $item->title }}
                                @endif
                            </td>
                            <td>{{ fa_num(number_format($item->price)) }}</td>
                            <td>{{ fa_num($item->quantity) }}</td>
                            <td><strong>{{ fa_num(number_format($item->price * $item->quantity)) }}</strong></td>
                        </tr>
                    @endforeach
                    <tr><td colspan="3"><strong>جمع کل</strong></td><td><strong style="color:var(--a-primary-2);font-size:17px">{{ fa_num(number_format($order->total)) }}</strong></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div style="display:grid;gap:22px;align-content:start">
        <div class="card">
            <div class="card__head"><h2 class="card__title"><i class="ri-user-3-line"></i>اطلاعات مشتری</h2></div>
            <div class="card__body">
                <dl class="kv">
                    <dt>نام</dt><dd>{{ $order->name }}</dd>
                    <dt>موبایل</dt><dd dir="ltr" style="text-align:right">{{ fa_num($order->mobile) }}</dd>
                    <dt>شهر</dt><dd>{{ $order->city ?: '—' }}</dd>
                    <dt>کد پستی</dt><dd>{{ fa_num($order->postal_code ?: '—') }}</dd>
                    <dt>آدرس</dt><dd>{{ $order->address }}</dd>
                    <dt>توضیحات</dt><dd>{{ $order->note ?: '—' }}</dd>
                    <dt>حساب کاربری</dt><dd>{{ $order->user?->name ?? 'مهمان' }}</dd>
                    <dt>تاریخ ثبت</dt><dd>{{ jdate($order->created_at, 'l j F Y - H:i') }}</dd>
                </dl>
            </div>
        </div>
        <form class="card" method="POST" action="{{ route('admin.orders.update', $order) }}" data-once>
            @csrf @method('PUT')
            <div class="card__head"><h2 class="card__title"><i class="ri-refresh-line"></i>وضعیت سفارش</h2></div>
            <div class="card__body">
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" for="status">وضعیت</label>
                    <select class="form-control" id="status" name="status">
                        @foreach (\App\Models\Order::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="admin_note">پیام برای مشتری (در حساب کاربری نمایش داده می‌شود)</label>
                    <textarea class="form-control" id="admin_note" name="admin_note" rows="3">{{ old('admin_note', $order->admin_note) }}</textarea>
                </div>
            </div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="ri-save-3-line"></i>ذخیره</button></div>
        </form>
    </div>
</div>
@endsection
