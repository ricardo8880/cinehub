<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Reaction;
use App\Support\EnglishGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Depth is stored per post, content locale and visitor/day. Group existing
        // aggregates; no additional tracking writes or visitor identifiers enter
        // the AI export. English regional history shares the public /en group.
        $depthRows = DB::table('page_engagement_daily')
            ->where('visit_date', '>=', $data['since']->toDateString())
            ->when($data['locale'], fn ($query) => $query->whereIn('locale', EnglishGroup::history($data['locale'])))
            ->when(! $data['locale'] && $data['locales'] !== [], fn ($query) => $query->whereIn('locale', EnglishGroup::expand(array_keys($data['locales']))))
            ->select('post_id', 'locale')
            ->selectRaw('SUM(visit_count) AS measured_visits, SUM(read_start) AS started, SUM(read_middle) AS middle, SUM(read_end) AS finished')
            ->groupBy('post_id', 'locale')
            ->get();

        $depthFields = ['measured_visits', 'started', 'middle', 'finished'];
        $emptyDepth = array_fill_keys($depthFields, 0);
        $siteDepth = $emptyDepth;
        $readingByPost = $depthRows->groupBy('post_id')->map(function ($rows) use ($depthFields, $emptyDepth, &$siteDepth): array {
            $postTotals = $emptyDepth;
            $languageTotals = [];
            foreach ($rows as $row) {
                $language = EnglishGroup::publicLocale($row->locale);
                $languageTotals[$language] ??= $emptyDepth;
                foreach ($depthFields as $field) {
                    $value = (int) $row->{$field};
                    $languageTotals[$language][$field] += $value;
                    $postTotals[$field] += $value;
                    $siteDepth[$field] += $value;
                }
            }

            return [
                'overall' => $this->readingDropoffStats($postTotals),
                'by_language' => collect($languageTotals)
                    ->map(fn (array $totals) => $this->readingDropoffStats($totals))
                    ->all(),
            ];
        });
        // Computing the collection is eager, so siteDepth is fully accumulated here.
        $siteReadingDropoff = $this->readingDropoffStats($siteDepth);

        $allPosts = $data['performancePosts']->map(function (array $row) use ($titleLocale, $data, $readingByPost): array {
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

            $featuredLocales = $post->localizations
                ->filter(fn ($localization) => $localization->is_active && $localization->is_featured)
                ->pluck('locale')
                ->values()
                ->all();

            return [
                'post_id' => $post->id,
                'title' => $row['title'],
                'content_locale' => EnglishGroup::publicLocale($row['content_locale'] ?? ''),
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
                'is_featured' => $data['locale'] !== ''
                    ? array_intersect(EnglishGroup::history($data['locale']), $featuredLocales) !== []
                    : $featuredLocales !== [],
                'featured_locales' => array_values(array_unique(array_map(EnglishGroup::publicLocale(...), $featuredLocales))),
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
                'page_time' => $row['page_time'],
                'reading_progress' => $readingByPost->get($post->id),
            ];
        })->sortByDesc('views')->values()->all();

        // A compact, AI-friendly list identifies the specific pages where
        // visitors appear to leave; all_posts_performance retains the same
        // breakdown, including null for posts with no depth observations.
        $readingDropoffByPage = collect($allPosts)
            ->filter(fn (array $post) => $post['reading_progress'] !== null)
            ->map(fn (array $post) => [
                'post_id' => $post['post_id'],
                'title' => $post['title'],
                'publication_type' => $post['publication_type'],
                'reading_progress' => $post['reading_progress'],
            ])
            ->sortByDesc(fn (array $post) => $post['reading_progress']['overall']['did_not_reach_end'])
            ->values()->all();

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
                'page_time' => $row['page_time'],
            ];
        };

        $report = [
            'report' => [
                'name' => 'MrRCine editorial metrics',
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
                'locale_filter' => $data['locale'] ? EnglishGroup::publicLocale($data['locale']) : 'all',
                'country_filter' => $data['locale']
                    ? ($data['localeLabels'][$data['locale']] ?? strtoupper($data['locale']))
                    : 'All languages',
                'purpose' => 'Structured data for editorial analysis and AI-assisted content decisions.',
            ],
            'definitions' => [
                'engagement_rate' => '(likes + approved comments) / views * 100',
                'page_time' => 'Measured page visits: average_page_seconds is elapsed time, average_engaged_seconds is foreground active time, bounce_rate_percent is the share of visits without 10 active seconds or an interaction. These are independent of deduplicated views.',
                'reading_dropoff' => 'Blog and standard posts only. Milestones measure whether a visit REACHED the content start, middle and end, not the exact exit location. did_not_reach_start, stopped_between_start_and_middle and stopped_between_middle_and_end are estimated non-progression counts, not confirmed exits; reached_end means the end milestone was seen. Each rate is relative to measured_visits except start_to_middle_percent and start_to_end_percent, which use reached_start. A null reading_progress means no post reading-depth observations; do not interpret it as 0% retention. The data starts when reading-depth tracking was introduced. Low sample sizes are not reliable evidence of a problem.',
                'like_rate' => 'likes / views * 100',
                'comment_rate' => 'approved comments / views * 100',
                'growth_rate' => '(current period views - previous period views) / previous period views * 100',
                'unique_visitors' => 'Approximation using distinct visitor identifiers stored by MrRCine.',
                'returning_visitors' => 'Among visitors with a counted view in the selected period, distinct visitors who started another visit after at least 30 minutes of inactivity. Returns may occur in any language. Session-based return tracking begins with the visitor activity migration; historical views are preserved but cannot reconstruct earlier sessions.',
                'english_region' => 'The /en group is shared by the US, UK, Canada and Australia. French and German each share one language locale across multiple countries. Locale identifies content language, not visitor geography. Historical English regional activity is grouped without altering source records.',
                'publication_type' => 'Publication format: standard content or Blog article.',
                'content_type' => 'The main editorial category assigned to the post, such as Games, Movies, Series or Curiosities.',
                'genres' => 'Genres explicitly assigned to each post and restricted by its selected categories.',
                'blog_category' => 'Internal Blog organization category. It is not shown to visitors.',
                'blog_genres' => 'Public Blog genres used to organize and filter Blog articles.',
                'sample_confidence' => 'Confidence factor from 0 to 1 based on view volume; it reaches full confidence at 25 views.',
                'editorial_score' => 'Weighted score combining reach, engagement, growth and number of posts while reducing the influence of very small samples.',
            ],
            'ai_analysis_instructions' => [
                'goal' => 'Use these metrics to decide what MrRCine should publish next. The main priority is actionable editorial recommendations, supported by a short diagnosis of what is working and what is losing audience.',
                'analysis_strategy' => [
                    'Start with a concise diagnosis of what is growing, declining and generating the strongest reach, engagement and audience response.',
                    'Identify the strongest and weakest content types, genres, Blog genres, publication formats and language groups.',
                    'Separate high-reach content from high-satisfaction content and consider both before recommending what to publish.',
                    'Compare current-period performance with the previous period instead of judging only by total views.',
                    'Use editorial_score as a ranking aid, but always inspect views, engagement, growth, sample confidence and number of posts behind the score.',
                    'For every strong-performing title, identify the characteristics that may explain its success, including genre, themes, setting, gameplay style, narrative style, atmosphere, franchise appeal and likely audience interest.',
                    'Use those characteristics to recommend specific related movies, series, games or subjects that may attract the same audience, even when they are from a different franchise.',
                    'Do not only recommend more content about the exact same title. Expand from the successful audience pattern into adjacent titles and themes.',
                    'Example: if Middle-earth: Shadow of Mordor or Shadow of War performs strongly, fantasy, medieval atmosphere, open-world action and RPG-like progression may support testing The Witcher 3, Skyrim, Elden Ring, Dragon Age or similar content.',
                    'Explain the connection between each successful content pattern and each recommended related title or subject.',
                    'For Blog articles, compare Blog genres and editorial angles to determine which themes deserve more long-form analysis.',
                    'Compare standard posts versus Blog articles and recommend the format that best fits each suggested subject when the data supports that distinction.',
                    'Use reading_dropoff_by_page to identify precisely WHICH posts lose reading progress most often, at WHICH milestone (before start, start-to-middle, middle-to-end), and in WHICH content language.',
                    'Compare start-to-middle and start-to-end conversion rates and measured visit counts; distinguish large absolute losses from high rates on tiny samples. Suggest improvements to the introduction, pacing, subheadings, content length and calls to action based on the weak stage.',
                    'Treat drop-off as estimated milestone non-progression, not proof a visitor exited at that exact point. Do not infer a cause or assert abandonment for posts without measured data.',
                    'Compare language groups when data exists. If a language has little or zero activity, explicitly say there is not enough evidence for reliable recommendations. Never infer visitor country from content language.',
                    'Identify declining or weak content patterns that should receive less editorial effort, without treating one small sample as definitive.',
                ],
                'required_output' => [
                    'Begin with a short editorial diagnosis: what is growing, what is declining, what has the strongest reach, what has the strongest engagement and which format is currently strongest.',
                    'Then provide a prioritized list of concrete content to publish next. Recommendations are the main output.',
                    'Suggest specific games, movies, series, curiosities or other subjects, not only broad categories.',
                    'For strong-performing titles, recommend related titles or themes that could appeal to the same audience and explain why the audience overlap is plausible.',
                    'For each recommendation, state whether it should preferably be a standard post or a Blog article when the data supports that choice.',
                    'Briefly cite metrics such as views, engagement, growth, category, genre, Blog genre or language performance.',
                    'Separate recommendations into: publish more, test next and reduce/deprioritize.',
                    'Add a section called Reading drop-off by page: name the posts, affected language, measured visits, percentages reaching the middle and end, and their main estimated stopping stage. Recommend concrete editorial fixes and qualify small samples.',
                    'When useful, include language-specific opportunities, but do not invent geographic conclusions or overinterpret sparse data.',
                    'Do not stop at summarizing the report. Turn the metrics into an editorial action plan.',
                ],
            ],
            'summary' => [
                'published_posts' => $data['publishedPosts'],
                'views' => $data['views'],
                'approx_unique_visitors' => $data['uniqueVisitors'],
                'returning_visitors' => $data['returningVisitors'],
                'all_public_page_views' => $data['siteViews'],
                'page_views_by_type' => $data['siteViewsByType'],
                'reading_depth' => $data['readingDepth'],
                'reading_dropoff_estimates' => $siteReadingDropoff,
                'likes' => $data['likes'],
                'approved_comments' => $data['comments'],
                'engagement_rate_percent' => $data['engagementRate'],
                'like_rate_percent' => $data['likeRate'],
                'comment_rate_percent' => $data['commentRate'],
                'page_time' => $data['pageTime'],
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
            'reading_dropoff_by_page' => $readingDropoffByPage,
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

        $scope = $data['locale'] ? EnglishGroup::publicLocale($data['locale']) : 'all-countries';
        $filename = 'mrrcine-metrics-'.$scope.'-'.$data['period'].'d-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(
            static function () use ($json): void {
                echo $json;
            },
            $filename,
            ['Content-Type' => 'application/json; charset=UTF-8']
        );
    }


    public function exportComments(Request $request): StreamedResponse
    {
        $period = (int) $request->integer('period', 30);
        $allowedPeriods = [7, 30, 90];

        if (! in_array($period, $allowedPeriods, true)) {
            $period = 30;
        }

        $locales = config('localization.locales', []);
        $supportedLocales = EnglishGroup::expand(array_keys($locales));
        $locale = $request->string('locale')->lower()->value();
        // Accept the shared /en locale as a filter while keeping the en-us key
        // for existing content, media and interaction records.
        if ($locale === 'en') {
            $locale = 'en-us';
        }

        if (! array_key_exists($locale, $locales)) {
            $locale = '';
        }

        $defaultLocale = config('localization.default');
        $since = now()->subDays($period - 1)->startOfDay();
        $until = now();

        $comments = Comment::query()
            ->with([
                'post.localizations',
                'post.categories.localizations',
                'post.genres.localizations',
                'post.blogCategory.localizations',
                'post.blogGenres.localizations',
            ])
            ->where('created_at', '>=', $since)
            ->where('created_at', '<=', $until)
            ->when($locale, fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->orderByDesc('created_at')
            ->get();

        $rows = $comments->map(function (Comment $comment) use ($defaultLocale): array {
            $post = $comment->post;
            $commentLocale = $comment->locale ?: $defaultLocale;

            if (! $post) {
                return [
                    'comment_id' => $comment->id,
                    'parent_comment_id' => $comment->parent_id,
                    'is_reply' => $comment->parent_id !== null,
                    'locale' => EnglishGroup::publicLocale($commentLocale),
                    'source_locale' => $commentLocale,
                    'status' => $comment->status,
                    'comment' => $comment->body,
                    'commented_at' => $comment->created_at?->toIso8601String(),
                    'post' => [
                        'post_id' => $comment->post_id,
                        'title' => null,
                        'slug' => null,
                        'publication_type' => null,
                        'content_type' => null,
                        'categories' => [],
                        'genres' => [],
                        'blog_category' => null,
                        'blog_genres' => [],
                    ],
                ];
            }

            $content = $post->localizations->firstWhere('locale', $commentLocale)
                ?? $post->localizations->firstWhere('locale', $defaultLocale)
                ?? $post->localizations->first();

            $categories = $post->categories->map(function ($category) use ($commentLocale, $defaultLocale): array {
                $localized = $category->localizations->firstWhere('locale', $commentLocale)
                    ?? $category->localizations->firstWhere('locale', $defaultLocale)
                    ?? $category->localizations->first();

                return [
                    'id' => $category->id,
                    'name' => $localized?->name ?: $category->name ?: $category->slug ?: '#'.$category->id,
                    'slug' => $localized?->slug ?: $category->slug,
                ];
            })->values()->all();

            $genres = $post->genres->map(function ($genre) use ($commentLocale, $defaultLocale): array {
                $localized = $genre->localizations->firstWhere('locale', $commentLocale)
                    ?? $genre->localizations->firstWhere('locale', $defaultLocale)
                    ?? $genre->localizations->first();

                return [
                    'id' => $genre->id,
                    'name' => $localized?->name ?: $genre->name,
                    'slug' => $localized?->slug ?: $genre->slug,
                ];
            })->values()->all();

            $blogCategory = null;

            if ($post->blogCategory) {
                $localized = $post->blogCategory->localizations->firstWhere('locale', $commentLocale)
                    ?? $post->blogCategory->localizations->firstWhere('locale', $defaultLocale)
                    ?? $post->blogCategory->localizations->first();

                $blogCategory = [
                    'id' => $post->blogCategory->id,
                    'name' => $localized?->name ?: $post->blogCategory->name,
                ];
            }

            $blogGenres = $post->blogGenres->map(function ($genre) use ($commentLocale, $defaultLocale): array {
                $localized = $genre->localizations->firstWhere('locale', $commentLocale)
                    ?? $genre->localizations->firstWhere('locale', $defaultLocale)
                    ?? $genre->localizations->first();

                return [
                    'id' => $genre->id,
                    'name' => $localized?->name ?: '#'.$genre->id,
                    'slug' => $localized?->slug,
                ];
            })->values()->all();

            $contentType = $this->postContentType($post);

            return [
                'comment_id' => $comment->id,
                'parent_comment_id' => $comment->parent_id,
                'is_reply' => $comment->parent_id !== null,
                'locale' => EnglishGroup::publicLocale($commentLocale),
                'source_locale' => $commentLocale,
                'status' => $comment->status,
                'comment' => $comment->body,
                'commented_at' => $comment->created_at?->toIso8601String(),
                'post' => [
                    'post_id' => $post->id,
                    'title' => $content?->title ?: '#'.$post->id,
                    'slug' => $content?->slug,
                    'publication_type' => $post->publication_type ?: 'standard',
                    'content_type' => [
                        'key' => $contentType['key'],
                        'label' => $contentType['label'],
                        'source_category_id' => $contentType['category_id'],
                    ],
                    'categories' => $categories,
                    'genres' => $genres,
                    'blog_category' => $blogCategory,
                    'blog_genres' => $blogGenres,
                    'published_at' => $post->published_at?->toIso8601String(),
                ],
            ];
        })->values();

        $statusCounts = $rows->countBy('status')->sortKeys()->all();
        $localeCounts = $rows->countBy('locale')->sortKeys()->all();
        $publicationTypeCounts = $rows
            ->map(fn (array $row) => $row['post']['publication_type'] ?: 'unknown')
            ->countBy()
            ->sortKeys()
            ->all();

        $report = [
            'report' => [
                'name' => 'MrRCine audience comments',
                'generated_at' => now()->toIso8601String(),
                'period_days' => $period,
                'current_period' => [
                    'from' => $since->toIso8601String(),
                    'to' => $until->toIso8601String(),
                ],
                'locale_filter' => $locale ? EnglishGroup::publicLocale($locale) : 'all',
                'country_filter' => $locale
                    ? $this->localeLabel($locale, $locales[$locale] ?? [])
                    : 'All languages',
                'purpose' => 'Structured audience comments for AI-assisted sentiment, interest and editorial analysis.',
                'privacy' => 'Author email and other direct visitor identifiers are intentionally excluded from this export.',
            ],
            'summary' => [
                'comments' => $rows->count(),
                'by_status' => $statusCounts,
                'by_locale' => $localeCounts,
                'by_publication_type' => $publicationTypeCounts,
            ],
            'definitions' => [
                'comment' => 'The text written by the visitor.',
                'source_locale' => 'Original stored locale code, retained for historical context; locale is the shared English group when applicable.',
                'status' => 'Moderation status at export time. Approved comments are public; pending comments may still be under review; spam should not be treated as reliable audience opinion.',
                'publication_type' => 'Publication format: standard content or Blog article.',
                'content_type' => 'The main editorial category associated with the post, such as Games, Movies or TV & Series.',
                'genres' => 'Genres associated with the post and useful for identifying recurring audience interests.',
                'locale' => 'Public content language group; en-us, en-gb, en-ca and en-au map to en. fr and de each represent a language across multiple countries. Original locale is available in source_locale.',
                'is_reply' => 'Whether the comment is a reply to another comment.',
            ],
            'ai_analysis_instructions' => [
                'goal' => 'Use the actual comment text and its post context to explain what the MrRCine audience likes, dislikes, asks for and wants to see next. The main priority is actionable editorial recommendations grounded in recurring audience opinion.',
                'analysis_strategy' => [
                    'Read each comment together with the title, publication format, content type, categories, genres, Blog genres and locale of the post where it was written.',
                    'Identify recurring praise, criticism, questions, requests, expectations, frustrations and topics that generate discussion.',
                    'Separate isolated opinions from recurring patterns. Do not present one comment as a broad audience conclusion unless several comments support the same pattern.',
                    'Give more weight to approved comments. Pending comments can be considered cautiously; spam should be excluded from audience conclusions.',
                    'Identify titles, franchises, genres, themes or related works that commenters explicitly ask for or show interest in.',
                    'When the audience reacts positively to a title or theme, infer adjacent content opportunities from the same audience pattern and recommend specific related games, movies, series or subjects when the connection is reasonable.',
                    'Example: if comments on Middle-earth: Shadow of Mordor praise fantasy, medieval atmosphere, combat and exploration, consider related opportunities such as The Witcher 3, Skyrim, Elden Ring, Dragon Age or similar subjects.',
                    'Compare language groups only when there are enough comments to support a meaningful pattern; the country of the visitor is unknown.',
                    'Consider negative feedback as an editorial signal: distinguish criticism of the work itself from criticism of the MrRCine article, presentation or coverage.',
                    'Treat sarcasm, jokes and ambiguous comments cautiously and state uncertainty when sentiment is unclear.',
                ],
                'required_output' => [
                    'Begin with a short audience-opinion diagnosis: what people appear to like most, dislike most and discuss most.',
                    'List the strongest recurring positive and negative patterns and indicate which posts or themes produced them.',
                    'Recommend specific content to publish next based on audience comments, not only on broad categories.',
                    'For each recommendation, explain which audience comment pattern supports it and why the related title or subject may appeal to the same people.',
                    'Highlight explicit audience requests for games, movies, series, franchises, comparisons, reviews or follow-up articles.',
                    'When useful, separate opportunities by language locale, not by inferred visitor country, and avoid conclusions from small samples.',
                    'Separate the final action plan into: publish more, test next, investigate/answer audience questions, and reduce or fix.',
                    'Do not only summarize comments. Convert them into a practical editorial plan for MrRCine.',
                ],
            ],
            'comments' => $rows->all(),
        ];

        $json = json_encode(
            $report,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
        );

        $scope = $locale ? EnglishGroup::publicLocale($locale) : 'all-countries';
        $filename = 'mrrcine-comments-'.$scope.'-'.$period.'d-'.now()->format('Y-m-d-His').'.json';

        return response()->streamDownload(
            static function () use ($json): void {
                echo $json;
            },
            $filename,
            ['Content-Type' => 'application/json; charset=UTF-8']
        );
    }

    private function readingDropoffStats(array $totals): array
    {
        $visits = max(0, (int) ($totals['measured_visits'] ?? 0));
        // A visit that reached the end also reached the middle and start.
        // Clamp defensively to keep the funnel coherent if old aggregates
        // contain inconsistent counters.
        $start = min($visits, max(0, (int) ($totals['started'] ?? 0)));
        $middle = min($start, max(0, (int) ($totals['middle'] ?? 0)));
        $end = min($middle, max(0, (int) ($totals['finished'] ?? 0)));
        $losses = [
            'before_start' => $visits - $start,
            'start_to_middle' => $start - $middle,
            'middle_to_end' => $middle - $end,
        ];
        $largest = max($losses);

        return [
            'measured_visits' => $visits,
            'reached_start' => $start,
            'reached_middle' => $middle,
            'reached_end' => $end,
            'did_not_reach_start' => $losses['before_start'],
            'stopped_between_start_and_middle' => $losses['start_to_middle'],
            'stopped_between_middle_and_end' => $losses['middle_to_end'],
            'did_not_reach_end' => $visits - $end,
            'start_reach_percent' => $visits ? round($start / $visits * 100, 1) : null,
            'middle_reach_percent' => $visits ? round($middle / $visits * 100, 1) : null,
            'end_reach_percent' => $visits ? round($end / $visits * 100, 1) : null,
            'start_to_middle_percent' => $start ? round($middle / $start * 100, 1) : null,
            'start_to_end_percent' => $start ? round($end / $start * 100, 1) : null,
            'largest_estimated_dropoff_stage' => $largest > 0 ? array_search($largest, $losses, true) : null,
            'sample_size_warning' => $visits < 20,
        ];
    }

    private function pageTimeStats(?object $totals): array
    {
        $count = (int) ($totals->measured_visits ?? 0);

        return [
            'measured_visits' => $count,
            'average_page_seconds' => $count ? round((int) $totals->duration_sum / $count, 1) : 0,
            'average_engaged_seconds' => $count ? round((int) $totals->active_sum / $count, 1) : 0,
            'bounce_rate_percent' => $count ? round((int) $totals->bounced / $count * 100, 1) : 0,
        ];
    }

    private function metricsData(Request $request): array
    {
        $period = (int) $request->integer('period', 30);
        $allowedPeriods = [7, 30, 90];

        if (! in_array($period, $allowedPeriods, true)) {
            $period = 30;
        }

        $locales = config('localization.locales', []);
        $supportedLocales = EnglishGroup::expand(array_keys($locales));
        $locale = $request->string('locale')->lower()->value();
        // Accept the shared /en locale as a filter while keeping the en-us key
        // for existing content, media and interaction records.
        if ($locale === 'en') {
            $locale = 'en-us';
        }

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
            ->when($locale, fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales));

        // Historical per-session rows remain available; new sessions use one
        // daily row per visitor/post/locale. Merge aggregate results only.
        $historicalEngagement = DB::table('page_engagements')
            ->where('started_at', '>=', $since)
            ->when($locale, fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($q) => $q->whereIn('locale', $supportedLocales))
            ->select('post_id')->selectRaw('COUNT(*) AS measured_visits, COALESCE(SUM(duration_seconds), 0) AS duration_sum, COALESCE(SUM(active_seconds), 0) AS active_sum, COALESCE(SUM(CASE WHEN engaged = 0 THEN 1 ELSE 0 END), 0) AS bounced')
            ->groupBy('post_id')->get();
        $dailyEngagement = DB::table('page_engagement_daily')
            ->where('visit_date', '>=', $since->toDateString())
            ->when($locale, fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($q) => $q->whereIn('locale', $supportedLocales))
            ->select('post_id')->selectRaw('SUM(visit_count) AS measured_visits, COALESCE(SUM(duration_sum), 0) AS duration_sum, COALESCE(SUM(active_sum), 0) AS active_sum, COALESCE(SUM(bounced), 0) AS bounced')
            ->groupBy('post_id')->get();

        $pageTimeTotals = ['measured_visits' => 0, 'duration_sum' => 0, 'active_sum' => 0, 'bounced' => 0];
        $pageTimeRows = [];
        foreach ($historicalEngagement->concat($dailyEngagement) as $row) {
            $postId = (int) $row->post_id;
            if (! isset($pageTimeRows[$postId])) {
                $pageTimeRows[$postId] = $pageTimeTotals;
            }
            foreach ($pageTimeTotals as $field => $_) {
                $pageTimeRows[$postId][$field] += (int) $row->{$field};
                $pageTimeTotals[$field] += (int) $row->{$field};
            }
        }
        $pageTime = $this->pageTimeStats((object) $pageTimeTotals);
        $pageTimeByPost = collect($pageTimeRows)->map(fn (array $row) => $this->pageTimeStats((object) $row));

        // Site-wide page loads are tracked independently of deduplicated post views.
        // This date range is independent of the editorial period filter.
        $siteRange = (string) $request->input('site_range', '30');
        if (! in_array($siteRange, ['30', '365', 'custom', 'all'], true)) $siteRange = '30';
        $siteFrom = $siteRange === '365' ? now()->subDays(364)->startOfDay()->toDateString()
            : ($siteRange === '30' ? now()->subDays(29)->startOfDay()->toDateString() : null);
        $siteTo = now()->toDateString();
        if ($siteRange === 'custom') {
            $fromInput = (string) $request->input('site_from', '');
            $toInput = (string) $request->input('site_to', '');
            $valid = static fn (string $date): bool => (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
                && \DateTimeImmutable::createFromFormat('!Y-m-d', $date)?->format('Y-m-d') === $date;
            if ($valid($fromInput) && $valid($toInput) && $fromInput <= $toInput && $toInput <= now()->toDateString()) {
                $siteFrom = $fromInput;
                $siteTo = $toInput;
            } else {
                $siteRange = '30';
                $siteFrom = now()->subDays(29)->toDateString();
            }
        }
        $siteViewsQuery = DB::table('site_page_views_daily')
            ->when($siteFrom, fn ($q) => $q->where('view_date', '>=', $siteFrom))
            ->where('view_date', '<=', $siteTo)
            ->when($locale, fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale)));
        $siteViews = (int) (clone $siteViewsQuery)->sum('views');
        $siteViewsByType = (clone $siteViewsQuery)->select('page_type')->selectRaw('SUM(views) AS total')
            ->groupBy('page_type')->orderByDesc('total')->get();
        $readingDepth = DB::table('page_engagement_daily')
            ->where('visit_date', '>=', $since->toDateString())
            ->when($locale, fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale)))
            ->selectRaw('COALESCE(SUM(read_start), 0) AS started, COALESCE(SUM(read_middle), 0) AS middle, COALESCE(SUM(read_end), 0) AS finished')
            ->first();
        $views = (int) (clone $viewsQuery)->sum('view_count');
        $uniqueVisitors = (clone $viewsQuery)->distinct()->count('visitor_key');

        // A return means a NEW visit after 30 minutes of inactivity, not a
        // second page view during one visit. Only activity recorded since this
        // migration can be classified; historical views remain unchanged.
        // The locale filter applies to counted visitors, while a return may
        // happen through any language on the site.
        $returningVisitors = DB::table('visitor_activity_daily')
            ->where('first_returned_at', '>=', $since)
            ->where('first_returned_at', '<=', now())
            ->when($locale, fn ($q) => $q->whereIn('visitor_key', (clone $viewsQuery)->whereNotNull('visitor_key')->select('visitor_key')))
            ->distinct()
            ->count('visitor_key');

        $likes = Reaction::query()
            ->where('type', 'like')
            ->where('created_at', '>=', $since)
            ->when($locale, fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->count();

        $comments = Comment::query()
            ->where('status', 'approved')
            ->where('created_at', '>=', $since)
            ->when($locale, fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)))
            ->when(! $locale && $supportedLocales !== [], fn ($query) => $query->whereIn('locale', $supportedLocales))
            ->count();

        $publishedPosts = Post::query()
            ->published()
            ->when($locale, fn ($query) => $query->whereHas('localizations', fn ($localizationQuery) => $localizationQuery
                ->whereIn('locale', EnglishGroup::history($locale))
                ->where('is_active', true)))
            ->count();

        $engagementRate = $views > 0 ? round((($likes + $comments) / $views) * 100, 1) : 0;
        $likeRate = $views > 0 ? round(($likes / $views) * 100, 1) : 0;
        $commentRate = $views > 0 ? round(($comments / $views) * 100, 1) : 0;

        $performancePosts = Post::query()
            ->published()
            ->with(['localizations', 'categories.localizations', 'genres', 'blogCategory', 'blogGenres.localizations'])
            ->when($locale, fn ($query) => $query->whereHas('localizations', fn ($localizationQuery) => $localizationQuery
                ->whereIn('locale', EnglishGroup::history($locale))
                ->where('is_active', true)))
            ->withCount([
                'reactions as metrics_likes_count' => fn ($query) => $query
                    ->where('type', 'like')
                    ->where('created_at', '>=', $since)
                    ->when($locale, fn ($reactionQuery) => $reactionQuery->whereIn('locale', EnglishGroup::history($locale)))
                    ->when(! $locale && $supportedLocales !== [], fn ($reactionQuery) => $reactionQuery->whereIn('locale', $supportedLocales)),
                'comments as metrics_comments_count' => fn ($query) => $query
                    ->where('status', 'approved')
                    ->where('created_at', '>=', $since)
                    ->when($locale, fn ($commentQuery) => $commentQuery->whereIn('locale', EnglishGroup::history($locale)))
                    ->when(! $locale && $supportedLocales !== [], fn ($commentQuery) => $commentQuery->whereIn('locale', $supportedLocales)),
            ])
            ->withSum([
                'views as metrics_views_count' => fn ($query) => $query
                    ->where('viewed_at', '>=', $since)
                    ->when($locale, fn ($viewQuery) => $viewQuery->whereIn('locale', EnglishGroup::history($locale)))
                    ->when(! $locale && $supportedLocales !== [], fn ($viewQuery) => $viewQuery->whereIn('locale', $supportedLocales)),
                'views as previous_views_count' => fn ($query) => $query
                    ->where('viewed_at', '>=', $previousSince)
                    ->where('viewed_at', '<', $previousUntil)
                    ->when($locale, fn ($viewQuery) => $viewQuery->whereIn('locale', EnglishGroup::history($locale)))
                    ->when(! $locale && $supportedLocales !== [], fn ($viewQuery) => $viewQuery->whereIn('locale', $supportedLocales))
            ], 'view_count')
            ->get()
            ->map(function (Post $post) use ($titleLocale, $defaultLocale, $pageTimeByPost): array {
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
                    'page_time' => $pageTimeByPost->get($post->id, $this->pageTimeStats(null)),
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
            ->selectRaw('SUM(view_count) as total')
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
            ->selectRaw('SUM(view_count) as total')
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
            $groupLocales = EnglishGroup::history($routeLocale);
            $topRow = $topPostRows->whereIn('locale', $groupLocales)
                ->groupBy('post_id')
                ->map(fn ($rows) => (object) [
                    'post_id' => $rows->first()->post_id,
                    'total' => $rows->sum('total'),
                ])
                ->sortByDesc('total')
                ->first();
            $topPost = $topRow ? $topPostModels->get($topRow->post_id) : null;
            $topLocalization = $topPost?->localizations?->firstWhere('locale', $routeLocale)
                ?? $topPost?->localizations?->first(fn ($loc) => in_array($loc->locale, $groupLocales, true));
            $localeViews = (int) $viewsByLocale->only($groupLocales)->sum();
            $localeLikes = (int) $likesByLocale->only($groupLocales)->sum();
            $localeComments = (int) $commentsByLocale->only($groupLocales)->sum();

            return [
                'locale' => EnglishGroup::publicLocale($routeLocale),
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
            ->selectRaw('DATE(viewed_at) as day, SUM(view_count) as total')
            ->where('viewed_at', '>=', $since)
            ->when($locale, fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)))
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
            'pageTime' => $pageTime,
            'uniqueVisitors' => $uniqueVisitors,
            'returningVisitors' => $returningVisitors,
            'siteViews' => $siteViews,
            'siteViewsByType' => $siteViewsByType,
            'siteRange' => $siteRange,
            'siteFrom' => $siteFrom,
            'siteTo' => $siteTo,
            'readingDepth' => $readingDepth,
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
