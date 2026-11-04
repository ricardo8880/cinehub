<?php

namespace App\Http\Controllers\Public;

use App\Support\EnglishGroup;

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
            $selectedGenre->load('localizations');
        }

        $posts = Post::query()
            ->published()
            ->where('publication_type', 'blog')
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->whereHas('blogCategory', fn (Builder $query) => $query->where('is_active', true))
            ->whereHas('blogCategory.localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->when($selectedGenre, fn (Builder $query) => $query->whereHas('blogGenres', fn (Builder $genreQuery) => $genreQuery->whereKey($selectedGenre->id)))
            ->with([
                'blogCategory' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)]),
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
                'reactions as likes_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)),
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
                    ->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true))
                    ->whereHas('blogCategory', fn (Builder $categoryQuery) => $categoryQuery->where('is_active', true))
                    ->whereHas('blogCategory.localizations', fn (Builder $categoryLocalizationQuery) => $categoryLocalizationQuery->where('locale', $locale)->where('is_active', true));
            })
            ->with(['localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $navigationCategories = $this->navigationCategories($locale);
        $currentPage = $posts->currentPage();
        $canonicalParameters = ['locale' => $locale];

        if ($selectedGenreLocalization) {
            $canonicalParameters['genre'] = $selectedGenreLocalization->slug;
        }

        if ($currentPage > 1) {
            $canonicalParameters['page'] = $currentPage;
        }

        $canonicalUrl = route('blog.index', $canonicalParameters);
        $localeLinks = [];
        $alternateUrls = [];

        foreach (array_keys(config('localization.locales')) as $routeLocale) {
            $genreVersion = $selectedGenre?->localization($routeLocale);

            if ($selectedGenre && ! $genreVersion) {
                $localeLinks[$routeLocale] = null;
                continue;
            }

            $parameters = ['locale' => $routeLocale];

            if ($genreVersion) {
                $parameters['genre'] = $genreVersion->slug;
            }

            if ($currentPage > 1) {
                $parameters['page'] = $currentPage;
            }

            $url = route('blog.index', $parameters);
            $localeLinks[$routeLocale] = $url;
            $alternateUrls[$routeLocale] = $url;
        }

        return view('public.blog.index', compact(
            'posts',
            'blogGenres',
            'selectedGenre',
            'selectedGenreLocalization',
            'navigationCategories',
            'localeLinks',
            'alternateUrls',
            'canonicalUrl'
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
                'blogCategory' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)]),
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
                'reactions as likes_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)),
            ])
            ->firstOrFail();


        $blogCategory = $post->blogCategory;
        abort_unless($blogCategory && $blogCategory->is_active, 404);
        $blogCategoryLocalization = $blogCategory->localization($locale);
        abort_unless($blogCategoryLocalization, 404);

        $primaryCategory = null;
        $primaryCategoryLocalization = null;

        $visitorKey = $visitorIdentity->key($request);

        if (! $visitorIdentity->isBot($request)) {
            $recentViewExists = PostView::query()
                ->where('post_id', $post->id)
                ->whereIn('locale', EnglishGroup::history($locale))
                ->where('visitor_key', $visitorKey)
                ->where('viewed_at', '>=', now()->subHours(6))
                ->exists();

            if (! $recentViewExists) {
                $post->views()->create(['locale' => $locale, 'visitor_key' => $visitorKey, 'viewed_at' => now()]);
                $post->views_count++;
            }
        }

        $viewerHasLiked = $request->user()
            ? $post->reactions()->whereIn('locale', EnglishGroup::history($locale))->where('user_id', $request->user()->id)->where('type', 'like')->exists()
            : false;
        $viewerHasFavorited = $request->user()
            ? $request->user()->favoritePosts()->where('posts.id', $post->id)->exists()
            : false;
        $visibleToViewer = static function ($query) use ($request): void {
            $query->where('status', 'approved');
            if ($request->user()) {
                $query->orWhere(fn ($private) => $private->whereIn('status', ['pending', 'spam'])->where('user_id', $request->user()->id));
            }
        };
        $comments = $post->comments()->whereIn('locale', EnglishGroup::history($locale))->whereNull('parent_id')
            ->where(fn ($query) => $visibleToViewer($query))
            ->withCount('likes')
            ->with([
                'likes' => fn ($q) => $q->where('user_id', $request->user()?->id ?? 0),
                'replies' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where(fn ($q) => $visibleToViewer($q))
                    ->withCount('likes')->with(['likes' => fn ($q) => $q->where('user_id', $request->user()?->id ?? 0)])->oldest(),
            ])
            ->latest()->paginate(10, ['*'], 'comments_page')->withQueryString();

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
            $relatedPostsQuery->where('blog_category_id', $post->blog_category_id);
        }

        $relatedPostsQuery
            ->whereHas('blogCategory', fn (Builder $query) => $query->where('is_active', true))
            ->whereHas('blogCategory.localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true));

        $relatedPosts = $relatedPostsQuery
            ->with([
                'blogCategory' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['localizations' => fn ($localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true)]),
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
                'reactions as likes_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale))->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->whereIn('locale', EnglishGroup::history($locale)),
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
            'blogCategory',
            'blogCategoryLocalization',
            'image',
            'imageUrl',
            'trailer',
            'trailerUrl',
            'comments',
            'viewerHasLiked',
            'viewerHasFavorited',
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
