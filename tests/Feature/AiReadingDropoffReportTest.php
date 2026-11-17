<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\MetricsController;
use App\Models\Post;
use App\Models\PostLocalization;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AiReadingDropoffReportTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function post(string $type, string $slug, bool $french = false): Post
    {
        $post = Post::create([
            'status' => 'published',
            'publication_type' => $type,
            'published_at' => now()->subDays(2),
        ]);
        PostLocalization::create([
            'post_id' => $post->id,
            'locale' => 'en-us',
            'slug' => $slug,
            'title' => 'English '.$slug,
            'is_active' => true,
        ]);
        if ($french) {
            PostLocalization::create([
                'post_id' => $post->id,
                'locale' => 'fr',
                'slug' => $slug.'-fr',
                'title' => 'French '.$slug,
                'is_active' => true,
            ]);
        }

        return $post;
    }

    private function depth(Post $post, string $locale, string $visitor, int $visits, int $start, int $middle, int $end, string $date = '2026-11-16'): void
    {
        DB::table('page_engagement_daily')->insert([
            'post_id' => $post->id,
            'locale' => $locale,
            'visitor_key' => $visitor,
            'visit_date' => $date,
            'visit_count' => $visits,
            'read_start' => $start,
            'read_middle' => $middle,
            'read_end' => $end,
        ]);
    }

    private function report(array $filters = []): array
    {
        $response = app(MetricsController::class)->export(Request::create('/admin/metrics/report', 'GET', $filters));
        ob_start();
        try {
            $response->sendContent();
            $json = ob_get_contents();
        } finally {
            ob_end_clean();
        }

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_ai_report_identifies_where_each_post_and_language_lose_readers(): void
    {
        Carbon::setTestNow('2026-11-16 12:00:00');
        $blog = $this->post('blog', 'blog-article', true);
        $standard = $this->post('standard', 'standard-post');
        $this->depth($blog, 'en-us', 'visitor-1', 10, 10, 6, 2);
        $this->depth($blog, 'en-gb', 'visitor-2', 2, 2, 1, 1);
        $this->depth($blog, 'fr', 'visitor-3', 2, 2, 2, 1);
        $this->depth($standard, 'en-us', 'visitor-4', 3, 2, 0, 0);

        $report = $this->report(['period' => 7]);
        $pages = collect($report['reading_dropoff_by_page'])->keyBy('post_id');
        $this->assertCount(2, $pages);
        $this->assertSame('blog', $pages[$blog->id]['publication_type']);
        $blogProgress = $pages[$blog->id]['reading_progress'];
        $this->assertSame(14, $blogProgress['overall']['measured_visits']);
        $this->assertSame(14, $blogProgress['overall']['reached_start']);
        $this->assertSame(9, $blogProgress['overall']['reached_middle']);
        $this->assertSame(4, $blogProgress['overall']['reached_end']);
        $this->assertSame(5, $blogProgress['overall']['stopped_between_start_and_middle']);
        $this->assertSame(5, $blogProgress['overall']['stopped_between_middle_and_end']);
        $this->assertSame(10, $blogProgress['overall']['did_not_reach_end']);
        $this->assertSame('start_to_middle', $blogProgress['overall']['largest_estimated_dropoff_stage']);
        $this->assertSame(64.3, $blogProgress['overall']['middle_reach_percent']);
        $this->assertSame(28.6, $blogProgress['overall']['end_reach_percent']);
        $this->assertSame(12, $blogProgress['by_language']['en']['measured_visits']);
        $this->assertSame(2, $blogProgress['by_language']['fr']['measured_visits']);

        $standardProgress = $pages[$standard->id]['reading_progress']['overall'];
        $this->assertSame(1, $standardProgress['did_not_reach_start']);
        $this->assertSame(2, $standardProgress['stopped_between_start_and_middle']);
        $this->assertSame('start_to_middle', $standardProgress['largest_estimated_dropoff_stage']);
        $this->assertSame(17, $report['summary']['reading_dropoff_estimates']['measured_visits']);
        $this->assertSame(4, $report['summary']['reading_dropoff_estimates']['reached_end']);
        $this->assertSame($blogProgress, collect($report['all_posts_performance'])->firstWhere('post_id', $blog->id)['reading_progress']);
        $this->assertStringContainsString('Reading drop-off by page', implode(' ', $report['ai_analysis_instructions']['required_output']));
    }

    public function test_language_period_filter_and_missing_data_are_not_misrepresented(): void
    {
        Carbon::setTestNow('2026-11-16 12:00:00');
        $blog = $this->post('blog', 'filtered-article', true);
        $empty = $this->post('standard', 'no-tracking');
        $this->depth($blog, 'en-us', 'visitor-1', 4, 4, 1, 0);
        $this->depth($blog, 'fr', 'visitor-2', 5, 5, 4, 4);
        $this->depth($blog, 'fr', 'visitor-3', 7, 7, 2, 1, '2026-10-01'); // excluded by period

        $report = $this->report(['locale' => 'fr', 'period' => 7]);
        $this->assertCount(1, $report['reading_dropoff_by_page']);
        $progress = $report['reading_dropoff_by_page'][0]['reading_progress'];
        $this->assertSame(5, $progress['overall']['measured_visits']);
        $this->assertSame(4, $progress['overall']['reached_end']);
        $this->assertSame(80.0, $progress['overall']['end_reach_percent']);
        $this->assertSame(['fr'], array_keys($progress['by_language']));
        $this->assertNull(collect($report['all_posts_performance'])->firstWhere('post_id', $empty->id)['reading_progress']);
    }
}
