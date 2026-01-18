<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'status' => \App\Http\Middleware\CheckUserStatus::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
            'dashboard.access' => \App\Http\Middleware\RedirectIfCustomerToProfile::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/payment/webhook',
        ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin/*') || $request->is('admin') || $request->is('dashboard/*') || $request->is('dashboard')) {
                return route('admin.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
