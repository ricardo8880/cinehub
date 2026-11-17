<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Recognizes a return only after 30 minutes without site activity.
 * One row per consenting visitor/day, not one row per page view or session.
 * The first visit of an unknown identifier can never be called a return.
 */
final class VisitorActivityRecorder
{
    private const INACTIVITY_MINUTES = 30;

    // Foreground engagement pings may arrive every minute. At most one
    // activity timestamp update per five minutes within an active session.
    private const TOUCH_MINUTES = 5;

    public function record(string $visitorKey): void
    {
        $now = now();
        $today = $now->toDateString();
        $idleCutoff = $now->copy()->subMinutes(self::INACTIVITY_MINUTES);
        $touchCutoff = $now->copy()->subMinutes(self::TOUCH_MINUTES);

        DB::transaction(function () use ($visitorKey, $today, $now, $idleCutoff, $touchCutoff): void {
            // Serialize updates for this visitor when a row is already present.
            $latest = DB::table('visitor_activity_daily')
                ->where('visitor_key', $visitorKey)
                ->orderByDesc('activity_date')
                ->lockForUpdate()
                ->first();

            // Ignore an older concurrent request that finished after a newer day.
            if ($latest !== null && $latest->activity_date > $today) {
                return;
            }

            if ($latest === null || $latest->activity_date !== $today) {
                $returned = $latest !== null && Carbon::parse($latest->last_seen_at)->lte($idleCutoff);

                // A unique visitor/day key prevents concurrent page loads
                // from inserting multiple activity rows.
                $created = DB::table('visitor_activity_daily')->insertOrIgnore([
                    'visitor_key' => $visitorKey,
                    'activity_date' => $today,
                    'first_seen_at' => $now,
                    'last_seen_at' => $now,
                    'first_returned_at' => $returned ? $now : null,
                ]);

                if ($created > 0) {
                    return;
                }

                // Another request created today's row first. Treat it as
                // an existing visit instead of double-counting a return.
                $latest = DB::table('visitor_activity_daily')
                    ->where('visitor_key', $visitorKey)
                    ->where('activity_date', $today)
                    ->lockForUpdate()
                    ->first();

                if ($latest === null) {
                    return;
                }
            }

            $lastSeen = Carbon::parse($latest->last_seen_at);
            if ($lastSeen->gt($now) || $lastSeen->gt($touchCutoff)) {
                return; // Same session, and the last activity is still fresh.
            }

            $update = ['last_seen_at' => $now];
            if ($latest->first_returned_at === null && $lastSeen->lte($idleCutoff)) {
                $update['first_returned_at'] = $now;
            }

            DB::table('visitor_activity_daily')
                ->where('visitor_key', $visitorKey)
                ->where('activity_date', $today)
                ->update($update);
        }, 3);
    }
}
