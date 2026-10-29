<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Support\PublicPostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

        $posts = Post::query()
            ->published()
            ->when($query !== '', function ($postQuery) use ($query, $locale): void {
                $like = '%'.$query.'%';
                $postQuery->whereHas('localizations', function ($lq) use ($locale, $like): void {
                    $lq->where('locale', $locale)->where('is_active', true)->where(function ($sq) use ($like): void {
                        $sq->where('title', 'like', $like)->orWhere('excerpt', 'like', $like)->orWhere('synopsis', 'like', $like)->orWhere('review', 'like', $like);
                    });
                });
            })
            ->when($query === '', fn ($q) => $q->whereRaw('1 = 0'))
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
            ])
            ->latest('published_at')->paginate(12)->withQueryString();

        $posts->setCollection($posts->getCollection()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values());

        $localeLinks = collect(config('localization.locales'))->mapWithKeys(fn (array $config, string $routeLocale) => [$routeLocale => route('search', ['locale' => $routeLocale, 'q' => $query])])->all();

        return view('public.search', compact('query', 'posts', 'navigationCategories', 'localeLinks'))->with('disableRegionalAlternates', true);
    }
}
