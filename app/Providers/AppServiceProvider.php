<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\Service;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('partials.pagination');

        // خدمات برای منو و فوتر سایت
        View::composer(['layouts.app', 'partials.*'], function ($view) {
            static $services;
            $services ??= rescue(fn () => Service::active()->ordered()->get(['id', 'title', 'slug', 'icon']), collect(), false);
            $view->with('menuServices', $services);
        });

        // تعداد پیام‌های خوانده‌نشده برای پنل
        View::composer('admin.layouts.app', function ($view) {
            $view->with('unreadCount', rescue(fn () => Message::where('is_read', false)->count(), 0, false));
            $view->with('pendingOrders', rescue(fn () => \App\Models\Order::where('status', 'pending')->count(), 0, false));
        });
    }
}
