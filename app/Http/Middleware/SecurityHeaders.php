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
        // Generate CSP nonce for inline scripts
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Strict Transport Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $scriptSources = [
            "'self'",
            "'nonce-{$nonce}'",
            'https://www.gstatic.com',
            'https://apis.google.com',
            'https://*.firebaseapp.com',
            'https://*.googleapis.com',
        ];

        $connectSources = [
            "'self'",
            'https://accounts.google.com',
            'https://*.firebaseio.com',
            'https://*.googleapis.com',
            'https://*.firebaseapp.com',
        ];

        if (app()->environment('local')) {
            $scriptSources = array_merge($scriptSources, [
                'http://localhost:5173',
                'http://127.0.0.1:5173',
            ]);

            $connectSources = array_merge($connectSources, [
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'ws://localhost:5173',
                'ws://127.0.0.1:5173',
            ]);
        }

        // Content Security Policy - use nonce instead of unsafe-inline
        $csp = "default-src 'self'; ".
               "script-src ".implode(' ', $scriptSources)."; ".
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
               "font-src 'self' https://fonts.gstatic.com; ".
               "img-src 'self' data: https:; ".
               "connect-src ".implode(' ', $connectSources)."; ".
               "frame-src 'self' https://accounts.google.com https://apis.google.com https://*.firebaseapp.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        // Add nonce to response for use in views
        $response->headers->set('X-CSP-Nonce', $nonce);

        // Permissions Policy - restrict unused browser features
        $permissions = 'camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), accelerometer=(), gyroscope=()';
        $response->headers->set('Permissions-Policy', $permissions);

        return $response;
    }
}
