<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CategoryLocalization;
use App\Models\PostLocalization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggleBlog(Request $request, string $locale, string $slug): RedirectResponse|JsonResponse
    {
        $localization = PostLocalization::query()->where('locale', $locale)->where('slug', $slug)->where('is_active', true)
            ->whereHas('post', fn ($q) => $q->published()->where('publication_type', 'blog')
                ->whereHas('blogCategory', fn ($cq) => $cq->where('is_active', true))
                ->whereHas('blogCategory.localizations', fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)))
            ->firstOrFail();
        $liked = $this->toggleReaction($request, $localization);
        if ($request->expectsJson()) return $this->status($localization, $liked);
        return redirect()->to(route('blog.show', compact('locale', 'slug')).'#interactions');
    }

    public function toggle(Request $request, string $locale, string $category, string $slug): RedirectResponse|JsonResponse
    {
        $categoryLocalization = CategoryLocalization::query()->where('locale', $locale)->where('slug', $category)->where('is_active', true)->firstOrFail();
        $localization = PostLocalization::query()->where('locale', $locale)->where('slug', $slug)->where('is_active', true)
            ->whereHas('post', fn ($q) => $q->published()->where('publication_type', 'standard')
                ->whereHas('categories', fn ($cq) => $cq->whereKey($categoryLocalization->category_id)))
            ->firstOrFail();
        $liked = $this->toggleReaction($request, $localization);
        if ($request->expectsJson()) return $this->status($localization, $liked);
        return redirect()->to(route('posts.show', compact('locale', 'category', 'slug')).'#interactions');
    }

    private function toggleReaction(Request $request, PostLocalization $localization): bool
    {
        $user = $request->user();
        $reactions = $localization->post->reactions();
        $reaction = (clone $reactions)->where('locale', $localization->locale)->where('user_id', $user->id)->where('type', 'like')->first();
        if ($reaction) {
            $reaction->delete();
            return false;
        }

        $reactions->firstOrCreate(
            ['locale' => $localization->locale, 'user_id' => $user->id, 'type' => 'like'],
            ['visitor_key' => hash_hmac('sha256', 'member:'.$user->id, (string) config('app.key'))]
        );
        return true;
    }

    private function status(PostLocalization $localization, bool $liked): JsonResponse
    {
        return response()->json([
            'liked' => $liked,
            'likes_count' => $localization->post->reactions()
                ->where('locale', $localization->locale)->where('type', 'like')->count(),
        ]);
    }
}
