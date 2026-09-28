<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['admin' => \App\Http\Middleware\EnsureUserIsAdmin::class]);
        $middleware->redirectGuestsTo(fn ($request) => $request->is('admin', 'admin/*') ? route('admin.login') : route('login'));
        $middleware->redirectUsersTo(fn ($request) => $request->user()?->is_admin && $request->is('admin', 'admin/*') ? route('admin.dashboard') : route('account'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
