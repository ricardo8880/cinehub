@extends('layouts.admin')

@section('title', 'Post media')

@section('content')
@php($content = $post->localization())
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <a class="text-body-secondary" href="{{ route('admin.posts.edit', $post) }}"><i class="bi bi-arrow-left me-1"></i>{{ __('Back to post') }}</a>
        <h1 class="h3 mt-2 mb-1">{{ __('Media') }}</h1>
        <p class="text-body-secondary mb-0">{{ $content?->title ?? __('Post') }}</p>
    </div>
    <a class="btn btn-danger" href="{{ route('admin.posts.media.create', $post) }}">
        <i class="bi bi-plus-lg me-1"></i>{{ __('Add media') }}
    </a>
</div>

<div class="row g-3">
    @forelse ($post->media as $medium)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 border-secondary-subtle">
                @if ($medium->thumbnail_path)
                    <div class="ratio ratio-16x9">
                        <img src="{{ asset('storage/'.$medium->thumbnail_path) }}" class="card-img-top object-fit-cover" alt="{{ $medium->alt_text ?: '' }}">
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge text-bg-secondary">{{ ucfirst($medium->type) }}</span>
                        @if ($medium->is_primary)<span class="badge text-bg-danger">{{ __('Primary') }}</span>@endif
                    </div>
                    <div class="small text-body-secondary mb-2">
                        {{ $medium->locale ? strtoupper($medium->locale) : __('All regions') }}
                        @if ($medium->provider) · {{ ucfirst($medium->provider) }} @endif
                    </div>
                    @if ($medium->alt_text)<div class="small">{{ $medium->alt_text }}</div>@endif
                    @if ($medium->embed_url)<div class="small text-truncate mt-2"><code>{{ $medium->embed_url }}</code></div>@endif
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('admin.posts.media.edit', [$post, $medium]) }}"><i class="bi bi-pencil me-1"></i>{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('admin.posts.media.destroy', [$post, $medium]) }}" onsubmit="return confirm('{{ __('Delete this media item?') }}')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-secondary mb-0"><i class="bi bi-images me-1"></i>{{ __('No media added yet.') }}</div>
        </div>
    @endforelse
</div>
@endsection
