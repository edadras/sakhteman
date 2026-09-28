@extends('admin.layouts.app')

@section('title', $def['label'])

@section('content')
<div class="page-head">
    <div>
        <h1><i class="{{ $def['icon'] }}"></i>{{ $def['label'] }}</h1>
        <p>{{ fa_num($items->total()) }} مورد ثبت شده</p>
    </div>
    <a href="{{ route('admin.resources.create', $def['key']) }}" class="btn btn-primary"><i class="ri-add-line"></i>افزودن {{ $def['singular'] }}</a>
</div>

<div class="card">
    <div class="card__head">
        <form class="toolbar" method="GET">
            @if (! empty($def['search']))
                <div class="search-box">
                    <i class="ri-search-line"></i>
                    <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="جستجو...">
                </div>
            @endif
            @foreach ($def['filters'] ?? [] as $filter => $label)
                <select class="form-control" name="{{ $filter }}" onchange="this.form.submit()">
                    <option value="">{{ $label }}: همه</option>
                    @foreach ($def['fields'][$filter]['options'] ?? [] as $value => $text)
                        <option value="{{ $value }}" @selected((string) request($filter) === (string) $value)>{{ $text }}</option>
                    @endforeach
                </select>
            @endforeach
            @if (! empty($def['search']))
                <button class="btn btn-light" type="submit"><i class="ri-filter-3-line"></i>اعمال</button>
            @endif
            @if (request()->hasAny(array_merge(['q'], array_keys($def['filters'] ?? []))))
                <a href="{{ route('admin.resources.index', $def['key']) }}" class="btn btn-light"><i class="ri-close-line"></i>حذف فیلتر</a>
            @endif
        </form>

        <form id="bulkForm" action="{{ route('admin.resources.bulk-destroy', $def['key']) }}" method="POST" data-confirm="موارد انتخاب شده حذف شوند؟">
            @csrf
            <div id="bulkBar" hidden>
                <button class="btn btn-danger btn-sm" type="submit"><i class="ri-delete-bin-6-line"></i>حذف <span class="bulk-count">۰</span> مورد</button>
            </div>
        </form>
    </div>

    @if ($items->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:36px"><input type="checkbox" id="checkAll" aria-label="انتخاب همه"></th>
                        @foreach ($def['columns'] as $col)
                            <th>{{ $col['label'] }}</th>
                        @endforeach
                        <th style="text-align:left">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><input type="checkbox" class="row-check" value="{{ $item->id }}" aria-label="انتخاب"></td>
                            @foreach ($def['columns'] as $name => $col)
                                @php $value = data_get($item, $name); $type = $col['type'] ?? 'text'; @endphp
                                <td @class(['title-cell' => $loop->index === 0 && $type === 'text' || $name === 'title' || $name === 'name' || $name === 'question'])>
                                    @switch($type)
                                        @case('image')
                                            <img class="thumb" src="{{ media_url($value) }}" alt="" loading="lazy">
                                            @break
                                        @case('icon')
                                            <span class="icon-cell"><i class="{{ $value ?: 'ri-question-line' }}"></i></span>
                                            @break
                                        @case('toggle')
                                            <form class="toggle-form" action="{{ route('admin.resources.toggle', [$def['key'], $item->id, $name]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="badge toggle-badge {{ $value ? 'badge-success' : 'badge-muted' }}" title="کلیک برای تغییر">
                                                    @if ($value)<i class="ri-checkbox-circle-fill"></i> فعال @else<i class="ri-close-circle-line"></i> غیرفعال @endif
                                                </button>
                                            </form>
                                            @break
                                        @case('badge')
                                            <span class="badge badge-primary">{{ $value }}</span>
                                            @break
                                        @case('date')
                                            {{ $value ? jdate($value) : '—' }}
                                            @break
                                        @case('price')
                                            {!! $value ? fa_num(number_format($value)).' <small class="text-muted">تومان</small>' : '<span class="text-muted">—</span>' !!}
                                            @break
                                        @case('number')
                                            {{ fa_num($value ?? 0) }}
                                            @break
                                        @default
                                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $value), 70) ?: '—' }}
                                    @endswitch
                                </td>
                            @endforeach
                            <td>
                                <div class="actions">
                                    @if (in_array($def['key'], ['services', 'projects', 'posts', 'products']) && $item->slug)
                                        <a class="btn btn-light btn-sm btn-icon" href="{{ $item->url }}" target="_blank" title="مشاهده در سایت"><i class="ri-eye-line"></i></a>
                                    @endif
                                    <a class="btn btn-light btn-sm" href="{{ route('admin.resources.edit', [$def['key'], $item->id]) }}"><i class="ri-edit-line"></i>ویرایش</a>
                                    <form action="{{ route('admin.resources.destroy', [$def['key'], $item->id]) }}" method="POST" data-confirm="«{{ \Illuminate\Support\Str::limit(strip_tags((string) ($item->title ?? $item->name ?? $item->question)), 40) }}» حذف شود؟">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm btn-icon" type="submit" title="حذف"><i class="ri-delete-bin-6-line"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $items->links('admin.partials.pagination') }}
    @else
        <div class="empty">
            <i class="{{ $def['icon'] }}"></i>
            <p>موردی یافت نشد.</p>
            <a href="{{ route('admin.resources.create', $def['key']) }}" class="btn btn-primary"><i class="ri-add-line"></i>افزودن اولین {{ $def['singular'] }}</a>
        </div>
    @endif
</div>
@endsection
