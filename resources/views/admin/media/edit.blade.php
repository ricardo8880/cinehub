@extends('layouts.admin')

@section('title', 'Edit media')

@section('content')
<div class="mb-4">
    <a class="text-body-secondary" href="{{ route('admin.posts.media.index', $post) }}"><i class="bi bi-arrow-left me-1"></i>{{ __('Media') }}</a>
    <h1 class="h3 mt-2 mb-0">{{ __('Edit media') }}</h1>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.posts.media.update', [$post, $medium]) }}">
    @csrf
    @method('PUT')
    <div class="card border-secondary-subtle"><div class="card-body">@include('admin.media._form')</div></div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a class="btn btn-outline-secondary" href="{{ route('admin.posts.media.index', $post) }}">{{ __('Cancel') }}</a>
        <button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save changes') }}</button>
    </div>
</form>
@endsection
