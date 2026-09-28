@extends('layouts.app')

@section('title', 'صفحه یافت نشد')

@section('content')
<section class="error-page">
    <div>
        <div class="error-page__code">{{ fa_num(404) }}</div>
        <h1 style="font-size:clamp(26px,4vw,44px)">این صفحه هنوز ساخته نشده!</h1>
        <p class="muted">صفحه‌ای که به دنبال آن هستید وجود ندارد یا جابه‌جا شده است.</p>
        <a href="{{ route('home') }}" class="btn" data-magnetic=".2" style="margin-top:20px"><span>بازگشت به خانه</span><i class="ri-home-5-line"></i></a>
    </div>
</section>
@endsection
