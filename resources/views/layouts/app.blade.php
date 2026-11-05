<!doctype html>
<html lang="{{ \App\Support\Seo::language((string) (request()->route('locale') ?? config('localization.default'))) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (config('site.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">
    @endif

    @php
        $routeName = request()->route()?->getName();
        $routeLocale = (string) (request()->route('locale') ?? config('localization.default'));
        $robots = trim($__env->yieldContent('robots', 'index, follow'));
        if (! \App\Support\Seo::indexable($routeLocale)) {
            $robots = 'noindex, follow';
        }

        $canonicalUrl = trim($__env->yieldContent('canonical', url()->current()));
        $seoTitle = trim($__env->yieldContent('og_title', $__env->yieldContent('title', config('site.name'))));
        $defaultDescription = __('Discover trailers, reviews, movies, TV shows, games and entertainment on CineHub.');
        $seoDescription = trim($__env->yieldContent('og_description', $__env->yieldContent('meta_description', $defaultDescription)));
        // Home has a public version for each published language. All other
        // pages must provide their actual equivalent URLs explicitly.
        $seoAlternates = $alternateUrls ?? [];
        if ($routeName === 'home' && ! isset($alternateUrls)) {
            $seoAlternates = [];
            foreach (\App\Support\Seo::indexableLocales() as $locale) {
                $seoAlternates[$locale] = route('home', ['locale' => $locale]);
            }
        }
        $seoAlternates = \App\Support\Seo::alternateLinks($seoAlternates);
    @endphp

    <title>{{ $__env->yieldContent('title', config('site.name')) }}</title>
    <meta name="description" content="{{ $__env->yieldContent('meta_description', $defaultDescription) }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    @if (! str_contains($robots, 'noindex') && ! ($disableRegionalAlternates ?? false) && count($seoAlternates) > 1)
        @foreach ($seoAlternates as $language => $alternateUrl)
            <link rel="alternate" hreflang="{{ $language }}" href="{{ $alternateUrl }}">
        @endforeach
        @if (isset($seoAlternates['en']))
            <link rel="alternate" hreflang="x-default" href="{{ $seoAlternates['en'] }}">
        @endif
    @endif

    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $__env->yieldContent('og_url', $canonicalUrl) }}">
    <meta property="og:type" content="{{ $__env->yieldContent('og_type', 'website') }}">
    <meta name="twitter:card" content="{{ $__env->yieldContent('twitter_card', 'summary') }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">

    @stack('seo')

    <script>
        window.cinehubMessages = {{ \Illuminate\Support\Js::from([
            'noResultsFor' => __('No results for'),
            'content' => __('Content'),
            'seeAllResultsFor' => __('See all results for'),
            'loadingRecent' => __('Loading recent content…'),
            'searching' => __('Searching…'),
            'actionError' => __('Could not complete this action. Please try again.'),
            'serverError' => __('Unexpected server response. Please try again.'),
            'interestsError' => __('Unable to save your interests.'),
            'showSpoilers' => __('Show spoilers'),
            'hideSpoilers' => __('Hide spoilers'),
        ]) }};
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (config('site.google_analytics_id') && request()->cookie('cinehub_analytics_consent') === 'granted')
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json(config('site.google_analytics_id')), { anonymize_ip: true });
        </script>
    @endif
</head>
<body>
    @yield('body')
    @stack('scripts')
</body>
</html>
