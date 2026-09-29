@extends('admin.layouts.app')

@section('title', 'حساب کاربری')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-user-settings-line"></i>حساب کاربری</h1>
        <p>اطلاعات ورود و رمز عبور خود را مدیریت کنید.</p>
    </div>
</div>

@php $me = auth()->user(); @endphp
<div class="card profile-role" style="max-width:820px">
    <span class="role-card__icon"><i class="{{ $me->role?->is_super ? 'ri-vip-crown-2-line' : 'ri-shield-user-line' }}"></i></span>
    <div>
        <strong>نقش شما: {{ $me->role?->name ?? 'بدون نقش' }}</strong>
        <small>
            {{ $me->role?->is_super ? 'دسترسی کامل به همه بخش‌ها' : fa_num(count($me->role?->permissions ?? [])).' دسترسی' }}
            @if ($me->last_login_at) — آخرین ورود: {{ jdate($me->last_login_at, 'j F Y، H:i') }}@endif
        </small>
    </div>
</div>

<form class="card" method="POST" action="{{ route('admin.profile.update') }}" data-once style="max-width:820px">
    @csrf @method('PUT')
    <div class="card__head"><h2 class="card__title"><i class="ri-profile-line"></i>اطلاعات پایه</h2></div>
    <div class="card__body">
        <div class="form-grid">
            <div class="form-group col-half">
                <label class="form-label" for="name">نام <span class="req">*</span></label>
                <input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="email">ایمیل <span class="req">*</span></label>
                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required dir="ltr" style="text-align:right">
            </div>
        </div>
    </div>
    <div class="card__head" style="border-top:1px solid var(--a-border)"><h2 class="card__title"><i class="ri-lock-password-line"></i>تغییر رمز عبور</h2></div>
    <div class="card__body">
        <div class="form-grid">
            <div class="form-group col-third">
                <label class="form-label" for="current_password">رمز عبور فعلی</label>
                <input class="form-control" type="password" id="current_password" name="current_password" dir="ltr" autocomplete="current-password">
            </div>
            <div class="form-group col-third">
                <label class="form-label" for="password">رمز عبور جدید</label>
                <input class="form-control" type="password" id="password" name="password" dir="ltr" autocomplete="new-password">
                <div class="form-help">حداقل ۸ کاراکتر</div>
            </div>
            <div class="form-group col-third">
                <label class="form-label" for="password_confirmation">تکرار رمز عبور جدید</label>
                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" dir="ltr" autocomplete="new-password">
            </div>
        </div>
        <div class="form-help">اگر نمی‌خواهید رمز عبور را تغییر دهید، این بخش را خالی بگذارید.</div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i>ذخیره تغییرات</button>
    </div>
</form>
@endsection
