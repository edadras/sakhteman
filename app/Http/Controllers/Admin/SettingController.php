<?php

namespace App\Http\Controllers\Admin;

use App\Admin\SettingFields;
use App\Http\Controllers\Controller;
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
            $rules[$key] = $field['type'] === 'image'
                ? 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg,ico,avif|max:6144'
                : 'nullable|string|max:20000';
        }
        $request->validate($rules, [], collect($fields)->map(fn ($f) => $f['label'])->all());

        $current = Setting::allCached();

        foreach ($fields as $key => $field) {
            if ($field['type'] === 'image') {
                if ($request->hasFile($key)) {
                    $this->deleteFile($current[$key] ?? null);
                    Setting::put($key, $request->file($key)->store('uploads/settings', 'public'));
                } elseif ($request->boolean($key.'_remove')) {
                    $this->deleteFile($current[$key] ?? null);
                    Setting::put($key, null);
                }
                continue;
            }

            if ($request->has($key)) {
                Setting::put($key, $request->input($key));
            }
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
