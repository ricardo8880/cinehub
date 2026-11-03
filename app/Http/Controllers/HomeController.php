<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Support\PublicPostPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, PublicPostPresenter $presenter): View
    {
        $locale = (string) $request->route('locale');
        $categories = $this->categories($locale);
        $activeCategory = null;

        $baseQuery = fn (): Builder => Post::query()
            ->published()
            ->where('publication_type', 'standard')
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->whereHas('categories.localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->whereHas('media', fn (Builder $q) => $q->where('locale', $locale)->whereIn('type', ['image', 'trailer']))
            ->with([
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true),
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn (Builder $q) => $q->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn (Builder $q) => $q->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn (Builder $q) => $q->where('locale', $locale),
            ]);

        $featuredPost = $baseQuery()
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true)->where('is_featured', true))
            ->latest('published_at')
            ->first();

        $featuredItem = $featuredPost ? $presenter->card($featuredPost, $locale) : null;
        if ($featuredItem) {
            $featuredItem['subtype_label'] = match ($featuredItem['content_kind'] ?? 'trailers') {
                'reviews' => __('Review'),
                'curiosities' => __('Curiosity'),
                'real-stories' => __('Real Story'),
                default => $featuredItem['video_embed_url'] ? __('Trailer') : __('Image'),
            };
        }

        $latestItems = $baseQuery()
            ->whereHas('media', fn (Builder $q) => $q->where('locale', $locale)->where('type', 'trailer')->where('provider', 'youtube'))
            ->latest('published_at')
            ->limit(4)
            ->get()
            ->map(fn (Post $post) => $presenter->card($post, $locale))
            ->filter()->values();

        $popularItems = Cache::remember("cinehub:home:{$locale}:popular", now()->addMinutes(2), function () use ($baseQuery, $locale, $presenter): Collection {
            return $baseQuery()
                ->trending($locale, 7)
                ->limit(5)
                ->get()
                ->map(fn (Post $post) => $presenter->card($post, $locale))
                ->filter()
                ->values();
        });

        $trendingItems = Cache::remember("cinehub:home:{$locale}:trending", now()->addMinutes(2), function () use ($baseQuery, $locale, $presenter): Collection {
            return $baseQuery()
                ->trending($locale, 2)
                ->limit(5)
                ->get()
                ->map(function (Post $post) use ($presenter, $locale): ?array {
                    $item = $presenter->card($post, $locale);
                    if (! $item) return null;

                    return [
                        'title' => $item['title'],
                        'subtitle' => $item['type_label'],
                        'views' => $presenter->formatCount((int) ($post->recent_views_count ?? 0)),
                        'image_url' => $item['image_url'],
                        'video_embed_url' => $item['video_embed_url'],
                        'url' => $item['url'],
                    ];
                })
                ->filter()
                ->values();
        });

        $ids = $latestItems->merge($popularItems)->pluck('id')->push($featuredItem['id'] ?? null)->filter()->unique()->all();
        $myLikedIds = $request->user() && $ids
            ? $request->user()->reactions()->where('locale', $locale)->where('type', 'like')->whereIn('post_id', $ids)->pluck('post_id')->all()
            : [];

        return view('home', compact('categories', 'activeCategory', 'featuredItem', 'latestItems', 'popularItems', 'trendingItems', 'myLikedIds'));
    }

    private function categories(string $locale): Collection
    {
        $tones = ['movies' => 'slate', 'tv-shows' => 'violet', 'games' => 'blue', 'curiosities' => 'amber', 'real-stories' => 'steel', 'reviews' => 'crimson'];

        return Category::query()
            ->where('is_active', true)
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->with(['localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (Category $category) use ($locale, $tones): ?array {
                $localized = $category->localization($locale);
                if (! $localized) return null;

                return [
                    'key' => $localized->slug,
                    'label' => $localized->name,
                    'icon' => $category->icon ?: 'bi-grid',
                    'description' => $localized->description ?: __('Explore more content'),
                    'tone' => $tones[$category->slug] ?? 'slate',
                    'url' => route('categories.show', ['locale' => $locale, 'category' => $localized->slug]),
                ];
            })
            ->filter()->values();
    }
}
