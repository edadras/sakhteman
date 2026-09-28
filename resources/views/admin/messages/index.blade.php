@extends('admin.layouts.app')

@section('title', 'پیام‌ها و درخواست‌ها')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-mail-line"></i>پیام‌ها و درخواست‌ها</h1>
        <p>پیام‌های ارسال شده از فرم تماس سایت — {{ fa_num($messages->total()) }} مورد</p>
    </div>
</div>

<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="جستجو در نام، ایمیل، تلفن...">
            </div>
            <select class="form-control" name="status" onchange="this.form.submit()">
                <option value="">همه پیام‌ها</option>
                <option value="unread" @selected(request('status') === 'unread')>خوانده نشده</option>
            </select>
            <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>اعمال</button>
        </form>
    </div>

    @if ($messages->count())
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>فرستنده</th><th>تلفن</th><th>موضوع</th><th>تاریخ</th><th>وضعیت</th><th></th></tr></thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'unread' }}">
                            <td><strong>{{ $message->name }}</strong><br><small class="text-muted">{{ $message->email }}</small></td>
                            <td dir="ltr" style="text-align:right">{{ fa_num($message->phone) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($message->subject ?: $message->body, 50) }}</td>
                            <td>{{ jdate($message->created_at, 'j F Y - H:i') }}</td>
                            <td>
                                @if ($message->is_read)
                                    <span class="badge badge-muted">خوانده شده</span>
                                @else
                                    <span class="badge badge-primary">جدید</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-light btn-sm" href="{{ route('admin.messages.show', $message) }}"><i class="ri-eye-line"></i>مشاهده</a>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="این پیام حذف شود؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm btn-icon" type="submit"><i class="ri-delete-bin-6-line"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $messages->links('admin.partials.pagination') }}
    @else
        <div class="empty"><i class="ri-inbox-line"></i>پیامی یافت نشد.</div>
    @endif
</div>
@endsection
