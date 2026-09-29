@extends('admin.layouts.app')

@section('title', 'دسترسی مجاز نیست')

@section('content')
<div class="card" style="max-width:640px;margin:40px auto;text-align:center;padding:48px 28px">
    <div style="font-size:56px;color:var(--a-rose);line-height:1"><i class="ri-lock-2-line"></i></div>
    <h1 style="margin:16px 0 8px;font-size:22px">اجازه دسترسی به این بخش را ندارید</h1>
    <p class="text-muted">نقش شما ({{ auth()->user()->role?->name ?? 'بدون نقش' }}) این دسترسی را ندارد. اگر به آن نیاز دارید، از مدیر سایت بخواهید دسترسی لازم را به نقش شما اضافه کند.</p>
    <div class="toolbar" style="justify-content:center;margin-top:22px">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"><i class="ri-dashboard-3-line"></i>بازگشت به داشبورد</a>
        <a href="javascript:history.back()" class="btn btn-light"><i class="ri-arrow-right-line"></i>صفحه قبل</a>
    </div>
</div>
@endsection
