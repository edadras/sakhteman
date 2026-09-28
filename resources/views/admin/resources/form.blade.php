@extends('admin.layouts.app')

@section('title', ($item->exists ? 'ویرایش ' : 'افزودن ').$def['singular'])

@push('head')
    @if (collect($def['fields'])->contains(fn ($f) => ($f['type'] ?? null) === 'editor'))
        <link rel="stylesheet" href="{{ asset('assets/vendor/quill/quill.snow.css') }}">
    @endif
@endpush

@section('content')
<div class="page-head">
    <div>
        <h1><i class="{{ $def['icon'] }}"></i>{{ $item->exists ? 'ویرایش '.$def['singular'] : 'افزودن '.$def['singular'].' جدید' }}</h1>
        <p><a href="{{ route('admin.resources.index', $def['key']) }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به {{ $def['label'] }}</a></p>
    </div>
    @if ($item->exists && in_array($def['key'], ['services', 'projects', 'posts', 'products']))
        <a href="{{ $item->url }}" target="_blank" class="btn btn-light"><i class="ri-eye-line"></i>مشاهده در سایت</a>
    @endif
</div>

<form class="card" method="POST" enctype="multipart/form-data" data-once data-dirty-check
      action="{{ $item->exists ? route('admin.resources.update', [$def['key'], $item->id]) : route('admin.resources.store', $def['key']) }}">
    @csrf
    @if ($item->exists) @method('PUT') @endif

    <div class="card__body">
        <div class="form-grid">
            @foreach ($def['fields'] as $name => $field)
                @include('admin.partials.field', [
                    'name' => $name,
                    'field' => $field,
                    'value' => $item->{$name},
                    'slugSource' => $def['slug'] ?? null,
                ])
            @endforeach
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary" name="_after" value="index"><i class="ri-save-3-line"></i>ذخیره</button>
        <button type="submit" class="btn btn-light" name="_after" value="continue"><i class="ri-save-line"></i>ذخیره و ادامه ویرایش</button>
        @unless ($item->exists)
            <button type="submit" class="btn btn-light" name="_after" value="new"><i class="ri-add-circle-line"></i>ذخیره و افزودن بعدی</button>
        @endunless
        <a href="{{ route('admin.resources.index', $def['key']) }}" class="btn btn-light" style="margin-right:auto">انصراف</a>
    </div>
</form>
@endsection

@push('scripts')
    @if (collect($def['fields'])->contains(fn ($f) => ($f['type'] ?? null) === 'editor'))
        <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    @endif
@endpush
