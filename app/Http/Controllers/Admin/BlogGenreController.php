<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogGenreRequest;
use App\Models\BlogCategory;
use App\Models\BlogGenre;
use App\Models\BlogGenreLocalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogGenreController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $defaultLocale = config('localization.default');

        $genres = BlogGenre::query()
            ->with(['localizations', 'categories'])
            ->withCount('posts')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('name', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-genres.index', compact('genres', 'search', 'defaultLocale'));
    }

    public function create(): View
    {
        return view('admin.blog-genres.create', [
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(BlogGenreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $genre = DB::transaction(function () use ($data): BlogGenre {
            $genre = BlogGenre::create([
                'is_active' => (bool) ($data['is_active'] ?? true),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $genre->categories()->sync($data['blog_categories']);
            $this->syncLocalizations($genre, $data['localizations']);

            return $genre;
        });

        return redirect()->route('admin.blog-genres.edit', $genre)->with('success', __('Blog genre created successfully.'));
    }

    public function edit(BlogGenre $blog_genre): View
    {
        $blog_genre->load(['localizations', 'categories']);

        return view('admin.blog-genres.edit', [
            'genre' => $blog_genre,
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(BlogGenreRequest $request, BlogGenre $blog_genre): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $blog_genre): void {
            $blog_genre->update([
                'is_active' => (bool) ($data['is_active'] ?? false),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $blog_genre->categories()->sync($data['blog_categories']);
            $this->syncLocalizations($blog_genre, $data['localizations']);

            $allowedCategoryIds = $blog_genre->categories()->pluck('blog_categories.id');
            $invalidPostIds = $blog_genre->posts()
                ->whereNotIn('posts.blog_category_id', $allowedCategoryIds)
                ->pluck('posts.id');

            if ($invalidPostIds->isNotEmpty()) {
                $blog_genre->posts()->detach($invalidPostIds->all());
            }
        });

        return redirect()->route('admin.blog-genres.edit', $blog_genre)->with('success', __('Blog genre updated successfully.'));
    }

    public function destroy(BlogGenre $blog_genre): RedirectResponse
    {
        $blog_genre->delete();

        return redirect()->route('admin.blog-genres.index')->with('success', __('Blog genre deleted successfully.'));
    }

    private function categories()
    {
        return BlogCategory::query()
            ->with('localizations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function syncLocalizations(BlogGenre $genre, array $localizations): void
    {
        foreach (array_keys(config('localization.locales')) as $locale) {
            $data = (array) ($localizations[$locale] ?? []);
            $name = trim((string) ($data['name'] ?? ''));
            $existing = $genre->localizations()->where('locale', $locale)->first();

            if ($name === '') {
                $existing?->update(['is_active' => false]);
                continue;
            }

            $genre->localizations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $name,
                    'slug' => $this->uniqueSlug($name, $locale, $existing?->id),
                    'is_active' => true,
                ]
            );
        }
    }

    private function uniqueSlug(string $name, string $locale, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'genre';
        $slug = $base;
        $suffix = 2;

        while (BlogGenreLocalization::query()
            ->where('locale', $locale)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
