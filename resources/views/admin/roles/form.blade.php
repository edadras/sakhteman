@extends('admin.layouts.app')

@php
    $me = auth()->user();
    $granted = collect(old('permissions', $role->permissions ?? []));
@endphp

@section('title', $role->exists ? 'ویرایش نقش' : 'نقش جدید')

@section('content')
<div class="page-head">
    <div>
        <h1><i class="ri-shield-keyhole-line"></i>{{ $role->exists ? 'ویرایش نقش «'.$role->name.'»' : 'نقش جدید' }}</h1>
        <p><a href="{{ route('admin.roles.index') }}" class="text-muted"><i class="ri-arrow-right-line"></i> بازگشت به نقش‌ها</a></p>
    </div>
</div>

<form method="POST" data-once action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}" data-perm-form>
    @csrf
    @if ($role->exists) @method('PUT') @endif

    <div class="card">
        <div class="card__body">
            <div class="form-grid">
                <div class="form-group col-half">
                    <label class="form-label" for="r_name">نام نقش <span class="req">*</span></label>
                    <input class="form-control" id="r_name" name="name" value="{{ old('name', $role->name) }}" required placeholder="مثلا: ویرایشگر محتوا">
                </div>
                <div class="form-group col-half">
                    <label class="form-label" for="r_desc">توضیح کوتاه</label>
                    <input class="form-control" id="r_desc" name="description" value="{{ old('description', $role->description) }}" placeholder="مثلا: مدیریت مقالات و پروژه‌ها">
                </div>
            </div>
        </div>
    </div>

    @if ($role->is_super)
        <div class="card perm-super">
            <i class="ri-vip-crown-2-line"></i>
            <div>
                <strong>این نقش دسترسی کامل دارد</strong>
                <p>مدیر کل به همه بخش‌های فعلی و بخش‌هایی که در آینده اضافه شوند دسترسی دارد و دسترسی‌هایش قابل محدود کردن نیست.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card__head">
                <h2 class="card__title"><i class="ri-key-2-line"></i>دسترسی‌ها</h2>
                <div class="toolbar">
                    <span class="badge badge-primary" data-perm-count>۰</span>
                    <button type="button" class="btn btn-light btn-sm" data-perm-all="1"><i class="ri-checkbox-multiple-line"></i>انتخاب همه</button>
                    <button type="button" class="btn btn-light btn-sm" data-perm-all="0"><i class="ri-checkbox-blank-line"></i>هیچ‌کدام</button>
                </div>
            </div>
            @foreach ($sections as $group => $items)
                <div class="perm-group">
                    <div class="perm-group__head">
                        <strong>{{ $group }}</strong>
                        <label class="perm-check"><input type="checkbox" data-perm-group> همه این گروه</label>
                    </div>
                    <div class="table-wrap">
                        <table class="table perm-table">
                            <thead>
                                <tr>
                                    <th>بخش</th>
                                    @foreach (\App\Admin\Permissions::ACTIONS as $action => $label)<th>{{ $label }}</th>@endforeach
                                    <th>همه</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $section => $def)
                                    <tr data-perm-row>
                                        <td><i class="{{ $def['icon'] }}"></i> {{ $def['label'] }}</td>
                                        @foreach (\App\Admin\Permissions::ACTIONS as $action => $label)
                                            <td>
                                                @if (in_array($action, $def['actions'], true))
                                                    @php $key = $section.'.'.$action; $allowed = $me->hasPermission($key); @endphp
                                                    <label class="perm-check" title="{{ $allowed ? $label.' '.$def['label'] : 'شما این دسترسی را ندارید' }}">
                                                        <input type="checkbox" name="permissions[]" value="{{ $key }}" data-action="{{ $action }}" @checked($granted->contains($key)) @disabled(! $allowed)>
                                                    </label>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td><label class="perm-check"><input type="checkbox" data-perm-rowall aria-label="همه دسترسی‌های {{ $def['label'] }}"></label></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            <div class="card__body form-help"><i class="ri-information-line"></i> با انتخاب افزودن، ویرایش یا حذف، «مشاهده» همان بخش هم خودکار فعال می‌شود. داشبورد و حساب کاربری شخصی برای همه کاربران پنل در دسترس است.</div>
        </div>
    @endif

    <div class="card">
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i>ذخیره نقش</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-light" style="margin-right:auto">انصراف</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.querySelector('[data-perm-form]');
    if (!form || !form.querySelector('[data-perm-row]')) return;
    const boxes = () => [...form.querySelectorAll('input[name="permissions[]"]:not(:disabled)')];
    const fa = (n) => String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]);
    const sync = () => {
        form.querySelectorAll('[data-perm-row]').forEach((row) => {
            const cb = [...row.querySelectorAll('input[name="permissions[]"]:not(:disabled)')];
            const all = row.querySelector('[data-perm-rowall]');
            all.checked = cb.length > 0 && cb.every((c) => c.checked);
            all.indeterminate = !all.checked && cb.some((c) => c.checked);
            all.disabled = cb.length === 0;
        });
        form.querySelectorAll('.perm-group').forEach((g) => {
            const cb = [...g.querySelectorAll('input[name="permissions[]"]:not(:disabled)')];
            const all = g.querySelector('[data-perm-group]');
            all.checked = cb.length > 0 && cb.every((c) => c.checked);
            all.indeterminate = !all.checked && cb.some((c) => c.checked);
        });
        form.querySelector('[data-perm-count]').textContent = fa(form.querySelectorAll('input[name="permissions[]"]:checked').length) + ' دسترسی انتخاب شده';
    };
    form.addEventListener('change', (e) => {
        const t = e.target;
        const row = t.closest('[data-perm-row]');
        if (t.matches('[data-perm-rowall]')) {
            row.querySelectorAll('input[name="permissions[]"]:not(:disabled)').forEach((c) => { c.checked = t.checked; });
        } else if (t.matches('[data-perm-group]')) {
            t.closest('.perm-group').querySelectorAll('input[name="permissions[]"]:not(:disabled)').forEach((c) => { c.checked = t.checked; });
        } else if (t.name === 'permissions[]' && row) {
            const view = row.querySelector('[data-action="view"]');
            // عمل بدون مشاهده ممکن نیست؛ برداشتن مشاهده بقیه را هم برمی‌دارد
            if (t.checked && t.dataset.action !== 'view' && view && !view.disabled) view.checked = true;
            if (!t.checked && t.dataset.action === 'view') row.querySelectorAll('input[name="permissions[]"]:not(:disabled)').forEach((c) => { c.checked = false; });
        }
        sync();
    });
    form.querySelectorAll('[data-perm-all]').forEach((b) => b.addEventListener('click', () => { boxes().forEach((c) => { c.checked = b.dataset.permAll === '1'; }); sync(); }));
    sync();
})();
</script>
@endpush
