<?php

namespace App\Support;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MemberFeed
{
    public function visiblePosts(string $locale): Builder
    {
        return Post::query()->published()
            ->whereHas('localizations', fn (Builder $q) => $q->where('locale', $locale)->where('is_active', true))
            ->where(function (Builder $q) use ($locale): void {
                $q->where(function (Builder $standard) use ($locale): void {
                    $standard->where('publication_type', 'standard')
                        ->whereHas('media', fn (Builder $mq) => $mq->where('locale', $locale)->whereIn('type', ['image', 'trailer']))
                        ->whereHas('categories', fn (Builder $cq) => $cq->where('is_active', true)->whereHas('localizations', fn (Builder $lq) => $lq->where('locale', $locale)->where('is_active', true)));
                })->orWhere(function (Builder $blog) use ($locale): void {
                    $blog->where('publication_type', 'blog')
                        ->whereHas('blogCategory', fn (Builder $cq) => $cq->where('is_active', true)
                            ->whereHas('localizations', fn (Builder $lq) => $lq->where('locale', $locale)->where('is_active', true)));
                });
            })
            ->with([
                'categories' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)]),
                'genres' => fn ($q) => $q->where('is_active', true),
                'blogCategory' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)]),
                'blogGenres' => fn ($q) => $q->where('is_active', true)->with(['localizations' => fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)]),
                'localizations' => fn ($q) => $q->where('locale', $locale)->where('is_active', true),
                'media' => fn ($q) => $q->where('locale', $locale),
            ])
            ->withCount([
                'reactions as likes_count' => fn ($q) => $q->where('locale', $locale)->where('type', 'like'),
                'comments as approved_comments_count' => fn ($q) => $q->where('locale', $locale)->where('status', 'approved'),
                'views as views_count' => fn ($q) => $q->where('locale', $locale),
            ]);
    }

    public function suggest(User $user, string $locale, PublicPostPresenter $presenter): Collection
    {
        $weights = [];
        foreach ($user->interests as $interest) {
            $key = $interest->kind.':'.$interest->reference_id;
            $weights[$key] = ($weights[$key] ?? 0) + 8;
        }

        // Reuse actual editorial relationships; no separate recommendation content.
        $likedIds = $user->reactions()->where('type', 'like')->latest()->limit(40)->pluck('post_id');
        $savedIds = $user->favoritePosts()->latest('user_favorites.created_at')->limit(40)->pluck('posts.id');
        $history = Post::query()->whereIn('id', $likedIds->merge($savedIds)->unique())
            ->with(['categories', 'genres', 'blogGenres', 'blogCategory'])->get();
        foreach ($history as $post) {
            foreach ($this->tags($post) as $tag) {
                $weights[$tag] = ($weights[$tag] ?? 0) + 2;
            }
        }

        $posts = $this->visiblePosts($locale)->latest('published_at')->limit(150)->get();
        // Give preference to a matched item, then to recent publication dates.
        $posts = $posts->sort(function (Post $a, Post $b) use ($weights): int {
            $score = fn (Post $post): int => array_sum(array_map(fn ($tag) => $weights[$tag] ?? 0, $this->tags($post)));
            return ($score($b) <=> $score($a)) ?: ($b->published_at <=> $a->published_at);
        });
        return $posts->take(24)->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values();
    }

    private function tags(Post $post): array
    {
        $tags = [];
        foreach ($post->categories as $row) $tags[] = 'category:'.$row->id;
        foreach ($post->genres as $row) $tags[] = 'genre:'.$row->id;
        foreach ($post->blogGenres as $row) $tags[] = 'blog_genre:'.$row->id;
        if ($post->blog_category_id) $tags[] = 'blog_category:'.$post->blog_category_id;
        return $tags;
    }
}
