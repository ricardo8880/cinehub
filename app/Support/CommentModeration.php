<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\ModerationTerm;

class CommentModeration
{
    public function classify(string $body, ?string $locale = null): string
    {
        $text = $this->normalize($body);

        if ($text === '') {
            return 'safe';
        }

        $locale ??= (string) request()->route('locale');
        // General safeguards apply to every language, with additional rules
        // for each supported audience. Admin terms stay language-specific.
        $language = (string) config("localization.locales.{$locale}.language", 'en');
        $blockedPatterns = array_merge(
            config('comment_moderation.blocked', []),
            config("comment_moderation.localized.{$language}.blocked", []),
        );

        foreach ($blockedPatterns as $pattern) {
            if (@preg_match('~'.$pattern.'~iu', $text)) {
                return 'blocked';
            }
        }

        if (isset(config('localization.locales')[$locale])) {
            $custom = Cache::remember('cinehub:moderation:'.$locale, now()->addMinutes(5),
                fn () => ModerationTerm::query()->where('is_active', true)->whereIn('locale', array_merge(['*'], EnglishGroup::history($locale)))->get(['phrase', 'classification']));
            $review = false;
            foreach ($custom as $term) {
                $phrase = $this->normalize($term->phrase);
                if ($phrase !== '' && preg_match('~(?<![\p{L}\p{N}])'.preg_quote($phrase, '~').'(?![\p{L}\p{N}])~iu', $text)) {
                    if ($term->classification === 'blocked') return 'blocked';
                    $review = true;
                }
            }
            if ($review) return 'review';
        }

        $reviewPatterns = array_merge(
            config('comment_moderation.review', []),
            config("comment_moderation.localized.{$language}.review", []),
        );

        foreach ($reviewPatterns as $pattern) {
            if (@preg_match('~'.$pattern.'~iu', $text)) {
                return 'review';
            }
        }


        // Long repetition, shouting and unusual sequences are typical of spam or bypass attempts.
        if (preg_match('~(.)\\1{10,}~u', $text) || preg_match('~\\b(\\S+)(?:\\s+\\1){4,}\\b~iu', $text)) {
            return 'review';
        }

        if (preg_match_all('~https?://|www\\.~iu', $text) >= 1) {
            return 'review';
        }

        return 'safe';
    }

    private function normalize(string $text): string
    {
        if (class_exists(\Normalizer::class)) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_KC) ?: $text;
        }

        $text = Str::lower(strip_tags($text));
        $text = str_replace(["\u{200B}", "\u{200C}", "\u{200D}", "\u{FEFF}"], '', $text);
        $text = strtr($text, ['@' => 'a', '0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's', '7' => 't', '$' => 's']);
        $text = preg_replace('~[\\p{C}]+~u', ' ', $text) ?? $text;
        $text = preg_replace('~\\s+~u', ' ', $text) ?? $text;

        // Join separated single-letter runs (f.u.c.k / f u c k) before applying the same rules.
        $text = preg_replace_callback(
            '~(?<![\p{L}\p{N}])(?:[a-z0-9][\s.\-_*]+){3,}[a-z0-9](?![\p{L}\p{N}])~iu',
            static fn ($matches) => preg_replace('~[^a-z0-9]~i', '', $matches[0]),
            $text
        ) ?? $text;

        return trim($text);
    }
}
