@extends('layouts.public')
@section('title', __('Reset password').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card border-secondary-subtle"><div class="card-body p-4">
<h1 class="h3">{{ __('Reset password') }}</h1>
@if (session('status'))<div class="alert alert-info">{{ session('status') }}</div>@endif
@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('member.password.email') }}">@csrf<label for="email" class="form-label">{{ __('Email') }}</label><input class="form-control mb-3" name="email" id="email" type="email" value="{{ old('email') }}" required><button type="submit" class="btn btn-danger">{{ __('Send reset link') }}</button></form>
</div></div></div></div>
@endsection
