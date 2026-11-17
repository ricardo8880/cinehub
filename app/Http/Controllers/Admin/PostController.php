<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\BlogCategory;
use App\Models\BlogGenre;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostLocalization;
use App\Support\ArticleImageManager;
use App\Support\HomeContentCache;
use App\Support\MediaFileChanges;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly ArticleImageManager $articleImages,
        private readonly MediaFileChanges $fileChanges,
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $status = trim((string) $request->string('status'));
        $publicationType = $request->string('publication_type')->toString() === 'blog' ? 'blog' : 'standard';
        $defaultLocale = config('localization.default');

        $posts = Post::query()
            ->with(['localizations', 'categories'])
            ->withCount('media')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', function (Builder $localizationQuery) use ($search): void {
                $localizationQuery->where('title', 'like', "%{$search}%");
            }))
            ->when(in_array($status, ['draft', 'published'], true), fn (Builder $query) => $query->where('status', $status))
            ->where('publication_type', $publicationType)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search', 'status', 'publicationType', 'defaultLocale'));
    }

    public function create(): View
    {
        $isBlog = request('publication_type') === 'blog';

        return view('admin.posts.create', [
            'categories' => $isBlog ? collect() : $this->categories(),
            'genres' => $isBlog ? collect() : $this->genres(),
            'blogCategories' => $isBlog ? $this->blogCategories() : collect(),
            'blogGenres' => $isBlog ? $this->blogGenres() : collect(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $publishedAt = $this->publishedAt($data['status'], $data['published_at'] ?? null);

        $post = $this->fileChanges->transaction(function () use ($request, $data, $publishedAt): Post {
            $post = Post::create([
                'author_id' => $request->user()->id,
                'status' => $data['status'],
                'publication_type' => $data['publication_type'],
                'blog_category_id' => $data['publication_type'] === 'blog' ? ($data['blog_category_id'] ?? null) : null,
                'is_featured' => false,
                'published_at' => $publishedAt,
            ]);

            $isBlog = $data['publication_type'] === 'blog';
            $post->categories()->sync($isBlog ? [] : ($data['categories'] ?? []));
            $post->genres()->sync($isBlog ? [] : ($data['genres'] ?? []));
            $post->blogGenres()->sync($isBlog ? ($data['blog_genres'] ?? []) : []);
            $this->syncLocalizations($post, $this->resolveEditorImages($data['localizations'], $request));
            $this->syncCountryMedia($post, $data['localizations'], $data['media'] ?? [], $request);

            return $post;
        });

        HomeContentCache::forget();
        $post->load('localizations');

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', __('Post created successfully. Add media when ready.'));
    }

    public function edit(Post $post): View
    {
        $post->load(['localizations', 'categories', 'genres', 'blogCategory', 'blogGenres', 'media']);

        $isBlog = $post->publication_type === 'blog';

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $isBlog ? collect() : $this->categories(),
            'genres' => $isBlog ? collect() : $this->genres(),
            'blogCategories' => $isBlog ? $this->blogCategories() : collect(),
            'blogGenres' => $isBlog ? $this->blogGenres() : collect(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();
        $publishedAt = $this->publishedAt($data['status'], $data['published_at'] ?? null, $post);
        $oldLocalizedContent = $post->localizations()->get()->keyBy('locale');

        $this->fileChanges->transaction(function () use ($request, $post, $data, $publishedAt, $oldLocalizedContent): void {
            $post->update([
                'status' => $data['status'],
                'publication_type' => $data['publication_type'],
                'blog_category_id' => $data['publication_type'] === 'blog' ? ($data['blog_category_id'] ?? null) : null,
                'is_featured' => false,
                'published_at' => $publishedAt,
            ]);

            $isBlog = $data['publication_type'] === 'blog';
            $post->categories()->sync($isBlog ? [] : ($data['categories'] ?? []));
            $post->genres()->sync($isBlog ? [] : ($data['genres'] ?? []));
            $post->blogGenres()->sync($isBlog ? ($data['blog_genres'] ?? []) : []);
            $this->syncLocalizations($post, $this->resolveEditorImages($data['localizations'], $request));
            $this->syncCountryMedia($post, $data['localizations'], $data['media'] ?? [], $request);
            $newLocalizedContent = $post->localizations()->get()->keyBy('locale');
            foreach ($oldLocalizedContent as $locale => $oldContent) {
                $newContent = $newLocalizedContent->get($locale);
                foreach (['article_body', 'excerpt', 'synopsis', 'review', 'spoilers'] as $field) {
                    $oldPaths = $this->articleImages->pathsFromHtml($oldContent->{$field});
                    $newPaths = $this->articleImages->pathsFromHtml($newContent?->{$field});
                    foreach (array_diff($oldPaths, $newPaths) as $path) {
                        $this->fileChanges->retire($path);
                    }
                }
            }
        });

        HomeContentCache::forget();
        $post->load('localizations');

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', __('Post updated successfully.'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $isBlog = $post->publication_type === 'blog';
        $postId = (int) $post->id;
        $articleBodies = [];
        foreach ($post->localizations()->get() as $localized) {
            foreach (['article_body', 'excerpt', 'synopsis', 'review', 'spoilers'] as $field) {
                if ($localized->{$field}) {
                    $articleBodies[] = $localized->{$field};
                }
            }
        }

        // Record every file before committing the deletion. If physical cleanup
        // fails, a durable pending row remains available for a manual retry.
        $this->fileChanges->transaction(function () use ($post, $postId, $articleBodies): void {
            foreach ($post->media()->pluck('thumbnail_path')->filter() as $path) {
                $this->fileChanges->retire($path);
            }
            foreach ($articleBodies as $body) {
                foreach ($this->articleImages->pathsFromHtml($body) as $path) {
                    $this->fileChanges->retire($path);
                }
            }
            foreach (Storage::disk('public')->allFiles('posts/'.$postId) as $path) {
                $this->fileChanges->retire($path);
            }
            $post->delete(); // hard deletion + FK cascades
        });

        HomeContentCache::forget();

        return redirect()
            ->route('admin.posts.index', ['publication_type' => $isBlog ? 'blog' : 'standard'])
            ->with('success', __($isBlog ? 'Blog article deleted successfully.' : 'Post deleted successfully.'));
    }

    private function categories()
    {
        return Category::query()->with('localizations')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
    }

    private function genres()
    {
        return Genre::query()
            ->with(['categories:id', 'localizations'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }


    private function blogCategories()
    {
        return BlogCategory::query()
            ->with('localizations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function blogGenres()
    {
        return BlogGenre::query()
            ->with(['categories:id', 'localizations'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /** Persist editor uploads only as part of the post save transaction. */
    private function resolveEditorImages(array $localizations, PostRequest $request): array
    {
        $stored = [];
        foreach (array_keys(config('localization.locales')) as $locale) {
            if ($request->input('publication_type') !== 'blog' ||
                ($locale !== config('localization.default') && trim((string) ($localizations[$locale]['title'] ?? '')) === '')) {
                continue;
            }
            $html = (string) ($localizations[$locale]['article_body'] ?? '');
            if (! str_contains($html, '/storage/blog-content/pending/')) {
                continue;
            }

            $files = (array) $request->file('article_uploads', []);
            $html = preg_replace_callback(
                '~(?<=\"|\')/storage/blog-content/pending/([a-f0-9]{32})\.webp(?=\"|\')~',
                function (array $match) use ($request, $locale, $files, &$stored): string {
                    if (isset($stored[$locale][$match[1]])) {
                        return $stored[$locale][$match[1]];
                    }
                    $file = $files[$match[1]] ?? null;
                    if (! $file instanceof \Illuminate\Http\UploadedFile || ! $file->isValid()) {
                        throw ValidationException::withMessages([
                            "localizations.$locale.article_body" => __('The editor image could not be uploaded. Please select it again.'),
                        ]);
                    }
                    $path = $this->fileChanges->storeArticle($file, (int) $request->user()->id, $locale);
                    return $stored[$locale][$match[1]] = Storage::disk('public')->url($path);
                },
                $html,
            );
            if (str_contains($html, '/storage/blog-content/pending/')) {
                throw ValidationException::withMessages([
                    "localizations.$locale.article_body" => __('An editor image is missing its upload.'),
                ]);
            }
            $localizations[$locale]['article_body'] = $html;
        }

        return $localizations;
    }

    private function syncLocalizations(Post $post, array $localizations): void
    {
        $defaultLocale = config('localization.default');

        foreach (array_keys(config('localization.locales')) as $locale) {
            $localizationData = (array) ($localizations[$locale] ?? []);
            $existing = $post->localizations()->where('locale', $locale)->first();
            $title = trim((string) ($localizationData['title'] ?? ''));
            $hasCountryVersion = $locale === $defaultLocale || $title !== '';

            if (! $hasCountryVersion) {
                $existing?->delete();
                continue;
            }
            $slug = $this->uniqueSlug($title, $locale, $existing?->id);

            $isFeatured = $post->publication_type === 'blog'
                ? false
                : filter_var($localizationData['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($isFeatured) {
                PostLocalization::query()
                    ->where('locale', $locale)
                    ->where('post_id', '!=', $post->id)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }

            $post->localizations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'is_active' => true,
                    'is_featured' => $isFeatured,
                    'slug' => $slug,
                    'title' => $title,
                    'excerpt' => $localizationData['excerpt'] ?? null,
                    'synopsis' => $localizationData['synopsis'] ?? null,
                    'review' => $localizationData['review'] ?? null,
                    'article_body' => $post->publication_type === 'blog' ? ($localizationData['article_body'] ?? null) : null,
                    'spoilers' => $localizationData['spoilers'] ?? null,
                    'seo_title' => $localizationData['seo_title'] ?: null,
                    'seo_description' => $localizationData['seo_description'] ?: null,
                ],
            );
        }
    }

    private function syncCountryMedia(Post $post, array $localizations, array $mediaData, PostRequest $request): void
    {
        $defaultLocale = config('localization.default');

        foreach (array_keys(config('localization.locales')) as $locale) {
            $title = trim((string) ($localizations[$locale]['title'] ?? ''));
            $hasCountryVersion = $locale === $defaultLocale || $title !== '';

            // Removing a language also removes its language-specific media. Other
            // languages and media intended for all languages remain untouched.
            if (! $hasCountryVersion) {
                $this->deleteCountryMedia($post, $locale);
                continue;
            }

            $settings = (array) ($mediaData[$locale] ?? []);
            if (! array_key_exists('type', $settings)) {
                // Media created in the dedicated manager must not be changed by
                // a form that omitted the quick-media controls.
                continue;
            }

            $selectedType = trim((string) ($settings['type'] ?? ''));
            $mediumId = (int) ($settings['id'] ?? 0);
            $existing = $mediumId > 0
                ? $post->media()->where('locale', $locale)->whereKey($mediumId)->first()
                : null;

            if ($mediumId > 0 && ! $existing) {
                throw ValidationException::withMessages([
                    "media.$locale.id" => __('The selected media item is no longer available. Reload the page.'),
                ]);
            }

            if ($selectedType === 'keep') {
                // Vimeo (and other items managed in the media screen) must be
                // preserved unless the administrator explicitly replaces it.
                continue;
            }

            if ($selectedType === '') {
                // "Remove selected media" only affects the specific quick-edit
                // item, never the remaining images, YouTube or Vimeo entries.
                if ($existing) {
                    $this->fileChanges->retire($existing->thumbnail_path);
                    $existing->delete();
                }
                continue;
            }

            if ($selectedType === 'youtube') {
                $youtubeUrl = trim((string) ($settings['youtube_url'] ?? ''));
                $youtubeId = $this->youtubeVideoId($youtubeUrl);

                if (! $youtubeId) {
                    throw ValidationException::withMessages([
                        "media.$locale.youtube_url" => __('Enter a valid YouTube video URL.'),
                    ]);
                }

                $this->fileChanges->retire($existing?->thumbnail_path);
                $medium = $existing ?: $post->media()->make(['locale' => $locale]);
                $medium->fill([
                    'type' => 'trailer',
                    'provider' => 'youtube',
                    'provider_id' => $youtubeId,
                    'thumbnail_path' => null,
                    'alt_text' => null,
                    'caption' => null,
                    'source_url' => $youtubeUrl,
                    'duration_seconds' => null,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
                $medium->save();
                $this->demoteOtherPrimaryMedia($post, $locale, 'trailer', (int) $medium->id);
                continue;
            }

            $uploadedImage = $request->file("media.$locale.image");
            $existingImagePath = $existing?->type === 'image' ? $existing->thumbnail_path : null;

            if (! $uploadedImage && ! $existingImagePath) {
                throw ValidationException::withMessages([
                    "media.$locale.image" => __('Choose an image file for this language.'),
                ]);
            }

            $path = $existingImagePath;
            if ($uploadedImage) {
                $path = $this->fileChanges->store($uploadedImage, 'posts/'.$post->id.'/'.$locale);
                $this->fileChanges->retire($existing?->thumbnail_path);
            }

            $medium = $existing ?: $post->media()->make(['locale' => $locale]);
            $medium->fill([
                'type' => 'image',
                'provider' => null,
                'provider_id' => null,
                'thumbnail_path' => $path,
                'alt_text' => trim((string) ($settings['alt_text'] ?? '')) ?: $title,
                'caption' => null,
                'source_url' => null,
                'duration_seconds' => null,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
            $medium->save();
            $this->demoteOtherPrimaryMedia($post, $locale, 'image', (int) $medium->id);
        }
    }

    private function deleteCountryMedia(Post $post, string $locale): void
    {
        $post->media()->where('locale', $locale)->get()->each(function (Media $medium): void {
            $this->fileChanges->retire($medium->thumbnail_path);
            $medium->delete();
        });
    }

    private function demoteOtherPrimaryMedia(Post $post, string $locale, string $type, int $keepId): void
    {
        $post->media()
            ->where('locale', $locale)
            ->where('type', $type)
            ->whereKeyNot($keepId)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
    }

    private function youtubeVideoId(string $url): ?string
    {
        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return null;
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host']));
        $path = $parts['path'] ?? '';
        $videoId = null;

        if ($host === 'youtu.be') {
            $videoId = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtube-nocookie.com'], true)) {
            if ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
            } elseif (preg_match('#^/(?:embed|shorts|live)/([^/?]+)#', $path, $matches)) {
                $videoId = $matches[1];
            }
        }

        return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{6,32}$/', $videoId)
            ? $videoId
            : null;
    }

    private function uniqueSlug(string $title, string $locale, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $suffix = 2;

        while (PostLocalization::query()
            ->where('locale', $locale)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function publishedAt(string $status, mixed $value, ?Post $post = null)
    {
        if ($status === 'draft') {
            return null;
        }

        return $value ?: $post?->published_at ?: now();
    }
}
