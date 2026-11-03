@extends('layouts.public')
@section('title', __('Choose a new password').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card border-secondary-subtle"><div class="card-body p-4">
<h1 class="h3">{{ __('Choose a new password') }}</h1>
@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('member.password.update') }}">@csrf<input type="hidden" name="token" value="{{ $token }}">
<label class="form-label" for="email">{{ __('Email') }}</label><input class="form-control mb-3" name="email" type="email" id="email" value="{{ $email }}" required>
<label class="form-label" for="password">{{ __('New password') }}</label><input class="form-control mb-3" name="password" type="password" id="password" required minlength="10">
<label class="form-label" for="password_confirmation">{{ __('Confirm password') }}</label><input class="form-control mb-3" name="password_confirmation" type="password" id="password_confirmation" required minlength="10">
<button class="btn btn-danger" type="submit">{{ __('Update password') }}</button></form>
</div></div></div></div>
@endsection
