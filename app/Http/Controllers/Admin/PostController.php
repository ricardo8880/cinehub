<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostLocalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $status = trim((string) $request->string('status'));
        $defaultLocale = config('localization.default');

        $posts = Post::query()
            ->with(['localizations', 'categories'])
            ->withCount('media')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', function (Builder $localizationQuery) use ($search): void {
                $localizationQuery->where('title', 'like', "%{$search}%");
            }))
            ->when(in_array($status, ['draft', 'published'], true), fn (Builder $query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'search', 'status', 'defaultLocale'));
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $publishedAt = $this->publishedAt($data['status'], $data['published_at'] ?? null);

        $post = DB::transaction(function () use ($request, $data, $publishedAt): Post {
            $post = Post::create([
                'author_id' => $request->user()->id,
                'status' => $data['status'],
                'is_featured' => false,
                'published_at' => $publishedAt,
            ]);

            $post->categories()->sync($data['categories']);
            $this->syncLocalizations($post, $data['localizations']);
            $this->syncCountryMedia($post, $data['localizations'], $data['media'] ?? [], $request);

            return $post;
        });

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', __('Post created successfully. Add media when ready.'));
    }

    public function edit(Post $post): View
    {
        $post->load(['localizations', 'categories', 'media']);

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();
        $publishedAt = $this->publishedAt($data['status'], $data['published_at'] ?? null, $post);

        DB::transaction(function () use ($request, $post, $data, $publishedAt): void {
            $post->update([
                'status' => $data['status'],
                'is_featured' => false,
                'published_at' => $publishedAt,
            ]);

            $post->categories()->sync($data['categories']);
            $this->syncLocalizations($post, $data['localizations']);
            $this->syncCountryMedia($post, $data['localizations'], $data['media'] ?? [], $request);
        });

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', __('Post updated successfully.'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', __('Post deleted successfully.'));
    }

    private function categories()
    {
        return Category::query()->with('localizations')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
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
                $existing?->update(['is_active' => false, 'is_featured' => false]);
                continue;
            }
            $slug = trim((string) ($localizationData['slug'] ?? ''));

            if ($slug === '') {
                $slug = $existing?->slug ?: $this->uniqueSlug($title, $locale, $existing?->id);
            }

            $isFeatured = filter_var($localizationData['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN);

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
            $settings = (array) ($mediaData[$locale] ?? []);
            $selectedType = trim((string) ($settings['type'] ?? ''));
            $existing = $post->media()
                ->where('locale', $locale)
                ->orderByDesc('is_primary')
                ->orderBy('id')
                ->first();

            if (! $hasCountryVersion || $selectedType === '') {
                $this->deleteCountryMedia($post, $locale);
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

                if ($existing?->thumbnail_path) {
                    Storage::disk('public')->delete($existing->thumbnail_path);
                }

                $medium = $existing ?: $post->media()->make(['locale' => $locale]);
                $medium->fill([
                    'type' => 'trailer',
                    'provider' => 'youtube',
                    'provider_id' => $youtubeId,
                    'embed_url' => null,
                    'thumbnail_path' => null,
                    'alt_text' => null,
                    'caption' => null,
                    'source_url' => $youtubeUrl,
                    'duration_seconds' => null,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
                $medium->save();
                $this->deleteOtherCountryMedia($post, $locale, $medium->id);
                continue;
            }

            $uploadedImage = $request->file("media.$locale.image");
            $existingImagePath = $existing?->type === 'image' ? $existing->thumbnail_path : null;

            if (! $uploadedImage && ! $existingImagePath) {
                throw ValidationException::withMessages([
                    "media.$locale.image" => __('Choose an image file for this country.'),
                ]);
            }

            $path = $existingImagePath;

            if ($uploadedImage) {
                if ($existing?->thumbnail_path) {
                    Storage::disk('public')->delete($existing->thumbnail_path);
                }

                $path = $uploadedImage->store('posts/'.$post->id.'/'.$locale, 'public');
            }

            $medium = $existing ?: $post->media()->make(['locale' => $locale]);
            $medium->fill([
                'type' => 'image',
                'provider' => null,
                'provider_id' => null,
                'embed_url' => null,
                'thumbnail_path' => $path,
                'alt_text' => trim((string) ($settings['alt_text'] ?? '')) ?: $title,
                'caption' => null,
                'source_url' => null,
                'duration_seconds' => null,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
            $medium->save();
            $this->deleteOtherCountryMedia($post, $locale, $medium->id);
        }
    }

    private function deleteCountryMedia(Post $post, string $locale): void
    {
        $post->media()->where('locale', $locale)->get()->each(function ($medium): void {
            if ($medium->thumbnail_path) {
                Storage::disk('public')->delete($medium->thumbnail_path);
            }

            $medium->delete();
        });
    }

    private function deleteOtherCountryMedia(Post $post, string $locale, int $keepId): void
    {
        $post->media()
            ->where('locale', $locale)
            ->where('id', '!=', $keepId)
            ->get()
            ->each(function ($medium): void {
                if ($medium->thumbnail_path) {
                    Storage::disk('public')->delete($medium->thumbnail_path);
                }

                $medium->delete();
            });
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
