@extends('layouts.public')

@php
    $isBlog = $isBlog ?? ($post->publication_type === 'blog');
    $pageTitle = $localization->seo_title ?: $localization->title;
    $pageDescription = $localization->seo_description ?: (\App\Support\RichTextHtml::plainText($localization->excerpt) ?: config('site.description'));
    $canonicalUrl = $isBlog
        ? route('blog.show', [
            'locale' => $localization->locale,
            'slug' => $localization->slug,
        ])
        : route('posts.show', [
            'locale' => $localization->locale,
            'category' => $primaryCategoryLocalization->slug,
            'slug' => $localization->slug,
        ]);
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => $isBlog ? 'BlogPosting' : 'Article',
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
        <li class="breadcrumb-item"><a href="{{ route('home', ['locale' => $localization->locale]) }}">{{ __('Home') }}</a></li>
        @if ($isBlog)
            <li class="breadcrumb-item"><a href="{{ route('blog.index', ['locale' => $localization->locale]) }}">{{ __('Blog') }}</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('categories.show', ['locale' => $localization->locale, 'category' => $primaryCategoryLocalization->slug]) }}">{{ $primaryCategoryLocalization->name }}</a></li>
        @endif
        <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $localization->title }}</li>
    </ol>
</nav>

<article class="public-article">
    <header class="mb-4">
        <div class="d-flex gap-2 flex-wrap mb-3">
            @if ($isBlog)
                <a class="badge text-bg-danger" href="{{ route('blog.index', ['locale' => $localization->locale]) }}">{{ __('Blog') }}</a>
                @foreach ($post->blogGenres as $blogGenre)
                    @php($blogGenreLocalization = $blogGenre->localization($localization->locale))
                    @if ($blogGenreLocalization)
                        <a class="badge text-bg-dark border text-decoration-none" href="{{ route('blog.index', ['locale' => $localization->locale, 'genre' => $blogGenreLocalization->slug]) }}">{{ $blogGenreLocalization->name }}</a>
                    @endif
                @endforeach
            @else
                <a class="badge text-bg-danger" href="{{ route('categories.show', ['locale' => $localization->locale, 'category' => $primaryCategoryLocalization->slug]) }}">{{ $primaryCategoryLocalization->name }}</a>
            @endif
        </div>
        <h1 class="display-5 fw-bold mb-3">{{ $localization->title }}</h1>
        @if ($localization->excerpt)
            <div class="lead text-body-secondary mb-3 public-article__text">{!! \App\Support\RichTextHtml::normalize($localization->excerpt) !!}</div>
        @endif
        <div class="d-flex flex-wrap gap-3 small text-body-secondary">
            @if ($post->published_at)
                <span><i class="bi bi-calendar3"></i> {{ $post->published_at->format('M j, Y') }}</span>
            @endif
            <span><i class="bi bi-person"></i> {{ $post->author?->name ?: config('site.name') }}</span>
            @guest
                <a class="text-body-secondary text-decoration-none" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#interactions']) }}" aria-label="{{ __('Sign in to like') }}" title="{{ __('Sign in to like') }}"><i class="bi bi-heart-fill text-danger"></i> <span data-post-like-count>{{ $post->likes_count }}</span></a>
                <a class="text-body-secondary text-decoration-none" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#comments']) }}" aria-label="{{ __('Sign in to comment') }}" title="{{ __('Sign in to comment') }}"><i class="bi bi-chat"></i> <span data-public-comment-count>{{ $post->approved_comments_count }}</span></a>
            @else
                <span><i class="bi bi-heart-fill text-danger"></i> <span data-post-like-count>{{ $post->likes_count }}</span></span>
                <a class="text-body-secondary text-decoration-none" href="#comments" aria-label="{{ __('Open comments') }}"><i class="bi bi-chat"></i> <span data-public-comment-count>{{ $post->approved_comments_count }}</span></a>
            @endguest
            <span><i class="bi bi-eye"></i> {{ $post->views_count }}</span>
        </div>
    </header>

    @if ($trailerUrl)
        <section class="mb-4 public-article__media-wrap" aria-label="{{ __('Trailer') }}">
            <div class="ratio ratio-16x9 rounded overflow-hidden bg-black public-article__media">
                <iframe src="{{ $trailerUrl }}" title="{{ $localization->title }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            @if ($trailer?->source_url)
                <div class="small text-body-secondary mt-2">{{ __('Source') }}: <a href="{{ $trailer->source_url }}" rel="nofollow noopener" target="_blank">{{ $trailer->provider ?: __('Official media') }}</a></div>
            @endif
        </section>
    @elseif ($imageUrl)
        <figure class="mb-4 public-article__media-wrap">
            <div class="public-article__image-frame rounded">
                <img class="img-fluid rounded public-article__cover" src="{{ $imageUrl }}" alt="{{ $image?->alt_text ?: $localization->title }}">
            </div>
            @if ($image?->caption)
                <figcaption class="small text-body-secondary mt-2">{{ $image->caption }}</figcaption>
            @endif
        </figure>
    @endif

    @if ($isBlog)
        @if ($localization->article_body)
            <section class="card public-article__section border-0 mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="public-article__text">{!! \App\Support\RichTextHtml::normalize($localization->article_body) !!}</div>
                </div>
            </section>
        @endif
    @else
    @if ($localization->synopsis)
        <section class="card public-article__section border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 fw-bold mb-3">{{ __('Synopsis') }}</h2>
                <div class="public-article__text">{!! \App\Support\RichTextHtml::normalize($localization->synopsis) !!}</div>
            </div>
        </section>
    @endif

    @if ($localization->review)
        <section class="card public-article__section border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 fw-bold mb-3">{{ __('Review') }}</h2>
                <div class="public-article__text">{!! \App\Support\RichTextHtml::normalize($localization->review) !!}</div>
            </div>
        </section>
    @endif

    @if ($localization->spoilers)
        @php($spoilerId = 'post-spoilers-'.$post->id)
        <section class="card public-article__section public-article__spoilers border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <h2 class="h4 fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>{{ __('Spoilers') }}</h2>
                        <p class="text-body-secondary mb-0">{{ __('This section reveals important plot details.') }}</p>
                    </div>
                    <button
                        class="btn btn-outline-danger flex-shrink-0"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $spoilerId }}"
                        aria-expanded="false"
                        aria-controls="{{ $spoilerId }}"
                        data-spoiler-toggle
                        data-show-label="{{ __('Show spoilers') }}"
                        data-hide-label="{{ __('Hide spoilers') }}"
                    >
                        <i class="bi bi-eye me-1" aria-hidden="true"></i>
                        <span data-spoiler-label>{{ __('Show spoilers') }}</span>
                    </button>
                </div>
                <div class="collapse" id="{{ $spoilerId }}" data-spoiler-content>
                    <div class="public-article__spoiler-content public-article__text mt-4 pt-4">{!! nl2br(e($localization->spoilers)) !!}</div>
                </div>
            </div>
        </section>
    @endif

    @endif

    <section id="interactions" class="card public-article__section border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                <div>
                    <h2 class="h4 fw-bold mb-1">{{ __('Did you like this content?') }}</h2>
                    <p class="text-body-secondary mb-0">{{ __('Your reaction helps highlight popular content.') }}</p>
                </div>
                @auth
                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" data-ajax-post-like data-on-label="{{ __('Liked') }}" data-off-label="{{ __('Like') }}" action="{{ $isBlog
                            ? route('blog.reactions.like', ['locale' => $localization->locale, 'slug' => $localization->slug])
                            : route('reactions.like', ['locale' => $localization->locale, 'category' => $primaryCategoryLocalization->slug, 'slug' => $localization->slug]) }}">
                            @csrf
                            <button class="btn {{ $viewerHasLiked ? 'btn-danger' : 'btn-outline-danger' }}" type="submit" aria-pressed="{{ $viewerHasLiked ? 'true' : 'false' }}" data-like-button><i class="bi {{ $viewerHasLiked ? 'bi-heart-fill' : 'bi-heart' }} me-1" data-like-icon></i><span data-like-label>{{ $viewerHasLiked ? __('Liked') : __('Like') }}</span></button>
                        </form>
                        <form method="POST" data-ajax-favorite data-on-label="{{ __('Saved') }}" data-off-label="{{ __('Save') }}" action="{{ route('member.favorites.toggle', ['post' => $post->id]) }}">
                            @csrf
                            <button class="btn {{ $viewerHasFavorited ? 'btn-secondary' : 'btn-outline-secondary' }}" type="submit" aria-pressed="{{ $viewerHasFavorited ? 'true' : 'false' }}" data-favorite-button><i class="bi {{ $viewerHasFavorited ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-1" data-favorite-icon></i><span data-favorite-label>{{ $viewerHasFavorited ? __('Saved') : __('Save') }}</span></button>
                        </form>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-outline-danger" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#interactions']) }}" title="{{ __('Sign in to like') }}"><i class="bi bi-heart me-1" aria-hidden="true"></i>{{ __('Like') }}</a>
                        <a class="btn btn-outline-secondary" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#comments']) }}" title="{{ __('Sign in to comment') }}"><i class="bi bi-chat me-1" aria-hidden="true"></i>{{ __('Comment') }}</a>
                        <a class="btn btn-outline-secondary" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#interactions']) }}" title="{{ __('Sign in to save') }}"><i class="bi bi-bookmark me-1" aria-hidden="true"></i>{{ __('Save') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    @if (! $isBlog && $post->categories->count() > 1)
        <div class="d-flex gap-2 flex-wrap mb-5">
            @foreach ($post->categories as $postCategory)
                @php($postCategoryLocalization = $postCategory->localization($localization->locale))
                @if ($postCategoryLocalization)
                    <a class="btn btn-sm btn-outline-secondary" href="{{ $isBlog ? route('blog.index', ['locale' => $localization->locale, 'category' => $postCategoryLocalization->slug]) : route('categories.show', ['locale' => $localization->locale, 'category' => $postCategoryLocalization->slug]) }}">{{ $postCategoryLocalization->name }}</a>
                @endif
            @endforeach
        </div>
    @endif
</article>

<section class="public-article mt-5" id="comments">
    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
        <h2 class="h3 fw-bold mb-0">{{ __('Comments') }}</h2>
        <span class="badge text-bg-secondary" data-public-comment-count>{{ $post->approved_comments_count }}</span>
    </div>
    @if (session('comment_success'))<div class="alert alert-success" role="status">{{ session('comment_success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @php($commentAction = $isBlog
        ? route('blog.comments.store', ['locale' => $localization->locale, 'slug' => $localization->slug])
        : route('comments.store', ['locale' => $localization->locale, 'category' => $primaryCategoryLocalization->slug, 'slug' => $localization->slug]))
    <div class="card public-article__section border-0 mb-4"><div class="card-body p-4">
        <h3 class="h5 fw-bold mb-3">{{ __('Leave a comment') }}</h3>
        @auth
            <form method="POST" action="{{ $commentAction }}" data-moderated-comment data-preview-url="{{ route('member.comments.preview') }}" data-block-warning="{{ __('Your comment contains abusive or prohibited language. Edit it to continue.') }}" data-check-error="{{ __('Unable to check this comment. Please try again.') }}">
                @csrf
                <div class="d-none" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
                <label for="comment_body" class="form-label">{{ __('Comment as :name', ['name' => auth()->user()->name]) }}</label>
                <textarea class="form-control" id="comment_body" name="body" rows="5" minlength="5" maxlength="2000" required>{{ !old('parent_id') ? old('body') : '' }}</textarea>
                <div class="small mt-2 text-danger" role="status" aria-live="polite" data-comment-warning hidden></div>
                <div class="small mt-2 text-success" role="status" aria-live="polite" data-comment-success hidden></div>
                <div class="d-flex align-items-center justify-content-between gap-3 mt-3">
                    <small class="text-body-secondary">{{ __('Respect other people. Criticism and everyday expressions are welcome.') }}</small>
                    <button class="btn btn-danger flex-shrink-0" type="submit" data-comment-submit><i class="bi bi-send me-1"></i>{{ __('Send comment') }}</button>
                </div>
            </form>
        @else
            <p class="mb-2 text-body-secondary">{{ __('Sign in to comment and reply to other members.') }}</p>
            <a class="btn btn-danger" href="{{ route('member.login', ['locale' => $localization->locale, 'return_to' => $canonicalUrl.'#comments']) }}"><i class="bi bi-chat me-1" aria-hidden="true"></i>{{ __('Sign in to comment') }}</a>
        @endauth
    </div></div>
    <div class="d-grid gap-3" data-comment-list>
        @forelse ($comments as $comment)
            <div data-comment-thread-id="{{ $comment->id }}">
                <x-comment-entry :comment="$comment" :form-action="$commentAction" />
                <div data-comment-replies>
                    @foreach ($comment->replies as $reply)
                        <x-comment-entry :comment="$reply" :form-action="$commentAction" reply />
                    @endforeach
                </div>
            </div>
        @empty
            <div data-comment-empty class="card public-empty-state border-0"><div class="card-body p-4 text-center text-body-secondary">{{ __('No comments yet.') }}</div></div>
        @endforelse
    </div>
    @if ($comments->hasPages())<div class="mt-3">{{ $comments->links('pagination::bootstrap-5') }}</div>@endif
</section>

@if ($relatedPosts->isNotEmpty())
    <section class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 fw-bold mb-0">{{ $isBlog ? __('Related Articles') : __('Related Content') }}</h2>
            <a href="{{ $isBlog ? route('blog.index', ['locale' => $localization->locale]) : route('categories.show', ['locale' => $localization->locale, 'category' => $primaryCategoryLocalization->slug]) }}" class="section-link">{{ __('View All') }} <i class="bi bi-arrow-right"></i></a>
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
