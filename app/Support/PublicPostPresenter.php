<?php

namespace App\Support;

use App\Models\CategoryLocalization;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostLocalization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicPostPresenter
{
    public function localization(Post $post, string $requestedLocale): ?PostLocalization
    {
        return $post->localizations
            ->first(fn (PostLocalization $localization) => $localization->locale === $requestedLocale && $localization->is_active);
    }

    public function card(Post $post, string $requestedLocale): ?array
    {
        $localization = $this->localization($post, $requestedLocale);

        if (! $localization) {
            return null;
        }

        $category = $post->categories
            ->first(fn ($category) => $category->is_active && $category->localization($requestedLocale));
        $categoryLocalization = $category?->localization($requestedLocale);

        if (! $category || ! $categoryLocalization) {
            return null;
        }

        $image = $this->image($post, $requestedLocale);
        $trailer = $this->trailer($post, $requestedLocale);

        if (! $image && ! $trailer && $post->publication_type !== 'blog') {
            return null;
        }

        return [
            'id' => $post->id,
            'title' => $localization->title,
            'excerpt' => Str::limit(RichTextHtml::plainText($localization->excerpt ?: $localization->article_body), 220),
            'type' => $categoryLocalization->slug,
            'type_label' => $categoryLocalization->name,
            'content_kind' => $this->contentKind($category->slug),
            'image_url' => $this->imageUrl($image, $post->id, $trailer),
            'image_alt' => $image?->alt_text ?: $localization->title,
            'video_embed_url' => $this->embedUrl($trailer),
            'duration' => $this->formatDuration($trailer?->duration_seconds),
            'likes_count' => $this->formatCount((int) ($post->likes_count ?? 0)),
            'comments_count' => $this->formatCount((int) ($post->approved_comments_count ?? 0)),
            'views_count' => $this->formatCount((int) ($post->views_count ?? $post->recent_views_count ?? 0)),
            'locale' => $requestedLocale,
            'publication_type' => $post->publication_type,
            'blog_genres' => $post->publication_type === 'blog'
                ? $post->blogGenres->map(function ($genre) use ($requestedLocale): ?array {
                    $localization = $genre->localization($requestedLocale);

                    return $localization ? [
                        'name' => $localization->name,
                        'slug' => $localization->slug,
                    ] : null;
                })->filter()->values()->all()
                : [],
            'url' => $post->publication_type === 'blog'
                ? route('blog.show', [
                    'locale' => $requestedLocale,
                    'slug' => $localization->slug,
                ])
                : route('posts.show', [
                    'locale' => $requestedLocale,
                    'category' => $categoryLocalization->slug,
                    'slug' => $localization->slug,
                ]),
        ];
    }

    public function image(Post $post, string $locale): ?Media
    {
        return $post->media
            ->filter(fn (Media $media) => $media->type === 'image' && $media->locale === $locale)
            ->sortBy([['is_primary', 'desc'], ['sort_order', 'asc'], ['id', 'asc']])
            ->first();
    }

    public function trailer(Post $post, string $locale): ?Media
    {
        return $post->media
            ->filter(fn (Media $media) => $media->type === 'trailer' && $media->locale === $locale)
            ->sortBy([['is_primary', 'desc'], ['sort_order', 'asc'], ['id', 'asc']])
            ->first();
    }

    public function imageUrl(?Media $media, int $postId, ?Media $trailer = null): string
    {
        if ($media?->thumbnail_path) {
            if (str_starts_with($media->thumbnail_path, 'http://') || str_starts_with($media->thumbnail_path, 'https://')) {
                return $media->thumbnail_path;
            }

            return Storage::disk('public')->url($media->thumbnail_path);
        }

        if ($trailer?->provider === 'youtube' && $trailer->provider_id) {
            return 'https://i.ytimg.com/vi/'.rawurlencode($trailer->provider_id).'/hqdefault.jpg';
        }

        return asset('images/demo/poster-01.webp');
    }

    public function embedUrl(?Media $media): ?string
    {
        if (! $media) return null;
        if ($media->provider === 'youtube' && $media->provider_id) return 'https://www.youtube-nocookie.com/embed/'.rawurlencode($media->provider_id).'?rel=0';
        if ($media->provider === 'vimeo' && $media->provider_id) return 'https://player.vimeo.com/video/'.rawurlencode($media->provider_id);
        return null;
    }

    public function formatDuration(?int $seconds): string
    {
        if (! $seconds) return '--:--';
        return sprintf('%d:%02d', intdiv($seconds, 60), $seconds % 60);
    }

    public function formatCount(int $count): string
    {
        if ($count >= 1_000_000) return rtrim(rtrim(number_format($count / 1_000_000, 1), '0'), '.').'M';
        if ($count >= 1_000) return rtrim(rtrim(number_format($count / 1_000, 1), '0'), '.').'K';
        return (string) $count;
    }

    private function contentKind(string $categorySlug): string
    {
        return match ($categorySlug) {
            'reviews' => 'reviews',
            'curiosities' => 'curiosities',
            'real-stories' => 'real-stories',
            default => 'trailers',
        };
    }
}
