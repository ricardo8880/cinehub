@extends('layouts.admin')

@section('title', 'Posts')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $publicationType === 'blog' ? __('Blog articles') : __('Posts') }}</h1>
        <p class="text-body-secondary mb-0">{{ $publicationType === 'blog' ? __('Manage the articles published in the Blog.') : __('Create the content once and reuse it across the site.') }}</p>
    </div>
    <a class="btn btn-danger" href="{{ route('admin.posts.create', $publicationType === 'blog' ? ['publication_type' => 'blog'] : []) }}">
        <i class="bi bi-plus-lg me-1"></i>{{ $publicationType === 'blog' ? __('New Blog article') : __('New post') }}
    </a>
</div>

<form class="row g-2 mb-3" method="GET">
    <input type="hidden" name="publication_type" value="{{ $publicationType }}">
    <div class="col-12 col-md-7">
        <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search posts') }}">
    </div>
    <div class="col-6 col-md-2">
        <select class="form-select" name="status">
            <option value="">{{ __('All statuses') }}</option>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}" @selected($status === $value)>{{ __($label) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-light" type="submit"><i class="bi bi-search me-1"></i>{{ __('Filter') }}</button>
    </div>
</form>

<div class="card border-secondary-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Post') }}</th>
                    <th>{{ __('Categories') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-center">{{ __('Media') }}</th>
                    <th>{{ __('Publication') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    @php($content = $post->localization($defaultLocale))
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $content?->title ?? __('Untitled post') }}</div>
                            <div class="small text-body-secondary">#{{ $post->id }}</div>
                        </td>
                        <td>
                            @forelse ($post->categories as $category)
                                <span class="badge text-bg-secondary">{{ $category->name }}</span>
                            @empty
                                <span class="text-body-secondary">—</span>
                            @endforelse
                        </td>
                        <td>
                            @php($statusClass = $post->status === 'published' ? 'success' : 'warning')
                            <span class="badge text-bg-{{ $statusClass }}">{{ ucfirst($post->status) }}</span>
                            @if ($post->is_featured)
                                <span class="badge text-bg-danger">{{ __('Featured') }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $post->media_count }}</td>
                        <td>{{ $post->published_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.posts.media.index', $post) }}" title="{{ __('Media') }}"><i class="bi bi-images"></i></a>
                            <a class="btn btn-outline-light btn-sm" href="{{ route('admin.posts.edit', $post) }}" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('{{ __('Delete this post?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit" title="{{ __('Delete') }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-body-secondary py-4">{{ $publicationType === 'blog' ? __('No Blog articles found.') : __('No posts found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($posts->hasPages())
    <div class="mt-3">{{ $posts->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
