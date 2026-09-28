<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| سایت عمومی
|--------------------------------------------------------------------------
*/
Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services.index');
    Route::get('/services/{slug}', 'service')->name('services.show');
    Route::get('/projects', 'projects')->name('projects.index');
    Route::get('/projects/{slug}', 'project')->name('projects.show');
    Route::get('/blog', 'blog')->name('blog.index');
    Route::get('/blog/{slug}', 'post')->name('blog.show');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendMessage')->middleware('throttle:5,1')->name('contact.send');
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
});

/*
|--------------------------------------------------------------------------
| پنل مدیریت
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        // مدیریت عمومی محتوا (خدمات، پروژه‌ها، مقالات، ...)
        Route::controller(ResourceController::class)->prefix('manage/{resource}')->name('resources.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::put('{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
            Route::patch('{id}/toggle/{field}', 'toggle')->name('toggle');
            Route::post('bulk-delete', 'bulkDestroy')->name('bulk-destroy');
        });
    });
});
