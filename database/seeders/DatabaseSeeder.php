<?php

namespace Database\Seeders;

use App\Admin\Permissions;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@tahournian.ir')],
            [
                'name' => env('ADMIN_NAME', 'مدیر سایت'),
                'password' => env('ADMIN_PASSWORD', 'Tahournian@1405'),
            ]
        );
        $super = Role::firstOrCreate(['is_super' => true], ['name' => 'مدیر کل', 'description' => 'دسترسی کامل به همه بخش‌های پنل', 'permissions' => []]);
        $admin->forceFill(['is_admin' => true, 'is_active' => true, 'role_id' => $admin->role_id ?? $super->id])->save();

        // نقش‌های آماده نمونه (از پنل قابل ویرایش هستند)
        $all = fn (array $sections, array $actions = ['view', 'create', 'edit', 'delete']) => collect($sections)
            ->crossJoin($actions)->map(fn ($p) => implode('.', $p))->intersect(Permissions::keys())->values()->all();

        Role::firstOrCreate(['name' => 'ویرایشگر محتوا'], [
            'description' => 'مدیریت پروژه‌ها، خدمات، مقالات و محتوای صفحات',
            'permissions' => $all(['slides', 'stats', 'steps', 'services', 'project-categories', 'projects', 'posts', 'team', 'testimonials', 'faqs', 'partners']),
        ]);
        Role::firstOrCreate(['name' => 'کارشناس فروش'], [
            'description' => 'پیگیری سفارش‌ها، پیام‌ها و مشتریان؛ مدیریت محصولات',
            'permissions' => array_merge(
                ['messages.view', 'orders.view', 'orders.edit', 'customers.view'],
                $all(['products', 'product-categories'], ['view', 'create', 'edit']),
            ),
        ]);

        $this->call(ContentSeeder::class);
    }
}
