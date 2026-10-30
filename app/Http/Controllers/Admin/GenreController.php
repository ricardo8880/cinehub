<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GenreRequest;
use App\Models\Category;
use App\Models\Genre;
use App\Models\GenreLocalization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $defaultLocale = config('localization.default');

        $genres = Genre::query()
            ->with(['localizations', 'categories.localizations'])
            ->withCount('posts')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('localizations', fn (Builder $localizationQuery) => $localizationQuery->where('name', 'like', "%{$search}%")))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.genres.index', compact('genres', 'search', 'defaultLocale'));
    }

    public function create(): View
    {
        return view('admin.genres.create', [
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function store(GenreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $genre = DB::transaction(function () use ($data): Genre {
            $defaultName = trim((string) ($data['localizations'][config('localization.default')]['name'] ?? ''));

            $genre = Genre::create([
                'name' => $defaultName,
                'slug' => $this->legacyUniqueSlug($defaultName),
                'is_active' => (bool) ($data['is_active'] ?? true),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $genre->categories()->sync($data['categories']);
            $this->syncLocalizations($genre, $data['localizations']);

            return $genre;
        });

        return redirect()->route('admin.genres.edit', $genre)->with('success', __('Genre created successfully.'));
    }

    public function edit(Genre $genre): View
    {
        $genre->load(['categories', 'localizations']);

        return view('admin.genres.edit', [
            'genre' => $genre,
            'categories' => $this->categories(),
            'locales' => config('localization.locales'),
            'defaultLocale' => config('localization.default'),
        ]);
    }

    public function update(GenreRequest $request, Genre $genre): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $genre): void {
            $defaultName = trim((string) ($data['localizations'][config('localization.default')]['name'] ?? ''));

            $genre->update([
                'name' => $defaultName,
                'slug' => $this->legacyUniqueSlug($defaultName, $genre->id),
                'is_active' => (bool) ($data['is_active'] ?? false),
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $genre->categories()->sync($data['categories']);
            $this->syncLocalizations($genre, $data['localizations']);

            $allowedCategoryIds = $genre->categories()->pluck('categories.id');
            $invalidPostIds = $genre->posts()
                ->whereDoesntHave('categories', fn (Builder $query) => $query->whereIn('categories.id', $allowedCategoryIds))
                ->pluck('posts.id');

            if ($invalidPostIds->isNotEmpty()) {
                $genre->posts()->detach($invalidPostIds->all());
            }
        });

        return redirect()->route('admin.genres.edit', $genre)->with('success', __('Genre updated successfully.'));
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', __('Genre deleted successfully.'));
    }

    private function categories()
    {
        return Category::query()
            ->with('localizations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    private function syncLocalizations(Genre $genre, array $localizations): void
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

        while (GenreLocalization::query()
            ->where('locale', $locale)
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function legacyUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'genre';
        $slug = $base;
        $suffix = 2;

        while (Genre::query()
            ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
