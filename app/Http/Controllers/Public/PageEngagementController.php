<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\VisitorIdentity;
use App\Support\VisitorActivityRecorder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageEngagementController extends Controller
{
    private const SESSION_KEY = 'mrrcine_page_engagement_visits';

    public function __construct(private readonly VisitorActivityRecorder $activity) {}

    public function __invoke(Request $request, VisitorIdentity $identity): JsonResponse
    {
        if ($identity->isBot($request) || ! $identity->analyticsAllowed($request)) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'token' => ['required', 'uuid'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'locale' => ['required', 'string', 'max:12'],
            'duration' => ['required', 'integer', 'min:0', 'max:14400'],
            'active' => ['required', 'integer', 'min:0', 'max:14400'],
            'interacted' => ['required', 'boolean'],
            'depth' => ['sometimes', 'integer', 'between:0,3'],
        ]);

        if (! array_key_exists($data['locale'], config('localization.locales', [])) ||
            ! Post::query()->published()->whereKey($data['post_id'])
                ->whereHas('localizations', fn ($q) => $q->where('locale', $data['locale'])->where('is_active', true))->exists()) {
            return response()->json(['ok' => false], 422);
        }

        // The Laravel session retains the last cumulative reading for each
        // page token. The DB stores one daily aggregate instead of one row
        // per visit. The route uses session blocking to serialize updates.
        if (! $request->hasSession()) {
            return response()->json(['ok' => false], 503);
        }

        $now = now();
        $visitorKey = $identity->analyticsKey($request);
        $visits = $request->session()->get(self::SESSION_KEY, []);
        $visits = is_array($visits) ? $visits : [];
        foreach ($visits as $token => $visit) {
            if (! is_array($visit) || ! isset($visit['last_seen']) || Carbon::parse($visit['last_seen'])->lt($now->copy()->subHours(6))) {
                unset($visits[$token]);
            }
        }

        $existing = $visits[$data['token']] ?? null;
        if ($existing !== null && (
            (int) $existing['post_id'] !== (int) $data['post_id'] ||
            $existing['locale'] !== $data['locale'] ||
            $existing['visitor_key'] !== $visitorKey
        )) {
            return response()->json(['ok' => false], 422);
        }

        // Carbon 3 has signed diffInSeconds(). Calculate elapsed time from
        // the *earlier* start toward now; never cap a visit to a negative time.
        $startedAt = $existing ? Carbon::parse($existing['started_at']) : $now;
        $elapsed = max(0, (int) $startedAt->diffInSeconds($now));
        $duration = min((int) $data['duration'], $elapsed + 5, 14400);
        $active = min((int) $data['active'], $duration);
        $engaged = $active >= 10 || (bool) $data['interacted'];
        $priorDuration = (int) ($existing['duration'] ?? 0);
        $priorActive = (int) ($existing['active'] ?? 0);
        $priorEngaged = (bool) ($existing['engaged'] ?? false);
        $firstEvent = $existing === null;
        $priorDepth = (int) ($existing['depth'] ?? 0);
        $depth = max($priorDepth, (int) ($data['depth'] ?? 0));

        $dailyKey = [
            'post_id' => (int) $data['post_id'],
            'locale' => $data['locale'],
            'visitor_key' => $visitorKey,
            'visit_date' => $existing['visit_date'] ?? $now->toDateString(),
        ];

        // insertOrIgnore handles simultaneous first visits; the increments
        // themselves are atomic, so separate tokens cannot overwrite totals.
        DB::table('page_engagement_daily')->insertOrIgnore($dailyKey + [
            'visit_count' => 0, 'duration_sum' => 0,
            'active_sum' => 0, 'bounced' => 0,
        ]);
        $durationDelta = max(0, $duration - $priorDuration);
        $activeDelta = max(0, $active - $priorActive);
        // Foreground reading is activity, even when the visitor remains on a
        // single page for longer than the inactivity threshold. The recorder
        // throttles timestamp updates to avoid a write on every heartbeat.
        if ($activeDelta > 0) {
            $this->activity->record($visitorKey);
        }
        $bounceDelta = $firstEvent ? ($engaged ? 0 : 1) : (! $priorEngaged && $engaged ? -1 : 0);
        DB::table('page_engagement_daily')->where($dailyKey)->update([
            'visit_count' => DB::raw('visit_count + '.($firstEvent ? 1 : 0)),
            'duration_sum' => DB::raw('duration_sum + '.$durationDelta),
            'active_sum' => DB::raw('active_sum + '.$activeDelta),
            'bounced' => DB::raw('bounced '.($bounceDelta < 0 ? '- '.abs($bounceDelta) : '+ '.$bounceDelta)),
            'read_start' => DB::raw('read_start + '.(int) ($priorDepth < 1 && $depth >= 1)),
            'read_middle' => DB::raw('read_middle + '.(int) ($priorDepth < 2 && $depth >= 2)),
            'read_end' => DB::raw('read_end + '.(int) ($priorDepth < 3 && $depth >= 3)),
        ]);

        $visits[$data['token']] = [
            'post_id' => (int) $data['post_id'],
            'locale' => $data['locale'],
            'visitor_key' => $visitorKey,
            'visit_date' => $dailyKey['visit_date'],
            'started_at' => $startedAt->toIso8601String(),
            'last_seen' => $now->toIso8601String(),
            'duration' => max($priorDuration, $duration),
            'active' => max($priorActive, $active),
            'engaged' => $priorEngaged || $engaged,
            'depth' => $depth,
        ];
        $request->session()->put(self::SESSION_KEY, $visits);

        return response()->json(['ok' => true]);
    }
}
