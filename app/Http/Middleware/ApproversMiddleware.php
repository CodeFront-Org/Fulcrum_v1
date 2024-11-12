<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApproversMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {        
        // Check if user is logged in and has 'admin' role
        if (Auth::check() && (Auth::user()->role_type === 'admin' || Auth::user()->role_type === 'hro' || Auth::user()->role_type === 'finance')) {
            return $next($request);
        }

        // Redirect or show unauthorized page if not an admin
        return back();
    }
}
