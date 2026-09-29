@extends('layouts.app')

@section('title', 'دسترسی مجاز نیست')

@section('content')
<section class="error-page grid-bg" data-grid-spot>
    <div>
        <div class="error-page__code">{{ fa_num(403) }}</div>
        <h1 style="font-size:clamp(26px,4vw,44px)">اجازه دسترسی ندارید</h1>
        <p class="muted">شما اجازه مشاهده این صفحه را ندارید.</p>
        <a href="{{ route('home') }}" class="btn" data-magnetic=".2" style="margin-top:20px"><span>بازگشت به خانه</span><i class="ri-home-5-line"></i></a>
    </div>
</section>
@endsection
