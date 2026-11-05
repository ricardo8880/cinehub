<?php

namespace App\Http\Controllers;

use App\Models\BlogGenreLocalization;
use App\Models\CategoryLocalization;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Models\SiteSetting;
use App\Support\Seo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $locales = Seo::indexableLocales();
        $pages = collect();

        // A family is one underlying page with its real published translations.
        // Sitemap alternates, like HTML hreflang tags, must be reciprocal.
        $addFamily = static function (Collection $versions) use ($pages): void {
            $alternates = Seo::alternateLinks($versions->pluck('loc', 'locale')->all());

            foreach ($versions as $entry) {
                $pages->push([
                    'loc' => $entry['loc'],
                    'lastmod' => $entry['lastmod'] ?? null,
                    'alternates' => count($alternates) > 1 ? $alternates : [],
                ]);
            }
        };

        $addFamily(collect($locales)->map(fn (string $locale) => [
            'locale' => $locale,
            'loc' => route('home', ['locale' => $locale]),
        ]));

        $blogLocales = collect($locales)->filter(fn (string $locale) => Post::query()
            ->published()
            ->where('publication_type', 'blog')
            ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->whereHas('blogCategory', fn (Builder $query) => $query->where('is_active', true))
            ->whereHas('blogCategory.localizations', fn (Builder $query) => $query->where('locale', $locale)->where('is_active', true))
            ->exists());

        $addFamily($blogLocales->map(fn (string $locale) => [
            'locale' => $locale,
            'loc' => route('blog.index', ['locale' => $locale]),
        ]));

        // Genre archives are editorial pages with unique titles and canonicals.
        // Only include filters that have published, localized articles.
        $blogGenres = BlogGenreLocalization::query()
            ->whereIn('locale', $locales)
            ->where('is_active', true)
            ->whereHas('genre', fn (Builder $query) => $query->where('is_active', true))
            ->with('genre')
            ->get()
            ->filter(fn (BlogGenreLocalization $version) => $version->genre->posts()
                ->published()
                ->where('publication_type', 'blog')
                ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $version->locale)->where('is_active', true))
                ->whereHas('blogCategory', fn (Builder $query) => $query->where('is_active', true))
                ->whereHas('blogCategory.localizations', fn (Builder $query) => $query->where('locale', $version->locale)->where('is_active', true))
                ->exists());

        foreach ($blogGenres->groupBy('blog_genre_id') as $versions) {
            $addFamily($versions->map(fn (BlogGenreLocalization $version) => [
                'locale' => $version->locale,
                'loc' => route('blog.index', ['locale' => $version->locale, 'genre' => $version->slug]),
            ]));
        }

        // Books are editorial versions, not translations of a global English record.
        $bookLocales = collect($locales)->filter(fn (string $locale): bool =>
            filled(SiteSetting::valueFor(SiteSetting::bookKey('title', $locale)))
        );
        if ($bookLocales->isNotEmpty()) {
            $addFamily($bookLocales->map(fn (string $locale) => [
                'locale' => $locale,
                'loc' => route('book', ['locale' => $locale]),
            ]));
        }

        $categoryVersions = CategoryLocalization::query()
            ->whereIn('locale', $locales)
            ->where('is_active', true)
            ->whereHas('category', fn (Builder $query) => $query->where('is_active', true))
            ->with('category')
            ->get()
            ->filter(function (CategoryLocalization $version): bool {
                // Avoid empty, thin archive pages. Only include categories
                // with at least one genuinely visible standard publication.
                return $version->category->posts()
                    ->published()
                    ->where('publication_type', 'standard')
                    ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $version->locale)->where('is_active', true))
                    ->whereHas('media', fn (Builder $query) => $query->where('locale', $version->locale)->whereIn('type', ['image', 'trailer']))
                    ->exists();
            });

        foreach ($categoryVersions->groupBy('category_id') as $categoryId => $versions) {
            $addFamily($versions->map(fn (CategoryLocalization $version) => [
                'locale' => $version->locale,
                'loc' => route('categories.show', ['locale' => $version->locale, 'category' => $version->slug]),
            ]));
        }

        // Category genre filters are only indexable when the filtered archive
        // really contains a visible published post for that language.
        foreach ($categoryVersions->groupBy('category_id') as $versions) {
            $category = $versions->first()->category;
            $genres = $category->genres()
                ->where('genres.is_active', true)
                ->with('localizations')
                ->get();

            foreach ($genres as $genre) {
                $genrePages = collect();

                foreach ($versions as $categoryVersion) {
                    $genreVersion = $genre->localization($categoryVersion->locale);
                    if (! $genreVersion) {
                        continue;
                    }

                    $hasPosts = $category->posts()
                        ->published()
                        ->where('publication_type', 'standard')
                        ->whereHas('genres', fn (Builder $query) => $query->whereKey($genre->id))
                        ->whereHas('localizations', fn (Builder $query) => $query->where('locale', $categoryVersion->locale)->where('is_active', true))
                        ->whereHas('media', fn (Builder $query) => $query->where('locale', $categoryVersion->locale)->whereIn('type', ['image', 'trailer']))
                        ->exists();

                    if ($hasPosts) {
                        $genrePages->push([
                            'locale' => $categoryVersion->locale,
                            'loc' => route('categories.show', [
                                'locale' => $categoryVersion->locale,
                                'category' => $categoryVersion->slug,
                                'genre' => $genreVersion->slug,
                            ]),
                        ]);
                    }
                }

                $addFamily($genrePages);
            }
        }

        $postVersions = PostLocalization::query()
            ->where('is_active', true)
            ->whereIn('locale', $locales)
            ->whereHas('post', fn (Builder $query) => $query->published())
            ->with([
                'post.categories' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with('localizations')
                    ->orderBy('sort_order')
                    ->orderBy('id'),
                'post.blogCategory.localizations',
            ])
            ->get();

        $validPosts = collect();

        foreach ($postVersions as $version) {
            $post = $version->post;
            if ($post->publication_type === 'blog') {
                if (! $post->blogCategory?->is_active || ! $post->blogCategory->localization($version->locale)) {
                    continue;
                }

                $url = route('blog.show', ['locale' => $version->locale, 'slug' => $version->slug]);
            } elseif ($post->publication_type === 'standard') {
                $category = $post->categories->first(fn ($item) => $item->is_active && $item->localization($version->locale));
                $categoryVersion = $category?->localization($version->locale);
                if (! $categoryVersion || ! $post->media->contains(fn ($media) => $media->locale === $version->locale && in_array($media->type, ['image', 'trailer'], true))) {
                    continue;
                }

                $url = route('posts.show', [
                    'locale' => $version->locale,
                    'category' => $categoryVersion->slug,
                    'slug' => $version->slug,
                ]);
            } else {
                continue;
            }

            $modified = $post->updated_at;
            if ($version->updated_at && (! $modified || $version->updated_at->greaterThan($modified))) {
                $modified = $version->updated_at;
            }

            $validPosts->push([
                'post_id' => $post->id,
                'locale' => $version->locale,
                'loc' => $url,
                'lastmod' => $modified?->toAtomString(),
            ]);
        }

        foreach ($validPosts->groupBy('post_id') as $postId => $versions) {
            $addFamily($versions);
        }

        return response()
            ->view('sitemap', ['urls' => $pages->unique('loc')->values()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
