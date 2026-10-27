@extends('layouts.app')

@section('title', config('site.name'))

@section('body')
@php
    $currentLocale = request()->route('locale');
    $supportedLocales = config('localization.locales');
@endphp

<div class="site-shell" id="top">
    <header class="topbar navbar fixed-top" aria-label="{{ __('Primary navigation') }}">
        <div class="container-fluid h-100 px-3">
            <button class="btn icon-button d-xl-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#siteSidebar" aria-controls="siteSidebar" aria-label="{{ __('Open menu') }}">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>

            <a class="brand" href="{{ route('home') }}" aria-label="{{ config('site.name') }}">
                <span class="brand__mark"><i class="bi bi-play-fill" aria-hidden="true"></i></span>
                <span class="brand__name"><span>{{ config('site.brand_primary') }}</span><strong>{{ config('site.brand_accent') }}</strong></span>
            </a>

            <nav class="topbar__links d-none d-xl-flex" aria-label="{{ __('Content categories') }}">
                <a class="topbar-link {{ $activeCategory === null ? 'is-active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-fill" aria-hidden="true"></i>
                    <span>{{ __('Home') }}</span>
                </a>
                @foreach ($categories as $category)
                    <a class="topbar-link {{ $activeCategory === $category['key'] ? 'is-active' : '' }}" href="{{ route('home', ['category' => $category['key']]) }}">
                        <i class="bi {{ $category['icon'] }}" aria-hidden="true"></i>
                        <span>{{ $category['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="topbar__actions ms-auto">
                <form class="site-search d-none d-md-flex" role="search" data-search-form>
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" class="form-control" placeholder="{{ __('Search for movies, series, games...') }}" aria-label="{{ __('Search for movies, series, games...') }}" data-site-search>
                    <button class="btn site-search__clear" type="button" aria-label="{{ __('Clear search') }}" data-search-clear hidden>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </form>

                <button class="btn icon-button d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSearchPanel" aria-controls="mobileSearchPanel" aria-label="{{ __('Search') }}">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>

                <button class="btn icon-button" type="button" data-theme-toggle aria-label="{{ __('Toggle color theme') }}">
                    <i class="bi bi-sun-fill" aria-hidden="true" data-theme-icon></i>
                </button>

                <div class="dropdown">
                    <button class="btn avatar-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Open account menu') }}">
                        <i class="bi bi-person-fill" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end locale-menu">
                        <li><h6 class="dropdown-header">{{ __('Language / Region') }}</h6></li>
                        @foreach ($supportedLocales as $routeLocale => $locale)
                            <li>
                                <a class="dropdown-item {{ $currentLocale === $routeLocale ? 'active' : '' }}" href="{{ route('home', ['locale' => $routeLocale]) }}">
                                    {{ strtoupper($locale['language']) }} · {{ $locale['region'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <aside class="sidebar offcanvas-xl offcanvas-start" tabindex="-1" id="siteSidebar" aria-labelledby="siteSidebarLabel">
        <div class="offcanvas-header d-xl-none">
            <span class="offcanvas-title fw-semibold" id="siteSidebarLabel">{{ __('Navigation') }}</span>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#siteSidebar" aria-label="{{ __('Close') }}"></button>
        </div>

        <div class="offcanvas-body sidebar__body">
            <nav class="sidebar-nav" aria-label="{{ __('Home sections') }}">
                <a class="sidebar-link is-active" href="#top"><i class="bi bi-house-door-fill"></i><span>{{ __('Home') }}</span></a>
                <a class="sidebar-link" href="#trending"><i class="bi bi-fire"></i><span>{{ __('Trending') }}</span></a>
                <a class="sidebar-link" href="#latest"><i class="bi bi-clock"></i><span>{{ __('Latest') }}</span></a>
                <a class="sidebar-link" href="#popular"><i class="bi bi-heart"></i><span>{{ __('Most Liked') }}</span></a>
                <button class="sidebar-link sidebar-link--button" type="button" data-watchlist-filter><i class="bi bi-bookmark"></i><span>{{ __('My Watchlist') }}</span></button>
                <a class="sidebar-link" href="#categories"><i class="bi bi-grid"></i><span>{{ __('Categories') }}</span></a>
            </nav>

            <div class="sidebar-divider"></div>
            <div class="sidebar-label">{{ __('Categories') }}</div>

            <nav class="sidebar-nav sidebar-nav--compact" aria-label="{{ __('Categories') }}">
                @foreach ($categories as $category)
                    <a class="sidebar-link {{ $activeCategory === $category['key'] ? 'is-current' : '' }}" href="{{ route('home', ['category' => $category['key']]) }}">
                        <i class="bi {{ $category['icon'] }}"></i><span>{{ $category['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="sidebar-divider"></div>
            <div class="sidebar-label">{{ __('Community') }}</div>
            <nav class="sidebar-nav sidebar-nav--compact" aria-label="{{ __('Community') }}">
                <button class="sidebar-link sidebar-link--button" type="button"><i class="bi bi-chat"></i><span>{{ __('Comments') }}</span></button>
                <button class="sidebar-link sidebar-link--button" type="button"><i class="bi bi-tags"></i><span>{{ __('Content Suggestions') }}</span></button>
            </nav>

            <section class="support-card" aria-label="{{ __('Support the Site') }}">
                <div class="support-card__title"><span class="support-card__icon"><i class="bi bi-play-btn-fill"></i></span>{{ __('Support the Site') }}</div>
                <p>{{ __('Help keep the project online with a small donation.') }}</p>
                <button class="btn support-card__button" type="button"><i class="bi bi-heart-fill"></i> {{ __('Make a Donation') }}</button>
            </section>
        </div>
    </aside>

    <main class="main-content">
        <div class="content-grid">
            <div class="content-column">
                <section class="featured-section" aria-label="{{ __('Featured') }}">
                    <article class="featured-card">
                        <div class="featured-card__media">
                            <iframe
                                src="{{ $featuredItem['video_embed_url'] }}"
                                title="{{ $featuredItem['title'] }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <div class="featured-card__content">
                            <div class="featured-card__badges">
                                <span class="badge featured-card__badge">{{ $featuredItem['type_label'] }}</span>
                                <span class="badge featured-card__badge featured-card__badge--muted">{{ $featuredItem['subtype_label'] }}</span>
                            </div>
                            <h1>{{ $featuredItem['title'] }}</h1>
                            <p>{{ $featuredItem['excerpt'] }}</p>

                            <div class="featured-card__meta">
                                <button class="media-action media-action--like" type="button" data-like-button aria-pressed="false">
                                    <i class="bi bi-heart-fill"></i><span data-like-count>{{ $featuredItem['likes_count'] }}</span>
                                </button>
                                <button class="media-action" type="button"><i class="bi bi-chat"></i><span>{{ $featuredItem['comments_count'] }}</span></button>
                                <button class="media-action ms-auto" type="button" data-watch-button aria-pressed="false"><i class="bi bi-bookmark"></i><span>{{ __('Watch Later') }}</span></button>
                            </div>

                            <button class="btn btn-accent w-100" type="button">{{ __('Read the Review') }} <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </article>
                </section>

                <section class="content-section" id="latest">
                    <div class="section-heading section-heading--filters">
                        <h2>{{ __('Latest Videos') }}</h2>
                        <div class="section-controls">
                            <div class="filter-tabs" role="group" aria-label="{{ __('Filter latest content') }}">
                                <button class="btn filter-tab active" type="button" data-content-filter="all">{{ __('All') }}</button>
                                <button class="btn filter-tab" type="button" data-content-filter="trailers">{{ __('Trailers') }}</button>
                                <button class="btn filter-tab" type="button" data-content-filter="reviews">{{ __('Reviews') }}</button>
                                <button class="btn filter-tab" type="button" data-content-filter="curiosities">{{ __('Curiosities') }}</button>
                                <button class="btn filter-tab" type="button" data-content-filter="real-stories">{{ __('Real Stories') }}</button>
                            </div>
                            <a class="section-link" href="#popular">{{ __('View All') }} <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <div class="latest-grid" data-card-grid>
                        @foreach ($latestItems as $item)
                            <x-media-card :item="$item" />
                        @endforeach
                    </div>
                </section>

                <section class="content-section" id="popular">
                    <div class="section-heading">
                        <h2>{{ __('Popular This Week') }}</h2>
                        <a class="section-link" href="#categories">{{ __('View All') }} <i class="bi bi-arrow-right"></i></a>
                    </div>

                    <div class="popular-grid" data-card-grid>
                        @foreach ($popularItems as $item)
                            <x-media-card :item="$item" compact />
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="right-rail" aria-label="{{ __('Secondary content') }}">
                <section class="rail-panel" id="trending">
                    <div class="rail-panel__heading">
                        <h2><i class="bi bi-fire"></i>{{ __('Trending Now') }}</h2>
                        <a href="#popular">{{ __('View All') }}</a>
                    </div>

                    <ol class="trending-list">
                        @foreach ($trendingItems as $item)
                            <li class="trending-item">
                                <span class="trending-item__rank">{{ $loop->iteration }}</span>
                                <button class="trending-item__thumb btn" type="button" data-bs-toggle="modal" data-bs-target="#videoModal" data-video-url="{{ $featuredItem['video_embed_url'] }}" data-video-title="{{ $item['title'] }}">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}">
                                    <span><i class="bi bi-play-fill"></i></span>
                                </button>
                                <div class="trending-item__copy">
                                    <strong>{{ $item['title'] }}</strong>
                                    <small>{{ $item['subtitle'] }}</small>
                                </div>
                                <span class="trending-item__views"><i class="bi bi-fire"></i>{{ $item['views'] }}</span>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="promo-card">
                    <div class="promo-card__copy">
                        <h3>{{ __('Create Your Own SaaS') }}</h3>
                        <p>{{ __('Turn your ideas into real projects with simple and powerful tools.') }}</p>
                        <button class="btn promo-card__button" type="button">{{ __('Get Started') }} <i class="bi bi-arrow-right"></i></button>
                    </div>
                    <div class="promo-card__visual" aria-hidden="true">
                        <div class="promo-window"><span></span><span></span><span></span><div></div><div></div><div></div></div>
                    </div>
                </section>

                <section class="ad-card" aria-label="{{ __('Advertisement') }}">
                    <div class="ad-card__top"><span>{{ __('Advertisement') }}</span><button class="btn" type="button">{{ __('Report') }}</button></div>
                    <div class="ad-card__body">
                        <span class="ad-card__logo"><i class="bi bi-cloud-fill"></i> Cloud Studio</span>
                        <h3>{{ __('Build what’s next') }}</h3>
                        <p>{{ __('Secure, scalable tools for your next project.') }}</p>
                        <button class="btn ad-card__button" type="button">{{ __('Learn more') }}</button>
                        <div class="ad-card__art" aria-hidden="true"><span></span><span></span><span></span></div>
                    </div>
                </section>
            </aside>
        </div>

        <section class="categories-section" id="categories">
            <div class="section-heading"><h2>{{ __('Explore by Category') }}</h2></div>
            <div class="category-grid">
                @foreach ($categories as $category)
                    <a class="category-card category-card--{{ $category['tone'] }}" href="{{ route('home', ['category' => $category['key']]) }}">
                        <span class="category-card__icon"><i class="bi {{ $category['icon'] }}"></i></span>
                        <span class="category-card__copy"><strong>{{ $category['label'] }}</strong><small>{{ $category['description'] }}</small></span>
                        <i class="bi bi-arrow-right category-card__arrow"></i>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
</div>

<div class="offcanvas offcanvas-top mobile-search-panel" tabindex="-1" id="mobileSearchPanel" aria-labelledby="mobileSearchPanelLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="mobileSearchPanelLabel">{{ __('Search') }}</h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
    </div>
    <div class="offcanvas-body">
        <form class="site-search site-search--mobile d-flex" role="search" data-search-form>
            <i class="bi bi-search" aria-hidden="true"></i>
            <input type="search" class="form-control" placeholder="{{ __('Search for movies, series, games...') }}" aria-label="{{ __('Search for movies, series, games...') }}" data-site-search>
            <button class="btn site-search__clear" type="button" aria-label="{{ __('Clear search') }}" data-search-clear hidden>
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </form>
    </div>
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content video-modal">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="videoModalLabel">{{ __('Video') }}</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="about:blank" title="{{ __('Video') }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen data-video-frame></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
