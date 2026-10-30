<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CategoryLocalization;
use App\Models\PostLocalization;
use App\Support\VisitorIdentity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggle(Request $request, string $locale, string $category, string $slug, VisitorIdentity $visitorIdentity): RedirectResponse
    {
        $categoryLocalization = CategoryLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $category)
            ->where('is_active', true)
            ->firstOrFail();

        $localization = PostLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('post', fn ($query) => $query->published()->whereHas('categories', fn ($categoryQuery) => $categoryQuery->whereKey($categoryLocalization->category_id)))
            ->firstOrFail();

        $visitorKey = $visitorIdentity->key($request);
        $reaction = $localization->post->reactions()
            ->where('locale', $locale)
            ->where('visitor_key', $visitorKey)
            ->where('type', 'like')
            ->first();

        if ($reaction) {
            $reaction->delete();
        } else {
            $localization->post->reactions()->create([
                'locale' => $locale,
                'visitor_key' => $visitorKey,
                'type' => 'like',
            ]);
        }

        $postUrl = $localization->post->publication_type === 'blog'
            ? route('blog.show', ['locale' => $locale, 'slug' => $slug])
            : route('posts.show', ['locale' => $locale, 'category' => $category, 'slug' => $slug]);

        return redirect()->to($postUrl.'#interactions');
    }
}
