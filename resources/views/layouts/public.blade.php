@extends('layouts.app')

@section('body')
@php
    $currentLocale = request()->route('locale');
    $supportedLocales = config('localization.locales');
    $navigationCategories = $navigationCategories ?? collect();
    $localeLinks = $localeLinks ?? collect($supportedLocales)->mapWithKeys(fn ($config, $routeLocale) => [$routeLocale => route('home', ['locale' => $routeLocale])])->all();
    $searchValue = $query ?? request()->query('q', '');
@endphp

<div class="public-shell">
    <header class="topbar navbar fixed-top" aria-label="{{ __('Primary navigation') }}">
        <div class="container-fluid h-100 px-3">
            <a class="brand" href="{{ route('home', ['locale' => $currentLocale]) }}" aria-label="{{ config('site.name') }}">
                <span class="brand__mark"><i class="bi bi-play-fill" aria-hidden="true"></i></span>
                <span class="brand__name"><span>{{ config('site.brand_primary') }}</span><strong>{{ config('site.brand_accent') }}</strong></span>
            </a>

            <nav class="topbar__links d-none d-xl-flex" aria-label="{{ __('Content categories') }}">
                <a class="topbar-link" href="{{ route('home', ['locale' => $currentLocale]) }}">
                    <i class="bi bi-house-fill" aria-hidden="true"></i>
                    <span>{{ __('Home') }}</span>
                </a>
                <a class="topbar-link {{ request()->routeIs('blog.*') ? 'is-active' : '' }}" href="{{ route('blog.index', ['locale' => $currentLocale]) }}">
                    <i class="bi bi-journal-richtext" aria-hidden="true"></i>
                    <span>{{ __('Blog') }}</span>
                </a>
                @foreach ($navigationCategories->take(6) as $navCategory)
                    @php($navLocalization = $navCategory->localization($currentLocale))
                    @if ($navLocalization)
                        <a class="topbar-link {{ isset($category) && $category->id === $navCategory->id ? 'is-active' : '' }}" href="{{ route('categories.show', ['locale' => $currentLocale, 'category' => $navLocalization->slug]) }}">
                            <i class="bi {{ $navCategory->icon ?: 'bi-grid' }}" aria-hidden="true"></i>
                            <span>{{ $navLocalization->name }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="topbar__actions ms-auto">
                <form class="site-search d-none d-md-flex" role="search" method="get" action="{{ route('search', ['locale' => $currentLocale]) }}" data-search-form data-search-mode="global" data-search-suggestions-url="{{ route('search.suggestions', ['locale' => $currentLocale]) }}">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input name="q" value="{{ $searchValue }}" type="search" class="form-control" placeholder="{{ __('Search for movies, series, games...') }}" aria-label="{{ __('Search for movies, series, games...') }}" autocomplete="off" data-site-search>
                    <button class="btn site-search__clear" type="button" aria-label="{{ __('Clear search') }}" data-search-clear @if ($searchValue === '') hidden @endif>
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                    <div class="site-search-results" data-search-results hidden></div>
                </form>

                <button class="btn icon-button d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSearchPanel" aria-controls="mobileSearchPanel" aria-label="{{ __('Search') }}">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>

                <button class="btn icon-button" type="button" data-theme-toggle aria-label="{{ __('Toggle color theme') }}">
                    <i class="bi bi-sun-fill" aria-hidden="true" data-theme-icon></i>
                </button>

                <div class="dropdown">
                    <button class="btn avatar-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('Open region menu') }}">
                        <i class="bi bi-globe2" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end locale-menu">
                        <li><h6 class="dropdown-header">{{ __('Language / Region') }}</h6></li>
                        @foreach ($supportedLocales as $routeLocale => $localeConfig)
                            <li>
                                @if (! empty($localeLinks[$routeLocale]))
                                    <a class="dropdown-item {{ $currentLocale === $routeLocale ? 'active' : '' }}" href="{{ $localeLinks[$routeLocale] }}">
                                        {{ strtoupper($localeConfig['language']) }} · {{ $localeConfig['region'] }}
                                    </a>
                                @else
                                    <span class="dropdown-item disabled" aria-disabled="true">
                                        {{ strtoupper($localeConfig['language']) }} · {{ $localeConfig['region'] }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="public-main">
        <div class="container-xxl py-4">
            @yield('public-content')
        </div>

        <footer class="border-top border-secondary-subtle mt-4">
            <div class="container-xxl py-4 d-flex flex-wrap gap-3 small text-body-secondary">
                <a href="{{ route('contact', ['locale' => $currentLocale]) }}">{{ __('Contact') }}</a>
                <a href="{{ route('suggestions.create', ['locale' => $currentLocale]) }}">{{ __('Suggest a topic') }}</a>
                <a href="{{ route('blog.index', ['locale' => $currentLocale]) }}">{{ __('Blog') }}</a>
                <a href="{{ route('book', ['locale' => $currentLocale]) }}">{{ __('Book') }}</a>
                <a href="{{ route('legal.show', ['locale' => $currentLocale, 'page' => 'privacy']) }}">{{ __('Privacy') }}</a>
                <a href="{{ route('legal.show', ['locale' => $currentLocale, 'page' => 'terms']) }}">{{ __('Terms') }}</a>
                <a href="{{ route('legal.show', ['locale' => $currentLocale, 'page' => 'cookies']) }}">{{ __('Cookies') }}</a>
                @if (! empty($publicSiteLinks['saas_url'] ?? null))
                    <a href="{{ $publicSiteLinks['saas_url'] }}" target="_blank" rel="noopener">{{ __('Our SaaS') }}</a>
                @endif
                @if (! empty($publicSiteLinks['donation_url'] ?? null))
                    <a href="{{ $publicSiteLinks['donation_url'] }}" target="_blank" rel="nofollow noopener">{{ __('Support CineHub') }}</a>
                @endif
            </div>
        </footer>
    </main>
</div>

<div class="offcanvas offcanvas-top mobile-search-panel" tabindex="-1" id="mobileSearchPanel" aria-labelledby="mobileSearchPanelLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="mobileSearchPanelLabel">{{ __('Search') }}</h2>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
    </div>
    <div class="offcanvas-body">
        <form class="site-search site-search--mobile d-flex" role="search" method="get" action="{{ route('search', ['locale' => $currentLocale]) }}" data-search-form data-search-mode="global" data-search-suggestions-url="{{ route('search.suggestions', ['locale' => $currentLocale]) }}">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input name="q" value="{{ $searchValue }}" type="search" class="form-control" placeholder="{{ __('Search for movies, series, games...') }}" aria-label="{{ __('Search for movies, series, games...') }}" autocomplete="off" data-site-search>
            <button class="btn site-search__clear" type="button" aria-label="{{ __('Clear search') }}" data-search-clear @if ($searchValue === '') hidden @endif>
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
            <div class="site-search-results" data-search-results hidden></div>
        </form>
    </div>
</div>
@endsection
