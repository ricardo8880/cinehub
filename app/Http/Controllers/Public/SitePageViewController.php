<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\VisitorActivityRecorder;
use App\Support\VisitorIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SitePageViewController extends Controller
{
    public function __invoke(Request $request, VisitorIdentity $identity, VisitorActivityRecorder $activity): JsonResponse
    {
        if ($identity->isBot($request) || ! $identity->analyticsAllowed($request)) {
            return response()->json(['ok' => true]);
        }
        $data = $request->validate([
            'locale' => ['required', 'string', 'max:12'],
            'page_type' => ['required', 'in:home,category,blog,post,search,book,other'],
        ]);
        if (! array_key_exists($data['locale'], config('localization.locales', []))) {
            return response()->json(['ok' => false], 422);
        }
        $key = ['view_date' => now()->toDateString(), 'locale' => $data['locale'], 'page_type' => $data['page_type']];
        DB::table('site_page_views_daily')->insertOrIgnore($key + ['views' => 0]);
        DB::table('site_page_views_daily')->where($key)->increment('views');
        $activity->record($identity->analyticsKey($request));
        return response()->json(['ok' => true]);
    }
}
