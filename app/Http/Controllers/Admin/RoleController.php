<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Permissions;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Support\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * نقش‌ها و دسترسی‌های پنل مدیریت
 */
class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->orderByDesc('is_super')->orderBy('name')->get();

        return view('admin.roles.index', ['roles' => $roles, 'total' => count(Permissions::keys())]);
    }

    public function create(Request $request)
    {
        $role = new Role(['permissions' => []]);

        // کپی دسترسی‌ها از یک نقش دیگر
        if ($from = Role::find($request->query('copy'))) {
            $role->permissions = $from->is_super ? Permissions::keys() : $from->permissions;
            $role->name = $from->name.' (کپی)';
        }

        return view('admin.roles.form', ['role' => $role, 'sections' => Permissions::sections()]);
    }

    public function store(Request $request)
    {
        $role = Role::create($this->validated($request, new Role));
        Activity::log('create', 'roles', $role->name.' — '.fa_num(count($role->permissions)).' دسترسی');

        return redirect()->route('admin.roles.index')->with('success', 'نقش «'.$role->name.'» ساخته شد.');
    }

    public function edit(Role $role)
    {
        $this->guard($role);

        return view('admin.roles.form', ['role' => $role, 'sections' => Permissions::sections()]);
    }

    public function update(Request $request, Role $role)
    {
        $this->guard($role);
        $data = $this->validated($request, $role);

        // دسترسی‌های نقش مدیر کل همیشه کامل است
        if ($role->is_super) {
            abort_unless($request->user()->isSuperAdmin(), 403);
            $data = ['name' => $data['name'], 'description' => $data['description']];
        } elseif ($role->is($request->user()->role) && ! $request->user()->isSuperAdmin()) {
            return back()->withInput()->with('error', 'نمی‌توانید دسترسی‌های نقش خودتان را تغییر دهید.');
        }

        $role->update($data);
        Activity::log('update', 'roles', $role->name.($role->is_super ? '' : ' — '.fa_num(count($role->permissions ?? [])).' دسترسی'));

        return redirect()->route('admin.roles.index')->with('success', 'نقش «'.$role->name.'» ذخیره شد.');
    }

    public function destroy(Role $role)
    {
        $this->guard($role);
        if ($role->is_super) {
            return back()->with('error', 'نقش مدیر کل قابل حذف نیست.');
        }
        if ($count = $role->users()->count()) {
            return back()->with('error', 'این نقش به '.fa_num($count).' کاربر داده شده است؛ ابتدا نقش آن‌ها را تغییر دهید.');
        }

        Activity::log('delete', 'roles', $role->name);
        $role->delete();

        return back()->with('success', 'نقش حذف شد.');
    }

    /**
     * فقط مدیر کل نقش‌هایی را تغییر می‌دهد که دسترسی بیشتری از کاربر فعلی دارند.
     */
    protected function guard(Role $role): void
    {
        $me = request()->user();
        if ($me->isSuperAdmin()) {
            return;
        }
        abort_if($role->is_super, 403);
        abort_if(collect($role->permissions ?? [])->contains(fn ($p) => ! $me->hasPermission($p)), 403);
    }

    protected function validated(Request $request, Role $role): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('roles', 'name')->ignore($role->id)],
            'description' => ['nullable', 'string', 'max:190'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(Permissions::keys())],
        ], [], ['name' => 'نام نقش', 'description' => 'توضیحات', 'permissions' => 'دسترسی‌ها']);

        $me = $request->user();
        $perms = collect($data['permissions'] ?? [])->unique();

        // کسی نمی‌تواند دسترسی‌ای بدهد که خودش ندارد
        $perms = $perms->filter(fn ($p) => $me->hasPermission($p));

        // هر عملی (افزودن/ویرایش/حذف) بدون «مشاهده» همان بخش معنا ندارد
        foreach ($perms->all() as $p) {
            [$section] = explode('.', $p);
            if ($me->hasPermission($section.'.view') && in_array($section.'.view', Permissions::keys(), true)) {
                $perms->push($section.'.view');
            }
        }
        $data['permissions'] = $perms->unique()->values()->all();

        return $data;
    }
}
