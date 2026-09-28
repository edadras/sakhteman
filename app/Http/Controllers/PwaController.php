<?php

namespace App\Http\Controllers;

/**
 * فایل manifest برنامه وب پیش‌رونده (PWA) — نام و رنگ‌ها از تنظیمات سایت خوانده می‌شوند.
 */
class PwaController extends Controller
{
    public function manifest()
    {
        $name = setting('site_title', config('app.name'));
        $short = \Illuminate\Support\Str::of($name)->explode(' ')->last();
        $shopOn = setting('shop_enabled', '1') === '1';

        $shortcuts = [
            ['name' => 'پروژه‌ها', 'url' => '/projects', 'icons' => [['src' => asset('assets/pwa/icon-192.png'), 'sizes' => '192x192']]],
            ['name' => 'تماس و مشاوره', 'url' => '/contact', 'icons' => [['src' => asset('assets/pwa/icon-192.png'), 'sizes' => '192x192']]],
        ];
        if ($shopOn) {
            array_splice($shortcuts, 1, 0, [['name' => 'فروشگاه', 'url' => '/shop', 'icons' => [['src' => asset('assets/pwa/icon-192.png'), 'sizes' => '192x192']]]]);
        }

        return response()->json([
            'id' => '/',
            'name' => $name,
            'short_name' => (string) $short,
            'description' => setting('meta_description', setting('site_tagline')),
            'lang' => 'fa',
            'dir' => 'rtl',
            'start_url' => '/?source=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'display_override' => ['standalone', 'minimal-ui'],
            'orientation' => 'portrait',
            'background_color' => '#0e1e21',
            'theme_color' => '#0e1e21',
            'categories' => ['business', 'lifestyle', 'shopping'],
            'icons' => [
                ['src' => asset('assets/pwa/icon-192.png'), 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => asset('assets/pwa/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
                ['src' => asset('assets/pwa/icon-maskable-512.png'), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'shortcuts' => $shortcuts,
        ], 200, ['Content-Type' => 'application/manifest+json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
