<?php

namespace App\Admin;

/**
 * فیلدهای بخش «تنظیمات سایت» در پنل، به تفکیک تب.
 */
class SettingFields
{
    public static function groups(): array
    {
        return [
            'general' => [
                'label' => 'عمومی',
                'icon' => 'ri-settings-3-line',
                'fields' => [
                    'site_title' => ['label' => 'نام سایت / شرکت', 'type' => 'text', 'col' => 'half'],
                    'site_tagline' => ['label' => 'شعار', 'type' => 'text', 'col' => 'half'],
                    'logo' => ['label' => 'لوگو (نسخه روشن برای پس‌زمینه تیره)', 'type' => 'image', 'col' => 'third'],
                    'logo_dark' => ['label' => 'لوگو (نسخه تیره)', 'type' => 'image', 'col' => 'third'],
                    'favicon' => ['label' => 'فاوآیکن', 'type' => 'image', 'col' => 'third'],
                    'established_year' => ['label' => 'سال تأسیس', 'type' => 'text', 'col' => 'third'],
                    'experience_years' => ['label' => 'سال‌های تجربه', 'type' => 'text', 'col' => 'third'],
                    'working_hours' => ['label' => 'ساعات کاری', 'type' => 'text', 'col' => 'third'],
                    'meta_description' => ['label' => 'توضیحات متا (سئو)', 'type' => 'textarea'],
                    'meta_keywords' => ['label' => 'کلمات کلیدی (سئو)', 'type' => 'text'],
                ],
            ],
            'contact' => [
                'label' => 'اطلاعات تماس',
                'icon' => 'ri-phone-line',
                'fields' => [
                    'phone' => ['label' => 'تلفن ثابت', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'mobile' => ['label' => 'موبایل', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'email' => ['label' => 'ایمیل', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'address' => ['label' => 'آدرس', 'type' => 'textarea'],
                    'map_embed' => ['label' => 'کد نقشه (iframe یا لینک embed گوگل/نشان)', 'type' => 'textarea', 'dir' => 'ltr'],
                ],
            ],
            'social' => [
                'label' => 'شبکه‌های اجتماعی',
                'icon' => 'ri-instagram-line',
                'fields' => [
                    'instagram' => ['label' => 'اینستاگرام', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'telegram' => ['label' => 'تلگرام', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'whatsapp' => ['label' => 'واتس‌اپ (شماره یا لینک)', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'linkedin' => ['label' => 'لینکدین', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'aparat' => ['label' => 'آپارات', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'youtube' => ['label' => 'یوتیوب', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                ],
            ],
            'home' => [
                'label' => 'صفحه اصلی',
                'icon' => 'ri-home-5-line',
                'fields' => [
                    'marquee_text' => ['label' => 'متن نوار متحرک (با کاما جدا کنید)', 'type' => 'text'],
                    'about_subtitle' => ['label' => 'زیرعنوان بخش درباره', 'type' => 'text', 'col' => 'half'],
                    'about_title' => ['label' => 'عنوان بخش درباره', 'type' => 'text', 'col' => 'half'],
                    'about_text' => ['label' => 'متن بخش درباره', 'type' => 'textarea'],
                    'about_features' => ['label' => 'ویژگی‌های کلیدی (هر خط یک مورد)', 'type' => 'textarea'],
                    'about_image' => ['label' => 'تصویر اصلی بخش درباره', 'type' => 'image', 'col' => 'half'],
                    'about_image_2' => ['label' => 'تصویر دوم بخش درباره', 'type' => 'image', 'col' => 'half'],
                    'video_title' => ['label' => 'عنوان بخش ویدیو', 'type' => 'text', 'col' => 'half'],
                    'video_url' => ['label' => 'لینک ویدیو (embed آپارات/یوتیوب یا فایل mp4)', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'video_cover' => ['label' => 'تصویر پس‌زمینه ویدیو', 'type' => 'image'],
                    'cta_title' => ['label' => 'عنوان بخش دعوت به همکاری', 'type' => 'text', 'col' => 'half'],
                    'cta_text' => ['label' => 'متن بخش دعوت به همکاری', 'type' => 'text', 'col' => 'half'],
                ],
            ],
            'sections' => [
                'label' => 'کاتالوگ، مشاوره و تیم',
                'icon' => 'ri-layout-masonry-line',
                'fields' => [
                    'catalogue_title' => ['label' => 'عنوان کارت کاتالوگ', 'type' => 'text', 'col' => 'half'],
                    'catalogue_subtitle' => ['label' => 'زیرعنوان انگلیسی کاتالوگ', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'catalogue_file' => ['label' => 'فایل کاتالوگ (PDF)', 'type' => 'file', 'col' => 'half'],
                    'catalogue_cover' => ['label' => 'تصویر کاور کاتالوگ', 'type' => 'image', 'col' => 'half'],
                    'consult_title' => ['label' => 'عنوان کارت درخواست مشاوره', 'type' => 'text', 'col' => 'half'],
                    'consult_subtitle' => ['label' => 'زیرعنوان انگلیسی مشاوره', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'consult_image' => ['label' => 'تصویر کارت مشاوره', 'type' => 'image'],
                    'team_text' => ['label' => 'متن بخش تیم ما', 'type' => 'textarea'],
                    'team_button_text' => ['label' => 'متن دکمه بخش تیم', 'type' => 'text', 'col' => 'half'],
                    'team_button_link' => ['label' => 'لینک دکمه بخش تیم', 'type' => 'text', 'col' => 'half', 'dir' => 'ltr'],
                    'shop_enabled' => ['label' => 'نمایش فروشگاه و سبد خرید در سایت', 'type' => 'select', 'options' => ['1' => 'فعال', '0' => 'غیرفعال'], 'rules' => 'required', 'col' => 'third'],
                ],
            ],
            'calculator' => [
                'label' => 'ماشین‌حساب هزینه',
                'icon' => 'ri-calculator-line',
                'fields' => [
                    'calc_enabled' => ['label' => 'نمایش ماشین‌حساب در سایت', 'type' => 'select', 'options' => ['1' => 'فعال', '0' => 'غیرفعال'], 'col' => 'third'],
                    'calc_price_economy' => ['label' => 'هزینه ساخت هر متر - اقتصادی (تومان)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_price_standard' => ['label' => 'هزینه ساخت هر متر - استاندارد (تومان)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_price_luxury' => ['label' => 'هزینه ساخت هر متر - لوکس (تومان)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_steel_factor' => ['label' => 'ضریب اسکلت فلزی نسبت به بتنی (مثلا 1.08)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_floor_factor' => ['label' => 'افزایش هزینه به ازای هر طبقه بالاتر از ۴ (درصد)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_basement_factor' => ['label' => 'افزایش هزینه زیرزمین/پارکینگ (درصد)', 'type' => 'text', 'col' => 'third', 'dir' => 'ltr'],
                    'calc_note' => ['label' => 'توضیح زیر نتیجه', 'type' => 'textarea'],
                ],
            ],
            'about' => [
                'label' => 'صفحه درباره ما',
                'icon' => 'ri-information-line',
                'fields' => [
                    'about_page_text' => ['label' => 'متن کامل درباره ما', 'type' => 'editor'],
                    'mission' => ['label' => 'مأموریت ما', 'type' => 'textarea', 'col' => 'third'],
                    'vision' => ['label' => 'چشم‌انداز', 'type' => 'textarea', 'col' => 'third'],
                    'values' => ['label' => 'ارزش‌های ما', 'type' => 'textarea', 'col' => 'third'],
                    'page_banner' => ['label' => 'تصویر بنر صفحات داخلی', 'type' => 'image'],
                    'ceo_name' => ['label' => 'نام مدیرعامل', 'type' => 'text', 'col' => 'half'],
                    'ceo_signature' => ['label' => 'امضای مدیرعامل (تصویر)', 'type' => 'image', 'col' => 'half'],
                ],
            ],
            'footer' => [
                'label' => 'فوتر',
                'icon' => 'ri-layout-bottom-line',
                'fields' => [
                    'footer_text' => ['label' => 'متن معرفی فوتر', 'type' => 'textarea'],
                    'copyright' => ['label' => 'متن کپی‌رایت', 'type' => 'text'],
                    'enamad' => ['label' => 'کد نماد اعتماد (HTML)', 'type' => 'textarea', 'dir' => 'ltr'],
                ],
            ],
        ];
    }

    public static function flat(): array
    {
        return collect(static::groups())->flatMap(fn ($g) => $g['fields'])->all();
    }
}
