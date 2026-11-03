@extends('layouts.public')
@section('title', __('Verify your email').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<div class="row justify-content-center"><div class="col-md-7"><div class="card border-secondary-subtle"><div class="card-body p-4">
<h1 class="h3">{{ __('Verify your email') }}</h1>
<p>{{ __('We sent a verification link to your email. Open it before commenting, liking or saving content.') }}</p>
@if (session('status'))<div class="alert alert-info">{{ session('status') }}</div>@endif
<form method="post" action="{{ route('verification.send') }}" class="d-inline">@csrf<button class="btn btn-danger" type="submit">{{ __('Resend email') }}</button></form>
<form method="post" action="{{ route('member.logout') }}" class="d-inline ms-2">@csrf<button class="btn btn-outline-secondary" type="submit">{{ __('Logout') }}</button></form>
</div></div></div></div>
@endsection
