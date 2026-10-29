<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Suggestion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'postsCount' => Post::count(),
            'categoriesCount' => Category::count(),
            'pendingCommentsCount' => Comment::where('status', 'pending')->count(),
            'pendingSuggestionsCount' => Suggestion::where('status', 'pending')->count(),
            'unreadContactsCount' => ContactMessage::where('status', 'unread')->count(),
        ]);
    }
}
