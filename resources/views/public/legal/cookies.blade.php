@extends('layouts.public')
@section('title', __('Cookie Preferences').' · '.config('site.name'))
@section('robots', 'noindex, follow')
@section('public-content')
<div class="col-12 col-lg-8 mx-auto">
    <h1 class="h2 mb-4">{{ __('Cookie Preferences') }}</h1>
    <p>{{ __('Essential cookies are used for security and application sessions. Analytics remains optional.') }}</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('privacy.consent', ['locale' => request()->route('locale')]) }}" class="d-flex flex-wrap gap-2">
        @csrf
        <button class="btn btn-danger" name="analytics" value="granted" type="submit">{{ __('Allow analytics') }}</button>
        <button class="btn btn-outline-secondary" name="analytics" value="denied" type="submit">{{ __('Reject analytics') }}</button>
    </form>
</div>
@endsection
