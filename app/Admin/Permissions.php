<?php

namespace App\Admin;

/**
 * فهرست دسترسی‌های پنل مدیریت.
 *
 * هر بخش چند عمل دارد (مشاهده، افزودن، ویرایش، حذف) و کلید هر دسترسی
 * به شکل «بخش.عمل» است؛ مثلا projects.edit یا orders.view
 * بخش‌های محتوایی به صورت خودکار از Resources ساخته می‌شوند.
 */
class Permissions
{
    public const ACTIONS = [
        'view' => 'مشاهده',
        'create' => 'افزودن',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
    ];

    /**
     * بخش‌ها به تفکیک گروه: [گروه => [کلید بخش => ['label', 'icon', 'actions']]]
     */
    public static function sections(): array
    {
        $groups = [
            'فروش و ارتباط با مشتری' => [
                'messages' => ['label' => 'پیام‌ها و درخواست‌ها', 'icon' => 'ri-mail-line', 'actions' => ['view', 'delete']],
                'orders' => ['label' => 'سفارش‌ها', 'icon' => 'ri-shopping-cart-2-line', 'actions' => ['view', 'edit', 'delete']],
                'customers' => ['label' => 'مشتریان', 'icon' => 'ri-group-line', 'actions' => ['view', 'delete']],
            ],
        ];

        foreach (Resources::grouped() as $group => $items) {
            foreach ($items as $key => $def) {
                $groups['محتوا: '.$group][$key] = ['label' => $def['label'], 'icon' => $def['icon'], 'actions' => array_keys(self::ACTIONS)];
            }
        }

        $groups['تنظیمات و مدیریت'] = [
            'settings' => ['label' => 'تنظیمات سایت', 'icon' => 'ri-settings-4-line', 'actions' => ['view', 'edit']],
            'users' => ['label' => 'کاربران مدیر', 'icon' => 'ri-admin-line', 'actions' => ['view', 'create', 'edit', 'delete']],
            'roles' => ['label' => 'نقش‌ها و دسترسی‌ها', 'icon' => 'ri-shield-keyhole-line', 'actions' => ['view', 'create', 'edit', 'delete']],
            'activity' => ['label' => 'گزارش فعالیت‌ها', 'icon' => 'ri-history-line', 'actions' => ['view']],
        ];

        return $groups;
    }

    /**
     * همه کلیدهای معتبر دسترسی
     */
    public static function keys(): array
    {
        $keys = [];
        foreach (static::sections() as $items) {
            foreach ($items as $section => $def) {
                foreach ($def['actions'] as $action) {
                    $keys[] = $section.'.'.$action;
                }
            }
        }

        return $keys;
    }

    /**
     * عنوان خوانای یک بخش برای گزارش‌ها
     */
    public static function sectionLabel(?string $section): ?string
    {
        if (! $section) {
            return null;
        }
        foreach (static::sections() as $items) {
            if (isset($items[$section])) {
                return $items[$section]['label'];
            }
        }

        return ['auth' => 'ورود و خروج', 'profile' => 'حساب کاربری'][$section] ?? $section;
    }
}
