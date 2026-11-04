<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CategoryLocalization;
use App\Models\Comment;
use App\Models\PostLocalization;
use App\Support\CommentModeration;
use App\Support\EnglishGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function preview(Request $request, CommentModeration $moderation): JsonResponse
    {
        $data = $request->validate(['body' => ['present', 'string', 'max:2000']]);
        return response()->json(['classification' => $moderation->classify($data['body'])]);
    }

    public function storeBlog(Request $request, string $locale, string $slug, CommentModeration $moderation): RedirectResponse|JsonResponse
    {
        $localization = PostLocalization::query()->where('locale', $locale)->where('slug', $slug)
            ->where('is_active', true)->whereHas('post', fn ($q) => $q->published()->where('publication_type', 'blog')
                ->whereHas('blogCategory', fn ($cq) => $cq->where('is_active', true))
                ->whereHas('blogCategory.localizations', fn ($lq) => $lq->where('locale', $locale)->where('is_active', true)))
            ->with('post')->firstOrFail();
        return $this->storeComment($request, $localization, route('blog.show', compact('locale', 'slug')), $moderation);
    }

    public function store(Request $request, string $locale, string $category, string $slug, CommentModeration $moderation): RedirectResponse|JsonResponse
    {
        $categoryLocalization = CategoryLocalization::query()->where('locale', $locale)->where('slug', $category)->where('is_active', true)->firstOrFail();
        $localization = PostLocalization::query()->where('locale', $locale)->where('slug', $slug)
            ->where('is_active', true)->whereHas('post', fn ($q) => $q->published()->where('publication_type', 'standard')
                ->whereHas('categories', fn ($cq) => $cq->whereKey($categoryLocalization->category_id)))
            ->with('post')->firstOrFail();
        return $this->storeComment($request, $localization, route('posts.show', compact('locale', 'category', 'slug')), $moderation);
    }

    private function storeComment(Request $request, PostLocalization $localization, string $postUrl, CommentModeration $moderation): RedirectResponse|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'min:5', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'min:1'],
            'website' => ['prohibited'],
        ]);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->to($postUrl.'#comments')->withErrors($validator)->withInput();
        }
        $data = $validator->validated();
        $status = $moderation->classify($data['body']);

        if ($status === 'blocked') {
            $message = __('Your comment contains abusive or prohibited language. Please edit it before sending.');
            if ($request->expectsJson()) return response()->json(['message' => $message], 422);
            return redirect()->to($postUrl.'#comments')->withErrors(['body' => $message])->withInput();
        }

        $parentId = null;
        if (! empty($data['parent_id'])) {
            $parent = Comment::query()->whereKey($data['parent_id'])
                ->where('post_id', $localization->post_id)->whereIn('locale', EnglishGroup::history($localization->locale))
                ->where(function ($q) use ($request): void {
                    $q->where('status', 'approved')
                        ->orWhere(fn ($private) => $private->whereIn('status', ['pending', 'spam'])->where('user_id', $request->user()->id));
                })->first();
            if (! $parent) {
                $message = __('This comment is no longer available.');
                if ($request->expectsJson()) return response()->json(['message' => $message], 422);
                return redirect()->to($postUrl.'#comments')->withErrors(['body' => $message]);
            }
            $parentId = $parent->parent_id ?: $parent->id;
        }

        $comment = $localization->post->comments()->create([
            'locale' => $localization->locale,
            'parent_id' => $parentId,
            'user_id' => $request->user()->id,
            'author_name' => $request->user()->name,
            'author_email' => null,
            'body' => trim($data['body']),
            'status' => $status === 'review' ? 'pending' : 'approved',
        ]);

        if ($request->expectsJson()) {
            $comment->loadCount('likes')->load([
                'likes' => fn ($query) => $query->where('user_id', $request->user()->id),
            ]);

            return response()->json([
                'message' => __('Your comment was posted.'),
                'comment_id' => $comment->id,
                'parent_id' => $parentId,
                'visibility' => $status === 'review' ? 'private' : 'public',
                'approved_count' => $localization->post->comments()
                    ->whereIn('locale', EnglishGroup::history($localization->locale))->where('status', 'approved')->count(),
                'html' => view('components.comment-entry', [
                    'comment' => $comment,
                    'formAction' => $request->url(),
                    'reply' => $parentId !== null,
                ])->render(),
            ], 201);
        }

        return redirect()->to($postUrl.'#comments')->with('comment_success', __('Your comment was posted.'));
    }
}
