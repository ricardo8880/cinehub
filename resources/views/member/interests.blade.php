@extends('layouts.public')
@section('title', __('My interests').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<h1 class="h2 mb-2">{{ __('My interests') }}</h1>
<p class="text-body-secondary">{{ __('Choose the topics you want to see more often. You can change this at any time.') }}</p>
@include('member._nav')
@if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('member.interests.update') }}" data-ajax-interests>@csrf @method('PUT')
@foreach (['category' => __('Categories'), 'genre' => __('Genres'), 'blog_category' => __('Blog topics'), 'blog_genre' => __('Blog genres')] as $kind => $heading)
@if ($data[$kind]->isNotEmpty())
<section class="card border-secondary-subtle mb-3"><div class="card-body">
<h2 class="h5 mb-3">{{ $heading }}</h2>
<div class="row g-2">
@foreach ($data[$kind] as $option)
@php($localized = $option->localization(request()->route('locale')))
@if ($localized)
<div class="col-6 col-md-4"><label class="form-check"><input class="form-check-input" type="checkbox" name="interests[]" value="{{ $kind }}:{{ $option->id }}" @checked(in_array($kind.':'.$option->id, old('interests', $selected)))><span class="form-check-label">{{ $localized->name }}</span></label></div>
@endif
@endforeach
</div></div></section>
@endif
@endforeach
<button class="btn btn-danger" type="submit"><i class="bi bi-check2 me-1"></i>{{ __('Save interests') }}</button>
<div class="small mt-2" role="status" aria-live="polite" data-interests-status hidden></div>
</form>
@endsection
