@extends('admin.layouts.app')

@section('title', 'نقش‌ها و دسترسی‌ها')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-shield-keyhole-line"></i>نقش‌ها و دسترسی‌ها</h1>
        <p>برای هر نقش مشخص کنید به کدام بخش‌های پنل دسترسی داشته باشد؛ سپس نقش را به کاربران بدهید.</p>
    </div>
    <div class="toolbar">
        @can('users.view')<a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="ri-admin-line"></i>کاربران مدیر</a>@endcan
        @can('roles.create')<a href="{{ route('admin.roles.create') }}" class="btn btn-primary"><i class="ri-add-line"></i>نقش جدید</a>@endcan
    </div>
</div>

<div class="role-cards">
    @foreach ($roles as $role)
        @php $count = $role->is_super ? $total : count($role->permissions ?? []); @endphp
        <div class="card role-card {{ $role->is_super ? 'is-super' : '' }}">
            <div class="role-card__head">
                <span class="role-card__icon"><i class="{{ $role->is_super ? 'ri-vip-crown-2-line' : 'ri-shield-user-line' }}"></i></span>
                <div>
                    <h3>{{ $role->name }}</h3>
                    <small>{{ $role->description ?: ($role->is_super ? 'دسترسی کامل' : 'بدون توضیح') }}</small>
                </div>
            </div>
            <div class="role-card__meter"><span style="width: {{ $total ? round($count / $total * 100) : 0 }}%"></span></div>
            <div class="role-card__meta">
                <span><i class="ri-key-2-line"></i>{{ $role->is_super ? 'همه دسترسی‌ها' : fa_num($count).' از '.fa_num($total).' دسترسی' }}</span>
                <span><i class="ri-user-line"></i>{{ fa_num($role->users_count) }} کاربر</span>
            </div>
            <div class="role-card__actions">
                @can('roles.edit')<a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-light btn-sm"><i class="ri-edit-line"></i>{{ $role->is_super ? 'ویرایش نام' : 'ویرایش دسترسی‌ها' }}</a>@endcan
                @can('roles.create')<a href="{{ route('admin.roles.create', ['copy' => $role->id]) }}" class="btn btn-light btn-sm btn-icon" title="کپی به عنوان نقش جدید"><i class="ri-file-copy-line"></i></a>@endcan
                @if ($role->users_count)
                    @can('users.view')<a href="{{ route('admin.users.index', ['role' => $role->id]) }}" class="btn btn-light btn-sm btn-icon" title="کاربران این نقش"><i class="ri-group-line"></i></a>@endcan
                @endif
                @can('roles.delete')
                    @unless ($role->is_super)
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" data-confirm="نقش «{{ $role->name }}» حذف شود؟">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm btn-icon" type="submit" title="حذف"><i class="ri-delete-bin-6-line"></i></button>
                        </form>
                    @endunless
                @endcan
            </div>
        </div>
    @endforeach
</div>
@endsection
