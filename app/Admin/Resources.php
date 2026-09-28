<?php

namespace App\Admin;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Slide;
use App\Models\Stat;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;

/**
 * تعریف بخش‌های قابل مدیریت در پنل.
 *
 * هر بخش شامل مدل، برچسب‌ها، ستون‌های جدول و فیلدهای فرم است. کنترلر
 * ResourceController بر اساس همین تعریف‌ها عملیات افزودن، ویرایش و حذف را انجام می‌دهد.
 *
 * انواع فیلد: text, textarea, editor, image, gallery, number, select, toggle, icon, url, email
 */
class Resources
{
    public static function all(): array
    {
        $sort = ['label' => 'ترتیب نمایش', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0, 'col' => 'third', 'help' => 'عدد کوچک‌تر، زودتر نمایش داده می‌شود'];
        $active = ['label' => 'نمایش در سایت', 'type' => 'toggle', 'default' => true, 'col' => 'third'];

        return [
            'slides' => [
                'model' => Slide::class,
                'label' => 'اسلایدر صفحه اصلی',
                'singular' => 'اسلاید',
                'icon' => 'ri-slideshow-3-line',
                'group' => 'صفحه اصلی',
                'search' => ['title', 'subtitle'],
                'columns' => [
                    'image' => ['label' => 'تصویر', 'type' => 'image'],
                    'title' => ['label' => 'عنوان'],
                    'sort' => ['label' => 'ترتیب', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'subtitle' => ['label' => 'زیرعنوان کوچک', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'half'],
                    'title' => ['label' => 'عنوان اصلی', 'type' => 'text', 'rules' => 'required|string|max:190', 'col' => 'half', 'help' => 'برای شکستن خط از | استفاده کنید'],
                    'description' => ['label' => 'توضیحات', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
                    'image' => ['label' => 'تصویر پس‌زمینه', 'type' => 'image', 'help' => 'ابعاد پیشنهادی ۱۹۲۰×۱۰۸۰'],
                    'button_text' => ['label' => 'متن دکمه', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'col' => 'half'],
                    'button_link' => ['label' => 'لینک دکمه', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 'half', 'dir' => 'ltr'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'services' => [
                'model' => Service::class,
                'label' => 'خدمات',
                'singular' => 'خدمت',
                'icon' => 'ri-tools-line',
                'group' => 'محتوا',
                'slug' => 'title',
                'search' => ['title', 'summary'],
                'columns' => [
                    'image' => ['label' => 'تصویر', 'type' => 'image'],
                    'icon' => ['label' => 'آیکن', 'type' => 'icon'],
                    'title' => ['label' => 'عنوان'],
                    'sort' => ['label' => 'ترتیب', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'title' => ['label' => 'عنوان خدمت', 'type' => 'text', 'rules' => 'required|string|max:190', 'col' => 'half'],
                    'slug' => ['label' => 'نامک (آدرس)', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'half', 'help' => 'خالی بگذارید تا خودکار ساخته شود', 'dir' => 'ltr'],
                    'icon' => ['label' => 'آیکن', 'type' => 'icon', 'rules' => 'nullable|string|max:80', 'col' => 'half'],
                    'image' => ['label' => 'تصویر', 'type' => 'image', 'col' => 'half'],
                    'summary' => ['label' => 'خلاصه', 'type' => 'textarea', 'rules' => 'nullable|string|max:600'],
                    'features' => ['label' => 'ویژگی‌ها', 'type' => 'textarea', 'rules' => 'nullable|string|max:3000', 'help' => 'هر ویژگی در یک خط'],
                    'body' => ['label' => 'توضیحات کامل', 'type' => 'editor', 'rules' => 'nullable|string'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'project-categories' => [
                'model' => ProjectCategory::class,
                'label' => 'دسته‌بندی پروژه‌ها',
                'singular' => 'دسته‌بندی',
                'icon' => 'ri-folders-line',
                'group' => 'محتوا',
                'slug' => 'name',
                'search' => ['name'],
                'counts' => ['projects'],
                'columns' => [
                    'name' => ['label' => 'نام'],
                    'projects_count' => ['label' => 'تعداد پروژه', 'type' => 'number'],
                    'sort' => ['label' => 'ترتیب', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'name' => ['label' => 'نام دسته', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'half'],
                    'slug' => ['label' => 'نامک', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 'half', 'dir' => 'ltr'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'projects' => [
                'model' => Project::class,
                'label' => 'پروژه‌ها',
                'singular' => 'پروژه',
                'icon' => 'ri-building-4-line',
                'group' => 'محتوا',
                'slug' => 'title',
                'search' => ['title', 'location', 'client'],
                'with' => ['category'],
                'filters' => ['category_id' => 'دسته‌بندی', 'status' => 'وضعیت'],
                'columns' => [
                    'cover' => ['label' => 'تصویر', 'type' => 'image'],
                    'title' => ['label' => 'عنوان'],
                    'category.name' => ['label' => 'دسته'],
                    'status_label' => ['label' => 'وضعیت اجرا', 'type' => 'badge'],
                    'is_featured' => ['label' => 'ویژه', 'type' => 'toggle'],
                    'is_active' => ['label' => 'نمایش', 'type' => 'toggle'],
                ],
                'fields' => [
                    'title' => ['label' => 'عنوان پروژه', 'type' => 'text', 'rules' => 'required|string|max:190', 'col' => 'half'],
                    'slug' => ['label' => 'نامک', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'half', 'dir' => 'ltr'],
                    'category_id' => ['label' => 'دسته‌بندی', 'type' => 'select', 'rules' => 'nullable|exists:project_categories,id', 'col' => 'third',
                        'options' => fn () => ProjectCategory::ordered()->pluck('name', 'id')->all()],
                    'status' => ['label' => 'وضعیت اجرا', 'type' => 'select', 'rules' => 'required|in:'.implode(',', array_keys(Project::STATUSES)), 'col' => 'third',
                        'options' => Project::STATUSES, 'default' => 'completed'],
                    'year' => ['label' => 'سال اجرا', 'type' => 'text', 'rules' => 'nullable|string|max:20', 'col' => 'third'],
                    'client' => ['label' => 'کارفرما', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'third'],
                    'location' => ['label' => 'موقعیت', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'third'],
                    'area' => ['label' => 'متراژ', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'col' => 'third'],
                    'floors' => ['label' => 'تعداد طبقات', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'col' => 'third'],
                    'cover' => ['label' => 'تصویر شاخص', 'type' => 'image', 'col' => 'half'],
                    'gallery' => ['label' => 'گالری تصاویر', 'type' => 'gallery', 'col' => 'half'],
                    'summary' => ['label' => 'خلاصه', 'type' => 'textarea', 'rules' => 'nullable|string|max:800'],
                    'body' => ['label' => 'توضیحات کامل', 'type' => 'editor', 'rules' => 'nullable|string'],
                    'is_featured' => ['label' => 'نمایش در صفحه اصلی (ویژه)', 'type' => 'toggle', 'default' => false, 'col' => 'third'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'posts' => [
                'model' => Post::class,
                'label' => 'مقالات و اخبار',
                'singular' => 'مقاله',
                'icon' => 'ri-article-line',
                'group' => 'محتوا',
                'slug' => 'title',
                'search' => ['title', 'excerpt', 'category'],
                'order' => ['published_at', 'desc'],
                'columns' => [
                    'cover' => ['label' => 'تصویر', 'type' => 'image'],
                    'title' => ['label' => 'عنوان'],
                    'category' => ['label' => 'دسته'],
                    'published_at' => ['label' => 'تاریخ انتشار', 'type' => 'date'],
                    'views' => ['label' => 'بازدید', 'type' => 'number'],
                    'is_active' => ['label' => 'انتشار', 'type' => 'toggle'],
                ],
                'fields' => [
                    'title' => ['label' => 'عنوان مقاله', 'type' => 'text', 'rules' => 'required|string|max:190', 'col' => 'half'],
                    'slug' => ['label' => 'نامک', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'half', 'dir' => 'ltr'],
                    'category' => ['label' => 'دسته‌بندی', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 'third'],
                    'author' => ['label' => 'نویسنده', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 'third'],
                    'cover' => ['label' => 'تصویر شاخص', 'type' => 'image', 'col' => 'third'],
                    'excerpt' => ['label' => 'خلاصه', 'type' => 'textarea', 'rules' => 'nullable|string|max:800'],
                    'body' => ['label' => 'متن مقاله', 'type' => 'editor', 'rules' => 'nullable|string'],
                    'is_active' => ['label' => 'منتشر شود', 'type' => 'toggle', 'default' => true, 'col' => 'third'],
                ],
            ],

            'team' => [
                'model' => TeamMember::class,
                'label' => 'اعضای تیم',
                'singular' => 'عضو تیم',
                'icon' => 'ri-team-line',
                'group' => 'درباره ما',
                'search' => ['name', 'position'],
                'columns' => [
                    'photo' => ['label' => 'عکس', 'type' => 'image'],
                    'name' => ['label' => 'نام'],
                    'position' => ['label' => 'سمت'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'name' => ['label' => 'نام و نام خانوادگی', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'half'],
                    'position' => ['label' => 'سمت', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 'half'],
                    'photo' => ['label' => 'عکس', 'type' => 'image', 'help' => 'تصویر عمودی با نسبت ۳ به ۴'],
                    'bio' => ['label' => 'بیوگرافی کوتاه', 'type' => 'textarea', 'rules' => 'nullable|string|max:600'],
                    'instagram' => ['label' => 'اینستاگرام', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'third', 'dir' => 'ltr'],
                    'linkedin' => ['label' => 'لینکدین', 'type' => 'text', 'rules' => 'nullable|string|max:190', 'col' => 'third', 'dir' => 'ltr'],
                    'email' => ['label' => 'ایمیل', 'type' => 'text', 'rules' => 'nullable|email|max:190', 'col' => 'third', 'dir' => 'ltr'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'stats' => [
                'model' => Stat::class,
                'label' => 'آمار و ارقام',
                'singular' => 'آمار',
                'icon' => 'ri-bar-chart-box-line',
                'group' => 'صفحه اصلی',
                'columns' => [
                    'icon' => ['label' => 'آیکن', 'type' => 'icon'],
                    'title' => ['label' => 'عنوان'],
                    'value' => ['label' => 'مقدار', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'title' => ['label' => 'عنوان', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'half'],
                    'icon' => ['label' => 'آیکن', 'type' => 'icon', 'rules' => 'nullable|string|max:80', 'col' => 'half'],
                    'value' => ['label' => 'عدد', 'type' => 'number', 'rules' => 'required|integer|min:0', 'col' => 'half'],
                    'suffix' => ['label' => 'پسوند (مثلا + یا ٪)', 'type' => 'text', 'rules' => 'nullable|string|max:20', 'col' => 'half'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'steps' => [
                'model' => Step::class,
                'label' => 'مراحل کار',
                'singular' => 'مرحله',
                'icon' => 'ri-route-line',
                'group' => 'صفحه اصلی',
                'columns' => [
                    'icon' => ['label' => 'آیکن', 'type' => 'icon'],
                    'title' => ['label' => 'عنوان'],
                    'sort' => ['label' => 'ترتیب', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'title' => ['label' => 'عنوان مرحله', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'half'],
                    'icon' => ['label' => 'آیکن', 'type' => 'icon', 'rules' => 'nullable|string|max:80', 'col' => 'half'],
                    'description' => ['label' => 'توضیح', 'type' => 'textarea', 'rules' => 'nullable|string|max:600'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'testimonials' => [
                'model' => Testimonial::class,
                'label' => 'نظرات مشتریان',
                'singular' => 'نظر',
                'icon' => 'ri-chat-quote-line',
                'group' => 'درباره ما',
                'search' => ['name', 'content'],
                'columns' => [
                    'avatar' => ['label' => 'تصویر', 'type' => 'image'],
                    'name' => ['label' => 'نام'],
                    'position' => ['label' => 'سمت'],
                    'rating' => ['label' => 'امتیاز', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'name' => ['label' => 'نام', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'third'],
                    'position' => ['label' => 'سمت / عنوان', 'type' => 'text', 'rules' => 'nullable|string|max:120', 'col' => 'third'],
                    'rating' => ['label' => 'امتیاز (۱ تا ۵)', 'type' => 'select', 'rules' => 'required|integer|between:1,5', 'col' => 'third',
                        'options' => [5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★'], 'default' => 5],
                    'avatar' => ['label' => 'تصویر', 'type' => 'image'],
                    'content' => ['label' => 'متن نظر', 'type' => 'textarea', 'rules' => 'required|string|max:1500'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'faqs' => [
                'model' => Faq::class,
                'label' => 'سوالات متداول',
                'singular' => 'سوال',
                'icon' => 'ri-question-answer-line',
                'group' => 'درباره ما',
                'search' => ['question', 'answer'],
                'columns' => [
                    'question' => ['label' => 'سوال'],
                    'sort' => ['label' => 'ترتیب', 'type' => 'number'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'question' => ['label' => 'سوال', 'type' => 'text', 'rules' => 'required|string|max:190'],
                    'answer' => ['label' => 'پاسخ', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],

            'partners' => [
                'model' => Partner::class,
                'label' => 'همکاران و برندها',
                'singular' => 'همکار',
                'icon' => 'ri-shake-hands-line',
                'group' => 'درباره ما',
                'columns' => [
                    'logo' => ['label' => 'لوگو', 'type' => 'image'],
                    'name' => ['label' => 'نام'],
                    'is_active' => ['label' => 'وضعیت', 'type' => 'toggle'],
                ],
                'fields' => [
                    'name' => ['label' => 'نام', 'type' => 'text', 'rules' => 'required|string|max:120', 'col' => 'half'],
                    'url' => ['label' => 'وب‌سایت', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 'half', 'dir' => 'ltr'],
                    'logo' => ['label' => 'لوگو', 'type' => 'image', 'help' => 'ترجیحا PNG یا SVG با پس‌زمینه شفاف'],
                    'sort' => $sort,
                    'is_active' => $active,
                ],
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        $all = static::all();

        return isset($all[$key]) ? $all[$key] + ['key' => $key] : null;
    }

    /**
     * گروه‌بندی بخش‌ها برای منوی کناری.
     */
    public static function grouped(): array
    {
        $groups = [];
        foreach (static::all() as $key => $def) {
            $groups[$def['group'] ?? 'سایر'][$key] = $def;
        }

        return $groups;
    }
}
