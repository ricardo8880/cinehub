<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CategoryLocalization;
use App\Models\PostLocalization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, string $locale, string $category, string $slug): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'author_name' => ['required', 'string', 'max:80'],
            'author_email' => ['nullable', 'email:rfc', 'max:255'],
            'body' => ['required', 'string', 'min:5', 'max:2000'],
            'website' => ['prohibited'],
        ]);

        $postUrl = route('posts.show', [
            'locale' => $locale,
            'category' => $category,
            'slug' => $slug,
        ]);

        if ($validator->fails()) {
            return redirect()->to($postUrl.'#comments')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

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

        $localization->post->comments()->create([
            'locale' => $locale,
            'author_name' => trim($validated['author_name']),
            'author_email' => filled($validated['author_email'] ?? null) ? strtolower(trim($validated['author_email'])) : null,
            'body' => trim($validated['body']),
            'status' => 'pending',
        ]);

        return redirect()->to($postUrl.'#comments')
            ->with('comment_success', __('Your comment was sent for moderation.'));
    }
}
