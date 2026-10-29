@extends('layouts.public')
@section('title', __('Privacy Policy').' · '.config('site.name'))
@section('public-content')
<article class="col-12 col-lg-8 mx-auto">
    <h1 class="h2 mb-4">{{ __('Privacy Policy') }}</h1>
    <p>{{ __('CineHub collects only information needed to operate the site, moderate interactions, protect against abuse and understand performance when analytics consent is granted.') }}</p>
    <h2 class="h4 mt-4">{{ __('Public interactions') }}</h2>
    <p>{{ __('Comments, reactions and view protection may use technical identifiers. Raw visitor IP addresses are not stored in reaction or view records.') }}</p>
    <h2 class="h4 mt-4">{{ __('Contact') }}</h2>
    <p>{{ __('Messages submitted through contact forms are stored so the administration can review and respond when appropriate.') }}</p>
    <h2 class="h4 mt-4">{{ __('Analytics') }}</h2>
    <p>{{ __('Analytics is loaded only when configured and when the visitor grants analytics consent.') }}</p>
    <p class="text-body-secondary small">{{ __('This page is a project template and should be legally reviewed before public launch.') }}</p>
</article>
@endsection
