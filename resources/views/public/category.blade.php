@extends('layouts.public')

@section('title', ($categoryLocalization->seo_title ?: $categoryLocalization->name).' | '.config('site.name'))
@section('meta_description', $categoryLocalization->seo_description ?: ($categoryLocalization->description ?: __('Explore :category content on CineHub.', ['category' => $categoryLocalization->name])))
@section('canonical', route('categories.show', ['locale' => request()->route('locale'), 'category' => $categoryLocalization->slug]))
@section('og_title', $categoryLocalization->seo_title ?: $categoryLocalization->name)
@section('og_description', $categoryLocalization->seo_description ?: ($categoryLocalization->description ?: config('site.description')))

@section('public-content')
<nav aria-label="{{ __('Breadcrumb') }}" class="mb-3">
    <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $categoryLocalization->name }}</li>
    </ol>
</nav>

<div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-4">
    <div>
        <h1 class="display-6 fw-bold mb-2">{{ $categoryLocalization->name }}</h1>
        @if ($categoryLocalization->description)
            <p class="text-body-secondary mb-0">{{ $categoryLocalization->description }}</p>
        @endif
    </div>
    <span class="badge rounded-pill text-bg-dark border">{{ $posts->total() }} {{ __('items') }}</span>
</div>

@if ($genres->isNotEmpty())
    <div class="mb-4">
        <div class="small text-body-secondary mb-2">{{ __('Genres') }}</div>
        <div class="d-flex flex-wrap gap-2">
            <a
                class="btn btn-sm {{ $selectedGenre ? 'btn-outline-light' : 'btn-light' }}"
                href="{{ route('categories.show', ['locale' => request()->route('locale'), 'category' => $categoryLocalization->slug]) }}"
            >
                {{ __('All') }}
            </a>

            @foreach ($genres as $genre)
                @php($genreLocalization = $genre->localization(request()->route('locale')))
                <a
                    class="btn btn-sm {{ $selectedGenre && $selectedGenre->id === $genre->id ? 'btn-light' : 'btn-outline-light' }}"
                    href="{{ route('categories.show', ['locale' => request()->route('locale'), 'category' => $categoryLocalization->slug, 'genre' => $genreLocalization->slug]) }}"
                >
                    {{ $genreLocalization->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif

@if ($posts->count())
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
@else
    <div class="card public-empty-state border-0">
        <div class="card-body text-center py-5">
            <i class="bi bi-collection fs-1 text-body-secondary"></i>
            <h2 class="h5 mt-3">{{ __('No published content yet') }}</h2>
            <p class="text-body-secondary mb-0">{{ __('Published posts in this category will appear here automatically.') }}</p>
        </div>
    </div>
@endif
@endsection
