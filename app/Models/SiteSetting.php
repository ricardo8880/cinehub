<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /** Each language's book fields are independent; legacy keys belong only to English. */
    public static function bookKey(string $field, string $locale): string
    {
        return 'book_'.$field.(match ($locale) {
            'en', 'en-us' => '',
            'fr' => '_fr',
            'de' => '_de',
            default => throw new \InvalidArgumentException('Unsupported book locale.'),
        });
    }

    /** @return array{title:?string,synopsis:?string,cover_url:?string,sample_url:?string,purchase_url:?string} */
    public static function bookFor(string $locale): array
    {
        $fields = ['title', 'synopsis', 'cover_url', 'sample_url', 'purchase_url'];
        $keys = array_map(fn (string $field): string => self::bookKey($field, $locale), $fields);
        $data = static::query()->whereIn('key', $keys)->pluck('value', 'key');

        return array_combine($fields, array_map(fn (string $key) => $data[$key] ?? null, $keys));
    }

    public static function valueFor(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }
}
