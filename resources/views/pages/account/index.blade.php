@extends('layouts.app')

@section('title', 'حساب کاربری')

@section('content')
@include('partials.page-hero', ['title' => 'حساب کاربری من', 'eyebrow' => 'خوش آمدید، '.$user->name, 'crumbs' => ['حساب کاربری' => null]])

<section class="section">
    <div class="container account-grid">
        <aside class="account-card account-user" data-reveal>
            <div class="account-user__avatar">{{ mb_substr($user->name, 0, 1) }}</div>
            <h3 style="margin:0">{{ $user->name }}</h3>
            <p class="muted ltr">{{ fa_num($user->mobile) }}</p>
            <div class="summary-row"><span class="muted">تعداد سفارش‌ها</span><strong>{{ fa_num($orders->count()) }}</strong></div>
            <div class="summary-row"><span class="muted">مجموع خرید</span><strong>{{ fa_num(number_format($orders->where('status', '!=', 'canceled')->sum('total'))) }}</strong></div>
            <div style="display:grid;gap:10px;margin-top:20px">
                <a href="{{ route('shop.index') }}" class="btn btn--sm">فروشگاه</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn--gray btn--sm btn--block" type="submit">خروج از حساب</button></form>
            </div>
        </aside>

        <div style="display:grid;gap:30px">
            <div class="account-card" data-reveal>
                <h3><i class="ri-file-list-3-line text-amber"></i> سفارش‌های من</h3>
                @if ($orders->count())
                    <div class="table-wrap">
                        <table class="table">
                            <thead><tr><th>کد سفارش</th><th>تاریخ</th><th>اقلام</th><th>مبلغ (تومان)</th><th>وضعیت</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="ltr" style="text-align:right">{{ $order->code }}</td>
                                        <td>{{ jdate($order->created_at) }}</td>
                                        <td>{{ fa_num($order->items_count) }}</td>
                                        <td>{{ fa_num(number_format($order->total)) }}</td>
                                        <td><span class="status status--{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                        <td><a href="{{ route('account.order', $order->code) }}" class="link-arrow">جزئیات <i class="ri-arrow-left-line"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="muted">هنوز سفارشی ثبت نکرده‌اید.</p>
                @endif
            </div>

            <div class="account-card" data-reveal>
                <h3><i class="ri-user-settings-line text-green"></i> ویرایش اطلاعات</h3>
                @if ($errors->any())<div class="alert alert--error">{{ $errors->first() }}</div>@endif
                <form action="{{ route('account.update') }}" method="POST" data-once>
                    @csrf @method('PUT')
                    <div class="form-grid">
                        <div class="field"><input type="text" id="a-name" name="name" value="{{ old('name', $user->name) }}" placeholder=" " required><label for="a-name">نام</label></div>
                        <div class="field"><input type="tel" id="a-mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" placeholder=" " required dir="ltr" style="text-align:right"><label for="a-mobile">موبایل</label></div>
                        <div class="field full"><input type="email" id="a-email" name="email" value="{{ old('email', $user->email) }}" placeholder=" " dir="ltr" style="text-align:right"><label for="a-email">ایمیل</label></div>
                        <div class="field full"><textarea id="a-address" name="address" placeholder=" " style="min-height:90px">{{ old('address', $user->address) }}</textarea><label for="a-address">آدرس</label></div>
                        <div class="field"><input type="password" id="a-cur" name="current_password" placeholder=" " dir="ltr"><label for="a-cur">رمز فعلی (برای تغییر رمز)</label></div>
                        <div class="field"><input type="password" id="a-new" name="password" placeholder=" " dir="ltr"><label for="a-new">رمز جدید</label></div>
                        <div class="field full"><input type="password" id="a-new2" name="password_confirmation" placeholder=" " dir="ltr"><label for="a-new2">تکرار رمز جدید</label></div>
                        <div class="full"><button class="btn" type="submit">ذخیره تغییرات</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
