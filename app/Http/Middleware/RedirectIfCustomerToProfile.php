<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfCustomerToProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check web guard (standard customer login)
        $webUser = \Illuminate\Support\Facades\Auth::guard('web')->user();
        if ($webUser && strtolower($webUser->role) === 'customer') {
            \Illuminate\Support\Facades\Log::info('Redirecting customer (web guard) from dashboard');
            return redirect('/my-account')->with('error', 'Unauthorized access.');
        }

        // 2. Check admin guard (just in case)
        $adminUser = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if ($adminUser && strtolower($adminUser->role) === 'customer') {
            \Illuminate\Support\Facades\Log::info('Redirecting customer (admin guard) from dashboard');
            return redirect('/my-account')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
