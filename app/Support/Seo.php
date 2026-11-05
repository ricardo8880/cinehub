<?php

namespace App\Support;

/**
 * SEO policy for public URL locales (which need not match database locales).
 */
final class Seo
{
    public static function indexable(string $locale): bool
    {
        $storageLocale = $locale === 'en' ? 'en-us' : $locale;

        if (! array_key_exists($storageLocale, config('localization.locales', []))) {
            return false;
        }

        // Translated sections remain accessible for editing and review, but
        // should not be indexed before their editorial content is ready.
        return match ($storageLocale) {
            'fr' => (bool) config('seo.index_french', false),
            'de' => (bool) config('seo.index_german', false),
            default => true,
        };
    }

    /** @return list<string> */
    public static function indexableLocales(): array
    {
        return array_values(array_filter(
            array_keys(config('localization.locales', [])),
            self::indexable(...),
        ));
    }

    public static function language(string $locale): string
    {
        $storageLocale = $locale === 'en' ? 'en-us' : $locale;
        $config = config("localization.locales.{$storageLocale}");

        if (! is_array($config)) {
            return 'en';
        }

        $language = strtolower((string) $config['language']);

        // International content targets the language, not one country.
        return in_array($language, ['en', 'fr', 'de'], true)
            ? $language
            : $language.'-'.strtoupper((string) $config['region']);
    }

    /**
     * Hreflang sets must only contain real, publicly indexable and equivalent
     * pages. Duplicate English regional storage keys collapse into one URL.
     *
     * @param array<string, string|null> $urls keyed by storage locale
     * @return array<string, string> keyed by BCP 47 language tag
     */
    public static function alternateLinks(array $urls): array
    {
        $alternates = [];

        foreach ($urls as $locale => $url) {
            if (! is_string($url) || $url === '' || ! self::indexable($locale)) {
                continue;
            }

            $alternates[self::language($locale)] = $url;
        }

        return $alternates;
    }
}
