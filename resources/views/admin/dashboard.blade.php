@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4"><div><h1 class="h3 mb-1">{{ __('Dashboard') }}</h1><p class="text-body-secondary mb-0">{{ __('Manage CineHub content from one place.') }}</p></div><a class="btn btn-danger" href="{{ route('admin.posts.create') }}"><i class="bi bi-plus-lg me-1"></i>{{ __('New post') }}</a></div>
<div class="row g-3">
@foreach([
    ['Posts',$postsCount,'bi-file-earmark-text',route('admin.posts.index')],
    ['Categories',$categoriesCount,'bi-grid',route('admin.categories.index')],
    ['Pending comments',$pendingCommentsCount,'bi-chat-left-text',route('admin.comments.index',['status'=>'pending'])],
    ['Pending suggestions',$pendingSuggestionsCount,'bi-lightbulb',route('admin.suggestions.index',['status'=>'pending'])],
    ['Unread contacts',$unreadContactsCount,'bi-envelope',route('admin.contacts.index',['status'=>'unread'])],
] as [$label,$value,$icon,$url])
<div class="col-12 col-sm-6 col-xl-3"><div class="card h-100 border-secondary-subtle"><div class="card-body"><div class="d-flex align-items-center justify-content-between"><div><div class="text-body-secondary small">{{ __($label) }}</div><div class="fs-3 fw-bold">{{ $value }}</div></div><i class="bi {{ $icon }} fs-2 text-body-secondary"></i></div><a class="stretched-link" href="{{ $url }}"></a></div></div></div>
@endforeach
<div class="col-12 col-sm-6 col-xl-3"><div class="card h-100 border-secondary-subtle"><div class="card-body"><div class="d-flex align-items-center justify-content-between"><div><div class="text-body-secondary small">{{ __('Metrics') }}</div><div class="fw-semibold">{{ __('Open report') }}</div></div><i class="bi bi-graph-up fs-2 text-body-secondary"></i></div><a class="stretched-link" href="{{ route('admin.metrics') }}"></a></div></div></div>
</div>
@endsection
