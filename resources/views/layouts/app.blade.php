<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (config('site.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">
    @endif

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', config('site.description'))">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @php
        $currentRoute = request()->route();
        $routeName = $currentRoute?->getName();
        $routeParameters = $currentRoute?->parameters() ?? [];
        $supportsRegionalAlternates = $routeName && array_key_exists('locale', $routeParameters);
    @endphp

    @if ($disableRegionalAlternates ?? false)
        {{-- Search and other noindex pages do not need regional alternates. --}}
    @elseif (! empty($alternateUrls ?? []))
        @foreach (config('localization.locales') as $routeLocale => $localeConfig)
            @if (! empty($alternateUrls[$routeLocale] ?? null))
                <link
                    rel="alternate"
                    hreflang="{{ strtolower($localeConfig['language'].'-'.$localeConfig['region']) }}"
                    href="{{ $alternateUrls[$routeLocale] }}"
                >
            @endif
        @endforeach
        @if (! empty($alternateUrls[config('localization.default')] ?? null))
            <link rel="alternate" hreflang="x-default" href="{{ $alternateUrls[config('localization.default')] }}">
        @endif
    @elseif ($supportsRegionalAlternates)
        @foreach (config('localization.locales') as $routeLocale => $localeConfig)
            <link
                rel="alternate"
                hreflang="{{ strtolower($localeConfig['language'].'-'.$localeConfig['region']) }}"
                href="{{ route($routeName, array_merge($routeParameters, ['locale' => $routeLocale])) }}"
            >
        @endforeach
        <link
            rel="alternate"
            hreflang="x-default"
            href="{{ route($routeName, array_merge($routeParameters, ['locale' => config('localization.default')])) }}"
        >
    @endif

    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', config('site.name'))))">
    <meta property="og:description" content="@yield('og_description', config('site.description'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">

    @stack('seo')

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
</body>
</html>
