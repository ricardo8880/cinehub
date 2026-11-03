@extends('layouts.public')

@php
    $baseSeoTitle = __('Blog');
    $baseSeoDescription = __('Guides, rankings, recommendations and editorial articles from CineHub.');
    $seoTitle = $selectedGenreLocalization ? $selectedGenreLocalization->name.' · '.$baseSeoTitle : $baseSeoTitle;
    $seoDescription = $selectedGenreLocalization ? $selectedGenreLocalization->name.' — '.$baseSeoDescription : $baseSeoDescription;

    if ($posts->currentPage() > 1) {
        $pageLabel = __('Page :current of :last', ['current' => $posts->currentPage(), 'last' => $posts->lastPage()]);
        $seoTitle .= ' · '.$pageLabel;
        $seoDescription .= ' · '.$pageLabel;
    }
@endphp

@section('title', $seoTitle.' | '.config('site.name'))
@section('meta_description', $seoDescription)
@section('canonical', $canonicalUrl)
@section('og_title', $seoTitle.' | '.config('site.name'))
@section('og_description', $seoDescription)
@section('og_url', $canonicalUrl)

@section('public-content')
<nav aria-label="{{ __('Breadcrumb') }}" class="mb-3">
    <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home', ['locale' => request()->route('locale')]) }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Blog') }}</li>
    </ol>
</nav>

<div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-4">
    <div>
        <h1 class="display-6 fw-bold mb-2">{{ __('Blog') }}</h1>
        <p class="text-body-secondary mb-0">{{ __('Guides, rankings, recommendations and personal articles.') }}</p>
    </div>
    <span class="badge rounded-pill text-bg-dark border">{{ $posts->total() }} {{ __('articles') }}</span>
</div>

@if ($blogGenres->isNotEmpty())
    <div class="d-flex flex-wrap gap-2 mb-4" aria-label="{{ __('Blog genres') }}">
        <a class="btn btn-sm {{ $selectedGenre ? 'btn-outline-secondary' : 'btn-danger' }}" href="{{ route('blog.index', ['locale' => request()->route('locale')]) }}">{{ __('All') }}</a>
        @foreach ($blogGenres as $blogGenre)
            @php($blogGenreLocalization = $blogGenre->localization(request()->route('locale')))
            @if ($blogGenreLocalization)
                <a class="btn btn-sm {{ $selectedGenre && $selectedGenre->id === $blogGenre->id ? 'btn-danger' : 'btn-outline-secondary' }}" href="{{ route('blog.index', ['locale' => request()->route('locale'), 'genre' => $blogGenreLocalization->slug]) }}">{{ $blogGenreLocalization->name }}</a>
            @endif
        @endforeach
    </div>
@endif

@if ($posts->count())
    <div class="row g-3">
        @foreach ($posts as $item)
            <div class="col-12 col-sm-6 col-lg-4">
                <x-public-card :item="$item" />
            </div>
        @endforeach
    </div>

    @if ($posts->hasPages())
        <div class="mt-4">{{ $posts->links('pagination::bootstrap-5') }}</div>
    @endif
@else
    <div class="card public-empty-state border-0">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-richtext fs-1 text-body-secondary"></i>
            <h2 class="h5 mt-3">{{ __('No Blog articles yet') }}</h2>
            <p class="text-body-secondary mb-0">{{ __('Published Blog articles will appear here automatically.') }}</p>
        </div>
    </div>
@endif
@endsection
