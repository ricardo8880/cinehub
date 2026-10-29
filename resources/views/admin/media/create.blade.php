@extends('layouts.admin')

@section('title', 'Add media')

@section('content')
<div class="mb-4">
    <a class="text-body-secondary" href="{{ route('admin.posts.media.index', $post) }}"><i class="bi bi-arrow-left me-1"></i>{{ __('Media') }}</a>
    <h1 class="h3 mt-2 mb-0">{{ __('Add media') }}</h1>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.posts.media.store', $post) }}">
    @csrf
    <div class="card border-secondary-subtle"><div class="card-body">@include('admin.media._form')</div></div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a class="btn btn-outline-secondary" href="{{ route('admin.posts.media.index', $post) }}">{{ __('Cancel') }}</a>
        <button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save media') }}</button>
    </div>
</form>
@endsection
