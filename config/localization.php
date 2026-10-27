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
    | Add future languages and regions here. The array key is the value used
    | in the URL; "locale" is the locale used by Laravel's translator.
    |
    */

    'locales' => [
        'en-us' => [
            'locale' => 'en_US',
            'language' => 'en',
            'region' => 'US',
        ],
        'en-gb' => [
            'locale' => 'en_GB',
            'language' => 'en',
            'region' => 'GB',
        ],
    ],
];
