<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaRequest;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaController extends Controller
{
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

        DB::transaction(function () use ($request, $post, $data): void {
            if ($data['is_primary']) {
                $post->media()->where('type', $data['type'])->update(['is_primary' => false]);
            }

            $path = $request->file('image')?->store('posts/'.$post->id, 'public');

            $post->media()->create([
                'locale' => $data['locale'] ?? null,
                'type' => $data['type'],
                'provider' => $data['provider'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'embed_url' => $data['embed_url'] ?? null,
                'thumbnail_path' => $path,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'duration_seconds' => $data['duration_seconds'] ?? null,
                'is_primary' => $data['is_primary'],
                'sort_order' => $data['sort_order'] ?? 0,
            ]);
        });

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
        $hasImage = $request->hasFile('image') || (bool) $medium->thumbnail_path;
        $this->validateMediaRequirements($data, $hasImage);

        DB::transaction(function () use ($request, $post, $medium, $data): void {
            if ($data['is_primary']) {
                $post->media()
                    ->where('type', $data['type'])
                    ->whereKeyNot($medium->id)
                    ->update(['is_primary' => false]);
            }

            $path = $medium->thumbnail_path;

            if ($request->hasFile('image')) {
                if ($path) {
                    Storage::disk('public')->delete($path);
                }

                $path = $request->file('image')->store('posts/'.$post->id, 'public');
            }

            $medium->update([
                'locale' => $data['locale'] ?? null,
                'type' => $data['type'],
                'provider' => $data['provider'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'embed_url' => $data['embed_url'] ?? null,
                'thumbnail_path' => $path,
                'alt_text' => $data['alt_text'] ?? null,
                'caption' => $data['caption'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'duration_seconds' => $data['duration_seconds'] ?? null,
                'is_primary' => $data['is_primary'],
                'sort_order' => $data['sort_order'] ?? 0,
            ]);
        });

        return redirect()
            ->route('admin.posts.media.index', $post)
            ->with('success', __('Media updated successfully.'));
    }

    public function destroy(Post $post, Media $medium): RedirectResponse
    {
        abort_unless($medium->post_id === $post->id, 404);

        if ($medium->thumbnail_path) {
            Storage::disk('public')->delete($medium->thumbnail_path);
        }

        $medium->delete();

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

        if ($data['type'] === 'trailer' && empty($data['embed_url']) && (empty($data['provider']) || empty($data['provider_id']))) {
            throw ValidationException::withMessages([
                'embed_url' => __('Provide an embed URL or both provider and provider ID for the trailer.'),
            ]);
        }
    }
}
