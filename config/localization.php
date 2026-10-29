<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default URL Locale
    |--------------------------------------------------------------------------
    |
    | This is the locale used when a visitor enters the application without a
    | locale prefix. Keep URL locales separate from Laravel's internal locale.
    |
    */

    'default' => env('LOCALIZATION_DEFAULT', 'en-us'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | The array key is used in the URL. "locale" is Laravel's translator
    | locale. Country and currency metadata stay centralized here so future
    | regional behavior does not need to be hardcoded throughout the project.
    |
    */

    'locales' => [
        'en-us' => [
            'locale' => 'en_US',
            'language' => 'en',
            'region' => 'US',
            'country' => 'United States',
            'currency' => 'USD',
            'currency_symbol' => '$',
        ],
        'en-gb' => [
            'locale' => 'en_GB',
            'language' => 'en',
            'region' => 'GB',
            'country' => 'United Kingdom',
            'currency' => 'GBP',
            'currency_symbol' => '£',
        ],
        'en-au' => [
            'locale' => 'en_AU',
            'language' => 'en',
            'region' => 'AU',
            'country' => 'Australia',
            'currency' => 'AUD',
            'currency_symbol' => 'A$',
        ],
        'en-ca' => [
            'locale' => 'en_CA',
            'language' => 'en',
            'region' => 'CA',
            'country' => 'Canada',
            'currency' => 'CAD',
            'currency_symbol' => 'C$',
        ],
        'ja-jp' => [
            'locale' => 'ja_JP',
            'language' => 'ja',
            'region' => 'JP',
            'country' => 'Japan',
            'currency' => 'JPY',
            'currency_symbol' => '¥',
        ],
    ],
];
