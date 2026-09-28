# وب‌سایت گروه ساختمانی طاهورنیان

وب‌سایت شرکتی حرفه‌ای برای **tahournian.ir** با **Laravel 11** و **MySQL**، طراحی تیره و طلایی، انیمیشن‌های پیشرفته جاوااسکریپت و **پنل مدیریت کاملا فارسی**.

---

## ✨ امکانات

### سایت
- صفحات: خانه، درباره ما، خدمات (لیست و جزئیات)، پروژه‌ها (لیست با فیلتر و جزئیات با گالری)، مقالات (لیست، جستجو، دسته‌بندی و جزئیات)، تماس با ما، صفحه ۴۰۴، نقشه سایت (`/sitemap.xml`)
- کاملا راست‌چین و واکنش‌گرا (موبایل، تبلت، دسکتاپ)
- تاریخ شمسی و اعداد فارسی
- سئو: متاتگ‌ها، Open Graph، داده ساختاریافته (Schema.org)، sitemap و robots.txt
- فرم تماس Ajax با اعتبارسنجی، محدودیت تعداد ارسال و فیلد ضد ربات
- **همه کتابخانه‌ها و فونت روی خود سرور هستند** (بدون وابستگی به CDN‌های خارجی، مناسب هاست ایران)

### انیمیشن‌ها (GSAP + ScrollTrigger + Flip، Swiper، Lenis)
- پیش‌بارگذار با رسم لوگو و شمارنده درصد
- انتقال پرده‌ای بین صفحات
- نشانگر ماوس سفارشی با متن («مشاهده»، «بزرگنمایی»)
- اسکرول نرم (Lenis)
- اسلایدر هیرو با افکت محو، زوم آرام تصویر، شمارنده و نوار پیشرفت
- **شبکه نقاط معماری تعاملی** (Canvas) در هیرو که به ماوس واکنش نشان می‌دهد
- نمایش کلمه‌به‌کلمه عناوین (بدون جدا کردن حروف فارسی)
- نمایش تصاویر با ماسک (clip-path) و پارالاکس
- **اسکرول افقی پین‌شده** برای پروژه‌های منتخب
- نوار متن متحرک با سرعت وابسته به سرعت اسکرول
- کارت‌های سه‌بعدی (Tilt) با نورافکن دنبال‌کننده ماوس
- دکمه‌های مغناطیسی
- شمارنده‌های آماری، خط متحرک مراحل کار و برجسته‌سازی تدریجی متن
- فیلتر پروژه‌ها با انیمیشن Flip
- اسلایدر نظرات با افکت Creative
- لایت‌باکس گالری، مودال ویدیو، دکمه بازگشت به بالا با حلقه پیشرفت و نوار پیشرفت مطالعه مقاله
- رعایت `prefers-reduced-motion` و عملکرد صحیح سایت حتی بدون جاوااسکریپت

### پنل مدیریت فارسی (`/admin`)
- ورود امن با محدودیت تلاش
- داشبورد با آمار، نمودار پیام‌ها، آخرین پیام‌ها و پروژه‌ها
- **افزودن، ویرایش و حذف** در همه بخش‌ها:
  اسلایدر، آمار، مراحل کار، خدمات، دسته‌بندی پروژه‌ها، پروژه‌ها (با گالری چندتصویری)، مقالات، اعضای تیم، نظرات مشتریان، سوالات متداول، همکاران و برندها
- جستجو، فیلتر، صفحه‌بندی، حذف گروهی و تغییر وضعیت سریع (فعال/غیرفعال) با یک کلیک
- ویرایشگر متن پیشرفته، آپلود تصویر با پیش‌نمایش و کشیدن و رها کردن
- ساخت خودکار نامک (slug) فارسی
- صندوق پیام‌های فرم تماس
- **تنظیمات سایت** در تب‌های جداگانه: عمومی و لوگو، اطلاعات تماس و نقشه، شبکه‌های اجتماعی، متن‌های صفحه اصلی، صفحه درباره ما و فوتر
- تغییر نام، ایمیل و رمز عبور مدیر
- حالت تیره و روشن

---

## 🚀 نصب

پیش‌نیازها: PHP 8.2 یا بالاتر (با افزونه‌های `pdo_mysql`، `mbstring`، `fileinfo` و `gd`)، Composer و MySQL 5.7+ یا MariaDB 10.3+

```bash
git clone <repo-url> tahournian && cd tahournian
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

پایگاه‌داده را در MySQL بسازید (`utf8mb4_unicode_ci`) و اطلاعات آن را در فایل `.env` وارد کنید:

```env
DB_DATABASE=tahournian
DB_USERNAME=...
DB_PASSWORD=...

ADMIN_EMAIL=admin@tahournian.ir
ADMIN_PASSWORD=یک-رمز-قوی
```

سپس:

```bash
php artisan migrate --seed      # ساخت جداول و محتوای نمونه
php artisan storage:link        # برای نمایش تصاویر آپلودی
php artisan optimize            # کش تنظیمات، مسیرها و قالب‌ها برای سرعت بیشتر
```

ورود به پنل: `https://tahournian.ir/admin` با ایمیل و رمزی که در `.env` تعیین کرده‌اید
(پیش‌فرض: `admin@tahournian.ir` / `Tahournian@1405`). **پس از اولین ورود، رمز عبور را از بخش «حساب کاربری» تغییر دهید.**

> محتوای نمونه (پروژه‌ها، مقالات، تیم و ...) فقط برای شروع است و همه آن‌ها از پنل مدیریت قابل ویرایش یا حذف هستند.

---

## 🌐 استقرار روی هاست

### سرور مجازی (Nginx)
ریشه سایت (document root) را روی پوشه `public` تنظیم کنید:

```nginx
server {
    server_name tahournian.ir www.tahournian.ir;
    root /var/www/tahournian/public;
    index index.php;
    client_max_body_size 20M;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
    location ~ /\.(?!well-known) { deny all; }
}
```

دسترسی نوشتن برای وب‌سرور: `chown -R www-data:www-data storage bootstrap/cache`

### هاست اشتراکی (cPanel / DirectAdmin)
- **روش پیشنهادی:** Document Root دامنه را روی پوشه `public` تنظیم کنید.
- اگر این امکان وجود ندارد، کل پروژه را در `public_html` قرار دهید؛ فایل `.htaccess` ریشه پروژه درخواست‌ها را به پوشه `public` هدایت می‌کند و دسترسی به فایل‌های حساس را می‌بندد.
- اگر دستور `storage:link` روی هاست اجرا نمی‌شود، از بخش Terminal یا Cron یک بار دستور `php artisan storage:link` را اجرا کنید.

در محیط واقعی حتما در `.env` مقادیر `APP_ENV=production` و `APP_DEBUG=false` و `APP_URL=https://tahournian.ir` تنظیم شده باشند.

---

## 🗂 ساختار کد

| مسیر | توضیح |
|---|---|
| `app/Admin/Resources.php` | تعریف همه بخش‌های قابل مدیریت پنل (فیلدها، ستون‌ها، فیلترها). برای افزودن بخش جدید کافی است یک مدل، یک migration و یک تعریف اینجا اضافه کنید. |
| `app/Admin/SettingFields.php` | فیلدهای صفحه تنظیمات سایت |
| `app/Http/Controllers/Admin/ResourceController.php` | کنترلر عمومی افزودن، ویرایش، حذف و آپلود برای همه بخش‌ها |
| `app/Http/Controllers/SiteController.php` | صفحات عمومی سایت |
| `app/helpers.php` | توابع کمکی: `setting()`، `jdate()` (تاریخ شمسی)، `fa_num()`، `media_url()`، `persian_slug()` |
| `resources/views/pages` و `resources/views/partials` | قالب‌های سایت |
| `resources/views/admin` | قالب‌های پنل مدیریت |
| `public/assets/css/app.css` و `public/assets/js/app.js` | استایل و انیمیشن‌های سایت |
| `public/assets/admin` | استایل و اسکریپت پنل |
| `public/assets/vendor` | کتابخانه‌های GSAP، Swiper، Lenis، Remix Icon، Quill، Chart.js و SweetAlert2 |

---

## 🛠 توسعه محلی

```bash
cp .env.example .env
# برای تست سریع بدون MySQL:
#   DB_CONNECTION=sqlite  و  DB_DATABASE=/مسیر-کامل/database/database.sqlite
touch database/database.sqlite
php artisan key:generate && php artisan migrate --seed && php artisan storage:link
php artisan serve
```

تصاویر نمونه از [Unsplash](https://unsplash.com) هستند و فقط برای نمایش استفاده شده‌اند. پیش از انتشار نهایی، تصاویر واقعی پروژه‌ها را از پنل جایگزین کنید.
