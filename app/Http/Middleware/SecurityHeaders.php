<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Inline scripts and styles are currently used by public pages and the
        // editor, so this allowlist is compatible with the existing frontend.
        $isAdmin = $request->is('admin', 'admin/*');
        $scriptSources = $isAdmin
            ? "'self' 'unsafe-inline' https://cdn.jsdelivr.net"
            : "'self' 'unsafe-inline' https://www.googletagmanager.com https://*.google-analytics.com https://*.googlesyndication.com https://*.googleadservices.com https://*.doubleclick.net https://*.google.com https://cdn.jsdelivr.net";
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                'script-src '.$scriptSources,
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "font-src 'self' data: https://cdn.jsdelivr.net",
                "img-src 'self' data: https:",
                "connect-src 'self' https://*.google-analytics.com https://*.googlesyndication.com https://*.google.com https://*.doubleclick.net",
                "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://*.doubleclick.net https://*.google.com",
                "media-src 'self' https:",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
            ]));
        }

        if ($request->isSecure() && app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        if ($isAdmin) {
            $response->headers->set('Cache-Control', 'no-store, private');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }
}
