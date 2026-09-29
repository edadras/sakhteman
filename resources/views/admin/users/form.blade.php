@extends('admin.layouts.app')

@php $self = $user->exists && $user->is(auth()->user()); @endphp

@section('title', $user->exists ? 'ویرایش کاربر' : 'افزودن کاربر')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-user-settings-line"></i>{{ $user->exists ? 'ویرایش «'.$user->name.'»' : 'افزودن کاربر مدیر' }}</h1>
        <p><a href="{{ route('admin.users.index') }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به کاربران</a></p>
    </div>
</div>

<form class="card" method="POST" data-once action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" autocomplete="off">
    @csrf
    @if ($user->exists) @method('PUT') @endif
    <div class="card__body">
        <div class="form-grid">
            <div class="form-group col-half">
                <label class="form-label" for="u_name">نام و نام خانوادگی <span class="req">*</span></label>
                <input class="form-control @error('name') is-invalid @enderror" id="u_name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="u_email">ایمیل (برای ورود) <span class="req">*</span></label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" id="u_email" name="email" value="{{ old('email', $user->email) }}" required dir="ltr" style="text-align:right">
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="u_mobile">موبایل</label>
                <input class="form-control @error('mobile') is-invalid @enderror" id="u_mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" inputmode="numeric" dir="ltr" style="text-align:right" placeholder="09xxxxxxxxx">
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="u_role">نقش <span class="req">*</span></label>
                @if ($self)
                    <input class="form-control" value="{{ $user->role?->name ?? 'بدون نقش' }}" disabled>
                    <div class="form-help">نقش خودتان را نمی‌توانید تغییر دهید.</div>
                @else
                    <select class="form-control @error('role_id') is-invalid @enderror" id="u_role" name="role_id" required>
                        <option value="">— انتخاب نقش —</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}" @selected((string) old('role_id', $user->role_id) === (string) $r->id)>{{ $r->name }}{{ $r->is_super ? ' (دسترسی کامل)' : ' — '.fa_num(count($r->permissions ?? [])).' دسترسی' }}</option>
                        @endforeach
                    </select>
                    @can('roles.create')<div class="form-help">نقش مناسب نیست؟ <a href="{{ route('admin.roles.create') }}" target="_blank">ساخت نقش جدید</a></div>@endcan
                @endif
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="u_pass">رمز عبور @unless ($user->exists)<span class="req">*</span>@endunless</label>
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="u_pass" name="password" autocomplete="new-password" dir="ltr" @unless ($user->exists) required @endunless>
                <div class="form-help">{{ $user->exists ? 'برای تغییر ندادن رمز، خالی بگذارید. ' : '' }}حداقل ۸ کاراکتر، شامل حرف و عدد</div>
            </div>
            <div class="form-group col-half">
                <label class="form-label" for="u_pass2">تکرار رمز عبور</label>
                <input class="form-control" type="password" id="u_pass2" name="password_confirmation" autocomplete="new-password" dir="ltr">
            </div>
            <div class="form-group">
                @if ($self)
                    <span class="badge badge-success"><i class="ri-checkbox-circle-fill"></i> حساب شما فعال است</span>
                @else
                    <label class="switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))>
                        <span class="switch__track"></span>
                        <span>حساب فعال است (با غیرفعال کردن، کاربر فورا از پنل خارج می‌شود)</span>
                    </label>
                @endif
            </div>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i>ذخیره</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light" style="margin-right:auto">انصراف</a>
    </div>
</form>
@endsection
