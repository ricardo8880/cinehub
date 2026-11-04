<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\VisitorIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageEngagementController extends Controller
{
    public function __invoke(Request $request, VisitorIdentity $identity): JsonResponse
    {
        if ($identity->isBot($request)) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'token' => ['required', 'uuid'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'locale' => ['required', 'string', 'max:12'],
            'duration' => ['required', 'integer', 'min:0', 'max:14400'],
            'active' => ['required', 'integer', 'min:0', 'max:14400'],
            'interacted' => ['required', 'boolean'],
        ]);
        if (! array_key_exists($data['locale'], config('localization.locales', [])) ||
            ! Post::query()->published()->whereKey($data['post_id'])->whereHas('localizations', fn ($q) => $q->where('locale', $data['locale'])->where('is_active', true))->exists()) {
            return response()->json(['ok' => false], 422);
        }

        $now = now();
        $existing = DB::table('page_engagements')->where('visit_token', $data['token'])->first();
        if ($existing && ($existing->post_id != $data['post_id'] || $existing->locale !== $data['locale'] || $existing->visitor_key !== $identity->key($request))) {
            return response()->json(['ok' => false], 422);
        }
        $duration = min($data['duration'], $existing ? $now->diffInSeconds($existing->started_at) + 5 : 5, 14400);
        $active = min($data['active'], $duration);
        $engaged = $active >= 10 || (bool) $data['interacted'];
        if (! $existing) {
            DB::table('page_engagements')->insertOrIgnore([
                'visit_token' => $data['token'], 'post_id' => $data['post_id'],
                'locale' => $data['locale'], 'visitor_key' => $identity->key($request),
                'duration_seconds' => 0, 'active_seconds' => 0, 'engaged' => false,
                'started_at' => $now, 'updated_at' => $now,
            ]);
        } else {
            DB::table('page_engagements')->where('visit_token', $data['token'])->update([
                'duration_seconds' => max($existing->duration_seconds, $duration),
                'active_seconds' => max($existing->active_seconds, $active),
                'engaged' => $existing->engaged || $engaged,
                'updated_at' => $now,
            ]);
        }
        return response()->json(['ok' => true]);
    }
}
