<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') · {{ config('site.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand-xl border-bottom border-secondary-subtle bg-dark sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}"><i class="bi bi-play-btn-fill text-danger me-2"></i>{{ config('site.name') }} Admin</a>
        @auth
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}"><i class="bi bi-file-earmark-text me-1"></i>{{ __('Posts') }}</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-grid me-1"></i>{{ __('Categories') }}</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.comments*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-left-text me-1"></i>{{ __('Comments') }}</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.suggestions*','admin.contacts*','admin.metrics','admin.logs','admin.settings*','admin.ad-slots*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">{{ __('More') }}</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('admin.suggestions.index') }}"><i class="bi bi-lightbulb me-2"></i>{{ __('Suggestions') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.contacts.index') }}"><i class="bi bi-envelope me-2"></i>{{ __('Contact') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.metrics') }}"><i class="bi bi-graph-up me-2"></i>{{ __('Metrics') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.ad-slots.index') }}"><i class="bi bi-badge-ad me-2"></i>{{ __('Ad slots') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}"><i class="bi bi-gear me-2"></i>{{ __('Settings') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.logs') }}"><i class="bi bi-journal-text me-2"></i>{{ __('Activity logs') }}</a></li>
                    </ul>
                </li>
            </ul>
            <a class="btn btn-outline-secondary btn-sm me-2" href="{{ route('home', ['locale' => config('localization.default')]) }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right me-1"></i>{{ __('Open site') }}</a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-outline-light btn-sm" type="submit"><i class="bi bi-box-arrow-right me-1"></i>{{ __('Logout') }}</button></form>
        </div>
        @endauth
    </div>
</nav>
<main class="container py-4 py-lg-5">
    @if (session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
</body>
</html>
