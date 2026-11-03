<nav class="d-flex flex-wrap gap-2 mb-4" aria-label="{{ __('Your account') }}">
    <a href="{{ route('member.for-you') }}" class="btn btn-sm {{ request()->routeIs('member.for-you') ? 'btn-danger' : 'btn-outline-secondary' }}"><i class="bi bi-stars me-1"></i>{{ __('For You') }}</a>
    <a href="{{ route('member.favorites') }}" class="btn btn-sm {{ request()->routeIs('member.favorites') ? 'btn-danger' : 'btn-outline-secondary' }}"><i class="bi bi-bookmark-heart me-1"></i>{{ __('Favorites') }}</a>
    <a href="{{ route('member.interests') }}" class="btn btn-sm {{ request()->routeIs('member.interests*') ? 'btn-danger' : 'btn-outline-secondary' }}"><i class="bi bi-sliders me-1"></i>{{ __('My interests') }}</a>
</nav>
