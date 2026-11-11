<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Support\PublicPostPresenter;
use App\Support\PublicSearchQuery;
use App\Support\SearchHistoryRecorder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request, string $locale, PublicPostPresenter $presenter, PublicSearchQuery $search, SearchHistoryRecorder $history): View
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'search_event' => ['nullable', 'string', 'max:64', 'regex:/^[a-zA-Z0-9_-]+$/'],
        ]);
        $query = trim((string) $request->query('q', ''));
        $navigationCategories = Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')->orderBy('id')->get();

        $posts = $search->searchPosts($locale, $query)
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        // Pagination, empty queries, bots and repeat page loads must not inflate demand.
        if ($query !== '' && ! $request->query->has('page')) {
            $history->record($request, $locale, $query, (int) $posts->total(), $request->query('search_event'));
        }

        $posts->setCollection($posts->getCollection()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values());

        $localeLinks = collect(config('localization.locales'))->mapWithKeys(fn (array $config, string $routeLocale) => [$routeLocale => route('search', ['locale' => $routeLocale, 'q' => $query])])->all();

        return view('public.search', compact('query', 'posts', 'navigationCategories', 'localeLinks'))->with('disableRegionalAlternates', true);
    }

    /** Count a suggestion chosen by the visitor without counting every typed character. */
    public function selection(Request $request, string $locale, PublicSearchQuery $search, SearchHistoryRecorder $history): Response
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'max:120'],
            'search_event' => ['nullable', 'string', 'max:64', 'regex:/^[a-zA-Z0-9_-]+$/'],
        ]);

        $term = trim($data['q']);
        if ($term !== '') {
            $hasResults = $search->searchPosts($locale, $term)->exists();
            $history->record($request, $locale, $term, $hasResults ? 1 : 0, $data['search_event'] ?? null);
        }

        return response()->noContent();
    }

    public function suggestions(Request $request, string $locale, PublicPostPresenter $presenter, PublicSearchQuery $search): JsonResponse
    {
        $request->validate(['q' => ['nullable', 'string', 'max:120']]);
        $query = trim((string) $request->query('q', ''));

        $normalizedQuery = Str::lower($query);

        $resultsQuery = $query === ''
            ? $search->basePostQuery($locale)
            : $search->searchPosts($locale, $query);

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
}
