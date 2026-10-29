<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Support\PublicPostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request, string $locale, PublicPostPresenter $presenter): View
    {
        $query = trim((string) $request->query('q', ''));
        $navigationCategories = Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')->orderBy('id')->get();

        $posts = $this->searchPosts($locale, $query)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $posts->setCollection($posts->getCollection()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values());

        $localeLinks = collect(config('localization.locales'))->mapWithKeys(fn (array $config, string $routeLocale) => [$routeLocale => route('search', ['locale' => $routeLocale, 'q' => $query])])->all();

        return view('public.search', compact('query', 'posts', 'navigationCategories', 'localeLinks'))->with('disableRegionalAlternates', true);
    }

    public function suggestions(Request $request, string $locale, PublicPostPresenter $presenter): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $normalizedQuery = Str::lower($query);

        $resultsQuery = $query === ''
            ? $this->basePostQuery($locale)
            : $this->searchPosts($locale, $query);

        $results = $resultsQuery
            ->latest('published_at')
            ->limit($query === '' ? 6 : 40)
            ->get()
            ->map(fn (Post $post) => $presenter->card($post, $locale))
            ->filter()
            ->when($query !== '', function ($items) use ($normalizedQuery) {
                return $items->sortBy(function (array $item) use ($normalizedQuery): string {
                    $title = Str::lower($item['title']);
                    $type = Str::lower($item['type_label']);
                    $excerpt = Str::lower((string) ($item['excerpt'] ?? ''));

                    $rank = match (true) {
                        $title === $normalizedQuery => 0,
                        str_starts_with($title, $normalizedQuery) => 1,
                        str_contains($title, $normalizedQuery) => 2,
                        str_contains($type, $normalizedQuery) => 3,
                        str_contains($excerpt, $normalizedQuery) => 4,
                        default => 5,
                    };

                    return sprintf('%02d-%s', $rank, $title);
                });
            })
            ->take($query === '' ? 6 : 8)
            ->values()
            ->map(fn (array $item) => [
                'title' => $item['title'],
                'excerpt' => Str::limit((string) ($item['excerpt'] ?? ''), 92),
                'type' => $query === '' ? __('Recent') : $item['type_label'],
                'image_url' => $item['image_url'],
                'url' => $item['url'],
            ]);

        return response()->json(['results' => $results]);
    }

    private function searchPosts(string $locale, string $query): Builder
    {
        $postQuery = $this->basePostQuery($locale);

        if ($query === '') {
            return $postQuery->whereRaw('1 = 0');
        }

        $like = '%'.$query.'%';

        return $postQuery->where(function (Builder $queryBuilder) use ($locale, $like): void {
            $queryBuilder
                ->whereHas('localizations', function (Builder $localizationQuery) use ($locale, $like): void {
                    $localizationQuery
                        ->where('locale', $locale)
                        ->where('is_active', true)
                        ->where(function (Builder $textQuery) use ($like): void {
                            $textQuery
                                ->where('title', 'like', $like)
                                ->orWhere('excerpt', 'like', $like)
                                ->orWhere('synopsis', 'like', $like)
                                ->orWhere('review', 'like', $like);
                        });
                })
                ->orWhereHas('categories.localizations', function (Builder $categoryQuery) use ($locale, $like): void {
                    $categoryQuery
                        ->where('locale', $locale)
                        ->where('is_active', true)
                        ->where('name', 'like', $like);
                });
        });
    }
    private function basePostQuery(string $locale): Builder
    {
        return Post::query()
            ->published()
            ->whereHas('media', fn ($q) => $q->where('locale', $locale)->whereIn('type', ['image', 'trailer']))
            ->with([
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true),
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($q) => $q->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($q) => $q->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($q) => $q->where('locale', $locale),
            ]);
    }

}
