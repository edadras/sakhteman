{{-- یک فیلد فرم بر اساس تعریف: $name, $field, $value --}}
@php
    $type = $field['type'] ?? 'text';
    $col = $field['col'] ?? 'full';
    $id = 'f_'.$name;
    $required = str_contains($field['rules'] ?? '', 'required');
    $dir = $field['dir'] ?? null;
@endphp
<div class="form-group {{ $col === 'half' ? 'col-half' : ($col === 'third' ? 'col-third' : '') }}">
    @if ($type !== 'toggle')
        <label class="form-label" for="{{ $id }}">{{ $field['label'] }} @if ($required)<span class="req">*</span>@endif</label>
    @endif

    @switch($type)
        @case('textarea')
            <textarea class="form-control @error($name) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" rows="4" @if ($dir) dir="{{ $dir }}" @endif>{{ old($name, $value) }}</textarea>
            @break

        @case('editor')
            <div class="editor-wrap">
                <textarea id="{{ $id }}" name="{{ $name }}" data-editor>{{ old($name, $value) }}</textarea>
            </div>
            @break

        @case('number')
            <input class="form-control @error($name) is-invalid @enderror" type="number" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}" min="0">
            @break

        @case('select')
            <select class="form-control @error($name) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}">
                @unless ($required)<option value="">— انتخاب کنید —</option>@endunless
                @foreach ($field['options'] ?? [] as $optValue => $optLabel)
                    <option value="{{ $optValue }}" @selected((string) old($name, $value) === (string) $optValue)>{{ $optLabel }}</option>
                @endforeach
            </select>
            @break

        @case('toggle')
            <label class="form-label">&nbsp;</label>
            <label class="switch">
                <input type="hidden" name="{{ $name }}" value="0">
                <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $value))>
                <span class="switch__track"></span>
                <span>{{ $field['label'] }}</span>
            </label>
            @break

        @case('icon')
            <div class="input-icon" style="position:relative">
                <i class="ri-palette-line"></i>
                <input class="form-control @error($name) is-invalid @enderror" type="text" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}" dir="ltr" style="text-align:right;padding-left:50px" placeholder="ri-building-2-line" data-icon-input>
                <span class="icon-preview"><i class="{{ old($name, $value) ?: 'ri-question-line' }}"></i></span>
            </div>
            <div class="form-help">نام آیکن از <a href="https://remixicon.com" target="_blank" rel="noopener" style="color:var(--a-primary)">remixicon.com</a> — مثلا <code dir="ltr">ri-home-4-line</code></div>
            @break

        @case('image')
            <div class="upload">
                <div class="upload__preview">
                    @if ($value)<img src="{{ media_url($value) }}" alt="">@else<i class="ri-image-add-line"></i>@endif
                </div>
                <div class="upload__body">
                    <strong><i class="ri-upload-cloud-2-line"></i> انتخاب یا رها کردن تصویر</strong>
                    <small>JPG، PNG، WEBP یا SVG — حداکثر ۶ مگابایت</small>
                    @if ($value)
                        <label class="upload__remove"><input type="checkbox" name="{{ $name }}_remove" value="1"> حذف تصویر فعلی</label>
                    @endif
                </div>
                <input type="file" id="{{ $id }}" name="{{ $name }}" accept="image/*">
            </div>
            @break

        @case('gallery')
            @if (! empty($value))
                <div class="gallery-admin">
                    @foreach ((array) $value as $img)
                        <label title="برای حذف انتخاب کنید">
                            <input type="checkbox" name="{{ $name }}_remove[]" value="{{ $img }}">
                            <img src="{{ media_url($img) }}" alt="">
                            <span><i class="ri-delete-bin-line"></i></span>
                        </label>
                    @endforeach
                </div>
                <div class="form-help" style="margin:-4px 0 10px">برای حذف، روی تصویر کلیک کنید.</div>
            @endif
            <div class="upload">
                <div class="upload__preview"><i class="ri-gallery-upload-line"></i></div>
                <div class="upload__body">
                    <strong><i class="ri-upload-cloud-2-line"></i> افزودن تصاویر جدید</strong>
                    <small>امکان انتخاب چند تصویر به صورت همزمان</small>
                </div>
                <input type="file" id="{{ $id }}" name="{{ $name }}_new[]" accept="image/*" multiple>
            </div>
            @break

        @default
            <input class="form-control @error($name) is-invalid @enderror" type="text" id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}"
                   @if ($dir) dir="{{ $dir }}" style="text-align:right" @endif
                   @if (! empty($slugSource) && $slugSource === $name) data-slug-source @endif
                   @if ($name === 'slug') placeholder="خودکار" @endif>
    @endswitch

    @if (! empty($field['help']) && $type !== 'icon')
        <div class="form-help">{{ $field['help'] }}</div>
    @endif
    @error($name)<div class="form-error">{{ $message }}</div>@enderror
</div>
