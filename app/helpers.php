<?php

use App\Models\Setting;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

if (! function_exists('setting')) {
    /**
     * خواندن یک تنظیم سایت از پایگاه‌داده (با کش).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::getValue($key, $default);
    }
}

if (! function_exists('media_url')) {
    /**
     * آدرس کامل فایل آپلود شده یا لینک خارجی.
     */
    function media_url(?string $path, ?string $fallback = null): string
    {
        if (! $path) {
            return $fallback ?? asset('assets/img/placeholder.svg');
        }

        if (preg_match('#^(https?:)?//#', $path)) {
            return $path;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('fa_num')) {
    /**
     * تبدیل ارقام انگلیسی به فارسی.
     */
    function fa_num(mixed $value): string
    {
        return strtr((string) $value, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    }
}

if (! function_exists('en_num')) {
    /**
     * تبدیل ارقام فارسی و عربی به انگلیسی.
     */
    function en_num(mixed $value): string
    {
        return strtr((string) $value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}

if (! function_exists('gregorian_to_jalali')) {
    /**
     * تبدیل تاریخ میلادی به شمسی.
     *
     * @return array{0:int,1:int,2:int}
     */
    function gregorian_to_jalali(int $gy, int $gm, int $gd): array
    {
        $gDaysInMonth = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = $gm > 2 ? $gy + 1 : $gy;
        $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100) + intdiv($gy2 + 399, 400) + $gd + $gDaysInMonth[$gm - 1];
        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }
}

if (! function_exists('jdate')) {
    /**
     * قالب‌بندی تاریخ به شمسی.
     * توکن‌ها: Y سال، m ماه دو رقمی، n ماه، d روز دو رقمی، j روز، F نام ماه، l نام روز هفته، H:i ساعت.
     */
    function jdate(mixed $date, string $format = 'j F Y', bool $persianDigits = true): string
    {
        if (! $date) {
            return '';
        }

        $date = $date instanceof CarbonInterface ? $date : Carbon::parse($date);
        $date = $date->copy()->timezone(config('app.timezone'));
        [$jy, $jm, $jd] = gregorian_to_jalali($date->year, $date->month, $date->day);

        $months = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        $weekDays = ['یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];

        $map = [
            'Y' => (string) $jy,
            'y' => substr((string) $jy, -2),
            'm' => str_pad((string) $jm, 2, '0', STR_PAD_LEFT),
            'n' => (string) $jm,
            'd' => str_pad((string) $jd, 2, '0', STR_PAD_LEFT),
            'j' => (string) $jd,
            'F' => $months[$jm - 1],
            'l' => $weekDays[$date->dayOfWeek],
            'H' => $date->format('H'),
            'i' => $date->format('i'),
        ];

        $out = '';
        foreach (mb_str_split($format) as $char) {
            $out .= $map[$char] ?? $char;
        }

        return $persianDigits ? fa_num($out) : $out;
    }
}

if (! function_exists('persian_slug')) {
    /**
     * ساخت نامک (slug) سازگار با حروف فارسی.
     */
    function persian_slug(?string $text): string
    {
        $text = en_num(trim((string) $text));
        $text = str_replace(["\u{200C}", 'ي', 'ك'], ['-', 'ی', 'ک'], $text);
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        $text = trim(mb_strtolower($text), '-');

        return mb_substr($text, 0, 180) ?: 'item';
    }
}

if (! function_exists('reading_time')) {
    function reading_time(?string $html): int
    {
        $words = count(preg_split('/\s+/u', trim(strip_tags((string) $html))) ?: []);

        return max(1, (int) ceil($words / 200));
    }
}
