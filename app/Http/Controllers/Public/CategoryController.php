<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryLocalization;
use App\Models\Post;
use App\Support\PublicPostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __invoke(string $locale, string $category, PublicPostPresenter $presenter): View
    {
        $categoryLocalization = CategoryLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $category)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $categoryModel = $categoryLocalization->category;
        abort_unless($categoryModel->is_active, 404);

        $navigationCategories = Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')->orderBy('id')->get();

        $posts = Post::query()
            ->published()
            ->whereHas('categories', fn ($query) => $query->whereKey($categoryModel->id))
            ->whereHas('localizations', fn ($query) => $query->where('locale', $locale)->where('is_active', true))
            ->whereHas('media', fn ($query) => $query->where('locale', $locale)->whereIn('type', ['image', 'trailer']))
            ->with([
                'categories' => fn ($query) => $query->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'localizations' => fn ($query) => $query->where('locale', $locale)->where('is_active', true),
                'media' => fn ($query) => $query->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($query) => $query->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($query) => $query->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($query) => $query->where('locale', $locale),
            ])
            ->latest('published_at')->paginate(12)->withQueryString();

        $posts->setCollection($posts->getCollection()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values());

        $localeLinks = [];
        $alternateUrls = [];
        $categoryModel->loadMissing('localizations');
        foreach (array_keys(config('localization.locales')) as $routeLocale) {
            $version = $categoryModel->localization($routeLocale);
            $url = $version ? route('categories.show', ['locale' => $routeLocale, 'category' => $version->slug]) : null;
            $localeLinks[$routeLocale] = $url;
            if ($url) $alternateUrls[$routeLocale] = $url;
        }

        return view('public.category', [
            'category' => $categoryModel,
            'categoryLocalization' => $categoryLocalization,
            'posts' => $posts,
            'navigationCategories' => $navigationCategories,
            'localeLinks' => $localeLinks,
            'alternateUrls' => $alternateUrls,
        ]);
    }
}
