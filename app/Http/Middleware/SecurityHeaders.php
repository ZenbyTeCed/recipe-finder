<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Strict Transport Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Content Security Policy - allow Firebase, Google APIs, and inline content
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://www.gstatic.com https://*.firebaseapp.com https://*.googleapis.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https://*.firebaseio.com https://*.googleapis.com https://*.firebaseapp.com; " .
               "frame-src 'self' https://*.firebaseapp.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions Policy - restrict unused browser features
        $permissions = "camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), accelerometer=(), gyroscope=()";
        $response->headers->set('Permissions-Policy', $permissions);

        return $response;
    }
}
