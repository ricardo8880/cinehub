<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Reaction;
use Illuminate\View\View;

class MetricsController extends Controller
{
    public function __invoke(): View
    {
        $since = now()->subDays(30);

        $topPosts = Post::query()
            ->published()
            ->with('localizations')
            ->withCount(['views', 'reactions as likes_count' => fn ($query) => $query->where('type', 'like')])
            ->orderByDesc('views_count')
            ->limit(10)
            ->get();

        return view('admin.metrics.index', [
            'views30' => PostView::where('viewed_at', '>=', $since)->count(),
            'likes30' => Reaction::where('type', 'like')->where('created_at', '>=', $since)->count(),
            'comments30' => Comment::where('status', 'approved')->where('created_at', '>=', $since)->count(),
            'publishedPosts' => Post::published()->count(),
            'topPosts' => $topPosts,
            'defaultLocale' => config('localization.default'),
        ]);
    }
}
