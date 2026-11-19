<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    private const PUBLIC_CACHE_KEY = 'mrrcine:public:site-settings';

    /** Single cached lookup shared across the public layout, book and sitemap. */
    public static function publicValues(): array
    {
        return Cache::remember(self::PUBLIC_CACHE_KEY, now()->addMinutes(5),
            fn (): array => static::query()->pluck('value', 'key')->all());
    }

    /** Publishing settings in the admin must appear without waiting for TTL. */
    public static function forgetPublicCache(): void
    {
        Cache::forget(self::PUBLIC_CACHE_KEY);
    }

    /** Each language's book fields are independent; legacy keys belong only to English. */
    public static function bookKey(string $field, string $locale): string
    {
        return 'book_'.$field.(match ($locale) {
            'en', 'en-us' => '',
            'fr' => '_fr',
            'de' => '_de',
            'pt-br' => '_pt_br',
            default => throw new \InvalidArgumentException('Unsupported book locale.'),
        });
    }

    /** @return array{title:?string,synopsis:?string,cover_url:?string,sample_url:?string,purchase_url:?string} */
    public static function bookFor(string $locale): array
    {
        $fields = ['title', 'synopsis', 'cover_url', 'sample_url', 'purchase_url'];
        $keys = array_map(fn (string $field): string => self::bookKey($field, $locale), $fields);
        $data = self::publicValues();

        return array_combine($fields, array_map(fn (string $key) => $data[$key] ?? null, $keys));
    }

    public static function valueFor(string $key, ?string $default = null): ?string
    {
        return self::publicValues()[$key] ?? $default;
    }
}
