@extends('layouts.public')
@section('title', __('Terms of Use').' · '.config('site.name'))
@section('public-content')
<article class="col-12 col-lg-8 mx-auto">
    <h1 class="h2 mb-4">{{ __('Terms of Use') }}</h1>
    <p>{{ __('CineHub provides editorial entertainment content. Reviews represent editorial opinion and should not be interpreted as an official relationship with studios, publishers or platforms.') }}</p>
    <h2 class="h4 mt-4">{{ __('User content') }}</h2>
    <p>{{ __('Comments and suggestions may be moderated, rejected or removed when they are abusive, spam, unlawful or unrelated.') }}</p>
    <h2 class="h4 mt-4">{{ __('Media') }}</h2>
    <p>{{ __('Trailers and promotional media remain subject to their owners and applicable platform or licensing terms.') }}</p>
    <p class="text-body-secondary small">{{ __('This page is a project template and should be legally reviewed before public launch.') }}</p>
</article>
@endsection
