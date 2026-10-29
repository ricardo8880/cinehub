<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $routeLocale = config('localization.default');
        $locale = config("localization.locales.{$routeLocale}.locale");

        if (! is_string($routeLocale) || ! is_string($locale)) {
            throw new RuntimeException('The default localization configuration is invalid.');
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $routeLocale]);

        View::composer('layouts.public', function ($view): void {
            $view->with('publicSiteLinks', SiteSetting::query()
                ->whereIn('key', ['saas_url', 'donation_url'])
                ->pluck('value', 'key'));
        });
    }
}
