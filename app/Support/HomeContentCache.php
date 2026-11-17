<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class HomeContentCache
{
    /** Invalidate cached titles, previews and media URLs when editorial data changes. */
    public static function forget(): void
    {
        foreach (array_keys(config('localization.locales')) as $locale) {
            Cache::forget("mrrcine:home:{$locale}:popular");
            Cache::forget("mrrcine:home:{$locale}:trending");
        }
    }
}
