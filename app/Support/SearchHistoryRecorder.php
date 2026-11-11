<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Aggregate deliberate public searches; do not retain visitor identifiers or IPs. */
final class SearchHistoryRecorder
{
    public function record(Request $request, string $locale, string $term, int $resultCount, ?string $eventId = null): void
    {
        $term = Str::squish($term);
        if ($term === '' || mb_strlen($term) > 120 || $this->isBot($request)) {
            return;
        }

        $normalized = Str::lower($term);
        $eventId = is_string($eventId) && preg_match('/^[a-zA-Z0-9_-]{1,64}$/', $eventId) ? $eventId : null;
        $seen = $request->session()->get('cinehub_search_seen', []);
        $seen = is_array($seen) ? $seen : [];
        $nowTimestamp = time();
        $seen = array_filter($seen, fn ($timestamp) => is_int($timestamp) && $timestamp >= $nowTimestamp - 3600);

        if ($eventId !== null && isset($seen[$eventId])) {
            return; // Back/refresh with the same submitted search event.
        }

        // URLs without a search event (bookmarks, links, no-JS forms) get a short
        // session cooldown so ordinary refreshes do not inflate the figures.
        $last = $request->session()->get('cinehub_search_last_unmarked');
        if ($eventId === null && is_array($last)
            && ($last['locale'] ?? null) === $locale
            && ($last['term'] ?? null) === $normalized
            && ($last['time'] ?? 0) >= $nowTimestamp - 20) {
            return;
        }

        $now = now();
        $resultCount = max(0, $resultCount);
        $inserted = DB::table('search_terms')->insertOrIgnore([
            'locale' => $locale,
            'term' => $term,
            'normalized_term' => $normalized,
            'search_count' => 1,
            'last_result_count' => $resultCount,
            'first_searched_at' => $now,
            'last_searched_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if (! $inserted) {
            DB::table('search_terms')
                ->where('locale', $locale)
                ->where('normalized_term', $normalized)
                ->update([
                    'search_count' => DB::raw('search_count + 1'),
                    'last_result_count' => $resultCount,
                    'last_searched_at' => $now,
                    'updated_at' => $now,
                ]);
        }

        if ($eventId !== null) {
            $seen[$eventId] = $nowTimestamp;
            $request->session()->put('cinehub_search_seen', array_slice($seen, -50, null, true));
        } else {
            $request->session()->put('cinehub_search_last_unmarked', [
                'locale' => $locale, 'term' => $normalized, 'time' => $nowTimestamp,
            ]);
        }
    }

    private function isBot(Request $request): bool
    {
        $agent = (string) $request->userAgent();

        return (bool) preg_match('/bot\b|crawler|spider|slurp|bingpreview|headlesschrome|lighthouse|facebookexternalhit/i', $agent);
    }
}
