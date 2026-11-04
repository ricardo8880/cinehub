<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Support\MemberFeed;
use App\Support\EnglishGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentReactionController extends Controller
{
    public function __invoke(Request $request, string $locale, Comment $comment, MemberFeed $feed): RedirectResponse|JsonResponse
    {
        abort_unless(in_array($comment->locale, EnglishGroup::history($locale), true) && ($comment->status === 'approved' || (in_array($comment->status, ['pending', 'spam'], true) && $comment->user_id === $request->user()->id)), 404);
        abort_unless($feed->visiblePosts($locale)->whereKey($comment->post_id)->exists(), 404);
        $existing = $comment->likes()->where('user_id', $request->user()->id)->first();
        if ($existing) $existing->delete();
        else $comment->likes()->firstOrCreate(['user_id' => $request->user()->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'liked' => ! (bool) $existing,
                'likes_count' => $comment->likes()->count(),
            ]);
        }

        return back();
    }
}
