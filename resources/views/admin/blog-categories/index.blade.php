@extends('layouts.admin')

@section('title', 'Blog categories')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('Blog categories') }}</h1>
        <p class="text-body-secondary mb-0">{{ __('Internal groups used to control which Blog genres are available while editing an article.') }}</p>
    </div>
    <a class="btn btn-danger" href="{{ route('admin.blog-categories.create') }}"><i class="bi bi-plus-lg me-1"></i>{{ __('New Blog category') }}</a>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-12 col-md-6"><input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search Blog categories') }}"></div>
    <div class="col-auto"><button class="btn btn-outline-light" type="submit"><i class="bi bi-search me-1"></i>{{ __('Search') }}</button></div>
</form>

<div class="card border-secondary-subtle"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
    <thead><tr><th>{{ __('Category') }}</th><th class="text-center">{{ __('Genres') }}</th><th class="text-center">{{ __('Articles') }}</th><th>{{ __('Status') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
    <tbody>
    @forelse ($categories as $category)
        <tr>
            <td><strong>{{ $category->name }}</strong></td>
            <td class="text-center">{{ $category->genres_count }}</td>
            <td class="text-center">{{ $category->posts_count }}</td>
            <td><span class="badge {{ $category->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $category->is_active ? __('Active') : __('Inactive') }}</span></td>
            <td class="text-end">
                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.blog-categories.edit', $category) }}"><i class="bi bi-pencil"></i></a>
                <form class="d-inline" method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" onsubmit="return confirm('{{ __('Delete this Blog category?') }}')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-body-secondary py-4">{{ __('No Blog categories found.') }}</td></tr>
    @endforelse
    </tbody>
</table></div></div>

@if ($categories->hasPages())<div class="mt-3">{{ $categories->links('pagination::bootstrap-5') }}</div>@endif
@endsection
