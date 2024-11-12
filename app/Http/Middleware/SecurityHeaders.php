<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Add security headers
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload')
                 //->header('Content-Security-Policy', "default-src 'self'; script-src 'self'; object-src 'none';")
                 ->header('X-Frame-Options', 'DENY')
                 ->header('X-Content-Type-Options', 'nosniff')
                 ->header('Referrer-Policy', 'no-referrer')
                 ->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
