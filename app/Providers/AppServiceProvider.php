<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

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
        $routeLocale = config('localization.default');
        $locale = config("localization.locales.{$routeLocale}.locale");

        if (! is_string($routeLocale) || ! is_string($locale)) {
            throw new RuntimeException('The default localization configuration is invalid.');
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $routeLocale]);
    }
}
