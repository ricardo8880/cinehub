<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\MetricsController;
use App\Http\Controllers\Public\PageEngagementController;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Support\VisitorIdentity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Phase2EngagementAndConsentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function publishedPost(): Post
    {
        $post = Post::create([
            'status' => 'published',
            'publication_type' => 'blog',
            'published_at' => now()->subDay(),
        ]);
        PostLocalization::create([
            'post_id' => $post->id,
            'locale' => 'en-us',
            'slug' => 'test-post-'.$post->id,
            'title' => 'Phase 2 test post',
            'is_active' => true,
        ]);

        return $post;
    }

    private function send(Store $session, Post $post, string $token, int $duration, int $active, bool $interacted = false, string $consent = 'granted'): void
    {
        $request = Request::create('/en/page-engagement', 'POST', [
            'token' => $token,
            'post_id' => $post->id,
            'locale' => 'en-us',
            'duration' => $duration,
            'active' => $active,
            'interacted' => $interacted,
        ], [
            'mrrcine_analytics_consent' => $consent,
            'mrrcine_analytics_visitor' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
        $request->setLaravelSession($session);
        $request->setUserResolver(fn () => null);
        $this->assertSame(200, app(PageEngagementController::class)($request, app(VisitorIdentity::class))->getStatusCode());
    }

    public function test_daily_engagement_accumulates_multiple_visits_and_avoids_duplicate_pings(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $session = new Store('test', new ArraySessionHandler(120));
        $session->start();
        $firstToken = '11111111-1111-4111-8111-111111111111';
        $secondToken = '22222222-2222-4222-8222-222222222222';

        $this->send($session, $post, $firstToken, 0, 0);
        Carbon::setTestNow('2026-11-16 08:00:30');
        $this->send($session, $post, $firstToken, 30, 15);
        $this->send($session, $post, $firstToken, 30, 15);
        Carbon::setTestNow('2026-11-16 08:00:40');
        $this->send($session, $post, $secondToken, 0, 0);

        $this->assertSame(0, DB::table('page_engagements')->count());
        $this->assertSame(1, DB::table('page_engagement_daily')->count());
        $row = DB::table('page_engagement_daily')->first();
        $this->assertSame(2, (int) $row->visit_count);
        $this->assertSame(30, (int) $row->duration_sum);
        $this->assertSame(15, (int) $row->active_sum);
        $this->assertSame(1, (int) $row->bounced);

        Carbon::setTestNow('2026-11-17 07:00:00');
        $this->send($session, $post, '33333333-3333-4333-8333-333333333333', 0, 0);
        $this->assertSame(2, DB::table('page_engagement_daily')->count());
        $this->assertSame(3, (int) DB::table('page_engagement_daily')->sum('visit_count'));
    }

    public function test_denied_analytics_does_not_write_engagement(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $session = new Store('test', new ArraySessionHandler(120));
        $session->start();
        $this->send($session, $post, '11111111-1111-4111-8111-111111111111', 30, 20, false, 'denied');
        $this->assertSame(0, DB::table('page_engagement_daily')->count());
    }

    public function test_admin_metrics_combines_historical_sessions_and_new_daily_totals(): void
    {
        Carbon::setTestNow('2026-11-16 09:00:00');
        $post = $this->publishedPost();
        $session = new Store('test', new ArraySessionHandler(120));
        $session->start();
        $this->send($session, $post, '11111111-1111-4111-8111-111111111111', 0, 0);
        Carbon::setTestNow('2026-11-16 09:00:30');
        $this->send($session, $post, '11111111-1111-4111-8111-111111111111', 30, 15);

        DB::table('page_engagements')->insert([
            'visit_token' => '99999999-9999-4999-8999-999999999999',
            'post_id' => $post->id, 'locale' => 'en-us',
            'visitor_key' => 'historic-visitor', 'duration_seconds' => 10,
            'active_seconds' => 2, 'engaged' => false,
            'started_at' => now(), 'updated_at' => now(),
        ]);

        $metrics = new \ReflectionMethod(MetricsController::class, 'metricsData');
        $data = $metrics->invoke(new MetricsController(), Request::create('/admin/metrics', 'GET'));
        $this->assertSame(2, $data['pageTime']['measured_visits']);
        $this->assertSame(20.0, $data['pageTime']['average_page_seconds']);
        $this->assertSame(8.5, $data['pageTime']['average_engaged_seconds']);
        $this->assertSame(50.0, $data['pageTime']['bounce_rate_percent']);
    }

    public function test_cookie_choice_is_visible_before_consent_and_hidden_after_refusal(): void
    {
        $this->get('/en')->assertOk()->assertSee('Cookie Preferences')->assertSee('Reject analytics');
        $this->get('/en/blog')->assertOk()->assertSee('Cookie Preferences')->assertSee('Reject analytics');

        // Keep EncryptCookies enabled: Laravel's withCookie() already encrypts the
        // outgoing test cookie, exactly as the browser receives it from the app.
        $this->from('/en')->post('/en/privacy/consent', ['analytics' => 'denied'])
            ->assertRedirect()->assertCookie('mrrcine_analytics_consent', 'denied');

        $this->withCookie('mrrcine_analytics_consent', 'denied')
            ->get('/en')->assertOk()->assertDontSee('Cookie Preferences');
        $this->withCookie('mrrcine_analytics_consent', 'denied')
            ->get('/en/blog')->assertOk()->assertDontSee('Cookie Preferences');
    }

    public function test_cookie_choice_is_hidden_after_acceptance(): void
    {
        $this->from('/en')->post('/en/privacy/consent', ['analytics' => 'granted'])
            ->assertRedirect()->assertCookie('mrrcine_analytics_consent', 'granted');

        $this->withCookie('mrrcine_analytics_consent', 'granted')
            ->get('/en')->assertOk()->assertDontSee('Cookie Preferences');
        $this->withCookie('mrrcine_analytics_consent', 'granted')
            ->get('/en/blog')->assertOk()->assertDontSee('Cookie Preferences');
    }
}
