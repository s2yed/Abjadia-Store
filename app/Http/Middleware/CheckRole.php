<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            // If user is a customer trying to access restricted routes, redirect to home
            if ($request->user() && $request->user()->role === 'customer') {
                return redirect('/')->with('error', __('unauthorized'));
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
