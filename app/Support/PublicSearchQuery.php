<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

/** The same published, locale-aware criteria used by search and the admin history. */
final class PublicSearchQuery
{
    public function searchPosts(string $locale, string $query): Builder
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
                                ->orWhere('review', 'like', $like)
                                ->orWhere('article_body', 'like', $like);
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

    public function basePostQuery(string $locale): Builder
    {
        return Post::query()
            ->published()
            ->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('locale', $locale)->where('is_active', true))
            ->where(function (Builder $query) use ($locale): void {
                $query->where(function (Builder $blog) use ($locale): void {
                    $blog->where('publication_type', 'blog')
                        ->whereHas('blogCategory', fn (Builder $category) => $category->where('is_active', true)
                            ->whereHas('localizations', fn (Builder $localization) => $localization->where('locale', $locale)->where('is_active', true)));
                })->orWhere(function (Builder $standard) use ($locale): void {
                    $standard->where('publication_type', 'standard')
                        ->whereHas('categories', fn (Builder $category) => $category->where('is_active', true)
                            ->whereHas('localizations', fn (Builder $localization) => $localization->where('locale', $locale)->where('is_active', true)))
                        ->whereHas('media', fn (Builder $media) => $media->where('locale', $locale)->whereIn('type', ['image', 'trailer']));
                });
            })
            ->with([
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)])->orderBy('sort_order'),
                'localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true),
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale))->where('type', 'like'),
                'comments as approved_comments_count' => fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale))->where('status', 'approved'),
                'views as views_count' => fn ($q) => $q->whereIn('locale', EnglishGroup::history($locale)),
            ]);
    }
}
