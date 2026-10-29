@extends('layouts.public')

@section('title', __('Search').' | '.config('site.name'))
@section('meta_description', __('Search CineHub entertainment content.'))
@section('robots', 'noindex, follow')
@section('canonical', route('search', ['locale' => request()->route('locale')]))

@section('public-content')
<div class="mb-4">
    <h1 class="display-6 fw-bold mb-2">{{ __('Search') }}</h1>
    @if ($query !== '')
        <p class="text-body-secondary mb-0">{{ $posts->total() }} {{ __('results for') }} “{{ $query }}”</p>
    @else
        <p class="text-body-secondary mb-0">{{ __('Enter a title, synopsis or review term.') }}</p>
    @endif
</div>

<form class="public-search-form mb-4" method="get" action="{{ route('search', ['locale' => request()->route('locale')]) }}" data-search-form data-search-mode="global" data-search-suggestions-url="{{ route('search.suggestions', ['locale' => request()->route('locale')]) }}">
    <div class="input-group input-group-lg">
        <span class="input-group-text bg-body-tertiary border-secondary-subtle"><i class="bi bi-search"></i></span>
        <input class="form-control bg-body-tertiary border-secondary-subtle" name="q" value="{{ $query }}" type="search" placeholder="{{ __('Search for movies, series, games...') }}" aria-label="{{ __('Search') }}" autocomplete="off" data-site-search>
        <button class="btn btn-danger" type="submit">{{ __('Search') }}</button>
    </div>
    <div class="site-search-results public-search-results" data-search-results hidden></div>
</form>

@if ($query !== '' && $posts->count())
    <div class="row g-3">
        @foreach ($posts as $item)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <x-public-card :item="$item" />
            </div>
        @endforeach
    </div>

    @if ($posts->hasPages())
        <nav class="d-flex justify-content-between align-items-center mt-4" aria-label="{{ __('Pagination') }}">
            @if ($posts->onFirstPage())
                <span class="btn btn-outline-secondary disabled">{{ __('Previous') }}</span>
            @else
                <a class="btn btn-outline-light" href="{{ $posts->previousPageUrl() }}">{{ __('Previous') }}</a>
            @endif
            <span class="small text-body-secondary">{{ __('Page :current of :last', ['current' => $posts->currentPage(), 'last' => $posts->lastPage()]) }}</span>
            @if ($posts->hasMorePages())
                <a class="btn btn-outline-light" href="{{ $posts->nextPageUrl() }}">{{ __('Next') }}</a>
            @else
                <span class="btn btn-outline-secondary disabled">{{ __('Next') }}</span>
            @endif
        </nav>
    @endif
@elseif ($query !== '')
    <div class="card public-empty-state border-0">
        <div class="card-body text-center py-5">
            <i class="bi bi-search fs-1 text-body-secondary"></i>
            <h2 class="h5 mt-3">{{ __('No results found') }}</h2>
            <p class="text-body-secondary mb-0">{{ __('Try another title or keyword.') }}</p>
        </div>
    </div>
@endif
@endsection
