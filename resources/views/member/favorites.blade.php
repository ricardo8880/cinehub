@extends('layouts.public')
@section('title', __('Saved content').' | '.config('site.name'))
@section('robots', 'noindex, nofollow')
@section('public-content')
<h1 class="h2 mb-3">{{ __('Saved content') }}</h1>
@include('member._nav')
@if ($items->isEmpty())<div class="alert alert-secondary">{{ __('You have not saved any available content in this region.') }}</div>
@else<div class="row g-3">@foreach ($items as $item)<div class="col-12 col-md-6 col-lg-4"><x-public-card :item="$item" /></div>@endforeach</div>@endif
@endsection
