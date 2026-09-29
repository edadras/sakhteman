<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Resources;
use App\Http\Controllers\Controller;
use App\Support\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    protected function definition(string $resource, string $action = 'view'): array
    {
        $def = Resources::find($resource);
        abort_if(! $def, 404);
        abort_unless(request()->user()->hasPermission($resource.'.'.$action), 403);

        foreach ($def['fields'] as $name => $field) {
            if (isset($field['options']) && $field['options'] instanceof \Closure) {
                $def['fields'][$name]['options'] = ($field['options'])();
            }
        }

        return $def;
    }

    /**
     * عنوان خوانای یک رکورد برای گزارش فعالیت
     */
    protected function label(Model $item): string
    {
        return (string) ($item->title ?? $item->name ?? $item->question ?? $item->label ?? '#'.$item->getKey());
    }

    protected function find(array $def, int|string $id): Model
    {
        return $def['model']::findOrFail($id);
    }

    public function index(Request $request, string $resource)
    {
        $def = $this->definition($resource);
        $query = $def['model']::query()->with($def['with'] ?? []);

        if (! empty($def['counts'])) {
            $query->withCount($def['counts']);
        }

        if (($term = trim((string) $request->query('q'))) !== '' && ! empty($def['search'])) {
            $query->where(function ($q) use ($def, $term) {
                foreach ($def['search'] as $column) {
                    $q->orWhere($column, 'like', '%'.$term.'%');
                }
            });
        }

        foreach (array_keys($def['filters'] ?? []) as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->query($filter));
            }
        }

        [$orderColumn, $orderDir] = $def['order'] ?? ['sort', 'asc'];
        $query->orderBy($orderColumn, $orderDir)->orderByDesc('id');

        $items = $query->paginate(15)->withQueryString();

        return view('admin.resources.index', compact('def', 'items'));
    }

    public function create(string $resource)
    {
        $def = $this->definition($resource, 'create');
        $item = new $def['model'];

        foreach ($def['fields'] as $name => $field) {
            if (array_key_exists('default', $field)) {
                $item->{$name} = $field['default'];
            }
        }

        return view('admin.resources.form', compact('def', 'item'));
    }

    public function store(Request $request, string $resource)
    {
        $def = $this->definition($resource, 'create');
        $item = new $def['model'];

        $item->fill($this->payload($request, $def, $item))->save();
        Activity::log('create', $def['key'], $this->label($item));

        return $this->redirectAfterSave($request, $def, $item, $def['singular'].' با موفقیت اضافه شد.');
    }

    public function edit(string $resource, int $id)
    {
        $def = $this->definition($resource, 'edit');
        $item = $this->find($def, $id);

        return view('admin.resources.form', compact('def', 'item'));
    }

    public function update(Request $request, string $resource, int $id)
    {
        $def = $this->definition($resource, 'edit');
        $item = $this->find($def, $id);

        $item->fill($this->payload($request, $def, $item))->save();
        Activity::log('update', $def['key'], $this->label($item));

        return $this->redirectAfterSave($request, $def, $item, 'تغییرات با موفقیت ذخیره شد.');
    }

    public function destroy(string $resource, int $id)
    {
        $def = $this->definition($resource, 'delete');
        $item = $this->find($def, $id);
        Activity::log('delete', $def['key'], $this->label($item));
        $this->deleteItem($def, $item);

        return back()->with('success', $def['singular'].' حذف شد.');
    }

    public function bulkDestroy(Request $request, string $resource)
    {
        $def = $this->definition($resource, 'delete');
        $ids = array_filter((array) $request->input('ids'), 'is_numeric');

        $def['model']::whereIn('id', $ids)->get()->each(function ($item) use ($def) {
            Activity::log('delete', $def['key'], $this->label($item));
            $this->deleteItem($def, $item);
        });

        return back()->with('success', fa_num(count($ids)).' مورد حذف شد.');
    }

    public function toggle(string $resource, int $id, string $field)
    {
        $def = $this->definition($resource, 'edit');
        abort_unless(($def['fields'][$field]['type'] ?? null) === 'toggle', 404);

        $item = $this->find($def, $id);
        $item->{$field} = ! $item->{$field};
        $item->save();
        Activity::log('update', $def['key'], $this->label($item).' — '.($def['fields'][$field]['label'] ?? $field).': '.($item->{$field} ? 'روشن' : 'خاموش'));

        if (request()->expectsJson()) {
            return response()->json(['value' => (bool) $item->{$field}]);
        }

        return back()->with('success', 'وضعیت به‌روزرسانی شد.');
    }

    /**
     * اعتبارسنجی و آماده‌سازی داده‌های فرم.
     */
    protected function payload(Request $request, array $def, Model $item): array
    {
        // اعداد فارسی و جداکننده هزارگان را قبل از اعتبارسنجی حذف می‌کنیم
        foreach ($def['fields'] as $name => $field) {
            if (in_array($field['type'] ?? null, ['number', 'price'], true) && $request->filled($name)) {
                $request->merge([$name => preg_replace('/[^\d]/', '', en_num((string) $request->input($name)))]);
            } elseif (str_contains($field['rules'] ?? '', 'numeric') && $request->filled($name)) {
                // اعداد اعشاری مثل مختصات جغرافیایی (پذیرش ارقام و ممیز فارسی)
                $request->merge([$name => str_replace(['٫', '/', '،', ','], '.', trim(en_num((string) $request->input($name))))]);
            }
        }

        $rules = [];
        foreach ($def['fields'] as $name => $field) {
            $type = $field['type'] ?? 'text';
            if ($type === 'image') {
                $rules[$name] = 'nullable|file|mimes:jpg,jpeg,png,webp,gif,svg,avif|max:6144';
            } elseif ($type === 'file') {
                $rules[$name] = $field['rules'] ?? 'nullable|file|mimes:pdf,zip,rar,jpg,jpeg,png,webp|max:30720';
            } elseif ($type === 'gallery') {
                $rules[$name.'_new'] = 'nullable|array';
                $rules[$name.'_new.*'] = 'file|mimes:jpg,jpeg,png,webp,gif,avif|max:6144';
                $rules[$name.'_remove'] = 'nullable|array';
            } elseif ($type === 'toggle') {
                continue;
            } else {
                $rules[$name] = $field['rules'] ?? 'nullable|string';
            }
        }

        $labels = collect($def['fields'])->mapWithKeys(fn ($f, $n) => [$n => $f['label']])->all();
        $request->validate($rules, [], $labels);

        $data = [];
        foreach ($def['fields'] as $name => $field) {
            $type = $field['type'] ?? 'text';
            $folder = 'uploads/'.$def['key'];

            switch ($type) {
                case 'toggle':
                    $data[$name] = $request->boolean($name);
                    break;

                case 'image':
                case 'file':
                    if ($request->hasFile($name)) {
                        $this->deleteFile($item->{$name});
                        $data[$name] = $request->file($name)->store($folder, 'public');
                    } elseif ($request->boolean($name.'_remove')) {
                        $this->deleteFile($item->{$name});
                        $data[$name] = null;
                    }
                    break;

                case 'gallery':
                    $current = (array) ($item->{$name} ?? []);
                    $remove = (array) $request->input($name.'_remove', []);
                    foreach ($remove as $path) {
                        if (in_array($path, $current, true)) {
                            $this->deleteFile($path);
                        }
                    }
                    $current = array_values(array_diff($current, $remove));
                    foreach ((array) $request->file($name.'_new', []) as $file) {
                        $current[] = $file->store($folder, 'public');
                    }
                    $data[$name] = $current;
                    break;

                case 'number':
                case 'price':
                    $raw = preg_replace('/[^\d]/', '', en_num((string) $request->input($name)));
                    $data[$name] = $raw !== '' ? (int) $raw : ($field['default'] ?? null);
                    break;

                case 'select':
                    $data[$name] = $request->filled($name) ? $request->input($name) : null;
                    break;

                default:
                    $data[$name] = $request->input($name);
            }
        }

        if ($source = $def['slug'] ?? null) {
            $data['slug'] = $this->uniqueSlug($def['model'], $data['slug'] ?? null ?: $data[$source], $item->getKey());
        }

        return $data;
    }

    protected function uniqueSlug(string $model, ?string $value, $ignoreId = null): string
    {
        $base = persian_slug($value);
        $slug = $base;
        $i = 2;

        while ($model::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    protected function deleteItem(array $def, Model $item): void
    {
        foreach ($def['fields'] as $name => $field) {
            if (in_array($field['type'] ?? null, ['image', 'file'], true)) {
                $this->deleteFile($item->{$name});
            }
            if (($field['type'] ?? null) === 'gallery') {
                foreach ((array) $item->{$name} as $path) {
                    $this->deleteFile($path);
                }
            }
        }

        $item->delete();
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://', '//', 'assets/'])) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function redirectAfterSave(Request $request, array $def, Model $item, string $message)
    {
        if ($request->input('_after') === 'continue') {
            return redirect()->route('admin.resources.edit', [$def['key'], $item->getKey()])->with('success', $message);
        }

        if ($request->input('_after') === 'new') {
            return redirect()->route('admin.resources.create', $def['key'])->with('success', $message);
        }

        return redirect()->route('admin.resources.index', $def['key'])->with('success', $message);
    }
}
