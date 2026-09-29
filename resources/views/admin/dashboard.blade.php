@extends('admin.layouts.app')

@section('title', 'داشبورد')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-hand-heart-line"></i>سلام، {{ auth()->user()->name }}</h1>
        <p>امروز {{ jdate(now(), 'l j F Y') }} — خلاصه وضعیت سایت را در ادامه می‌بینید.</p>
    </div>
    <div class="toolbar">
        @can('projects.create')<a href="{{ route('admin.resources.create', 'projects') }}" class="btn btn-primary"><i class="ri-add-line"></i>پروژه جدید</a>@endcan
        @can('posts.create')<a href="{{ route('admin.resources.create', 'posts') }}" class="btn btn-light"><i class="ri-quill-pen-line"></i>مقاله جدید</a>@endcan
    </div>
</div>

@if (count($cards))
<div class="stat-cards">
    @foreach ($cards as $card)
        <a href="{{ $card['route'] }}" class="card stat-card c-{{ $card['color'] }}">
            <span class="stat-card__icon"><i class="{{ $card['icon'] }}"></i></span>
            <div>
                <div class="stat-card__value">{{ fa_num(number_format($card['value'])) }}</div>
                <div class="stat-card__label">{{ $card['label'] }}</div>
            </div>
        </a>
    @endforeach
</div>
@endif

<div class="grid-2">
    @can('messages.view')
    <div class="card">
        <div class="card__head">
            <h2 class="card__title"><i class="ri-line-chart-line"></i>پیام‌های دریافتی ۱۴ روز اخیر</h2>
            <span class="badge badge-muted">مجموع: {{ fa_num($extra['messages']) }}</span>
        </div>
        <div class="card__body"><canvas id="msgChart" height="120"></canvas></div>
    </div>
    @endcan
    <div class="card">
        <div class="card__head"><h2 class="card__title"><i class="ri-flashlight-line"></i>دسترسی سریع</h2></div>
        <div class="card__body">
            <div class="quick-links">
                @can('projects.view')<a href="{{ route('admin.resources.index', 'projects') }}"><i class="ri-building-4-line"></i>پروژه‌ها</a>@endcan
                @can('posts.view')<a href="{{ route('admin.resources.index', 'posts') }}"><i class="ri-article-line"></i>مقالات</a>@endcan
                @can('products.view')<a href="{{ route('admin.resources.index', 'products') }}"><i class="ri-store-2-line"></i>محصولات</a>@endcan
                @can('orders.view')<a href="{{ route('admin.orders.index') }}"><i class="ri-shopping-cart-2-line"></i>سفارش‌ها</a>@endcan
                @can('slides.view')<a href="{{ route('admin.resources.index', 'slides') }}"><i class="ri-slideshow-3-line"></i>اسلایدر</a>@endcan
                @can('services.view')<a href="{{ route('admin.resources.index', 'services') }}"><i class="ri-tools-line"></i>خدمات</a>@endcan
                @can('team.view')<a href="{{ route('admin.resources.index', 'team') }}"><i class="ri-team-line"></i>تیم ({{ fa_num($extra['team']) }})</a>@endcan
                @can('testimonials.view')<a href="{{ route('admin.resources.index', 'testimonials') }}"><i class="ri-chat-quote-line"></i>نظرات ({{ fa_num($extra['testimonials']) }})</a>@endcan
                @can('settings.view')<a href="{{ route('admin.settings.edit') }}#contact"><i class="ri-phone-line"></i>اطلاعات تماس</a>
                <a href="{{ route('admin.settings.edit') }}#home"><i class="ri-home-5-line"></i>محتوای صفحه اصلی</a>@endcan
                @can('users.view')<a href="{{ route('admin.users.index') }}"><i class="ri-admin-line"></i>کاربران مدیر</a>@endcan
                <a href="{{ route('admin.profile.edit') }}"><i class="ri-user-settings-line"></i>حساب کاربری من</a>
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    @can('messages.view')
    <div class="card">
        <div class="card__head">
            <h2 class="card__title"><i class="ri-mail-line"></i>آخرین پیام‌ها</h2>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-light btn-sm">مشاهده همه</a>
        </div>
        @forelse ($latestMessages as $message)
            <a href="{{ route('admin.messages.show', $message) }}" class="list-item">
                <span class="avatar">{{ mb_substr($message->name, 0, 1) }}</span>
                <div class="list-item__body">
                    <strong>{{ $message->name }} @unless ($message->is_read)<span class="badge badge-primary">جدید</span>@endunless</strong>
                    <small>{{ $message->subject ?: \Illuminate\Support\Str::limit($message->body, 70) }}</small>
                </div>
                <small class="text-muted">{{ jdate($message->created_at, 'j F') }}</small>
            </a>
        @empty
            <div class="empty"><i class="ri-inbox-line"></i>هنوز پیامی دریافت نشده است.</div>
        @endforelse
    </div>
    @endcan

    @can('posts.view')
    <div class="card">
        <div class="card__head"><h2 class="card__title"><i class="ri-fire-line"></i>پربازدیدترین مقالات</h2></div>
        @forelse ($popularPosts as $post)
            <a href="{{ auth()->user()->hasPermission('posts.edit') ? route('admin.resources.edit', ['posts', $post->id]) : $post->url }}" class="list-item">
                <img src="{{ media_url($post->cover) }}" alt="">
                <div class="list-item__body">
                    <strong>{{ $post->title }}</strong>
                    <small><i class="ri-eye-line"></i> {{ fa_num(number_format($post->views)) }} بازدید</small>
                </div>
            </a>
        @empty
            <div class="empty"><i class="ri-article-line"></i>مقاله‌ای وجود ندارد.</div>
        @endforelse
    </div>
    @endcan
</div>

@can('projects.view')
<div class="card">
    <div class="card__head">
        <h2 class="card__title"><i class="ri-building-4-line"></i>آخرین پروژه‌ها</h2>
        <a href="{{ route('admin.resources.index', 'projects') }}" class="btn btn-light btn-sm">مدیریت پروژه‌ها</a>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>تصویر</th><th>عنوان</th><th>دسته</th><th>وضعیت</th><th>تاریخ ثبت</th><th></th></tr></thead>
            <tbody>
                @forelse ($latestProjects as $project)
                    <tr>
                        <td><img class="thumb" src="{{ media_url($project->cover) }}" alt=""></td>
                        <td class="title-cell">{{ $project->title }}</td>
                        <td>{{ $project->category?->name ?? '—' }}</td>
                        <td><span class="badge badge-primary">{{ $project->status_label }}</span></td>
                        <td>{{ jdate($project->created_at) }}</td>
                        <td><div class="actions">@can('projects.edit')<a class="btn btn-light btn-sm" href="{{ route('admin.resources.edit', ['projects', $project->id]) }}"><i class="ri-edit-line"></i>ویرایش</a>@endcan</div></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty">پروژه‌ای ثبت نشده است.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/chartjs/chart.umd.min.js') }}"></script>
<script>
    (function () {
        const el = document.getElementById('msgChart');
        if (!el || !window.Chart) return;
        const ctx = el.getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0, 'rgba(79,154,83,.35)');
        grad.addColorStop(1, 'rgba(79,154,83,0)');
        Chart.defaults.font.family = 'Vazirmatn';
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chart['labels']),
                datasets: [{ label: 'پیام', data: @json($chart['data']), borderColor: '#4f9a53', backgroundColor: grad, fill: true, tension: .4, pointRadius: 3, pointBackgroundColor: '#fbb12e' }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, callback: (v) => Number(v).toLocaleString('fa-IR') }, grid: { color: 'rgba(128,128,128,.12)' } },
                    x: { grid: { display: false }, reverse: true },
                },
            },
        });
    })();
</script>
@endpush
