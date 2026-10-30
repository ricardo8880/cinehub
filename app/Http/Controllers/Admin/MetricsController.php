<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MetricsController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('admin.metrics.index', $this->metricsData($request));
    }

    public function export(Request $request): StreamedResponse
    {
        $data = $this->metricsData($request);
        $titleLocale = $data['locale'] ?: $data['defaultLocale'];

        $allPosts = $data['performancePosts']->map(function (array $row) use ($titleLocale, $data): array {
            $post = $row['post'];
            $categories = $post->categories->map(function ($category) use ($titleLocale, $data): array {
                $content = $category->localizations->firstWhere('locale', $titleLocale)
                    ?? $category->localizations->firstWhere('locale', $data['defaultLocale'])
                    ?? $category->localizations->first();

                return [
                    'id' => $category->id,
                    'name' => $content?->name ?: $category->name ?: '#'.$category->id,
                ];
            })->values()->all();

            $genres = $post->genres->map(fn ($genre): array => [
                'id' => $genre->id,
                'name' => $genre->name,
                'slug' => $genre->slug,
            ])->values()->all();

            $blogGenres = $post->blogGenres->map(function ($genre) use ($titleLocale, $data): ?array {
                $content = $genre->localizations->firstWhere('locale', $titleLocale)
                    ?? $genre->localizations->firstWhere('locale', $data['defaultLocale'])
                    ?? $genre->localizations->first();

                return $content ? [
                    'id' => $genre->id,
                    'name' => $content->name,
                    'slug' => $content->slug,
                ] : null;
            })->filter()->values()->all();

            return [
                'post_id' => $post->id,
                'title' => $row['title'],
                'content_locale' => $row['content_locale'],
                'publication_type' => $row['publication_type'],
                'content_type' => [
                    'key' => $row['content_type_key'],
                    'label' => $row['content_type_label'],
                    'source_category_id' => $row['content_type_category_id'],
                ],
                'genres' => $genres,
                'blog_category' => $post->blogCategory ? ['id' => $post->blogCategory->id, 'name' => $post->blogCategory->name] : null,
                'blog_genres' => $blogGenres,
                'published_at' => $post->published_at?->toIso8601String(),
                'is_featured' => (bool) $post->is_featured,
                'categories' => $categories,
                'views' => $row['views'],
                'likes' => $row['likes'],
                'approved_comments' => $row['comments'],
                'engagement_rate_percent' => $row['engagement_rate'],
                'like_rate_percent' => $row['like_rate'],
                'comment_rate_percent' => $row['comment_rate'],
                'previous_period_views' => $row['previous_views'],
                'view_change' => $row['view_delta'],
                'growth_rate_percent' => $row['growth_rate'],
                'new_activity' => $row['is_new_growth'],
            ];
        })->sortByDesc('views')->values()->all();

        $compactPost = function (?array $row) use ($titleLocale, $data): ?array {
            if ($row === null) {
                return null;
            }

            return [
                'post_id' => $row['post']->id,
                'title' => $row['title'],
                'publication_type' => $row['publication_type'],
                'content_type' => [
                    'key' => $row['content_type_key'],
                    'label' => $row['content_type_label'],
                ],
                'genres' => $row['post']->genres->map(fn ($genre): array => [
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'slug' => $genre->slug,
                ])->values()->all(),
                'blog_category' => $row['post']->blogCategory ? ['id' => $row['post']->blogCategory->id, 'name' => $row['post']->blogCategory->name] : null,
                'blog_genres' => $row['post']->blogGenres->map(function ($genre) use ($titleLocale, $data): ?array {
                    $content = $genre->localizations->firstWhere('locale', $titleLocale)
                        ?? $genre->localizations->firstWhere('locale', $data['defaultLocale'])
                        ?? $genre->localizations->first();

                    return $content ? ['id' => $genre->id, 'name' => $content->name, 'slug' => $content->slug] : null;
                })->filter()->values()->all(),
                'views' => $row['views'],
                'likes' => $row['likes'],
                'approved_comments' => $row['comments'],
                'engagement_rate_percent' => $row['engagement_rate'],
                'like_rate_percent' => $row['like_rate'],
                'comment_rate_percent' => $row['comment_rate'],
                'previous_period_views' => $row['previous_views'],
                'view_change' => $row['view_delta'],
                'growth_rate_percent' => $row['growth_rate'],
                'new_activity' => $row['is_new_growth'],
            ];
        };

        $report = [
            'report' => [
                'name' => 'CineHub editorial metrics',
                'generated_at' => now()->toIso8601String(),
                'period_days' => $data['period'],
                'current_period' => [
                    'from' => $data['since']->toIso8601String(),
                    'to' => now()->toIso8601String(),
                ],
                'comparison_period' => [
                    'from' => $data['previousSince']->toIso8601String(),
                    'to' => $data['previousUntil']->copy()->subSecond()->toIso8601String(),
                ],
                'locale_filter' => $data['locale'] ?: 'all',
                'country_filter' => $data['locale']
                    ? ($data['localeLabels'][$data['locale']] ?? strtoupper($data['locale']))
                    : 'All countries',
                'purpose' => 'Structured data for editorial analysis and AI-assisted content decisions.',
            ],
            'definitions' => [
                'engagement_rate' => '(likes + approved comments) / views * 100',
                'like_rate' => 'likes / views * 100',
                'comment_rate' => 'approved comments / views * 100',
                'growth_rate' => '(current period views - previous period views) / previous period views * 100',
                'unique_visitors' => 'Approximation using distinct visitor identifiers stored by CineHub.',
                'publication_type' => 'Publication format: standard content or Blog article.',
                'content_type' => 'The main editorial category assigned to the post, such as Games, Movies, Series or Curiosities.',
                'genres' => 'Genres explicitly assigned to each post and restricted by its selected categories.',
                'blog_category' => 'Internal Blog organization category. It is not shown to visitors.',
                'blog_genres' => 'Public Blog genres used to organize and filter Blog articles.',
                'sample_confidence' => 'Confidence factor from 0 to 1 based on view volume; it reaches full confidence at 25 views.',
                'editorial_score' => 'Weighted score combining reach, engagement, growth and number of posts while reducing the influence of very small samples.',
            ],
            'ai_analysis_instructions' => [
                'goal' => 'Use these metrics to decide what CineHub should publish next.',
                'prioritize' => [
                    'Find content types and genres with strong views, engagement and positive growth.',
                    'Compare standard posts versus Blog articles to identify which publication format performs better.',
                    'For Blog articles, compare Blog genres to identify which editorial angles and themes deserve more articles.',
                    'Compare category + genre combinations to discover specific subjects worth publishing more often.',
                    'Separate high-reach content from high-satisfaction content.',
                    'Compare countries/locales when data is available.',
                    'Identify declining content types and genres that should receive less editorial effort.',
                    'Use editorial_score as a ranking aid and still inspect the underlying views, engagement and growth.',
                ],
                'required_output' => [
                    'Recommend concrete future post ideas, not only metric summaries.',
                    'Suggest specific games, movies, series, curiosities or other subjects supported by the patterns.',
                    'Recommend whether each idea is better as a standard post or a Blog article when the data supports that distinction.',
                    'Explain briefly which metric pattern supports each recommendation.',
                ],
            ],
            'summary' => [
                'published_posts' => $data['publishedPosts'],
                'views' => $data['views'],
                'approx_unique_visitors' => $data['uniqueVisitors'],
                'likes' => $data['likes'],
                'approved_comments' => $data['comments'],
                'engagement_rate_percent' => $data['engagementRate'],
                'like_rate_percent' => $data['likeRate'],
                'comment_rate_percent' => $data['commentRate'],
            ],
            'editorial_highlights' => [
                'best_content_type' => $data['bestContentType'],
                'best_genre' => $data['bestGenre'],
                'best_blog_genre' => $data['bestBlogGenre'],
                'most_engaging_post' => $compactPost($data['mostEngagingPost']),
                'fastest_growing_post' => $compactPost($data['risingPost']),
                'best_like_rate_post' => $compactPost($data['mostLikedPost']),
            ],
            'performance_by_country' => $data['countryStats']->values()->all(),
            'views_by_day' => $data['dailyViews']->values()->all(),
            'publication_format_performance' => $data['publicationTypeStats']->values()->all(),
            'content_type_performance' => $data['contentTypeStats']->values()->all(),
            'genre_performance' => $data['genreStats']->values()->all(),
            'blog_genre_performance' => $data['blogGenreStats']->values()->all(),
            'rankings' => [
                'top_by_views' => $data['topPosts']->map($compactPost)->values()->all(),
                'top_by_engagement' => $data['topEngagementPosts']->map($compactPost)->values()->all(),
                'gaining_audience' => $data['risingPosts']->map($compactPost)->values()->all(),
                'losing_audience' => $data['fallingPosts']->map($compactPost)->values()->all(),
            ],
            'all_posts_performance' => $allPosts,
        ];

        $json = json_encode(
            $report,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
        );

        $scope = $data['locale'] ?: 'all-countries';
        $filename = 'cinehub-metrics-'.$scope.'-'.$data['period'].'d-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(
            static function () use ($json): void {
                echo $json;
            },
            $filename,
            ['Content-Type' => 'application/json; charset=UTF-8']
        );
    }

    private function metricsData(Request $request): array
    {
        $period = (int) $request->integer('period', 30);
        $allowedPeriods = [7, 30, 90];

        if (! in_array($period, $allowedPeriods, true)) {
            $period = 30;
        }

        $locales = config('localization.locales', []);
        $supportedLocales = array_keys($locales);
        $locale = $request->string('locale')->lower()->value();

        if (! array_key_exists($locale, $locales)) {
            $locale = '';
        }

        $since = now()->subDays($period - 1)->startOfDay();
        $previousSince = $since->copy()->subDays($period);
        $previousUntil = $since->copy();
        $defaultLocale = config('localization.default');
        $titleLocale = $locale ?: $defaultLocale;

        $viewsQuery = PostView::query()
            ->where('viewed_at', '>=', $since)
            ->when($locale, fn ($query) => $query->where('locale', $locale))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales));

        $views = (clone $viewsQuery)->count();
        $uniqueVisitors = (clone $viewsQuery)->distinct()->count('visitor_key');

        $likes = Reaction::query()
            ->where('type', 'like')
            ->where('created_at', '>=', $since)
            ->when($locale, fn ($query) => $query->where('locale', $locale))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->count();

        $comments = Comment::query()
            ->where('status', 'approved')
            ->where('created_at', '>=', $since)
            ->when($locale, fn ($query) => $query->where('locale', $locale))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->count();

        $publishedPosts = Post::query()
            ->published()
            ->when($locale, fn ($query) => $query->whereHas('localizations', fn ($localizationQuery) => $localizationQuery
                ->where('locale', $locale)
                ->where('is_active', true)))
            ->count();

        $engagementRate = $views > 0 ? round((($likes + $comments) / $views) * 100, 1) : 0;
        $likeRate = $views > 0 ? round(($likes / $views) * 100, 1) : 0;
        $commentRate = $views > 0 ? round(($comments / $views) * 100, 1) : 0;

        $performancePosts = Post::query()
            ->published()
            ->with(['localizations', 'categories.localizations', 'genres', 'blogCategory', 'blogGenres.localizations'])
            ->when($locale, fn ($query) => $query->whereHas('localizations', fn ($localizationQuery) => $localizationQuery
                ->where('locale', $locale)
                ->where('is_active', true)))
            ->withCount([
                'views as metrics_views_count' => fn ($query) => $query
                    ->where('viewed_at', '>=', $since)
                    ->when($locale, fn ($viewQuery) => $viewQuery->where('locale', $locale))
                    ->when(! $locale && $supportedLocales !== [], fn ($viewQuery) => $viewQuery->whereIn('locale', $supportedLocales)),
                'reactions as metrics_likes_count' => fn ($query) => $query
                    ->where('type', 'like')
                    ->where('created_at', '>=', $since)
                    ->when($locale, fn ($reactionQuery) => $reactionQuery->where('locale', $locale))
                    ->when(! $locale && $supportedLocales !== [], fn ($reactionQuery) => $reactionQuery->whereIn('locale', $supportedLocales)),
                'comments as metrics_comments_count' => fn ($query) => $query
                    ->where('status', 'approved')
                    ->where('created_at', '>=', $since)
                    ->when($locale, fn ($commentQuery) => $commentQuery->where('locale', $locale))
                    ->when(! $locale && $supportedLocales !== [], fn ($commentQuery) => $commentQuery->whereIn('locale', $supportedLocales)),
                'views as previous_views_count' => fn ($query) => $query
                    ->where('viewed_at', '>=', $previousSince)
                    ->where('viewed_at', '<', $previousUntil)
                    ->when($locale, fn ($viewQuery) => $viewQuery->where('locale', $locale))
                    ->when(! $locale && $supportedLocales !== [], fn ($viewQuery) => $viewQuery->whereIn('locale', $supportedLocales)),
            ])
            ->get()
            ->map(function (Post $post) use ($titleLocale, $defaultLocale): array {
                $content = $post->localizations->firstWhere('locale', $titleLocale)
                    ?? $post->localizations->firstWhere('locale', $defaultLocale)
                    ?? $post->localizations->first();

                $postViews = (int) $post->metrics_views_count;
                $postLikes = (int) $post->metrics_likes_count;
                $postComments = (int) $post->metrics_comments_count;
                $previousViews = (int) $post->previous_views_count;
                $viewDelta = $postViews - $previousViews;
                $contentType = $this->postContentType($post);

                return [
                    'post' => $post,
                    'title' => $content?->title ?: '#'.$post->id,
                    'content_locale' => $content?->locale,
                    'publication_type' => $post->publication_type ?: 'standard',
                    'content_type_key' => $contentType['key'],
                    'content_type_label' => $contentType['label'],
                    'content_type_category_id' => $contentType['category_id'],
                    'views' => $postViews,
                    'likes' => $postLikes,
                    'comments' => $postComments,
                    'engagement_rate' => $postViews > 0 ? round((($postLikes + $postComments) / $postViews) * 100, 1) : 0,
                    'like_rate' => $postViews > 0 ? round(($postLikes / $postViews) * 100, 1) : 0,
                    'comment_rate' => $postViews > 0 ? round(($postComments / $postViews) * 100, 1) : 0,
                    'previous_views' => $previousViews,
                    'view_delta' => $viewDelta,
                    'growth_rate' => $previousViews > 0 ? round(($viewDelta / $previousViews) * 100, 1) : null,
                    'is_new_growth' => $previousViews === 0 && $postViews > 0,
                ];
            });

        $topPosts = $performancePosts
            ->filter(fn (array $row) => $row['views'] > 0 || $row['likes'] > 0 || $row['comments'] > 0)
            ->sortByDesc('views')
            ->take(10)
            ->values();

        $topEngagementPosts = $performancePosts
            ->filter(fn (array $row) => $row['views'] > 0)
            ->sort(function (array $a, array $b): int {
                $engagementComparison = $b['engagement_rate'] <=> $a['engagement_rate'];

                return $engagementComparison !== 0
                    ? $engagementComparison
                    : ($b['views'] <=> $a['views']);
            })
            ->take(10)
            ->values();

        $risingPosts = $performancePosts
            ->filter(fn (array $row) => $row['view_delta'] > 0)
            ->sort(function (array $a, array $b): int {
                $deltaComparison = $b['view_delta'] <=> $a['view_delta'];

                return $deltaComparison !== 0
                    ? $deltaComparison
                    : ($b['views'] <=> $a['views']);
            })
            ->take(5)
            ->values();

        $fallingPosts = $performancePosts
            ->filter(fn (array $row) => $row['view_delta'] < 0)
            ->sortBy('view_delta')
            ->take(5)
            ->values();

        $genreBuckets = [];

        foreach ($performancePosts as $row) {
            foreach ($row['post']->genres as $genre) {
                if (! isset($genreBuckets[$genre->id])) {
                    $genreBuckets[$genre->id] = [
                        'id' => $genre->id,
                        'name' => $genre->name,
                        'slug' => $genre->slug,
                        'posts' => 0,
                        'views' => 0,
                        'likes' => 0,
                        'comments' => 0,
                        'previous_views' => 0,
                    ];
                }

                $genreBuckets[$genre->id]['posts']++;
                $genreBuckets[$genre->id]['views'] += $row['views'];
                $genreBuckets[$genre->id]['likes'] += $row['likes'];
                $genreBuckets[$genre->id]['comments'] += $row['comments'];
                $genreBuckets[$genre->id]['previous_views'] += $row['previous_views'];
            }
        }

        $genreStats = collect($genreBuckets)
            ->map(function (array $row): array {
                $row['engagement_rate'] = $row['views'] > 0
                    ? round((($row['likes'] + $row['comments']) / $row['views']) * 100, 1)
                    : 0;
                $row['average_views'] = $row['posts'] > 0
                    ? round($row['views'] / $row['posts'], 1)
                    : 0;
                $row['view_delta'] = $row['views'] - $row['previous_views'];
                $row['growth_rate'] = $row['previous_views'] > 0
                    ? round(($row['view_delta'] / $row['previous_views']) * 100, 1)
                    : null;
                $row['is_new_growth'] = $row['previous_views'] === 0 && $row['views'] > 0;
                $row['sample_confidence'] = round(min(1, $row['views'] / 25), 3);
                $row['editorial_score'] = $this->editorialScore(
                    $row['views'],
                    $row['posts'],
                    $row['engagement_rate'],
                    $row['growth_rate']
                );

                return $row;
            })
            ->sortByDesc('views')
            ->values();

        $blogGenreBuckets = [];

        foreach ($performancePosts->where('publication_type', 'blog') as $row) {
            foreach ($row['post']->blogGenres as $blogGenre) {
                $content = $blogGenre->localizations->firstWhere('locale', $titleLocale)
                    ?? $blogGenre->localizations->firstWhere('locale', $defaultLocale)
                    ?? $blogGenre->localizations->first();

                if (! $content) {
                    continue;
                }

                if (! isset($blogGenreBuckets[$blogGenre->id])) {
                    $blogGenreBuckets[$blogGenre->id] = [
                        'id' => $blogGenre->id,
                        'name' => $content->name,
                        'slug' => $content->slug,
                        'posts' => 0,
                        'views' => 0,
                        'likes' => 0,
                        'comments' => 0,
                        'previous_views' => 0,
                    ];
                }

                $blogGenreBuckets[$blogGenre->id]['posts']++;
                $blogGenreBuckets[$blogGenre->id]['views'] += $row['views'];
                $blogGenreBuckets[$blogGenre->id]['likes'] += $row['likes'];
                $blogGenreBuckets[$blogGenre->id]['comments'] += $row['comments'];
                $blogGenreBuckets[$blogGenre->id]['previous_views'] += $row['previous_views'];
            }
        }

        $blogGenreStats = collect($blogGenreBuckets)
            ->map(function (array $row): array {
                $row['engagement_rate'] = $row['views'] > 0 ? round((($row['likes'] + $row['comments']) / $row['views']) * 100, 1) : 0;
                $row['average_views'] = $row['posts'] > 0 ? round($row['views'] / $row['posts'], 1) : 0;
                $row['view_delta'] = $row['views'] - $row['previous_views'];
                $row['growth_rate'] = $row['previous_views'] > 0 ? round(($row['view_delta'] / $row['previous_views']) * 100, 1) : null;
                $row['is_new_growth'] = $row['previous_views'] === 0 && $row['views'] > 0;
                $row['sample_confidence'] = round(min(1, $row['views'] / 25), 3);
                $row['editorial_score'] = $this->editorialScore($row['views'], $row['posts'], $row['engagement_rate'], $row['growth_rate']);

                return $row;
            })
            ->sortByDesc('views')
            ->values();

        $contentTypeBuckets = [];

        foreach ($performancePosts as $row) {
            foreach ($row['post']->categories as $category) {
                $categoryContent = $category->localizations->firstWhere('locale', $titleLocale)
                    ?? $category->localizations->firstWhere('locale', $defaultLocale)
                    ?? $category->localizations->first();

                if (! isset($contentTypeBuckets[$category->id])) {
                    $contentTypeBuckets[$category->id] = [
                        'key' => $category->slug ?: 'category-'.$category->id,
                        'label' => $categoryContent?->name ?: $category->name ?: '#'.$category->id,
                        'posts' => 0,
                        'views' => 0,
                        'likes' => 0,
                        'comments' => 0,
                        'previous_views' => 0,
                        'top_post' => null,
                        'top_post_views' => 0,
                    ];
                }

                $contentTypeBuckets[$category->id]['posts']++;
                $contentTypeBuckets[$category->id]['views'] += $row['views'];
                $contentTypeBuckets[$category->id]['likes'] += $row['likes'];
                $contentTypeBuckets[$category->id]['comments'] += $row['comments'];
                $contentTypeBuckets[$category->id]['previous_views'] += $row['previous_views'];

                if ($row['views'] > $contentTypeBuckets[$category->id]['top_post_views']) {
                    $contentTypeBuckets[$category->id]['top_post'] = $row['title'];
                    $contentTypeBuckets[$category->id]['top_post_views'] = $row['views'];
                }
            }
        }

        $contentTypeStats = collect($contentTypeBuckets)
            ->map(function (array $row): array {
                $row['average_views'] = $row['posts'] > 0 ? round($row['views'] / $row['posts'], 1) : 0;
                $row['engagement_rate'] = $row['views'] > 0
                    ? round((($row['likes'] + $row['comments']) / $row['views']) * 100, 1)
                    : 0;
                $row['view_delta'] = $row['views'] - $row['previous_views'];
                $row['growth_rate'] = $row['previous_views'] > 0
                    ? round(($row['view_delta'] / $row['previous_views']) * 100, 1)
                    : null;
                $row['is_new_growth'] = $row['previous_views'] === 0 && $row['views'] > 0;
                $row['sample_confidence'] = round(min(1, $row['views'] / 25), 3);
                $row['editorial_score'] = $this->editorialScore(
                    $row['views'],
                    $row['posts'],
                    $row['engagement_rate'],
                    $row['growth_rate']
                );

                return $row;
            })
            ->sortByDesc('views')
            ->values();

        $publicationTypeStats = $performancePosts
            ->groupBy(fn (array $row): string => $row['publication_type'])
            ->map(function ($rows, string $type): array {
                $views = (int) $rows->sum('views');
                $likes = (int) $rows->sum('likes');
                $comments = (int) $rows->sum('comments');
                $previousViews = (int) $rows->sum('previous_views');
                $posts = $rows->count();
                $viewDelta = $views - $previousViews;
                $engagementRate = $views > 0 ? round((($likes + $comments) / $views) * 100, 1) : 0;
                $growthRate = $previousViews > 0 ? round(($viewDelta / $previousViews) * 100, 1) : null;

                return [
                    'key' => $type,
                    'label' => $type === 'blog' ? 'Blog articles' : 'Standard posts',
                    'posts' => $posts,
                    'views' => $views,
                    'average_views' => $posts > 0 ? round($views / $posts, 1) : 0,
                    'likes' => $likes,
                    'comments' => $comments,
                    'engagement_rate' => $engagementRate,
                    'previous_views' => $previousViews,
                    'view_delta' => $viewDelta,
                    'growth_rate' => $growthRate,
                    'sample_confidence' => round(min(1, $views / 25), 3),
                    'editorial_score' => $this->editorialScore($views, $posts, $engagementRate, $growthRate),
                ];
            })
            ->sortByDesc('editorial_score')
            ->values();

        $bestContentType = $contentTypeStats
            ->filter(fn (array $row) => $row['views'] > 0)
            ->sort(function (array $a, array $b): int {
                $scoreComparison = $b['editorial_score'] <=> $a['editorial_score'];

                return $scoreComparison !== 0
                    ? $scoreComparison
                    : ($b['views'] <=> $a['views']);
            })
            ->first();

        $bestGenre = $genreStats
            ->filter(fn (array $row) => $row['views'] > 0)
            ->sort(function (array $a, array $b): int {
                $scoreComparison = $b['editorial_score'] <=> $a['editorial_score'];

                return $scoreComparison !== 0
                    ? $scoreComparison
                    : ($b['views'] <=> $a['views']);
            })
            ->first();

        $bestBlogGenre = $blogGenreStats
            ->filter(fn (array $row) => $row['views'] > 0)
            ->sort(function (array $a, array $b): int {
                $scoreComparison = $b['editorial_score'] <=> $a['editorial_score'];

                return $scoreComparison !== 0 ? $scoreComparison : ($b['views'] <=> $a['views']);
            })
            ->first();

        $mostEngagingPost = $topEngagementPosts->first();
        $risingPost = $risingPosts->first();
        $mostLikedPost = $performancePosts
            ->filter(fn (array $row) => $row['views'] > 0)
            ->sort(function (array $a, array $b): int {
                $rateComparison = $b['like_rate'] <=> $a['like_rate'];

                return $rateComparison !== 0
                    ? $rateComparison
                    : ($b['likes'] <=> $a['likes']);
            })
            ->first();

        $viewsByLocale = PostView::query()
            ->select('locale')
            ->selectRaw('COUNT(*) as total')
            ->where('viewed_at', '>=', $since)
            ->when($supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->groupBy('locale')
            ->pluck('total', 'locale');

        $likesByLocale = Reaction::query()
            ->select('locale')
            ->selectRaw('COUNT(*) as total')
            ->where('type', 'like')
            ->where('created_at', '>=', $since)
            ->when($supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->groupBy('locale')
            ->pluck('total', 'locale');

        $commentsByLocale = Comment::query()
            ->select('locale')
            ->selectRaw('COUNT(*) as total')
            ->where('status', 'approved')
            ->where('created_at', '>=', $since)
            ->when($supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->groupBy('locale')
            ->pluck('total', 'locale');

        $topPostRows = PostView::query()
            ->select('post_id', 'locale')
            ->selectRaw('COUNT(*) as total')
            ->where('viewed_at', '>=', $since)
            ->when($supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->groupBy('post_id', 'locale')
            ->orderByDesc('total')
            ->get();

        $topPostIds = $topPostRows->pluck('post_id')->unique()->values();
        $topPostModels = Post::query()
            ->with('localizations')
            ->whereIn('id', $topPostIds)
            ->get()
            ->keyBy('id');

        $countryStats = collect($locales)->map(function (array $settings, string $routeLocale) use ($viewsByLocale, $likesByLocale, $commentsByLocale, $topPostRows, $topPostModels): array {
            $topRow = $topPostRows->firstWhere('locale', $routeLocale);
            $topPost = $topRow ? $topPostModels->get($topRow->post_id) : null;
            $topLocalization = $topPost?->localizations?->firstWhere('locale', $routeLocale);
            $localeViews = (int) ($viewsByLocale[$routeLocale] ?? 0);
            $localeLikes = (int) ($likesByLocale[$routeLocale] ?? 0);
            $localeComments = (int) ($commentsByLocale[$routeLocale] ?? 0);

            return [
                'locale' => $routeLocale,
                'label' => $this->localeLabel($routeLocale, $settings),
                'views' => $localeViews,
                'likes' => $localeLikes,
                'comments' => $localeComments,
                'engagement_rate' => $localeViews > 0 ? round((($localeLikes + $localeComments) / $localeViews) * 100, 1) : 0,
                'top_post' => $topLocalization?->title,
                'top_post_views' => (int) ($topRow?->total ?? 0),
            ];
        })->values();

        $totalCountryViews = max(1, $countryStats->sum('views'));
        $countryStats = $countryStats->map(function (array $stat) use ($totalCountryViews): array {
            $stat['share'] = $stat['views'] > 0
                ? round(($stat['views'] / $totalCountryViews) * 100, 1)
                : 0;

            return $stat;
        });

        $dailyRows = PostView::query()
            ->selectRaw('DATE(viewed_at) as day, COUNT(*) as total')
            ->where('viewed_at', '>=', $since)
            ->when($locale, fn ($query) => $query->where('locale', $locale))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $dailyViews = collect(range(0, $period - 1))->map(function (int $offset) use ($since, $dailyRows): array {
            $day = $since->copy()->addDays($offset)->toDateString();

            return [
                'date' => $day,
                'label' => $since->copy()->addDays($offset)->format('d/m'),
                'views' => (int) ($dailyRows[$day] ?? 0),
            ];
        });

        $maxDailyViews = max(1, (int) $dailyViews->max('views'));

        return [
            'period' => $period,
            'allowedPeriods' => $allowedPeriods,
            'locale' => $locale,
            'locales' => $locales,
            'localeLabels' => collect($locales)->map(fn (array $settings, string $routeLocale) => $this->localeLabel($routeLocale, $settings)),
            'views' => $views,
            'uniqueVisitors' => $uniqueVisitors,
            'likes' => $likes,
            'comments' => $comments,
            'publishedPosts' => $publishedPosts,
            'engagementRate' => $engagementRate,
            'likeRate' => $likeRate,
            'commentRate' => $commentRate,
            'topPosts' => $topPosts,
            'topEngagementPosts' => $topEngagementPosts,
            'risingPosts' => $risingPosts,
            'fallingPosts' => $fallingPosts,
            'publicationTypeStats' => $publicationTypeStats,
            'contentTypeStats' => $contentTypeStats,
            'bestContentType' => $bestContentType,
            'genreStats' => $genreStats,
            'bestGenre' => $bestGenre,
            'blogGenreStats' => $blogGenreStats,
            'bestBlogGenre' => $bestBlogGenre,
            'mostEngagingPost' => $mostEngagingPost,
            'risingPost' => $risingPost,
            'mostLikedPost' => $mostLikedPost,
            'countryStats' => $countryStats,
            'dailyViews' => $dailyViews,
            'maxDailyViews' => $maxDailyViews,
            'defaultLocale' => $defaultLocale,
            'performancePosts' => $performancePosts,
            'since' => $since,
            'previousSince' => $previousSince,
            'previousUntil' => $previousUntil,
        ];
    }

    private function editorialScore(int $views, int $posts, float $engagementRate, ?float $growthRate): float
    {
        if ($views <= 0) {
            return 0.0;
        }

        $confidence = min(1, $views / 25);
        $reachScore = log10($views + 1) * 30;
        $engagementScore = $engagementRate * $confidence * 0.5;
        $growthScore = $growthRate === null
            ? 0
            : max(-100, min(100, $growthRate)) * $confidence * 0.15;
        $breadthScore = min($posts, 5);

        return round(max(0, $reachScore + $engagementScore + $growthScore + $breadthScore), 1);
    }

    private function postContentType(Post $post): array
    {
        $category = $post->categories->first();

        if ($category) {
            return [
                'key' => $category->slug ?: 'category-'.$category->id,
                'label' => $category->name ?: $category->slug ?: '#'.$category->id,
                'category_id' => $category->id,
            ];
        }

        return [
            'key' => 'other',
            'label' => 'Other / Unclassified',
            'category_id' => null,
        ];
    }

    private function localeLabel(string $routeLocale, array $settings): string
    {
        $configuredLabel = $settings['country'] ?? $settings['label'] ?? $settings['name'] ?? null;

        if (is_string($configuredLabel) && trim($configuredLabel) !== '') {
            return trim($configuredLabel);
        }

        $region = strtoupper((string) ($settings['region'] ?? substr($routeLocale, -2)));

        return match ($region) {
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'JP' => 'Japan',
            'CN' => 'China',
            default => $region ?: strtoupper($routeLocale),
        };
    }
}
