<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostView;
use App\Support\PostViewRecorder;
use App\Support\VisitorIdentity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class Phase2ViewAggregationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function visitRequest(string $consent = 'granted', string $identifier = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'): Request
    {
        return Request::create('/en/blog/example', 'GET', [], [
            'mrrcine_analytics_consent' => $consent,
            'mrrcine_analytics_visitor' => $identifier,
        ], [], ['HTTP_USER_AGENT' => 'Mozilla/5.0']);
    }

    public function test_qualifying_repeat_views_update_one_row_per_visitor_and_day(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subDay()]);
        $request = $this->visitRequest();
        $recorder = app(PostViewRecorder::class);

        $this->assertTrue($recorder->record($request, $post, 'en-us'));
        $this->assertFalse($recorder->record($request, $post, 'en-us'));
        Carbon::setTestNow('2026-11-16 15:00:00');
        $this->assertTrue($recorder->record($request, $post, 'en-us'));
        $this->assertSame(1, PostView::count());
        $this->assertSame(2, (int) PostView::sum('view_count'));
        $this->assertSame('2026-11-16', PostView::first()->view_date->toDateString());

        Carbon::setTestNow('2026-11-17 01:00:00');
        $this->assertTrue($recorder->record($request, $post, 'en-us'));
        $this->assertSame(2, PostView::count());
        $this->assertSame(3, (int) PostView::sum('view_count'));
        $this->assertSame(1, PostView::distinct()->count('visitor_key'));
        $this->assertSame(3, (int) Post::query()->withSum('views', 'view_count')->findOrFail($post->id)->views_sum_view_count);
    }

    public function test_analytics_opt_out_and_bots_do_not_create_view_rows(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subDay()]);
        $recorder = app(PostViewRecorder::class);

        $this->assertFalse($recorder->record($this->visitRequest('denied'), $post, 'en-us'));
        $this->assertFalse($recorder->record($this->visitRequest(''), $post, 'en-us'));
        $bot = $this->visitRequest();
        $bot->headers->set('User-Agent', 'FriendlyBot/1.0');
        $this->assertFalse($recorder->record($bot, $post, 'en-us'));
        $this->assertSame(0, PostView::count());
    }

    public function test_preexisting_individual_view_rows_are_preserved_and_still_apply_six_hour_rule(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subDay()]);
        $request = $this->visitRequest();
        $recorder = app(PostViewRecorder::class);
        $key = app(VisitorIdentity::class)->analyticsKey($request);

        PostView::create(['post_id' => $post->id, 'locale' => 'en-us', 'visitor_key' => $key, 'viewed_at' => now()->subHours(3)]);
        $this->assertNull(PostView::first()->view_date);
        $this->assertFalse($recorder->record($request, $post, 'en-us'));
        Carbon::setTestNow('2026-11-16 12:00:00');
        $this->assertTrue($recorder->record($request, $post, 'en-us'));
        $this->assertSame(2, PostView::count());
        $this->assertSame(2, (int) PostView::sum('view_count'));
    }

    public function test_legacy_session_identifier_still_prevents_a_duplicate_view_during_transition(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subDay()]);
        $request = $this->visitRequest();
        $identity = app(VisitorIdentity::class);
        $legacyKey = $identity->key($request);
        PostView::create([
            'post_id' => $post->id,
            'locale' => 'en-us',
            'visitor_key' => $legacyKey,
            'viewed_at' => now()->subHours(2),
        ]);

        $this->assertFalse(app(PostViewRecorder::class)->record($request, $post, 'en-us'));
        Carbon::setTestNow('2026-11-16 15:00:00');
        $this->assertTrue(app(PostViewRecorder::class)->record($request, $post, 'en-us'));
        $this->assertSame(2, PostView::count());
    }

    public function test_other_visitors_and_locales_keep_separate_daily_rows(): void
    {
        Carbon::setTestNow('2026-11-16 08:00:00');
        $post = Post::create(['status' => 'published', 'publication_type' => 'blog', 'published_at' => now()->subDay()]);
        $recorder = app(PostViewRecorder::class);
        $this->assertTrue($recorder->record($this->visitRequest(), $post, 'en-us'));
        $this->assertTrue($recorder->record($this->visitRequest('granted', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb'), $post, 'en-us'));
        $this->assertTrue($recorder->record($this->visitRequest(), $post, 'fr'));
        $this->assertSame(3, PostView::count());
        $this->assertSame(2, (int) PostView::where('locale', 'en-us')->sum('view_count'));
        $this->assertSame(1, (int) PostView::where('locale', 'fr')->sum('view_count'));
    }
}
