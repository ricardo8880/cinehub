<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $categories = BlogCategory::query()
            ->withCount(['genres', 'posts'])
            ->when($search !== '', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.blog-categories.create');
    }

    public function store(BlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $category = BlogCategory::create([
            'name' => trim($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.edit', $category)->with('success', __('Blog category created successfully.'));
    }

    public function edit(BlogCategory $blog_category): View
    {
        return view('admin.blog-categories.edit', ['category' => $blog_category]);
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blog_category): RedirectResponse
    {
        $data = $request->validated();

        $blog_category->update([
            'name' => trim($data['name']),
            'is_active' => (bool) ($data['is_active'] ?? false),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.edit', $blog_category)->with('success', __('Blog category updated successfully.'));
    }

    public function destroy(BlogCategory $blog_category): RedirectResponse
    {
        $blog_category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', __('Blog category deleted successfully.'));
    }
}
