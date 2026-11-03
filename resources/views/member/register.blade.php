@extends('layouts.public')
@section('title', __('Create an account').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card border-secondary-subtle"><div class="card-body p-4">
<h1 class="h3 mb-3">{{ __('Create an account') }}</h1>
@if ($errors->any()) <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
<form method="post" action="{{ route('member.register.store') }}">@csrf
<label for="name" class="form-label">{{ __('Name') }}</label><input id="name" name="name" class="form-control mb-3" value="{{ old('name') }}" required maxlength="80" autocomplete="name">
<label for="email" class="form-label">{{ __('Email') }}</label><input id="email" name="email" type="email" class="form-control mb-3" value="{{ old('email') }}" required autocomplete="email">
<label for="password" class="form-label">{{ __('Password (at least 10 characters)') }}</label><input id="password" name="password" type="password" class="form-control mb-3" required minlength="10" autocomplete="new-password">
<label for="password_confirmation" class="form-label">{{ __('Confirm password') }}</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control mb-3" required minlength="10" autocomplete="new-password">
<button class="btn btn-danger w-100" type="submit">{{ __('Create an account') }}</button></form>
@if (config('cinehub_auth.google_client_id') && config('cinehub_auth.google_client_secret'))
<a class="btn btn-outline-light w-100 mt-3" href="{{ route('member.google') }}"><i class="bi bi-google me-2"></i>{{ __('Continue with Google') }}</a>
@endif
<p class="small mt-3 mb-0"><a href="{{ route('member.login') }}">{{ __('Already have an account? Sign in') }}</a></p>
</div></div></div></div>
@endsection
