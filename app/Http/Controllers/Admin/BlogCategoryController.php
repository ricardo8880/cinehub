<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $defaultLocale = config('localization.default');

        $categories = BlogCategory::query()
            ->with('localizations')
            ->withCount(['genres', 'posts'])
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('name', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-categories.index', [
            'categories' => $categories,
            'search' => $search,
            'defaultLocale' => $defaultLocale,
            'locales' => config('localization.locales'),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog-categories.create', [
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(BlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $defaultLocale = config('localization.default');
        $defaultName = trim((string) ($data['localizations'][$defaultLocale]['name'] ?? ''));

        $category = DB::transaction(function () use ($data, $defaultName): BlogCategory {
            $category = BlogCategory::create([
                'name' => $defaultName,
                'is_active' => (bool) ($data['is_active'] ?? true),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncLocalizations($category, $data['localizations']);

            return $category;
        });

        return redirect()->route('admin.blog-categories.edit', $category)->with('success', __('Blog category created successfully.'));
    }

    public function edit(BlogCategory $blog_category): View
    {
        $blog_category->load('localizations');

        return view('admin.blog-categories.edit', [
            'category' => $blog_category,
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blog_category): RedirectResponse
    {
        $data = $request->validated();
        $defaultLocale = config('localization.default');
        $defaultName = trim((string) ($data['localizations'][$defaultLocale]['name'] ?? ''));

        DB::transaction(function () use ($data, $defaultName, $blog_category): void {
            $blog_category->update([
                'name' => $defaultName,
                'is_active' => (bool) ($data['is_active'] ?? false),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncLocalizations($blog_category, $data['localizations']);
        });

        return redirect()->route('admin.blog-categories.edit', $blog_category)->with('success', __('Blog category updated successfully.'));
    }

    public function destroy(BlogCategory $blog_category): RedirectResponse
    {
        $blog_category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', __('Blog category deleted successfully.'));
    }

    private function syncLocalizations(BlogCategory $category, array $localizations): void
    {
        foreach (array_keys(config('localization.locales')) as $locale) {
            $data = (array) ($localizations[$locale] ?? []);
            $name = trim((string) ($data['name'] ?? ''));
            $existing = $category->localizations()->where('locale', $locale)->first();

            if ($name === '') {
                $existing?->update(['is_active' => false]);
                continue;
            }

            $category->localizations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );
        }
    }
}
