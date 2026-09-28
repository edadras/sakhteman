<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\ShopController;
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

Route::get('/manifest.webmanifest', [PwaController::class, 'manifest'])->name('pwa.manifest');

/*
|--------------------------------------------------------------------------
| فروشگاه و سبد خرید
|--------------------------------------------------------------------------
*/
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('add', 'add')->name('add');
    Route::patch('{product}', 'update')->name('update');
    Route::delete('{product}', 'remove')->name('remove');
    Route::post('checkout', 'checkout')->middleware('throttle:10,1')->name('checkout');
    Route::get('done/{code}', 'done')->name('done');
});

/*
|--------------------------------------------------------------------------
| ورود، عضویت و حساب کاربری مشتریان
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->controller(CustomerAuthController::class)->group(function () {
    Route::get('login', 'showLogin')->name('login');
    Route::post('login', 'login')->middleware('throttle:10,1');
    Route::get('register', 'showRegister')->name('register');
    Route::post('register', 'register')->middleware('throttle:6,1');
});
Route::post('logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->controller(AccountController::class)->prefix('account')->group(function () {
    Route::get('/', 'index')->name('account');
    Route::put('/', 'update')->name('account.update');
    Route::get('orders/{code}', 'order')->name('account.order');
});

/*
|--------------------------------------------------------------------------
| پنل مدیریت
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::delete('customers/{user}', [CustomerController::class, 'destroy'])->name('customers.destroy');

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
