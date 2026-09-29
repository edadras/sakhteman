@extends('admin.layouts.app')

@section('title', 'مشاهده پیام')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-mail-open-line"></i>پیام {{ $message->name }}</h1>
        <p><a href="{{ route('admin.messages.index') }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به پیام‌ها</a></p>
    </div>
    <div class="toolbar">
        @if ($message->phone)
            <a href="tel:{{ $message->phone }}" class="btn btn-primary"><i class="ri-phone-line"></i>تماس</a>
        @endif
        @if ($message->email)
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('پاسخ: '.($message->subject ?? '')) }}" class="btn btn-light"><i class="ri-reply-line"></i>پاسخ با ایمیل</a>
        @endif
        @can('messages.delete')
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="این پیام حذف شود؟">
            @csrf @method('DELETE')
            <button class="btn btn-danger" type="submit"><i class="ri-delete-bin-6-line"></i>حذف</button>
        </form>
        @endcan
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card__head"><h2 class="card__title"><i class="ri-chat-3-line"></i>{{ $message->subject ?: 'بدون موضوع' }}</h2></div>
        <div class="card__body" style="white-space:pre-line;line-height:2.1;font-size:15.5px">{{ $message->body }}</div>
    </div>
    <div class="card">
        <div class="card__head"><h2 class="card__title"><i class="ri-user-3-line"></i>اطلاعات فرستنده</h2></div>
        <div class="list-item"><i class="ri-user-line"></i><div class="list-item__body"><small>نام</small><strong>{{ $message->name }}</strong></div></div>
        <div class="list-item"><i class="ri-phone-line"></i><div class="list-item__body"><small>تلفن</small><strong dir="ltr" style="text-align:right">{{ fa_num($message->phone ?: '—') }}</strong></div></div>
        <div class="list-item"><i class="ri-mail-line"></i><div class="list-item__body"><small>ایمیل</small><strong>{{ $message->email ?: '—' }}</strong></div></div>
        <div class="list-item"><i class="ri-calendar-line"></i><div class="list-item__body"><small>تاریخ ارسال</small><strong>{{ jdate($message->created_at, 'l j F Y - ساعت H:i') }}</strong></div></div>
        <div class="list-item"><i class="ri-global-line"></i><div class="list-item__body"><small>آی‌پی</small><strong dir="ltr" style="text-align:right">{{ $message->ip ?: '—' }}</strong></div></div>
    </div>
</div>
@endsection
