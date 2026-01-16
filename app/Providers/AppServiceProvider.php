<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production or when behind an SSL proxy (Koyeb), but NEVER on local
        if (!app()->isLocal()) {
            if (app()->environment('production') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            }
        }

        try {
            // Share global categories for navbar
            $navbarCategories = (new \App\Services\CategoryService())->getNavbarCategories();

            \Illuminate\Support\Facades\View::share('navbarCategories', $navbarCategories);
        } catch (\Exception $e) {
            // Fallback if migration hasn't run yet or other DB issue
            \Illuminate\Support\Facades\View::share('navbarCategories', collect([]));
        }
    }
}
