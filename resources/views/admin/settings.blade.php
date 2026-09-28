@extends('admin.layouts.app')

@section('title', 'تنظیمات سایت')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/quill/quill.snow.css') }}">
@endpush

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-settings-4-line"></i>تنظیمات سایت</h1>
        <p>اطلاعات عمومی، تماس، شبکه‌های اجتماعی و متن‌های صفحات را از این بخش ویرایش کنید.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" data-tabs data-once data-dirty-check>
    @csrf @method('PUT')
    <input type="hidden" name="_tab" value="">

    <div class="tabs">
        @foreach ($groups as $key => $group)
            <button type="button" data-tab="{{ $key }}"><i class="{{ $group['icon'] }}"></i>{{ $group['label'] }}</button>
        @endforeach
    </div>

    <div class="card">
        <div class="card__body">
            @foreach ($groups as $key => $group)
                <div class="tab-panel" id="tab-{{ $key }}">
                    <div class="form-grid">
                        @foreach ($group['fields'] as $name => $field)
                            @include('admin.partials.field', ['name' => $name, 'field' => $field, 'value' => $values[$name] ?? null])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i>ذخیره تنظیمات</button>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-light"><i class="ri-eye-line"></i>مشاهده سایت</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
@endpush
