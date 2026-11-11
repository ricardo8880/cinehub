<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchTerm;
use App\Support\PublicSearchQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchTermController extends Controller
{
    public function index(Request $request, PublicSearchQuery $search): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'locale' => ['nullable', 'in:en-us,fr,de'],
            'status' => ['nullable', 'in:missing'],
        ]);
        $term = trim((string) ($filters['q'] ?? ''));
        $locale = $filters['locale'] ?? '';
        $onlyMissing = ($filters['status'] ?? '') === 'missing';

        $searchTerms = SearchTerm::query()
            ->when($locale !== '', fn ($builder) => $builder->where('locale', $locale))
            ->when($onlyMissing, fn ($builder) => $builder->where('last_result_count', 0))
            ->when($term !== '', fn ($builder) => $builder->where('term', 'like', '%'.$term.'%'))
            ->orderByDesc('search_count')
            ->orderByDesc('last_searched_at')
            ->paginate(20)
            ->withQueryString();

        // Check the currently published catalog, not the potentially outdated
        // result count recorded when the visitor originally searched.
        $currentResults = [];
        foreach ($searchTerms as $item) {
            $currentResults[$item->id] = $search->searchPosts($item->locale, $item->term)->exists();
        }

        return view('admin.search-terms.index', compact('searchTerms', 'currentResults', 'term', 'locale', 'onlyMissing'));
    }

    public function destroy(SearchTerm $searchTerm): RedirectResponse
    {
        $searchTerm->delete();

        return back()->with('success', 'Search removed from the history.');
    }
}
