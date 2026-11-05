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
        // Keep existing stored content (en-us), but expose one English entry in
        // the admin and in regional menus. Other languages are untouched.
        $locales = config('localization.locales', []);

        if (isset($locales['en-us'])) {
            foreach (['en-gb', 'en-ca', 'en-au'] as $regionalEnglish) {
                unset($locales[$regionalEnglish]);
            }

            $locales['en-us']['region'] = 'EN';
            $locales['en-us']['country'] = 'English (US, UK, CA, AU)';
            $locales['en-us']['label'] = 'English';
            config()->set('localization.locales', $locales);
        }
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

        // Named-route URLs continue to use en-us as their storage locale,
        // but visitors only ever see the common /en address.
        URL::formatPathUsing(static fn (string $path): string => preg_replace(
            '~^/en-(?:us|gb|ca|au)(?=/|$)~i', '/en', $path
        ) ?? $path);

        View::composer('layouts.public', function ($view): void {
            $view->with('publicSiteLinks', SiteSetting::query()
                ->whereIn('key', ['saas_url', 'donation_url'])
                ->pluck('value', 'key'));
        });
    }
}
