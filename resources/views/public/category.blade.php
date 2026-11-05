@extends('layouts.public')

@php
    $genreLocalization = $selectedGenre?->localization(request()->route('locale'));
    $baseSeoTitle = $categoryLocalization->seo_title ?: $categoryLocalization->name;
    $baseSeoDescription = \App\Support\SeoDescription::resolve($categoryLocalization->seo_description, $categoryLocalization->description, __('Explore :category content on CineHub.', ['category' => $categoryLocalization->name]));
    $seoTitle = $genreLocalization ? $genreLocalization->name.' · '.$baseSeoTitle : $baseSeoTitle;
    $seoDescription = $genreLocalization ? $genreLocalization->name.' — '.$baseSeoDescription : $baseSeoDescription;

    if ($posts->currentPage() > 1) {
        $pageLabel = __('Page :current of :last', ['current' => $posts->currentPage(), 'last' => $posts->lastPage()]);
        $seoTitle .= ' · '.$pageLabel;
        $seoDescription .= ' · '.$pageLabel;
    }
@endphp

@section('title', $seoTitle.' | '.config('site.name'))
@section('meta_description', $seoDescription)
@section('canonical', $canonicalUrl)
@section('robots', $posts->total() > 0 ? 'index, follow' : 'noindex, follow')
@section('og_title', $seoTitle)
@section('og_description', $seoDescription)
@section('og_url', $canonicalUrl)

@push('seo')
@if ($posts->total() > 0)
@php
    $archiveBreadcrumbs = [
        ['@type' => 'ListItem', 'position' => 1, 'name' => __('Home'), 'item' => route('home', ['locale' => request()->route('locale')])],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $categoryLocalization->name, 'item' => route('categories.show', ['locale' => request()->route('locale'), 'category' => $categoryLocalization->slug])],
    ];
    if ($genreLocalization) {
        $archiveBreadcrumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $genreLocalization->name, 'item' => $canonicalUrl];
    }
    $archiveSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'CollectionPage',
                'name' => $seoTitle,
                'description' => $seoDescription,
                'url' => $canonicalUrl,
                'inLanguage' => \App\Support\Seo::language(request()->route('locale')),
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'itemListElement' => $posts->getCollection()->values()->map(fn (array $item, int $index) => [
                        '@type' => 'ListItem',
                        'position' => ($posts->currentPage() - 1) * $posts->perPage() + $index + 1,
                        'url' => $item['url'],
                        'name' => $item['title'],
                    ])->all(),
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $archiveBreadcrumbs,
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($archiveSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) !!}</script>
@endif
@endpush


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
