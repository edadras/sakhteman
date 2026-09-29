<?php

namespace App\Http\Controllers\Admin;

use App\Admin\SettingFields;
use App\Http\Controllers\Controller;
use App\Support\Activity;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'groups' => SettingFields::groups(),
            'values' => Setting::allCached(),
        ]);
    }

    public function update(Request $request)
    {
        $fields = SettingFields::flat();

        $rules = [];
        foreach ($fields as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg,ico,avif|max:6144',
                'file' => 'nullable|file|mimes:pdf,zip,rar,jpg,jpeg,png,webp|max:30720',
                default => 'nullable|string|max:20000',
            };
        }
        $request->validate($rules, [], collect($fields)->map(fn ($f) => $f['label'])->all());

        $current = Setting::allCached();
        $changed = [];

        foreach ($fields as $key => $field) {
            if (in_array($field['type'], ['image', 'file'], true)) {
                if ($request->hasFile($key)) {
                    $changed[] = $field['label'];
                    $this->deleteFile($current[$key] ?? null);
                    Setting::put($key, $request->file($key)->store('uploads/settings', 'public'));
                } elseif ($request->boolean($key.'_remove')) {
                    $changed[] = $field['label'];
                    $this->deleteFile($current[$key] ?? null);
                    Setting::put($key, null);
                }
                continue;
            }

            if ($request->has($key)) {
                if ((string) ($current[$key] ?? '') !== (string) $request->input($key)) {
                    $changed[] = $field['label'];
                }
                Setting::put($key, $request->input($key));
            }
        }

        if ($changed) {
            Activity::log('update', 'settings', implode('، ', array_slice($changed, 0, 6)).(count($changed) > 6 ? ' و '.fa_num(count($changed) - 6).' مورد دیگر' : ''));
        }

        return back()->with('success', 'تنظیمات با موفقیت ذخیره شد.')->withFragment($request->input('_tab', ''));
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://', '//', 'assets/'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
