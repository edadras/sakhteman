<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Support\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * مدیریت کاربران پنل (مدیران و همکاران)
 */
class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_admin', true)
            ->with('role')
            ->when($request->query('q'), function ($q, $t) {
                $t = en_num($t);
                $q->where(fn ($q) => $q->where('name', 'like', "%$t%")->orWhere('email', 'like', "%$t%")->orWhere('mobile', 'like', "%$t%"));
            })
            ->when($request->query('role'), fn ($q, $r) => $q->where('role_id', $r))
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', ['users' => $users, 'roles' => Role::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['is_active' => true]), 'roles' => $this->assignableRoles()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, new User);

        $user = new User;
        $user->fill($data);
        $user->forceFill(['is_admin' => true, 'role_id' => $data['role_id'], 'is_active' => $data['is_active']])->save();
        Activity::log('create', 'users', $user->name.' — نقش: '.$user->role?->name);

        return redirect()->route('admin.users.index')->with('success', 'کاربر «'.$user->name.'» اضافه شد.');
    }

    public function edit(User $user)
    {
        $this->guardTarget($user);

        return view('admin.users.form', ['user' => $user, 'roles' => $this->assignableRoles()]);
    }

    public function update(Request $request, User $user)
    {
        $this->guardTarget($user);
        $data = $this->validated($request, $user);
        $self = $user->is($request->user());

        // کاربر نمی‌تواند نقش خودش را تغییر دهد یا حساب خودش را غیرفعال کند
        if ($self) {
            $data['role_id'] = $user->role_id;
            $data['is_active'] = true;
        }

        $newRole = Role::find($data['role_id']);
        if ($user->isSuperAdmin() && (! $data['is_active'] || ! $newRole?->is_super) && $this->activeSuperCount() <= 1) {
            return back()->withInput()->with('error', 'حداقل یک مدیر کل فعال باید باقی بماند.');
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->fill($data);
        $user->forceFill(['role_id' => $data['role_id'], 'is_active' => $data['is_active']])->save();
        Activity::log('update', 'users', $user->name.' — نقش: '.$user->fresh('role')->role?->name.($user->is_active ? '' : ' (غیرفعال)'));

        return redirect()->route('admin.users.index')->with('success', 'اطلاعات «'.$user->name.'» ذخیره شد.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->guardTarget($user);

        if ($user->is($request->user())) {
            return back()->with('error', 'نمی‌توانید حساب خودتان را حذف کنید.');
        }
        if ($user->isSuperAdmin() && $this->activeSuperCount() <= 1) {
            return back()->with('error', 'آخرین مدیر کل قابل حذف نیست.');
        }

        Activity::log('delete', 'users', $user->name.' ('.$user->email.')');
        $user->delete();

        return back()->with('success', 'کاربر حذف شد.');
    }

    /**
     * فقط کاربران مدیر قابل مدیریت هستند و فقط مدیر کل می‌تواند مدیر کل دیگری را تغییر دهد.
     */
    protected function guardTarget(User $user): void
    {
        abort_unless($user->is_admin, 404);
        $me = request()->user();
        if ($me->isSuperAdmin() || $user->is($me)) {
            return;
        }
        // کاربری که دسترسی بیشتری از شما دارد (یا مدیر کل است) قابل تغییر نیست
        abort_if($user->isSuperAdmin(), 403);
        abort_if($user->role && ! $this->assignableRoles()->contains('id', $user->role_id), 403);
    }

    /**
     * نقش‌هایی که کاربر فعلی اجازه اختصاص آن‌ها را دارد (مدیر کل فقط توسط مدیر کل)
     */
    protected function assignableRoles()
    {
        $me = request()->user();
        $roles = Role::orderByDesc('is_super')->orderBy('name')->get();
        if ($me->isSuperAdmin()) {
            return $roles;
        }

        // کاربر عادی فقط نقش‌هایی را می‌دهد که همه دسترسی‌هایشان را خودش هم دارد
        return $roles->reject(fn (Role $r) => $r->is_super || collect($r->permissions ?? [])->contains(fn ($p) => ! $me->hasPermission($p)))->values();
    }

    protected function activeSuperCount(): int
    {
        return User::where('is_admin', true)->where('is_active', true)
            ->whereHas('role', fn ($q) => $q->where('is_super', true))->count();
    }

    protected function validated(Request $request, User $user): array
    {
        if ($user->exists && $user->is($request->user())) {
            $request->merge(['role_id' => $user->role_id, 'is_active' => '1']);
        }
        $request->merge(['mobile' => $request->filled('mobile') ? preg_replace('/\D+/', '', en_num($request->input('mobile'))) : null]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['nullable', 'regex:/^09\d{9}$/', Rule::unique('users', 'mobile')->ignore($user->id)],
            'password' => [$user->exists ? 'nullable' : 'required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role_id' => ['required', Rule::in($this->assignableRoles()->pluck('id')->push($user->is($request->user()) ? $user->role_id : null)->filter()->all())],
        ], [
            'mobile.regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
            'role_id.in' => 'نقش انتخاب‌شده معتبر نیست.',
        ], [
            'name' => 'نام', 'email' => 'ایمیل', 'mobile' => 'موبایل', 'password' => 'رمز عبور', 'role_id' => 'نقش',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
