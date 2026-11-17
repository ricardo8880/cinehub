<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\MetricsController;
use App\Http\Controllers\Public\PageEngagementController;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Models\PostView;
use App\Support\PostViewRecorder;
use App\Support\VisitorActivityRecorder;
use App\Support\VisitorIdentity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Phase2ReturningVisitorsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function requestFor(string $id = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', string $consent = 'granted'): Request
    {
        return Request::create('/en/blog/example', 'GET', [], [
            'mrrcine_analytics_consent' => $consent,
            'mrrcine_analytics_visitor' => $id,
        ], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
    }

    private function publishedPost(): Post
    {
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subMonth()]);
        PostLocalization::create([
            'post_id' => $post->id,
            'locale' => 'en-us',
            'slug' => 'return-test-'.$post->id,
            'title' => 'Return test',
            'is_active' => true,
        ]);

        return $post;
    }

    private function metrics(?string $locale = null, int $period = 7): array
    {
        $request = Request::create('/admin/metrics', 'GET', array_filter([
            'locale' => $locale,
            'period' => $period,
        ], fn ($value) => $value !== null));

        return (new \ReflectionMethod(MetricsController::class, 'metricsData'))
            ->invoke(new MetricsController(), $request);
    }

    public function test_multiple_pages_same_session_do_not_create_a_return(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $one = $this->publishedPost();
        $two = $this->publishedPost();
        $request = $this->requestFor();
        $recorder = app(PostViewRecorder::class);

        $this->assertTrue($recorder->record($request, $one, 'en-us'));
        Carbon::setTestNow('2026-11-16 08:10:00');
        $this->assertTrue($recorder->record($request, $two, 'en-us'));
        Carbon::setTestNow('2026-11-16 08:25:00');
        $this->assertFalse($recorder->record($request, $one, 'en-us')); // within 6h

        $this->assertSame(1, DB::table('visitor_activity_daily')->count());
        $this->assertNull(DB::table('visitor_activity_daily')->value('first_returned_at'));
        $this->assertSame(0, $this->metrics()['returningVisitors']);
        $this->assertSame(1, $this->metrics()['uniqueVisitors']);
        $this->assertSame(2, (int) PostView::sum('view_count'));
    }

    public function test_new_session_after_thirty_minutes_counts_once_even_if_view_is_deduplicated(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $request = $this->requestFor();
        $recorder = app(PostViewRecorder::class);
        $this->assertTrue($recorder->record($request, $post, 'en-us'));

        Carbon::setTestNow('2026-11-16 08:31:00');
        $this->assertFalse($recorder->record($request, $post, 'en-us'));
        $this->assertSame(1, $this->metrics()['returningVisitors']);
        $this->assertSame(1, $this->metrics()['uniqueVisitors']);
        $this->assertSame(1, (int) PostView::sum('view_count'));

        $firstReturn = DB::table('visitor_activity_daily')->value('first_returned_at');
        Carbon::setTestNow('2026-11-16 09:00:00');
        $this->assertFalse($recorder->record($request, $post, 'en-us'));
        $this->assertSame(1, DB::table('visitor_activity_daily')->count());
        $this->assertSame($firstReturn, DB::table('visitor_activity_daily')->value('first_returned_at'));
    }

    public function test_midnight_does_not_automatically_start_a_return(): void
    {
        $post = $this->publishedPost();
        $recorder = app(PostViewRecorder::class);
        $request = $this->requestFor();

        Carbon::setTestNow('2026-11-16 23:50:00');
        $recorder->record($request, $post, 'en-us');
        Carbon::setTestNow('2026-11-17 00:05:00');
        $recorder->record($request, $post, 'en-us');
        $this->assertSame(2, DB::table('visitor_activity_daily')->count());
        $this->assertSame(0, DB::table('visitor_activity_daily')->whereNotNull('first_returned_at')->count());

        Carbon::setTestNow('2026-11-17 00:40:00');
        $recorder->record($request, $post, 'en-us');
        $this->assertSame(1, DB::table('visitor_activity_daily')->whereNotNull('first_returned_at')->count());
    }

    public function test_return_any_language_is_recognized_for_active_english_visitor(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $recorder = app(PostViewRecorder::class);
        $first = $this->requestFor('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        $second = $this->requestFor('bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
        $this->assertTrue($recorder->record($first, $post, 'en-us'));
        $this->assertTrue($recorder->record($second, $post, 'en-us'));
        Carbon::setTestNow('2026-11-16 08:05:00');
        $recorder->record($first, $post, 'fr'); // still the same session
        $this->assertSame(0, $this->metrics('en-us')['returningVisitors']);

        Carbon::setTestNow('2026-11-16 08:45:00');
        $recorder->record($first, $post, 'fr'); // new site-wide session
        $this->assertSame(2, $this->metrics('en-us')['uniqueVisitors']);
        $this->assertSame(1, $this->metrics('en-us')['returningVisitors']);
        $this->assertSame(1, $this->metrics()['returningVisitors']);
    }

    public function test_active_reading_prevents_false_return_after_long_open_page(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $request = $this->requestFor();
        app(PostViewRecorder::class)->record($request, $post, 'en-us');
        $session = new Store('test', new ArraySessionHandler(120));
        $session->start();

        Carbon::setTestNow('2026-11-16 08:20:00');
        $ping = Request::create('/en/page-engagement', 'POST', [
            'token' => '11111111-1111-4111-8111-111111111111',
            'post_id' => $post->id, 'locale' => 'en-us',
            'duration' => 1200, 'active' => 1200, 'interacted' => false,
        ], [
            'mrrcine_analytics_consent' => 'granted',
            'mrrcine_analytics_visitor' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
        $ping->setLaravelSession($session);
        $ping->setUserResolver(fn () => null);
        $this->assertSame(200, app(PageEngagementController::class)($ping, app(VisitorIdentity::class))->getStatusCode());

        Carbon::setTestNow('2026-11-16 08:40:00');
        app(PostViewRecorder::class)->record($request, $post, 'en-us');
        $this->assertSame(0, $this->metrics()['returningVisitors']);
    }

    public function test_denied_consent_and_bots_do_not_write_visit_activity(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = $this->publishedPost();
        $recorder = app(PostViewRecorder::class);
        $this->assertFalse($recorder->record($this->requestFor(consent: 'denied'), $post, 'en-us'));
        $bot = $this->requestFor();
        $bot->headers->set('User-Agent', 'Googlebot/1.0');
        $this->assertFalse($recorder->record($bot, $post, 'en-us'));
        $this->assertSame(0, DB::table('visitor_activity_daily')->count());
        $this->assertSame(0, $this->metrics()['returningVisitors']);
    }

    public function test_historical_views_are_preserved_without_inventing_return_sessions(): void
    {
        Carbon::setTestNow('2026-11-16 15:00:00');
        $post = $this->publishedPost();
        PostView::create(['post_id' => $post->id, 'locale' => 'en-us', 'visitor_key' => 'legacy', 'viewed_at' => '2026-11-16 09:00:00']);
        PostView::create(['post_id' => $post->id, 'locale' => 'en-us', 'visitor_key' => 'legacy', 'viewed_at' => '2026-11-16 10:00:00']);
        $this->assertSame(2, $this->metrics()['views']);
        $this->assertSame(1, $this->metrics()['uniqueVisitors']);
        $this->assertSame(0, $this->metrics()['returningVisitors']);
    }
}
