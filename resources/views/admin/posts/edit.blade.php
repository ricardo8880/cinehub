@extends('layouts.admin')

@php($isBlog = $post->publication_type === 'blog')

@section('title', $isBlog ? 'Edit Blog article' : 'Edit post')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <a class="text-body-secondary" href="{{ route('admin.posts.index', ['publication_type' => $isBlog ? 'blog' : 'standard']) }}">
            <i class="bi bi-arrow-left me-1"></i>{{ $isBlog ? __('Blog') : __('Posts') }}
        </a>
        <h1 class="h3 mt-2 mb-0">{{ $isBlog ? __('Edit Blog article') : __('Edit post') }}</h1>
    </div>
    <a class="btn btn-outline-light" href="{{ route('admin.posts.media.index', $post) }}">
        <i class="bi bi-images me-1"></i>{{ __('Manage media') }} <span class="badge text-bg-secondary ms-1">{{ $post->media->count() }}</span>
    </a>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.posts.update', $post) }}">
    @csrf
    @method('PUT')
    <div class="card border-secondary-subtle"><div class="card-body">@include('admin.posts._form')</div></div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a class="btn btn-outline-secondary" href="{{ route('admin.posts.index', ['publication_type' => $isBlog ? 'blog' : 'standard']) }}">{{ __('Cancel') }}</a>
        <button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save changes') }}</button>
    </div>
</form>
@endsection
