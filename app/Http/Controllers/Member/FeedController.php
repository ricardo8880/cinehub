<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogGenre;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Post;
use App\Support\MemberFeed;
use App\Support\PublicPostPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function forYou(Request $request, string $locale, MemberFeed $feed, PublicPostPresenter $presenter): View
    {
        $user = $request->user()->load('interests');
        return view('member.for-you', ['items' => $feed->suggest($user, $locale, $presenter)]);
    }

    public function favorites(Request $request, string $locale, MemberFeed $feed, PublicPostPresenter $presenter): View
    {
        $ids = $request->user()->favoritePosts()->orderByDesc('user_favorites.created_at')->pluck('posts.id');
        $posts = $feed->visiblePosts($locale)->whereIn('posts.id', $ids)->get()->keyBy('id');
        $items = $ids->map(fn ($id) => $posts->get($id))
            ->filter()->map(fn (Post $post) => $presenter->card($post, $locale))->filter()->values();
        return view('member.favorites', compact('items'));
    }

    public function toggleFavorite(Request $request, string $locale, Post $post, MemberFeed $feed): RedirectResponse|JsonResponse
    {
        abort_unless($feed->visiblePosts($locale)->whereKey($post->id)->exists(), 404);
        $relation = $request->user()->favoritePosts();
        $saved = ! $relation->where('posts.id', $post->id)->exists();
        if ($saved) {
            $request->user()->favoritePosts()->syncWithoutDetaching([$post->id]);
        } else {
            $request->user()->favoritePosts()->detach($post->id);
        }

        if ($request->expectsJson()) return response()->json(['saved' => $saved]);
        return back();
    }

    public function interests(Request $request, string $locale): View
    {
        $localize = fn ($q) => $q->where('locale', $locale)->where('is_active', true);
        $data = [
            'category' => Category::query()->where('is_active', true)->whereHas('localizations', $localize)->with(['localizations' => $localize])->orderBy('sort_order')->get(),
            'genre' => Genre::query()->where('is_active', true)->whereHas('localizations', $localize)->with(['localizations' => $localize])->orderBy('sort_order')->get(),
            'blog_category' => BlogCategory::query()->where('is_active', true)->whereHas('localizations', $localize)->with(['localizations' => $localize])->orderBy('sort_order')->get(),
            'blog_genre' => BlogGenre::query()->where('is_active', true)->whereHas('localizations', $localize)->with(['localizations' => $localize])->orderBy('sort_order')->get(),
        ];
        $selected = $request->user()->interests()->get()->map(fn ($row) => $row->kind.':'.$row->reference_id)->all();
        return view('member.interests', compact('data', 'selected'));
    }

    public function updateInterests(Request $request, string $locale): RedirectResponse|JsonResponse
    {
        $data = $request->validate(['interests' => ['array', 'max:100'], 'interests.*' => ['required', 'string', 'regex:/^(category|genre|blog_category|blog_genre):[1-9][0-9]*$/']]);
        $mapping = ['category' => Category::class, 'genre' => Genre::class, 'blog_category' => BlogCategory::class, 'blog_genre' => BlogGenre::class];
        $rows = [];
        foreach (array_unique($data['interests'] ?? []) as $value) {
            [$kind, $id] = explode(':', $value, 2);
            if (! $mapping[$kind]::query()->whereKey((int) $id)->where('is_active', true)
                ->whereHas('localizations', fn ($q) => $q->where('locale', $locale)->where('is_active', true))->exists()) {
                throw ValidationException::withMessages(['interests' => __('One of the selected interests is unavailable.')]);
            }
            $rows[] = ['kind' => $kind, 'reference_id' => (int) $id];
        }
        DB::transaction(function () use ($request, $rows): void {
            $request->user()->interests()->delete();
            $request->user()->interests()->createMany($rows);
        });
        if ($request->expectsJson()) return response()->json(['message' => __('Interests saved.')]);
        return redirect()->route('member.for-you', ['locale' => $locale])->with('status', __('Interests saved.'));
    }
}
