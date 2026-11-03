@extends('layouts.public')
@section('title', __('For You').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<h1 class="h2 mb-2">{{ __('For You') }}</h1>
<p class="text-body-secondary">{{ __('New content based on your favorite topics, saved posts and likes. Recommendations update as we publish.') }}</p>
@include('member._nav')
@if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
@if ($items->isEmpty())
    <div class="alert alert-secondary">{{ __('No content is available in this region yet.') }} <a href="{{ route('member.interests') }}">{{ __('Choose your interests') }}</a></div>
@else
    <div class="row g-3">@foreach ($items as $item)<div class="col-12 col-md-6 col-lg-4"><x-public-card :item="$item" /></div>@endforeach</div>
@endif
@endsection
