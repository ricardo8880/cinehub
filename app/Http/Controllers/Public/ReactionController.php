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

    public function toggleBlog(Request $request, string $locale, string $slug, VisitorIdentity $visitorIdentity): RedirectResponse
    {
        $localization = PostLocalization::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('post', fn ($query) => $query
                ->published()
                ->where('publication_type', 'blog')
                ->whereHas('blogCategory', fn ($categoryQuery) => $categoryQuery->where('is_active', true))
                ->whereHas('blogCategory.localizations', fn ($localizationQuery) => $localizationQuery
                    ->where('locale', $locale)
                    ->where('is_active', true)))
            ->firstOrFail();

        $this->toggleReaction($request, $localization, $visitorIdentity);

        return redirect()->to(route('blog.show', ['locale' => $locale, 'slug' => $slug]).'#interactions');
    }

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

        $this->toggleReaction($request, $localization, $visitorIdentity);

        return redirect()->to(route('posts.show', ['locale' => $locale, 'category' => $category, 'slug' => $slug]).'#interactions');
    }

    private function toggleReaction(Request $request, PostLocalization $localization, VisitorIdentity $visitorIdentity): void
    {
        $visitorKey = $visitorIdentity->key($request);
        $reaction = $localization->post->reactions()
            ->where('locale', $localization->locale)
            ->where('visitor_key', $visitorKey)
            ->where('type', 'like')
            ->first();

        if ($reaction) {
            $reaction->delete();

            return;
        }

        $localization->post->reactions()->create([
            'locale' => $localization->locale,
            'visitor_key' => $visitorKey,
            'type' => 'like',
        ]);
    }
}
