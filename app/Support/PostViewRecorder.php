<?php

namespace App\Support;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Records at most one qualifying view every six hours. Repeated qualifying
 * views by the same visitor of the same post/locale/day update one row.
 * Historical individual view rows are still read for the six-hour window.
 */
final class PostViewRecorder
{
    public function __construct(
        private readonly VisitorIdentity $identity,
        private readonly VisitorActivityRecorder $activity,
    ) {}

    public function record(Request $request, Post $post, string $locale): bool
    {
        if ($this->identity->isBot($request) || ! $this->identity->analyticsAllowed($request)) {
            return false;
        }

        $visitorKey = $this->identity->analyticsKey($request);
        // Count a return only after real inactivity, even if this page view is
        // ignored by the six-hour per-post deduplication rule.
        $this->activity->record($visitorKey);
        $now = now();
        $cutoff = $now->copy()->subHours(6);

        if (PostView::query()
            ->where('post_id', $post->id)
            ->whereIn('locale', EnglishGroup::history($locale))
            ->whereIn('visitor_key', array_unique([$visitorKey, $this->identity->key($request)]))
            ->where('viewed_at', '>=', $cutoff)
            ->exists()) {
            return false;
        }

        $dailyKey = [
            'post_id' => $post->id,
            'locale' => $locale,
            'visitor_key' => $visitorKey,
            'view_date' => $now->toDateString(),
        ];

        // The conditional UPDATE is atomic even when two requests reach this
        // point simultaneously. INSERT IGNORE handles a race for a first visit.
        if (DB::table('post_views')->where($dailyKey)
            ->where('viewed_at', '<', $cutoff)
            ->update(['view_count' => DB::raw('view_count + 1'), 'viewed_at' => $now]) > 0) {
            return true;
        }

        return DB::table('post_views')->insertOrIgnore($dailyKey + [
            'view_count' => 1,
            'first_viewed_at' => $now,
            'viewed_at' => $now,
        ]) > 0;
    }
}
