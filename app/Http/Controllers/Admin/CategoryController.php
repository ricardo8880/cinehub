<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Models\CategoryLocalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $defaultLocale = config('localization.default');

        $categories = Category::query()
            ->with(['localizations', 'posts'])
            ->withCount('posts')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', fn (Builder $lq) => $lq->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search', 'defaultLocale'));
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $defaultLocale = config('localization.default');
        $default = $data['localizations'][$defaultLocale];

        $category = DB::transaction(function () use ($data, $defaultLocale, $default): Category {
            $category = Category::create([
                'slug' => $this->uniqueBaseSlug($default['slug'] ?: $default['name']),
                'name' => $default['name'],
                'icon' => $data['icon'] ?? null,
                'description' => $default['description'] ?? null,
                'seo_title' => $default['seo_title'] ?? null,
                'seo_description' => $default['seo_description'] ?? null,
                'is_active' => true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncLocalizations($category, $data['localizations']);
            return $category;
        });

        return redirect()->route('admin.categories.edit', $category)->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category): View
    {
        $category->load('localizations');

        return view('admin.categories.edit', [
            'category' => $category,
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $defaultLocale = config('localization.default');
        $default = $data['localizations'][$defaultLocale];

        DB::transaction(function () use ($category, $data, $default): void {
            $category->update([
                'slug' => $this->uniqueBaseSlug($default['slug'] ?: $default['name'], $category->id),
                'name' => $default['name'],
                'icon' => $data['icon'] ?? null,
                'description' => $default['description'] ?? null,
                'seo_title' => $default['seo_title'] ?? null,
                'seo_description' => $default['seo_description'] ?? null,
                'is_active' => true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->syncLocalizations($category, $data['localizations']);
        });

        return redirect()->route('admin.categories.edit', $category)->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', __('Category deleted successfully.'));
    }

    private function syncLocalizations(Category $category, array $localizations): void
    {
        foreach (array_keys(config('localization.locales')) as $locale) {
            $data = (array) ($localizations[$locale] ?? []);
            $name = trim((string) ($data['name'] ?? ''));
            $existing = $category->localizations()->where('locale', $locale)->first();

            if ($name === '') {
                $existing?->update(['is_active' => false]);
                continue;
            }

            $slug = trim((string) ($data['slug'] ?? ''));
            if ($slug === '') {
                $slug = $existing?->slug ?: $this->uniqueLocalizedSlug($name, $locale, $existing?->id);
            }

            $category->localizations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'is_active' => true,
                    'slug' => $slug,
                    'name' => $name,
                    'description' => $data['description'] ?? null,
                    'seo_title' => $data['seo_title'] ?: null,
                    'seo_description' => $data['seo_description'] ?: null,
                ]
            );
        }
    }

    private function uniqueLocalizedSlug(string $name, string $locale, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $suffix = 2;

        while (CategoryLocalization::query()->where('locale', $locale)->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function uniqueBaseSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'category';
        $slug = $base;
        $suffix = 2;

        while (Category::query()->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
