<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryLocalization;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Models\PostView;
use App\Support\PublicPostPresenter;
use App\Support\VisitorIdentity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __invoke(Request $request, string $locale, string $category, string $slug, PublicPostPresenter $presenter, VisitorIdentity $visitorIdentity): View
    {
        $categoryLocalization = CategoryLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $category)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $localization = PostLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('post', fn ($q) => $q->published()->where('publication_type', 'standard')->whereHas('categories', fn ($cq) => $cq->whereKey($categoryLocalization->category_id)))
            ->firstOrFail();

        $post = $localization->post()
            ->with([
                'author',
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'genres' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                'localizations',
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($q) => $q->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($q) => $q->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($q) => $q->where('locale', $locale),
            ])
            ->firstOrFail();

        $primaryCategory = $post->categories->first(fn ($item) => $item->localization($locale));
        abort_unless($primaryCategory, 404);
        $primaryCategoryLocalization = $primaryCategory->localization($locale);
        abort_unless($primaryCategoryLocalization, 404);
        abort_unless($categoryLocalization->category_id === $primaryCategory->id, 404);

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

        $relatedPosts = Post::query()
            ->published()
            ->where('publication_type', 'standard')
            ->whereKeyNot($post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->whereHas('localizations', fn ($q) => $q->where('locale', $locale)->where('is_active', true))
            ->whereHas('media', fn ($q) => $q->where('locale', $locale)->whereIn('type', ['image', 'trailer']))
            ->with([
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'genres' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                'localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true),
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($q) => $q->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($q) => $q->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($q) => $q->where('locale', $locale),
            ])
            ->latest('published_at')->limit(4)->get()
            ->map(fn (Post $relatedPost) => $presenter->card($relatedPost, $locale))->filter()->values();

        $navigationCategories = Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')->orderBy('id')->get();

        $localeLinks = [];
        $alternateUrls = [];
        $post->loadMissing('localizations');
        $primaryCategory->loadMissing('localizations');

        foreach (array_keys(config('localization.locales')) as $routeLocale) {
            $postVersion = $post->localizations->first(fn ($item) => $item->locale === $routeLocale && $item->is_active);
            $categoryVersion = $primaryCategory->localization($routeLocale);
            $url = ($postVersion && $categoryVersion)
                ? route('posts.show', ['locale' => $routeLocale, 'category' => $categoryVersion->slug, 'slug' => $postVersion->slug])
                : null;
            $localeLinks[$routeLocale] = $url;
            if ($url) $alternateUrls[$routeLocale] = $url;
        }

        $isBlog = false;

        return view('public.post', compact('post', 'localization', 'primaryCategory', 'primaryCategoryLocalization', 'image', 'imageUrl', 'trailer', 'trailerUrl', 'comments', 'viewerHasLiked', 'relatedPosts', 'navigationCategories', 'localeLinks', 'alternateUrls', 'isBlog'));
    }
}
