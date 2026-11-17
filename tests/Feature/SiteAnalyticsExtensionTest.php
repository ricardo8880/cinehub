<?php

namespace Tests\Feature;

use App\Http\Controllers\Public\SitePageViewController;
use App\Support\VisitorActivityRecorder;
use App\Support\VisitorIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SiteAnalyticsExtensionTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_views_count_every_consented_load_and_exclude_bots(): void
    {
        $controller = app(SitePageViewController::class);
        foreach (['home', 'home', 'blog', 'post'] as $type) {
            $request = Request::create('/en/site-page-view', 'POST', [
                'locale' => 'en-us', 'page_type' => $type,
            ], ['mrrcine_analytics_consent' => 'granted', 'mrrcine_analytics_visitor' => str_repeat('a', 32)], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
            $this->assertSame(200, $controller($request, app(VisitorIdentity::class), app(VisitorActivityRecorder::class))->status());
        }
        $this->assertSame(4, (int) DB::table('site_page_views_daily')->sum('views'));
        $this->assertSame(2, (int) DB::table('site_page_views_daily')->where('page_type', 'home')->value('views'));
        $bot = Request::create('/en/site-page-view', 'POST', ['locale' => 'en-us', 'page_type' => 'home'], ['mrrcine_analytics_consent' => 'granted'], [], ['HTTP_USER_AGENT' => 'Googlebot']);
        $controller($bot, app(VisitorIdentity::class), app(VisitorActivityRecorder::class));
        $this->assertSame(4, (int) DB::table('site_page_views_daily')->sum('views'));
    }
}
