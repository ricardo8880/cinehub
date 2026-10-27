<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the locale selected by the URL to the current request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeLocale = $request->route('locale');
        $locale = config("localization.locales.{$routeLocale}");

        abort_unless(is_array($locale), 404);

        app()->setLocale($locale['locale']);
        URL::defaults(['locale' => $routeLocale]);

        return $next($request);
    }
}
