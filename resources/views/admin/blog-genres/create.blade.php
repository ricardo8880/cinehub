@extends('layouts.admin')
@section('title', 'New Blog genre')
@section('content')
<div class="mb-4"><a class="text-body-secondary" href="{{ route('admin.blog-genres.index') }}"><i class="bi bi-arrow-left me-1"></i>{{ __('Blog genres') }}</a><h1 class="h3 mt-2 mb-0">{{ __('New Blog genre') }}</h1></div>
<form method="POST" action="{{ route('admin.blog-genres.store') }}">@csrf<div class="card border-secondary-subtle"><div class="card-body">@include('admin.blog-genres._form')</div></div><div class="d-flex justify-content-end gap-2 mt-3"><a class="btn btn-outline-secondary" href="{{ route('admin.blog-genres.index') }}">{{ __('Cancel') }}</a><button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save') }}</button></div></form>
@endsection
