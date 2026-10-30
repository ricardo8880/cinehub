<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogGenre;
use App\Models\BlogGenreLocalization;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Models\PostView;
use App\Support\PublicPostPresenter;
use App\Support\VisitorIdentity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, string $locale, PublicPostPresenter $presenter): View
    {
        $genreSlug = $request->string('genre')->lower()->value();
        $selectedGenre = null;
        $selectedGenreLocalization = null;

        if ($genreSlug !== '') {
            $selectedGenreLocalization = BlogGenreLocalization::query()
                ->where('locale', $locale)
                ->where('slug', $genreSlug)
                ->where('is_active', true)
                ->whereHas('genre', fn (Builder $query) => $query->where('is_active', true))
                ->with('genre')
                ->firstOrFail();
            $selectedGenre = $selectedGenreLocalization->genre;
        }

        $posts = Post::query()
            ->published()
            ->where('publication_type', 'blog')
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->whereHas('categories.localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->when($selectedGenre, fn (Builder $query) => $query->whereHas('blogGenres', fn (Builder $genreQuery) => $genreQuery->whereKey($selectedGenre->id)))
            ->with([
                'blogCategory',
                'blogGenres' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])
                    ->orderBy('sort_order')
                    ->orderBy('id'),
                'categories' => fn ($query) => $query->where('is_active', true)->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'genres' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                'localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true),
                'media' => fn ($query) => $query->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($query) => $query->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->where('locale', $locale),
            ])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $posts->setCollection($posts->getCollection()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values());

        $blogGenres = BlogGenre::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->whereHas('posts', function (Builder $query) use ($locale): void {
                $query->published()
                    ->where('publication_type', 'blog')
                    ->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true));
            })
            ->with(['localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $navigationCategories = $this->navigationCategories($locale);
        $localeLinks = collect(config('localization.locales'))->mapWithKeys(fn (array $config, string $routeLocale) => [$routeLocale => route('blog.index', ['locale' => $routeLocale])])->all();

        return view('public.blog.index', compact(
            'posts',
            'blogGenres',
            'selectedGenre',
            'selectedGenreLocalization',
            'navigationCategories',
            'localeLinks'
        ));
    }

    public function show(Request $request, string $locale, string $slug, PublicPostPresenter $presenter, VisitorIdentity $visitorIdentity): View
    {
        $localization = PostLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('post', fn (Builder $query) => $query->published()->where('publication_type', 'blog'))
            ->firstOrFail();

        $post = $localization->post()
            ->with([
                'author',
                'blogCategory',
                'blogGenres' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])
                    ->orderBy('sort_order')
                    ->orderBy('id'),
                'categories' => fn ($query) => $query->where('is_active', true)->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'genres' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                'localizations',
                'media' => fn ($query) => $query->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($query) => $query->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->where('locale', $locale),
            ])
            ->firstOrFail();


        $primaryCategory = $post->categories->first(fn ($category) => $category->localization($locale));
        abort_unless($primaryCategory, 404);
        $primaryCategoryLocalization = $primaryCategory->localization($locale);
        abort_unless($primaryCategoryLocalization, 404);

        $visitorKey = $visitorIdentity->key($request);

        if (! $visitorIdentity->isBot($request)) {
            $recentViewExists = PostView::query()
                ->where('post_id', $post->id)
                ->where('locale', $locale)
                ->where('visitor_key', $visitorKey)
                ->where('viewed_at', '>=', now()->subHours(6))
                ->exists();

            if (! $recentViewExists) {
                $post->views()->create(['locale' => $locale, 'visitor_key' => $visitorKey, 'viewed_at' => now()]);
                $post->views_count++;
            }
        }

        $viewerHasLiked = $post->reactions()->where('locale', $locale)->where('visitor_key', $visitorKey)->where('type', 'like')->exists();
        $comments = $post->comments()->where('locale', $locale)->where('status', 'approved')->whereNull('parent_id')->latest()->paginate(10, ['*'], 'comments_page')->withQueryString();

        $image = $presenter->image($post, $locale);
        $trailer = $presenter->trailer($post, $locale);
        $imageUrl = $presenter->imageUrl($image, $post->id, $trailer);
        $trailerUrl = $presenter->embedUrl($trailer);

        $blogGenreIds = $post->blogGenres->pluck('id');
        $relatedPostsQuery = Post::query()
            ->published()
            ->where('publication_type', 'blog')
            ->whereKeyNot($post->id)
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true));

        if ($blogGenreIds->isNotEmpty()) {
            $relatedPostsQuery->whereHas('blogGenres', fn (Builder $query) => $query->whereIn('blog_genres.id', $blogGenreIds));
        } else {
            $relatedPostsQuery->whereHas('categories', fn (Builder $query) => $query->whereIn('categories.id', $post->categories->pluck('id')));
        }

        $relatedPosts = $relatedPostsQuery
            ->with([
                'blogGenres' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])
                    ->orderBy('sort_order')
                    ->orderBy('id'),
                'categories' => fn ($query) => $query->where('is_active', true)->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true),
                'media' => fn ($query) => $query->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($query) => $query->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->where('locale', $locale),
            ])
            ->latest('published_at')
            ->limit(4)
            ->get()
            ->map(fn (Post $relatedPost) => $presenter->card($relatedPost, $locale))
            ->filter()
            ->values();

        $navigationCategories = $this->navigationCategories($locale);
        $localeLinks = [];
        $alternateUrls = [];

        foreach (array_keys(config('localization.locales')) as $routeLocale) {
            $version = $post->localizations->first(fn ($item) => $item->locale === $routeLocale && $item->is_active);
            $url = $version ? route('blog.show', ['locale' => $routeLocale, 'slug' => $version->slug]) : null;
            $localeLinks[$routeLocale] = $url;
            if ($url) {
                $alternateUrls[$routeLocale] = $url;
            }
        }

        $isBlog = true;

        return view('public.post', compact(
            'post',
            'localization',
            'primaryCategory',
            'primaryCategoryLocalization',
            'image',
            'imageUrl',
            'trailer',
            'trailerUrl',
            'comments',
            'viewerHasLiked',
            'relatedPosts',
            'navigationCategories',
            'localeLinks',
            'alternateUrls',
            'isBlog'
        ));
    }

    private function navigationCategories(string $locale)
    {
        return Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
