<?php

namespace App\Support;

/** Editorial meta descriptions; never truncate a word just to reach a character limit. */
final class SeoDescription
{
    public const PREVIEW_LENGTH = 160;

    public static function summarize(?string $content, int $limit = self::PREVIEW_LENGTH): string
    {
        $text = RichTextHtml::plainText($content);
        if ($text === '' || $limit <= 0) {
            return '';
        }

        if (self::length($text) <= $limit) {
            return $text;
        }

        $prefix = self::substring($text, 0, $limit);
        $next = self::substring($text, $limit, 1);
        if (preg_match('/^\s$/u', $next)) {
            return rtrim($prefix);
        }

        // The final word was interrupted: omit it instead of publishing a fragment.
        $atWordBoundary = preg_replace('/\s+\S*$/u', '', $prefix);

        return trim($atWordBoundary ?: $prefix);
    }

    public static function resolve(?string $stored, ?string $source, string $fallback = ''): string
    {
        $saved = RichTextHtml::plainText($stored);
        $original = RichTextHtml::plainText($source);

        if ($saved === '') {
            return self::summarize($original !== '' ? $original : $fallback);
        }

        // Older automatic values were cut at 170 characters. Repair those exact
        // prefixes at render time, without overwriting custom descriptions.
        $length = self::length($saved);
        if ($length >= self::PREVIEW_LENGTH && $length <= 170
            && self::length($original) > $length
            && self::substring($original, 0, $length) === $saved) {
            return self::summarize($original);
        }

        return $saved;
    }

    private static function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : iconv_strlen($value, 'UTF-8');
    }

    private static function substring(string $value, int $offset, ?int $length = null): string
    {
        return function_exists('mb_substr')
            ? mb_substr($value, $offset, $length, 'UTF-8')
            : iconv_substr($value, $offset, $length, 'UTF-8');
    }
}
