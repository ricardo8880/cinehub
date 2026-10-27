@props(['item', 'compact' => false])

@php
    $title = data_get($item, 'title');
    $imageUrl = data_get($item, 'image_url');
    $videoUrl = data_get($item, 'video_embed_url');
    $type = data_get($item, 'type');
    $typeLabel = data_get($item, 'type_label');
    $duration = data_get($item, 'duration');
    $excerpt = data_get($item, 'excerpt');
    $likes = data_get($item, 'likes_count');
    $comments = data_get($item, 'comments_count');
    $contentKind = data_get($item, 'content_kind', 'trailers');
    $searchable = strtolower(trim(implode(' ', array_filter([$title, $typeLabel, $excerpt]))));
    $cardId = 'card-'.substr(md5((string) $title), 0, 10);
@endphp

<article
    {{ $attributes->class(['media-card', 'media-card--compact' => $compact]) }}
    data-content-card
    data-content-type="{{ $contentKind }}"
    data-category="{{ $type }}"
    data-searchable="{{ $searchable }}"
    data-card-id="{{ $cardId }}"
>
    <div class="media-card__visual">
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="media-card__image" loading="lazy">

        <span class="badge media-card__badge">{{ $typeLabel }}</span>

        <button
            class="media-play btn"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#videoModal"
            data-video-url="{{ $videoUrl }}"
            data-video-title="{{ $title }}"
            aria-label="{{ __('Play :title', ['title' => $title]) }}"
        >
            <i class="bi bi-play-fill" aria-hidden="true"></i>
        </button>

        <span class="media-card__duration">{{ $duration }}</span>
    </div>

    <div class="media-card__body">
        <h3 class="media-card__title">{{ $title }}</h3>

        @if ($excerpt)
            <p class="media-card__excerpt">{{ $excerpt }}</p>
        @endif

        <div class="media-card__meta mt-auto">
            <button class="media-action media-action--like" type="button" data-like-button aria-pressed="false" aria-label="{{ __('Like :title', ['title' => $title]) }}">
                <i class="bi bi-heart-fill" aria-hidden="true"></i>
                <span data-like-count>{{ $likes }}</span>
            </button>

            <button class="media-action" type="button" aria-label="{{ __('Comments for :title', ['title' => $title]) }}">
                <i class="bi bi-chat" aria-hidden="true"></i>
                <span>{{ $comments }}</span>
            </button>

            <button class="media-action media-action--save ms-auto" type="button" data-watch-button aria-pressed="false" aria-label="{{ __('Save :title to watchlist', ['title' => $title]) }}">
                <i class="bi bi-bookmark" aria-hidden="true"></i>
            </button>

            <div class="dropdown">
                <button class="media-action" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('More options') }}">
                    <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end card-menu">
                    <li><button class="dropdown-item" type="button" data-copy-title="{{ $title }}">{{ __('Copy title') }}</button></li>
                    <li><button class="dropdown-item" type="button" data-watch-button aria-pressed="false">{{ __('Add to Watchlist') }}</button></li>
                </ul>
            </div>
        </div>
    </div>
</article>
