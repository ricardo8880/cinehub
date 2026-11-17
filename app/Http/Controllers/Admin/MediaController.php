<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaRequest;
use App\Models\Media;
use App\Models\Post;
use App\Support\MediaFileChanges;
use App\Support\HomeContentCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(private readonly MediaFileChanges $fileChanges)
    {
    }

    public function index(Post $post): View
    {
        $post->load(['localizations', 'media']);

        return view('admin.media.index', compact('post'));
    }

    public function create(Post $post): View
    {
        $post->load('localizations');

        return view('admin.media.create', [
            'post' => $post,
            'locales' => config('localization.locales'),
        ]);
    }

    public function store(MediaRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();
        $this->validateMediaRequirements($data, $request->hasFile('image'));

        $this->fileChanges->transaction(function () use ($request, $post, $data): void {
            if ($data['is_primary']) {
                $post->media()->where('type', $data['type'])
                    ->where('locale', $data['locale'] ?? null)
                    ->update(['is_primary' => false]);
            }

            $path = $data['type'] === 'image' && $request->file('image')
                ? $this->fileChanges->store($request->file('image'), 'posts/'.$post->id)
                : null;

            $post->media()->create([
                'locale' => $data['locale'] ?? null,
                'type' => $data['type'],
                'provider' => $data['provider'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'thumbnail_path' => $path,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'duration_seconds' => $data['duration_seconds'] ?? null,
                'is_primary' => $data['is_primary'],
                'sort_order' => $data['sort_order'] ?? 0,
            ]);
        });

        HomeContentCache::forget();

        return redirect()
            ->route('admin.posts.media.index', $post)
            ->with('success', __('Media added successfully.'));
    }

    public function edit(Post $post, Media $medium): View
    {
        abort_unless($medium->post_id === $post->id, 404);

        return view('admin.media.edit', [
            'post' => $post,
            'medium' => $medium,
            'locales' => config('localization.locales'),
        ]);
    }

    public function update(MediaRequest $request, Post $post, Media $medium): RedirectResponse
    {
        abort_unless($medium->post_id === $post->id, 404);

        $data = $request->validated();
        $hasImage = $request->hasFile('image') || ($data['type'] === 'image' && (bool) $medium->thumbnail_path);
        $this->validateMediaRequirements($data, $hasImage);

        $this->fileChanges->transaction(function () use ($request, $post, $medium, $data): void {
            if ($data['is_primary']) {
                $post->media()
                    ->where('type', $data['type'])
                    ->where('locale', $data['locale'] ?? null)
                    ->whereKeyNot($medium->id)
                    ->update(['is_primary' => false]);
            }

            $previousPath = $medium->thumbnail_path;
            $path = $data['type'] === 'image' ? $previousPath : null;

            if ($data['type'] === 'image' && $request->hasFile('image')) {
                $path = $this->fileChanges->store($request->file('image'), 'posts/'.$post->id);
            }

            if ($previousPath && $path !== $previousPath) {
                $this->fileChanges->retire($previousPath);
            }

            $medium->update([
                'locale' => $data['locale'] ?? null,
                'type' => $data['type'],
                'provider' => $data['provider'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'thumbnail_path' => $path,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'duration_seconds' => $data['duration_seconds'] ?? null,
                'is_primary' => $data['is_primary'],
                'sort_order' => $data['sort_order'] ?? 0,
            ]);
        });

        HomeContentCache::forget();

        return redirect()
            ->route('admin.posts.media.index', $post)
            ->with('success', __('Media updated successfully.'));
    }

    public function destroy(Post $post, Media $medium): RedirectResponse
    {
        abort_unless($medium->post_id === $post->id, 404);

        $this->fileChanges->transaction(function () use ($medium): void {
            $this->fileChanges->retire($medium->thumbnail_path);
            $medium->delete();
        });

        HomeContentCache::forget();

        return redirect()
            ->route('admin.posts.media.index', $post)
            ->with('success', __('Media deleted successfully.'));
    }

    private function validateMediaRequirements(array $data, bool $hasImage): void
    {
        if ($data['type'] === 'image' && ! $hasImage) {
            throw ValidationException::withMessages([
                'image' => __('An image file is required for image media.'),
            ]);
        }

        if ($data['type'] === 'trailer' && (empty($data['provider']) || empty($data['provider_id']))) {
            throw ValidationException::withMessages([
                'provider' => __('Choose YouTube or Vimeo and provide the video ID.'),
            ]);
        }
    }
}
