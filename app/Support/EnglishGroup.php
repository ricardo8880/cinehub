<?php

namespace App\Support;

/**
 * Public English region (/en) with the original en-us persistence key.
 * Legacy regional activity remains readable without modifying historical data.
 */
final class EnglishGroup
{
    private const HISTORIC_LOCALES = ['en-us', 'en-gb', 'en-ca', 'en-au'];

    public static function history(string $locale): array
    {
        return in_array($locale, ['en', ...self::HISTORIC_LOCALES], true)
            ? self::HISTORIC_LOCALES
            : [$locale];
    }

    public static function expand(array $locales): array
    {
        return array_values(array_unique(array_merge(...array_map(self::history(...), $locales))));
    }

    public static function publicLocale(string $locale): string
    {
        return in_array($locale, self::HISTORIC_LOCALES, true) ? 'en' : $locale;
    }
}
