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

        //  if (app()->environment('production') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
        //         \Illuminate\Support\Facades\URL::forceScheme('https');
        //     }
        // Force HTTPS URLs when behind a proxy (cloud platforms like Koyeb)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Also force HTTPS in production environment
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
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
