@extends('layouts.admin')
@section('title', 'Metrics')
@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('Metrics') }}</h1>
        <div class="text-body-secondary small">{{ __('Audience interest and editorial performance by content country/locale.') }}</div>
    </div>

    <form class="row g-2 align-items-end" method="GET" action="{{ route('admin.metrics') }}">
        <div class="col-12 col-sm-auto">
            <label class="form-label small mb-1" for="metrics-period">{{ __('Period') }}</label>
            <select class="form-select form-select-sm" id="metrics-period" name="period">
                @foreach ($allowedPeriods as $option)
                    <option value="{{ $option }}" @if ($period == $option) selected @endif>{{ $option }} {{ __('days') }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-sm-auto">
            <label class="form-label small mb-1" for="metrics-locale">{{ __('Country') }}</label>
            <select class="form-select form-select-sm" id="metrics-locale" name="locale">
                <option value="">{{ __('All countries') }}</option>
                @foreach ($locales as $routeLocale => $settings)
                    <option value="{{ $routeLocale }}" @if ($locale == $routeLocale) selected @endif>
                        {{ $localeLabels[$routeLocale] ?? strtoupper($routeLocale) }} · {{ strtoupper($routeLocale) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-sm-auto d-flex gap-2">
            <button class="btn btn-danger btn-sm flex-fill" type="submit">
                <i class="bi bi-funnel me-1"></i>{{ __('Apply') }}
            </button>
            <button class="btn btn-outline-light btn-sm flex-fill text-nowrap" type="submit" formaction="{{ route('admin.metrics.report') }}">
                <i class="bi bi-download me-1"></i>{{ __('Download AI report') }}
            </button>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['Published posts', $publishedPosts, 'bi-file-earmark-check'],
        ['Views', $views, 'bi-eye'],
        ['Likes', $likes, 'bi-heart'],
        ['Approved comments', $comments, 'bi-chat-left-text']
    ] as [$label, $value, $icon])
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 border-secondary-subtle">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-body-secondary small">{{ __($label) }}</div>
                        <div class="fs-3 fw-bold">{{ number_format($value) }}</div>
                        @if ($label != 'Published posts')
                            <div class="text-body-secondary small">{{ $period }} {{ __('days') }}</div>
                        @endif
                    </div>
                    <i class="bi {{ $icon }} fs-2 text-body-secondary"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-secondary-subtle">
            <div class="card-body">
                <div class="text-body-secondary small">{{ __('Approx. unique visitors') }}</div>
                <div class="fs-3 fw-bold">{{ number_format($uniqueVisitors) }}</div>
                <div class="text-body-secondary small">{{ __('Distinct visitor identifiers in the period') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-secondary-subtle">
            <div class="card-body">
                <div class="text-body-secondary small">{{ __('Engagement rate') }}</div>
                <div class="fs-3 fw-bold">{{ number_format($engagementRate, 1) }}%</div>
                <div class="text-body-secondary small">{{ __('Likes + comments per 100 views') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-secondary-subtle">
            <div class="card-body">
                <div class="text-body-secondary small">{{ __('Like rate') }}</div>
                <div class="fs-3 fw-bold">{{ number_format($likeRate, 1) }}%</div>
                <div class="text-body-secondary small">{{ __('Likes per 100 views') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-secondary-subtle">
            <div class="card-body">
                <div class="text-body-secondary small">{{ __('Comment rate') }}</div>
                <div class="fs-3 fw-bold">{{ number_format($commentRate, 1) }}%</div>
                <div class="text-body-secondary small">{{ __('Approved comments per 100 views') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('What is pleasing the audience now') }}</span>
        <span class="text-body-secondary small">{{ $period }} {{ __('days') }}</span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Most engaging post') }}</div>
                    @if ($mostEngagingPost)
                        <div class="fw-semibold">{{ $mostEngagingPost['title'] }}</div>
                        <div class="small mt-1">{{ number_format($mostEngagingPost['engagement_rate'], 1) }}% {{ __('engagement') }} · {{ number_format($mostEngagingPost['views']) }} {{ __('views') }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No data yet.') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Strongest content type') }}</div>
                    @if ($bestContentType)
                        <div class="fw-semibold">{{ $bestContentType['label'] }}</div>
                        <div class="small mt-1">{{ number_format($bestContentType['engagement_rate'], 1) }}% {{ __('engagement') }} · {{ number_format($bestContentType['views']) }} {{ __('views') }}</div>
                        <div class="text-body-secondary small">{{ __('Editorial score') }}: {{ number_format($bestContentType['editorial_score'], 1) }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No data yet.') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Strongest genre') }}</div>
                    @if ($bestGenre)
                        <div class="fw-semibold">{{ $bestGenre['name'] }}</div>
                        <div class="small mt-1">{{ number_format($bestGenre['engagement_rate'], 1) }}% {{ __('engagement') }} · {{ number_format($bestGenre['views']) }} {{ __('views') }}</div>
                        <div class="text-body-secondary small">{{ __('Editorial score') }}: {{ number_format($bestGenre['editorial_score'], 1) }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No data yet.') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Strongest Blog genre') }}</div>
                    @if ($bestBlogGenre)
                        <div class="fw-semibold">{{ $bestBlogGenre['name'] }}</div>
                        <div class="small mt-1">{{ number_format($bestBlogGenre['engagement_rate'], 1) }}% {{ __('engagement') }} · {{ number_format($bestBlogGenre['views']) }} {{ __('views') }}</div>
                        <div class="text-body-secondary small">{{ __('Editorial score') }}: {{ number_format($bestBlogGenre['editorial_score'], 1) }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No Blog data yet.') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Growing most') }}</div>
                    @if ($risingPost)
                        <div class="fw-semibold">{{ $risingPost['title'] }}</div>
                        <div class="small mt-1 text-success">+{{ number_format($risingPost['view_delta']) }} {{ __('views vs previous period') }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No growth detected yet.') }}</div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <div class="border rounded p-3 h-100">
                    <div class="text-body-secondary small mb-1">{{ __('Best like rate') }}</div>
                    @if ($mostLikedPost)
                        <div class="fw-semibold">{{ $mostLikedPost['title'] }}</div>
                        <div class="small mt-1">{{ number_format($mostLikedPost['like_rate'], 1) }}% · {{ number_format($mostLikedPost['likes']) }} {{ __('likes') }}</div>
                    @else
                        <div class="text-body-secondary">{{ __('No data yet.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Performance by country') }}</span>
        <span class="text-body-secondary small">{{ $period }} {{ __('days') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Country') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Share') }}</th>
                    <th>{{ __('Likes') }}</th>
                    <th>{{ __('Comments') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Most viewed post') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($countryStats as $stat)
                    <tr @if ($locale == $stat['locale']) class="table-active" @endif>
                        <td>
                            <div class="fw-semibold">{{ $stat['label'] }}</div>
                            <div class="text-body-secondary small">{{ strtoupper($stat['locale']) }}</div>
                        </td>
                        <td class="fw-semibold">{{ number_format($stat['views']) }}</td>
                        <td style="min-width: 150px;">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ number_format($stat['share'], 1) }}%</span>
                            </div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $stat['share'] }}" aria-valuemin="0" aria-valuemax="100" style="height: 6px;">
                                <div class="progress-bar" style="width: {{ $stat['share'] }}%"></div>
                            </div>
                        </td>
                        <td>{{ number_format($stat['likes']) }}</td>
                        <td>{{ number_format($stat['comments']) }}</td>
                        <td>{{ number_format($stat['engagement_rate'], 1) }}%</td>
                        <td>
                            @if ($stat['top_post'])
                                <div>{{ $stat['top_post'] }}</div>
                                <div class="text-body-secondary small">{{ number_format($stat['top_post_views']) }} {{ __('views') }}</div>
                            @else
                                <span class="text-body-secondary">{{ __('No data yet.') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Views by day') }}</span>
        <span class="text-body-secondary small">
            @if ($locale)
                {{ $localeLabels[$locale] ?? strtoupper($locale) }} · {{ strtoupper($locale) }}
            @else
                {{ __('All countries') }}
            @endif
        </span>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-end gap-2 overflow-x-auto pb-2" style="height: 220px;">
            @foreach ($dailyViews as $day)
                <div class="d-flex flex-column justify-content-end align-items-center flex-shrink-0 h-100" style="width: 34px;" title="{{ $day['date'] }} · {{ $day['views'] }} {{ __('views') }}">
                    <div class="small fw-semibold mb-1">{{ $day['views'] ?: '' }}</div>
                    <div class="bg-danger rounded-top w-75" style="height: {{ $day['views'] > 0 ? max(8, round(($day['views'] / $maxDailyViews) * 150)) : 2 }}px;"></div>
                    <div class="text-body-secondary small mt-2">{{ $day['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Performance by publication format') }}</span>
        <span class="text-body-secondary small">{{ __('Compare standard posts with Blog articles.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Format') }}</th>
                    <th>{{ __('Posts') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Avg. views/post') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Score') }}</th>
                    <th>{{ __('Trend') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publicationTypeStats as $stat)
                    <tr>
                        <td class="fw-semibold">{{ __($stat['label']) }}</td>
                        <td>{{ number_format($stat['posts']) }}</td>
                        <td class="fw-semibold">{{ number_format($stat['views']) }}</td>
                        <td>{{ number_format($stat['average_views'], 1) }}</td>
                        <td>{{ number_format($stat['engagement_rate'], 1) }}%</td>
                        <td>
                            <div class="fw-semibold">{{ number_format($stat['editorial_score'], 1) }}</div>
                            <div class="text-body-secondary small">{{ number_format($stat['sample_confidence'] * 100, 0) }}% {{ __('confidence') }}</div>
                        </td>
                        <td>
                            @if ($stat['growth_rate'] !== null && $stat['growth_rate'] > 0)
                                <span class="text-success">+{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] < 0)
                                <span class="text-danger">{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null)
                                <span class="text-body-secondary">0.0%</span>
                            @else
                                <span class="text-body-secondary">{{ $stat['views'] > 0 ? __('New activity') : '—' }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-body-secondary">{{ __('No data yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Performance by content type') }}</span>
        <span class="text-body-secondary small">{{ __('Games, movies, series, curiosities and other editorial groups.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Posts') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Avg. views/post') }}</th>
                    <th>{{ __('Likes') }}</th>
                    <th>{{ __('Comments') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Score') }}</th>
                    <th>{{ __('Trend') }}</th>
                    <th>{{ __('Top post') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contentTypeStats as $stat)
                    <tr>
                        <td class="fw-semibold">{{ $stat['label'] }}</td>
                        <td>{{ number_format($stat['posts']) }}</td>
                        <td class="fw-semibold">{{ number_format($stat['views']) }}</td>
                        <td>{{ number_format($stat['average_views'], 1) }}</td>
                        <td>{{ number_format($stat['likes']) }}</td>
                        <td>{{ number_format($stat['comments']) }}</td>
                        <td>{{ number_format($stat['engagement_rate'], 1) }}%</td>
                        <td>
                            <div class="fw-semibold">{{ number_format($stat['editorial_score'], 1) }}</div>
                            <div class="text-body-secondary small">{{ number_format($stat['sample_confidence'] * 100, 0) }}% {{ __('confidence') }}</div>
                        </td>
                        <td>
                            @if ($stat['is_new_growth'])
                                <span class="text-success">{{ __('New activity') }}</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] > 0)
                                <span class="text-success">+{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] < 0)
                                <span class="text-danger">{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null)
                                <span class="text-body-secondary">0.0%</span>
                            @else
                                <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($stat['top_post'])
                                <div>{{ $stat['top_post'] }}</div>
                                <div class="text-body-secondary small">{{ number_format($stat['top_post_views']) }} {{ __('views') }}</div>
                            @else
                                <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-body-secondary">{{ __('No data yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Genre performance') }}</span>
        <span class="text-body-secondary small">{{ __('Genres are analyzed separately from the main content category.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Genre') }}</th>
                    <th>{{ __('Posts') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Avg. views/post') }}</th>
                    <th>{{ __('Likes') }}</th>
                    <th>{{ __('Comments') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Score') }}</th>
                    <th>{{ __('Trend') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($genreStats as $stat)
                    <tr>
                        <td class="fw-semibold">{{ $stat['name'] }}</td>
                        <td>{{ number_format($stat['posts']) }}</td>
                        <td class="fw-semibold">{{ number_format($stat['views']) }}</td>
                        <td>{{ number_format($stat['average_views'], 1) }}</td>
                        <td>{{ number_format($stat['likes']) }}</td>
                        <td>{{ number_format($stat['comments']) }}</td>
                        <td>{{ number_format($stat['engagement_rate'], 1) }}%</td>
                        <td>
                            <div class="fw-semibold">{{ number_format($stat['editorial_score'], 1) }}</div>
                            <div class="text-body-secondary small">{{ number_format($stat['sample_confidence'] * 100, 0) }}% {{ __('confidence') }}</div>
                        </td>
                        <td>
                            @if ($stat['is_new_growth'])
                                <span class="text-success">{{ __('New activity') }}</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] > 0)
                                <span class="text-success">+{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] < 0)
                                <span class="text-danger">{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null)
                                <span class="text-body-secondary">0.0%</span>
                            @else
                                <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-body-secondary">{{ __('No data yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($blogGenreStats->isNotEmpty())
<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Blog genre performance') }}</span>
        <span class="text-body-secondary small">{{ __('Only public genres used inside Blog articles.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Blog genre') }}</th>
                    <th>{{ __('Articles') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Avg. views/article') }}</th>
                    <th>{{ __('Likes') }}</th>
                    <th>{{ __('Comments') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Score') }}</th>
                    <th>{{ __('Trend') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blogGenreStats as $stat)
                    <tr>
                        <td class="fw-semibold">{{ $stat['name'] }}</td>
                        <td>{{ number_format($stat['posts']) }}</td>
                        <td class="fw-semibold">{{ number_format($stat['views']) }}</td>
                        <td>{{ number_format($stat['average_views'], 1) }}</td>
                        <td>{{ number_format($stat['likes']) }}</td>
                        <td>{{ number_format($stat['comments']) }}</td>
                        <td>{{ number_format($stat['engagement_rate'], 1) }}%</td>
                        <td>
                            <div class="fw-semibold">{{ number_format($stat['editorial_score'], 1) }}</div>
                            <div class="text-body-secondary small">{{ number_format($stat['sample_confidence'] * 100, 0) }}% {{ __('confidence') }}</div>
                        </td>
                        <td>
                            @if ($stat['is_new_growth'])
                                <span class="text-success">{{ __('New activity') }}</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] > 0)
                                <span class="text-success">+{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null && $stat['growth_rate'] < 0)
                                <span class="text-danger">{{ number_format($stat['growth_rate'], 1) }}%</span>
                            @elseif ($stat['growth_rate'] !== null)
                                <span class="text-body-secondary">0.0%</span>
                            @else
                                <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12 col-xl-6">
        <div class="card border-secondary-subtle h-100">
            <div class="card-header d-flex justify-content-between align-items-center gap-2">
                <span>{{ __('Posts gaining audience') }}</span>
                <span class="text-body-secondary small">{{ __('vs previous') }} {{ $period }} {{ __('days') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Post') }}</th>
                            <th>{{ __('Current') }}</th>
                            <th>{{ __('Previous') }}</th>
                            <th>{{ __('Change') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($risingPosts as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['title'] }}</td>
                                <td>{{ number_format($row['views']) }}</td>
                                <td>{{ number_format($row['previous_views']) }}</td>
                                <td class="text-success">
                                    +{{ number_format($row['view_delta']) }}
                                    @if ($row['growth_rate'] !== null)
                                        <span class="small">({{ number_format($row['growth_rate'], 1) }}%)</span>
                                    @else
                                        <span class="small">({{ __('new') }})</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-body-secondary">{{ __('No growth detected yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card border-secondary-subtle h-100">
            <div class="card-header d-flex justify-content-between align-items-center gap-2">
                <span>{{ __('Posts losing audience') }}</span>
                <span class="text-body-secondary small">{{ __('vs previous') }} {{ $period }} {{ __('days') }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Post') }}</th>
                            <th>{{ __('Current') }}</th>
                            <th>{{ __('Previous') }}</th>
                            <th>{{ __('Change') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fallingPosts as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['title'] }}</td>
                                <td>{{ number_format($row['views']) }}</td>
                                <td>{{ number_format($row['previous_views']) }}</td>
                                <td class="text-danger">
                                    {{ number_format($row['view_delta']) }}
                                    @if ($row['growth_rate'] !== null)
                                        <span class="small">({{ number_format($row['growth_rate'], 1) }}%)</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-body-secondary">{{ __('No decline detected yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card border-secondary-subtle mb-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <span>{{ __('Top posts by views') }}</span>
        <span class="text-body-secondary small">
            @if ($locale)
                {{ $localeLabels[$locale] ?? strtoupper($locale) }} · {{ strtoupper($locale) }}
            @else
                {{ __('All countries') }}
            @endif
            · {{ $period }} {{ __('days') }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Post') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Likes') }}</th>
                    <th>{{ __('Comments') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Trend') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topPosts as $row)
                    <tr>
                        <td class="text-body-secondary">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold">{{ $row['title'] }}</div>
                            @if ($row['content_locale'])
                                <div class="text-body-secondary small">{{ strtoupper($row['content_locale']) }}</div>
                            @endif
                        </td>
                        <td>{{ $row['content_type_label'] }}</td>
                        <td class="fw-semibold">{{ number_format($row['views']) }}</td>
                        <td>{{ number_format($row['likes']) }}</td>
                        <td>{{ number_format($row['comments']) }}</td>
                        <td>{{ number_format($row['engagement_rate'], 1) }}%</td>
                        <td>
                            @if ($row['is_new_growth'])
                                <span class="text-success">{{ __('New activity') }}</span>
                            @elseif ($row['growth_rate'] !== null && $row['growth_rate'] > 0)
                                <span class="text-success">+{{ number_format($row['growth_rate'], 1) }}%</span>
                            @elseif ($row['growth_rate'] !== null && $row['growth_rate'] < 0)
                                <span class="text-danger">{{ number_format($row['growth_rate'], 1) }}%</span>
                            @elseif ($row['growth_rate'] !== null)
                                <span class="text-body-secondary">0.0%</span>
                            @else
                                <span class="text-body-secondary">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-body-secondary">{{ __('No data yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-secondary-subtle">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-1">
        <span>{{ __('Top posts by engagement') }}</span>
        <span class="text-body-secondary small">{{ __('High engagement with very few views can be less reliable; compare both columns.') }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Post') }}</th>
                    <th>{{ __('Engagement') }}</th>
                    <th>{{ __('Views') }}</th>
                    <th>{{ __('Like rate') }}</th>
                    <th>{{ __('Comment rate') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($topEngagementPosts as $row)
                    <tr>
                        <td class="text-body-secondary">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $row['title'] }}</td>
                        <td class="fw-semibold">{{ number_format($row['engagement_rate'], 1) }}%</td>
                        <td>{{ number_format($row['views']) }}</td>
                        <td>{{ number_format($row['like_rate'], 1) }}%</td>
                        <td>{{ number_format($row['comment_rate'], 1) }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-body-secondary">{{ __('No data yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
