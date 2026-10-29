@extends('layouts.admin')

@section('title', 'New post')

@section('content')
<div class="mb-4">
    <a class="text-body-secondary" href="{{ route('admin.posts.index') }}"><i class="bi bi-arrow-left me-1"></i>{{ __('Posts') }}</a>
    <h1 class="h3 mt-2 mb-0">{{ __('New post') }}</h1>
</div>

<form method="POST" enctype="multipart/form-data" action="{{ route('admin.posts.store') }}">
    @csrf
    <div class="card border-secondary-subtle"><div class="card-body">@include('admin.posts._form')</div></div>
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a class="btn btn-outline-secondary" href="{{ route('admin.posts.index') }}">{{ __('Cancel') }}</a>
        <button class="btn btn-danger" type="submit" @disabled($categories->isEmpty())><i class="bi bi-check-lg me-1"></i>{{ __('Save post') }}</button>
    </div>
</form>
@endsection
