@php
    $context = $context ?? 'default';
    $campaigns = collect(config('sidebar_ads.campaigns', []))
        ->filter(fn ($ad) => ! empty($ad['title']) && ! empty($ad['url']) && in_array($context, $ad['contexts'] ?? ['default'], true))
        ->values();
    $client = config('sidebar_ads.adsense_client');
    $slot = config('sidebar_ads.adsense_slot');
    $hasAdsense = filled($client) && filled($slot);
    $seconds = max(8, (int) config('sidebar_ads.rotation_seconds', 12));
@endphp
<div class="sidebar-ad-stack" aria-label="{{ __('Advertisements') }}">
    <section class="sidebar-ad-unit" aria-label="Google AdSense">
        <div class="sidebar-ad-unit__label">{{ __('Advertisement') }} · Google</div>
        @if ($hasAdsense)
            <ins class="adsbygoogle" style="display:block" data-ad-client="{{ $client }}" data-ad-slot="{{ $slot }}" data-ad-format="auto" data-full-width-responsive="true"></ins>
            @once
                @push('scripts')
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $client }}" crossorigin="anonymous"></script>
                @endpush
            @endonce
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        @else
            <div class="sidebar-ad-placeholder">{{ __('Google AdSense space') }}</div>
        @endif
    </section>

    @for ($column = 0; $column < 2; $column++)
        <section class="sidebar-ad-unit" data-sidebar-rotation="{{ $column }}" data-interval="{{ $seconds * 1000 }}" aria-label="{{ __('Promotions') }}">
            <div class="sidebar-ad-unit__label">{{ __('Advertisement') }} · {{ __('Partner / Our projects') }}</div>
            <div class="sidebar-ad-viewport" data-sidebar-ad-viewport>
            @if ($campaigns->isEmpty())
                @foreach ([
                    ['title' => 'Affiliate products', 'description' => 'Products related to this page'],
                    ['title' => 'Our projects', 'description' => 'Discover our other websites'],
                    ['title' => 'Recommended products', 'description' => 'Useful picks for visitors'],
                    ['title' => 'Our books', 'description' => 'Explore upcoming releases'],
                ] as $index => $demo)
                    <div class="sidebar-ad-campaign sidebar-ad-placeholder" @if ($index !== $column) hidden @endif>
                        <strong>{{ $demo['title'] }}</strong>
                        <span>{{ $demo['description'] }}</span>
                        <small>Demo · {{ $index + 1 }}/4</small>
                    </div>
                @endforeach
            @else
                @foreach ($campaigns as $index => $ad)
                    <a class="sidebar-ad-campaign" href="{{ $ad['url'] }}" target="_blank" rel="{{ ($ad['kind'] ?? '') === 'affiliate' ? 'sponsored nofollow noopener' : 'noopener' }}" @if ($index !== $column % $campaigns->count()) hidden @endif>
                        @if (! empty($ad['image']))
                            <img src="{{ $ad['image'] }}" alt="" loading="lazy">
                        @endif
                        <strong>{{ $ad['title'] }}</strong>
                        @if (! empty($ad['description']))<span>{{ $ad['description'] }}</span>@endif
                    </a>
                @endforeach
            @endif
            </div>
        </section>
    @endfor
    @if ($campaigns->contains(fn ($ad) => ($ad['kind'] ?? '') === 'affiliate'))
        <small class="sidebar-ad-disclosure">{{ __('As an Amazon Associate, we earn from qualifying purchases.') }}</small>
    @endif
</div>
@once
    <style>
        /* Each promotional slot clips the cards while they scroll upward. */
        .sidebar-ad-viewport {
            display: grid;
            position: relative;
            overflow: hidden;
            border-radius: 7px;
            min-height: 130px;
        }
        .sidebar-ad-viewport > .sidebar-ad-campaign {
            grid-area: 1 / 1;
            min-width: 0;
        }
    </style>
    @push('scripts')
        <script>
            document.querySelectorAll('[data-sidebar-rotation]').forEach((panel) => {
                const viewport = panel.querySelector('[data-sidebar-ad-viewport]');
                if (!viewport) return;

                const items = [...viewport.querySelectorAll('.sidebar-ad-campaign')];
                if (items.length < 2) return;

                const interval = Math.max(8000, Number(panel.dataset.interval) || 12000);
                const duration = Math.min(5400, Math.round(interval * 0.45));
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
                let index = Number(panel.dataset.sidebarRotation) % items.length;
                let animating = false;

                items.forEach((item, position) => {
                    item.hidden = position !== index;
                    if (item.hidden) item.setAttribute('aria-hidden', 'true');
                    else item.removeAttribute('aria-hidden');
                });

                // Keep the slot height steady even when campaign content varies.
                const measureHeight = () => {
                    if (animating) return;
                    let height = 130;
                    items.forEach((item) => {
                        const wasHidden = item.hidden;
                        item.style.visibility = 'hidden';
                        item.hidden = false;
                        height = Math.max(height, item.offsetHeight);
                        item.hidden = wasHidden;
                        item.style.visibility = '';
                    });
                    viewport.style.height = `${height}px`;
                };
                measureHeight();
                viewport.querySelectorAll('img').forEach((img) => {
                    if (!img.complete) img.addEventListener('load', measureHeight, { once: true });
                });
                window.addEventListener('resize', measureHeight, { passive: true });

                const rotate = () => {
                    if (animating || document.hidden || panel.matches(':hover') || panel.contains(document.activeElement)) return;

                    const outgoing = items[index];
                    const nextIndex = (index + 1) % items.length;
                    const incoming = items[nextIndex];

                    if (reducedMotion.matches || !outgoing.animate) {
                        outgoing.hidden = true;
                        outgoing.setAttribute('aria-hidden', 'true');
                        incoming.hidden = false;
                        incoming.removeAttribute('aria-hidden');
                        index = nextIndex;
                        return;
                    }

                    animating = true;
                    incoming.hidden = false;
                    incoming.removeAttribute('aria-hidden');
                    const distance = viewport.clientHeight;
                    const options = { duration, easing: 'cubic-bezier(.45, 0, .55, 1)', fill: 'both' };
                    const outgoingAnimation = outgoing.animate([
                        { transform: 'translateY(0)', opacity: 1 },
                        { transform: `translateY(-${distance}px)`, opacity: 0 },
                    ], options);
                    const incomingAnimation = incoming.animate([
                        { transform: `translateY(${distance}px)`, opacity: 0 },
                        { transform: 'translateY(0)', opacity: 1 },
                    ], options);

                    Promise.allSettled([outgoingAnimation.finished, incomingAnimation.finished]).then(() => {
                        outgoing.hidden = true;
                        outgoing.setAttribute('aria-hidden', 'true');
                        outgoingAnimation.cancel();
                        incomingAnimation.cancel();
                        index = nextIndex;
                        animating = false;
                    });
                };

                window.setInterval(rotate, interval);
            });
        </script>
    @endpush
@endonce
