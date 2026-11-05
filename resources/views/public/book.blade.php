@extends('layouts.public')
@php
    // Book metadata is global English copy. Do not present it as a translated edition.
    if (request()->route('locale') !== 'en-us') {
        $book = ['title' => null, 'synopsis' => null, 'cover_url' => null, 'sample_url' => null, 'purchase_url' => null];
    }
@endphp
@section('title', ($book['title'] ?: __('Book')).' · '.config('site.name'))
@section('meta_description', $book['synopsis'] ?: config('site.description'))
@section('robots', $book['title'] && request()->route('locale') === 'en-us' ? 'index, follow' : 'noindex, follow')
@section('twitter_card', $book['cover_url'] ? 'summary_large_image' : 'summary')

@if ($book['title'])
@push('seo')
@php
    $bookUrl = route('book', ['locale' => request()->route('locale')]);
    $bookSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Book',
        'name' => $book['title'],
        'description' => $book['synopsis'] ?: null,
        'url' => $bookUrl,
        'inLanguage' => \App\Support\Seo::language(request()->route('locale')),
    ];
    if ($book['cover_url']) {
        $bookSchema['image'] = $book['cover_url'];
    }
@endphp
@if ($book['cover_url'])
<meta property="og:image" content="{{ $book['cover_url'] }}">
<meta name="twitter:image" content="{{ $book['cover_url'] }}">
@endif
<script type="application/ld+json">{!! json_encode($bookSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) !!}</script>
@endpush
@endif

@section('public-content')
<div class="row g-4 align-items-start">
    @if ($book['cover_url'])
        <div class="col-12 col-md-4"><img class="img-fluid rounded shadow-sm" src="{{ $book['cover_url'] }}" alt="{{ $book['title'] ?: __('Book cover') }}" loading="lazy" decoding="async"></div>
    @endif
    <div class="col">
        <h1 class="h2">{{ $book['title'] ?: __('Book area') }}</h1>
        <p class="text-body-secondary">{{ $book['synopsis'] ?: __('Book information has not been published yet.') }}</p>
        <div class="d-flex flex-wrap gap-2">
            @if ($book['sample_url'])<a class="btn btn-outline-light" href="{{ $book['sample_url'] }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-file-earmark-text me-1"></i>{{ __('Free sample') }}</a>@endif
            @if ($book['purchase_url'])<a class="btn btn-danger" href="{{ $book['purchase_url'] }}" target="_blank" rel="sponsored noopener noreferrer"><i class="bi bi-cart me-1"></i>{{ __('Buy book') }}</a>@endif
        </div>
    </div>
</div>
@endsection
