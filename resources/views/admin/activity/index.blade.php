@extends('admin.layouts.app')

@section('title', 'گزارش فعالیت‌ها')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-history-line"></i>گزارش فعالیت‌ها</h1>
        <p>چه کسی، چه زمانی، چه تغییری در پنل داده است — {{ fa_num($logs->total()) }} رویداد</p>
    </div>
</div>

<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            <div class="search-box"><i class="ri-search-line"></i><input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="جستجو در عنوان‌ها..."></div>
            <select class="form-control" name="user" onchange="this.form.submit()">
                <option value="">کاربر: همه</option>
                @foreach ($users as $u)<option value="{{ $u->id }}" @selected((string) request('user') === (string) $u->id)>{{ $u->name }}</option>@endforeach
            </select>
            <select class="form-control" name="action" onchange="this.form.submit()">
                <option value="">نوع: همه</option>
                @foreach (\App\Models\ActivityLog::ACTIONS as $key => [$label])<option value="{{ $key }}" @selected(request('action') === $key)>{{ $label }}</option>@endforeach
            </select>
            <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>اعمال</button>
            @if (request()->hasAny(['q', 'user', 'action']))<a href="{{ route('admin.activity.index') }}" class="btn btn-light"><i class="ri-close-line"></i>حذف فیلتر</a>@endif
        </form>
    </div>
    @if ($logs->count())
        <div class="activity-list">
            @foreach ($logs as $log)
                @php [$label, $icon, $color] = \App\Models\ActivityLog::ACTIONS[$log->action] ?? [$log->action, 'ri-record-circle-line', 'muted']; @endphp
                <div class="activity-item">
                    <span class="activity-item__icon c-{{ $color }}"><i class="{{ $icon }}"></i></span>
                    <div class="activity-item__body">
                        <div>
                            <strong>{{ $log->user?->name ?? 'کاربر حذف‌شده' }}</strong>
                            <span class="badge badge-{{ $color === 'muted' ? 'muted' : $color }}">{{ $label }}</span>
                            @if ($section = \App\Admin\Permissions::sectionLabel($log->section))<span class="text-muted">در {{ $section }}</span>@endif
                        </div>
                        @if ($log->subject)<p>{{ $log->subject }}</p>@endif
                    </div>
                    <div class="activity-item__meta">
                        <span title="{{ jdate($log->created_at, 'j F Y — H:i:s') }}">{{ jdate($log->created_at, 'j F — H:i') }}</span>
                        <small dir="ltr">{{ $log->ip }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $logs->links('admin.partials.pagination') }}
    @else
        <div class="empty"><i class="ri-history-line"></i>رویدادی ثبت نشده است.</div>
    @endif
</div>
@endsection
