<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->lower()->value();
        $search = trim($request->string('q')->value());
        $allowedStatuses = ['pending', 'approved', 'spam'];

        if (! in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        $comments = Comment::query()
            ->with(['post.localizations'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($searchQuery) use ($search): void {
                    $searchQuery->where('author_name', 'like', "%{$search}%")
                        ->orWhere('author_email', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%")
                        ->orWhereHas('post.localizations', fn ($localizationQuery) => $localizationQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.comments.index', [
            'comments' => $comments,
            'status' => $status,
            'search' => $search,
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,spam'],
        ]);

        $comment->update($validated);

        return back()->with('success', __('Comment status updated.'));
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', __('Comment deleted.'));
    }
}
