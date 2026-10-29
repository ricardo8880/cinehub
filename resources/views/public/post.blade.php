@extends('layouts.public')

@php
    $pageTitle = $localization->seo_title ?: $localization->title;
    $pageDescription = $localization->seo_description ?: ($localization->excerpt ?: config('site.description'));
    $canonicalUrl = route('posts.show', [
        'locale' => $localization->locale,
        'category' => $primaryCategoryLocalization->slug,
        'slug' => $localization->slug,
    ]);
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $localization->title,
        'description' => $pageDescription,
        'image' => [$imageUrl],
        'datePublished' => optional($post->published_at)?->toIso8601String(),
        'dateModified' => optional($post->updated_at)?->toIso8601String(),
        'url' => $canonicalUrl,
        'mainEntityOfPage' => $canonicalUrl,
        'author' => [
            '@type' => 'Person',
            'name' => $post->author?->name ?: config('site.name'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('site.name'),
        ],
        'commentCount' => (int) $post->approved_comments_count,
        'interactionStatistic' => [
            [
                '@type' => 'InteractionCounter',
                'interactionType' => 'https://schema.org/LikeAction',
                'userInteractionCount' => (int) $post->likes_count,
            ],
            [
                '@type' => 'InteractionCounter',
                'interactionType' => 'https://schema.org/ViewAction',
                'userInteractionCount' => (int) $post->views_count,
            ],
        ],
    ];
@endphp

@section('title', $pageTitle.' | '.config('site.name'))
@section('meta_description', $pageDescription)
@section('canonical', $canonicalUrl)
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)
@section('og_url', $canonicalUrl)
@section('og_type', 'article')

@push('seo')
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="article:published_time" content="{{ optional($post->published_at)?->toIso8601String() }}">
<meta property="article:modified_time" content="{{ optional($post->updated_at)?->toIso8601String() }}">
<script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('public-content')
<nav aria-label="{{ __('Breadcrumb') }}" class="mb-3">
    <ol class="breadcrumb small mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('categories.show', ['category' => $primaryCategoryLocalization->slug]) }}">{{ $primaryCategoryLocalization->name }}</a></li>
        <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $localization->title }}</li>
    </ol>
</nav>

<article class="public-article">
    <header class="mb-4">
        <div class="d-flex gap-2 flex-wrap mb-3">
            <a class="badge text-bg-danger" href="{{ route('categories.show', ['category' => $primaryCategoryLocalization->slug]) }}">{{ $primaryCategoryLocalization->name }}</a>
        </div>
        <h1 class="display-5 fw-bold mb-3">{{ $localization->title }}</h1>
        @if ($localization->excerpt)
            <p class="lead text-body-secondary mb-3">{{ $localization->excerpt }}</p>
        @endif
        <div class="d-flex flex-wrap gap-3 small text-body-secondary">
            @if ($post->published_at)
                <span><i class="bi bi-calendar3"></i> {{ $post->published_at->format('M j, Y') }}</span>
            @endif
            <span><i class="bi bi-person"></i> {{ $post->author?->name ?: config('site.name') }}</span>
            <span><i class="bi bi-heart-fill text-danger"></i> {{ $post->likes_count }}</span>
            <span><i class="bi bi-chat"></i> {{ $post->approved_comments_count }}</span>
            <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
        </div>
    </header>

    @if ($trailerUrl)
        <section class="mb-4" aria-label="{{ __('Trailer') }}">
            <div class="ratio ratio-16x9 rounded overflow-hidden bg-black public-article__media">
                <iframe src="{{ $trailerUrl }}" title="{{ $localization->title }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            @if ($trailer?->source_url)
                <div class="small text-body-secondary mt-2">{{ __('Source') }}: <a href="{{ $trailer->source_url }}" rel="nofollow noopener" target="_blank">{{ $trailer->provider ?: __('Official media') }}</a></div>
            @endif
        </section>
    @elseif ($imageUrl)
        <figure class="mb-4">
            <img class="img-fluid w-100 rounded public-article__cover" src="{{ $imageUrl }}" alt="{{ $image?->alt_text ?: $localization->title }}">
            @if ($image?->caption)
                <figcaption class="small text-body-secondary mt-2">{{ $image->caption }}</figcaption>
            @endif
        </figure>
    @endif

    @if ($localization->synopsis)
        <section class="card public-article__section border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 fw-bold mb-3">{{ __('Synopsis') }}</h2>
                <div class="public-article__text">{!! nl2br(e($localization->synopsis)) !!}</div>
            </div>
        </section>
    @endif

    @if ($localization->review)
        <section class="card public-article__section border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 fw-bold mb-3">{{ __('Review') }}</h2>
                <div class="public-article__text">{!! nl2br(e($localization->review)) !!}</div>
            </div>
        </section>
    @endif

    <section id="interactions" class="card public-article__section border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                <div>
                    <h2 class="h4 fw-bold mb-1">{{ __('Did you like this content?') }}</h2>
                    <p class="text-body-secondary mb-0">{{ __('Your reaction helps highlight popular content.') }}</p>
                </div>
                <form method="POST" action="{{ route('reactions.like', ['category' => $primaryCategoryLocalization->slug, 'slug' => $localization->slug]) }}">
                    @csrf
                    <button class="btn {{ $viewerHasLiked ? 'btn-danger' : 'btn-outline-danger' }}" type="submit">
                        <i class="bi {{ $viewerHasLiked ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                        {{ $viewerHasLiked ? __('Liked') : __('Like') }}
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if ($post->categories->count() > 1)
        <div class="d-flex gap-2 flex-wrap mb-5">
            @foreach ($post->categories as $postCategory)
                @php($postCategoryLocalization = $postCategory->localization($localization->locale))
                @if ($postCategoryLocalization)
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('categories.show', ['category' => $postCategoryLocalization->slug]) }}">{{ $postCategoryLocalization->name }}</a>
                @endif
            @endforeach
        </div>
    @endif
</article>

<section class="public-article mt-5" id="comments">
    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
        <h2 class="h3 fw-bold mb-0">{{ __('Comments') }}</h2>
        <span class="badge text-bg-secondary">{{ $post->approved_comments_count }}</span>
    </div>

    @if (session('comment_success'))
        <div class="alert alert-success" role="status">{{ session('comment_success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card public-article__section border-0 mb-4">
        <div class="card-body p-4">
            <h3 class="h5 fw-bold mb-3">{{ __('Leave a comment') }}</h3>
            <form method="POST" action="{{ route('comments.store', ['category' => $primaryCategoryLocalization->slug, 'slug' => $localization->slug]) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="author_name">{{ __('Name') }}</label>
                        <input class="form-control" id="author_name" name="author_name" value="{{ old('author_name') }}" maxlength="80" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="author_email">{{ __('Email') }} <span class="text-body-secondary">({{ __('optional') }})</span></label>
                        <input class="form-control" id="author_email" name="author_email" type="email" value="{{ old('author_email') }}" maxlength="255">
                    </div>
                    <div class="col-12 d-none" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="comment_body">{{ __('Comment') }}</label>
                        <textarea class="form-control" id="comment_body" name="body" rows="5" minlength="5" maxlength="2000" required>{{ old('body') }}</textarea>
                    </div>
                    <div class="col-12 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                        <small class="text-body-secondary">{{ __('Comments are reviewed before publication.') }}</small>
                        <button class="btn btn-danger" type="submit"><i class="bi bi-send me-1"></i>{{ __('Send comment') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-grid gap-3">
        @forelse ($comments as $comment)
            <article class="card public-article__section border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between gap-3 mb-2">
                        <strong>{{ $comment->author_name ?: __('Anonymous') }}</strong>
                        <time class="small text-body-secondary" datetime="{{ $comment->created_at?->toIso8601String() }}">{{ $comment->created_at?->format('M j, Y') }}</time>
                    </div>
                    <div>{!! nl2br(e($comment->body)) !!}</div>
                </div>
            </article>
        @empty
            <div class="card public-empty-state border-0">
                <div class="card-body p-4 text-center text-body-secondary">{{ __('No approved comments yet.') }}</div>
            </div>
        @endforelse
    </div>

    @if ($comments->hasPages())
        <div class="mt-3">{{ $comments->links('pagination::bootstrap-5') }}</div>
    @endif
</section>

@if ($relatedPosts->isNotEmpty())
    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 fw-bold mb-0">{{ __('Related Content') }}</h2>
            <a href="{{ route('categories.show', ['category' => $primaryCategoryLocalization->slug]) }}" class="section-link">{{ __('View All') }} <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach ($relatedPosts as $item)
                <div class="col-12 col-sm-6 col-lg-3">
                    <x-public-card :item="$item" />
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
