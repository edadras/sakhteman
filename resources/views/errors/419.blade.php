@extends('errors.minimal')
@section('title', 'صفحه منقضی شده')
@section('code', '۴۱۹')
@section('heading', 'زمان این صفحه به پایان رسیده')
@section('message', 'این صفحه مدت زیادی باز مانده بود و برای امنیت شما منقضی شد. صفحه را دوباره باز کنید و فرم را مجددا ارسال کنید.')
@section('actions')
    <a href="javascript:history.back()"><i class="ri-refresh-line"></i>بازگشت و تلاش دوباره</a>
@endsection
