@extends('layouts.admin')
@section('title', 'Visitor searches')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-search me-2"></i>Visitor searches</h1>
        <p class="text-body-secondary mb-0">See what visitors search for. Search matches are checked against currently published content.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.search-terms.index') }}" class="row g-2 mb-4">
    <div class="col-12 col-md-6 col-lg-5">
        <label for="searchTermFilter" class="visually-hidden">Search term</label>
        <input id="searchTermFilter" type="search" class="form-control" name="q" maxlength="120" value="{{ $term }}" placeholder="Filter by search term">
    </div>
    <div class="col-8 col-md-3 col-lg-3">
        <label for="searchLocaleFilter" class="visually-hidden">Language</label>
        <select id="searchLocaleFilter" class="form-select" name="locale">
            <option value="">All languages</option>
            <option value="en-us" @selected($locale === 'en-us')>English (EN)</option>
            <option value="fr" @selected($locale === 'fr')>French (FR)</option>
            <option value="de" @selected($locale === 'de')>German (DE)</option>
        </select>
    </div>
    <div class="col-8 col-md-3 col-lg-2">
        <label for="searchStatusFilter" class="visually-hidden">Results</label>
        <select id="searchStatusFilter" class="form-select" name="status">
            <option value="">All searches</option>
            <option value="missing" @selected($onlyMissing)>No results when searched</option>
        </select>
    </div>
    <div class="col-4 col-md-auto"><button type="submit" class="btn btn-outline-light w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
    <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('admin.search-terms.index') }}">Clear</a></div>
</form>

<div class="card border-secondary-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Search term</th>
                    <th scope="col">Language</th>
                    <th scope="col" class="text-end">Searches</th>
                    <th scope="col">Search results now</th>
                    <th scope="col">Last searched</th>
                    <th scope="col" class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($searchTerms as $searchTerm)
                    <tr>
                        <td class="fw-semibold text-break">
                            {{ $searchTerm->term }}
                            <div class="small text-body-secondary fw-normal">First: {{ $searchTerm->first_searched_at?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>{{ ['en-us' => 'EN', 'fr' => 'FR', 'de' => 'DE'][$searchTerm->locale] ?? $searchTerm->locale }}</td>
                        <td class="text-end fw-semibold">{{ number_format($searchTerm->search_count, 0, ',', '.') }}</td>
                        <td>
                            @if ($currentResults[$searchTerm->id] ?? false)
                                <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>Found</span>
                            @else
                                <span class="badge text-bg-warning"><i class="bi bi-exclamation-circle me-1"></i>Not found</span>
                            @endif
                        </td>
                        <td class="small text-body-secondary">{{ $searchTerm->last_searched_at?->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.search-terms.destroy', $searchTerm) }}" onsubmit="return confirm('Delete this search history entry? Published content will not be affected.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete search history only" aria-label="Delete search: {{ $searchTerm->term }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-body-secondary py-4">No searches found for these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $searchTerms->links() }}</div>
@endsection
