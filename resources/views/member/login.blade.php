@extends('layouts.public')
@section('title', __('Sign in').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5">
    <div class="card border-secondary-subtle"><div class="card-body p-4">
        <h1 class="h3 mb-3">{{ __('Sign in') }}</h1>
        @if (session('status')) <div class="alert alert-info">{{ session('status') }}</div> @endif
        @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
        <form method="post" action="{{ route('member.login.store') }}">@csrf
            <label for="email" class="form-label">{{ __('Email') }}</label><input id="email" name="email" type="email" autocomplete="email" class="form-control mb-3" value="{{ old('email') }}" required>
            <label for="password" class="form-label">{{ __('Password') }}</label><input id="password" name="password" type="password" autocomplete="current-password" class="form-control mb-3" required>
            <label class="form-check mb-3"><input name="remember" value="1" type="checkbox" class="form-check-input"><span class="form-check-label">{{ __('Remember me') }}</span></label>
            <button class="btn btn-danger w-100" type="submit">{{ __('Sign in') }}</button>
        </form>
        @if (config('cinehub_auth.google_client_id') && config('cinehub_auth.google_client_secret'))
            <a class="btn btn-outline-light w-100 mt-3" href="{{ route('member.google') }}"><i class="bi bi-google me-2"></i>{{ __('Continue with Google') }}</a>
        @endif
        <p class="small mt-3 mb-0"><a href="{{ route('member.register') }}">{{ __('Create an account') }}</a> · <a href="{{ route('member.password.forgot') }}">{{ __('Forgot password?') }}</a></p>
    </div></div>
</div></div>
@endsection
