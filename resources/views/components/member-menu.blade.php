@auth
    <li><a class="dropdown-item" href="{{ route('member.for-you') }}"><i class="bi bi-stars me-2"></i>{{ __('For You') }}</a></li>
    <li><a class="dropdown-item" href="{{ route('member.favorites') }}"><i class="bi bi-bookmark me-2"></i>{{ __('Favorites') }}</a></li>
    <li><a class="dropdown-item" href="{{ route('member.interests') }}"><i class="bi bi-sliders me-2"></i>{{ __('My interests') }}</a></li>
    @if (auth()->user()->is_admin)
        <li><a class="dropdown-item" href="{{ route('admin.login') }}">{{ __('Admin') }}</a></li>
    @endif
    <li><hr class="dropdown-divider"></li>
    <li><form method="post" action="{{ route('member.logout') }}">@csrf<button type="submit" class="dropdown-item">{{ __('Logout') }}</button></form></li>
@else
    <li><a class="dropdown-item" href="{{ route('member.login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Sign in') }}</a></li>
    <li><a class="dropdown-item" href="{{ route('member.register') }}"><i class="bi bi-person-plus me-2"></i>{{ __('Create an account') }}</a></li>
@endauth
