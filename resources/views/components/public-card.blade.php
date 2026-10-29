@props(['item'])

<article class="card h-100 public-content-card border-0">
    <a href="{{ $item['url'] }}" class="ratio ratio-16x9 overflow-hidden rounded-top bg-dark">
        <img src="{{ $item['image_url'] }}" alt="{{ $item['image_alt'] ?? $item['title'] }}" class="public-content-card__image" loading="lazy">
    </a>
    <div class="card-body d-flex flex-column">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge text-bg-danger">{{ $item['type_label'] }}</span>
            @if (($item['locale'] ?? request()->route('locale')) !== request()->route('locale'))
                <span class="badge text-bg-secondary">{{ strtoupper($item['locale']) }}</span>
            @endif
        </div>
        <h2 class="h5 card-title mb-2"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></h2>
        @if ($item['excerpt'])
            <p class="card-text text-body-secondary small">{{ $item['excerpt'] }}</p>
        @endif
        <div class="d-flex gap-3 mt-auto small text-body-secondary">
            <span><i class="bi bi-heart-fill text-danger"></i> {{ $item['likes_count'] }}</span>
            <span><i class="bi bi-chat"></i> {{ $item['comments_count'] }}</span>
            <span><i class="bi bi-eye"></i> {{ $item['views_count'] }}</span>
        </div>
    </div>
</article>
