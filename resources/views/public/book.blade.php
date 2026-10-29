@extends('layouts.public')
@section('title', ($book['title'] ?: __('Book')).' · '.config('site.name'))
@section('public-content')
<div class="row g-4 align-items-start">
    @if ($book['cover_url'])
        <div class="col-12 col-md-4"><img class="img-fluid rounded shadow-sm" src="{{ $book['cover_url'] }}" alt="{{ $book['title'] ?: __('Book cover') }}" loading="lazy"></div>
    @endif
    <div class="col">
        <h1 class="h2">{{ $book['title'] ?: __('Book area') }}</h1>
        <p class="text-body-secondary">{{ $book['synopsis'] ?: __('Book information has not been published yet.') }}</p>
        <div class="d-flex flex-wrap gap-2">
            @if ($book['sample_url'])<a class="btn btn-outline-light" href="{{ $book['sample_url'] }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-file-earmark-text me-1"></i>{{ __('Free sample') }}</a>@endif
            @if ($book['purchase_url'])<a class="btn btn-danger" href="{{ $book['purchase_url'] }}" target="_blank" rel="sponsored noopener noreferrer"><i class="bi bi-cart me-1"></i>{{ __('Buy book') }}</a>@endif
        </div>
    </div>
</div>
@endsection
