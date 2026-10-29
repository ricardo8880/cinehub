<?php

namespace App\Http\Controllers;

use App\Models\CategoryLocalization;
use App\Models\PostLocalization;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect();

        foreach (array_keys(config('localization.locales')) as $locale) {
            $urls->push(['loc' => route('home', ['locale' => $locale]), 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '1.0']);
        }

        if (SiteSetting::valueFor('book_title')) {
            foreach (array_keys(config('localization.locales')) as $locale) {
                $urls->push(['loc' => route('book', ['locale' => $locale]), 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.5']);
            }
        }

        CategoryLocalization::query()
            ->where('is_active', true)
            ->whereIn('locale', array_keys(config('localization.locales')))
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->with('category')
            ->get()
            ->each(function (CategoryLocalization $version) use ($urls): void {
                $urls->push([
                    'loc' => route('categories.show', ['locale' => $version->locale, 'category' => $version->slug]),
                    'lastmod' => optional($version->updated_at)?->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ]);
            });

        $localizations = PostLocalization::query()
            ->where('is_active', true)
            ->whereIn('locale', array_keys(config('localization.locales')))
            ->whereHas('post', fn ($query) => $query->published())
            ->with(['post.categories.localizations'])
            ->get();

        foreach ($localizations as $localization) {
            $category = $localization->post->categories->first(fn ($item) => $item->is_active && $item->localization($localization->locale));
            $categoryVersion = $category?->localization($localization->locale);
            if (! $categoryVersion) continue;

            $urls->push([
                'loc' => route('posts.show', ['locale' => $localization->locale, 'category' => $categoryVersion->slug, 'slug' => $localization->slug]),
                'lastmod' => optional($localization->post->updated_at)?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);
        }

        return response()->view('sitemap', ['urls' => $urls->unique('loc')->values()])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
