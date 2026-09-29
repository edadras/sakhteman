@extends('admin.layouts.app')

@section('title', 'کاربران مدیر')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-admin-line"></i>کاربران مدیر</h1>
        <p>افرادی که به پنل مدیریت دسترسی دارند — {{ fa_num($users->total()) }} نفر</p>
    </div>
    <div class="toolbar">
        @can('roles.view')<a href="{{ route('admin.roles.index') }}" class="btn btn-light"><i class="ri-shield-keyhole-line"></i>نقش‌ها و دسترسی‌ها</a>@endcan
        @can('users.create')<a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="ri-user-add-line"></i>افزودن کاربر</a>@endcan
    </div>
</div>

<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            <div class="search-box"><i class="ri-search-line"></i><input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="نام، ایمیل یا موبایل..."></div>
            <select class="form-control" name="role" onchange="this.form.submit()">
                <option value="">نقش: همه</option>
                @foreach ($roles as $r)<option value="{{ $r->id }}" @selected((string) request('role') === (string) $r->id)>{{ $r->name }}</option>@endforeach
            </select>
            <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>جستجو</button>
        </form>
    </div>
    @if ($users->count())
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>کاربر</th><th>نقش</th><th>وضعیت</th><th>آخرین ورود</th><th></th></tr></thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr @class(['is-muted' => ! $u->is_active])>
                            <td>
                                <div class="user-cell">
                                    <span class="avatar">{{ mb_substr($u->name, 0, 1) }}</span>
                                    <div>
                                        <strong>{{ $u->name }} @if ($u->is(auth()->user()))<span class="badge badge-muted">شما</span>@endif</strong>
                                        <small dir="ltr">{{ $u->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($u->role)
                                    <span class="badge {{ $u->role->is_super ? 'badge-amber' : 'badge-blue' }}">@if ($u->role->is_super)<i class="ri-vip-crown-2-line"></i>@endif{{ $u->role->name }}</span>
                                @else
                                    <span class="badge badge-muted">بدون نقش</span>
                                @endif
                            </td>
                            <td>
                                @if ($u->is_active)<span class="badge badge-success"><i class="ri-checkbox-circle-fill"></i> فعال</span>
                                @else<span class="badge badge-muted"><i class="ri-forbid-line"></i> غیرفعال</span>@endif
                            </td>
                            <td>
                                @if ($u->last_login_at)
                                    {{ jdate($u->last_login_at, 'j F Y — H:i') }}
                                    <small class="text-muted d-block" dir="ltr" style="text-align:right">{{ $u->last_login_ip }}</small>
                                @else
                                    <span class="text-muted">هنوز وارد نشده</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    @can('activity.view')<a href="{{ route('admin.activity.index', ['user' => $u->id]) }}" class="btn btn-light btn-sm btn-icon" title="فعالیت‌ها"><i class="ri-history-line"></i></a>@endcan
                                    @can('users.edit')<a href="{{ route('admin.users.edit', $u) }}" class="btn btn-light btn-sm"><i class="ri-edit-line"></i>ویرایش</a>@endcan
                                    @can('users.delete')
                                        @unless ($u->is(auth()->user()))
                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" data-confirm="حساب «{{ $u->name }}» حذف شود؟ این کاربر دیگر به پنل دسترسی نخواهد داشت.">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm btn-icon" type="submit" title="حذف"><i class="ri-delete-bin-6-line"></i></button>
                                            </form>
                                        @endunless
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links('admin.partials.pagination') }}
    @else
        <div class="empty"><i class="ri-admin-line"></i>کاربری یافت نشد.</div>
    @endif
</div>
@endsection
