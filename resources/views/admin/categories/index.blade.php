@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('Categories') }}</h1>
        <p class="text-body-secondary mb-0">{{ __('Organize posts without duplicating content.') }}</p>
    </div>
    <a class="btn btn-danger" href="{{ route('admin.categories.create') }}">
        <i class="bi bi-plus-lg me-1"></i>{{ __('New category') }}
    </a>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-12 col-md-6">
        <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search categories') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-light" type="submit"><i class="bi bi-search me-1"></i>{{ __('Search') }}</button>
    </div>
</form>

<div class="card border-secondary-subtle">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Countries') }}</th>
                    <th class="text-center">{{ __('Posts') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    @php
                        $defaultVersion = $category->localization($defaultLocale);
                        $activeVersions = $category->localizations->where('is_active', true);
                    @endphp
                    <tr>
                        <td>
                            <i class="bi {{ $category->icon ?: 'bi-folder' }} me-2"></i>
                            <strong>{{ $defaultVersion?->name ?? $category->name }}</strong>
                        </td>
                        <td>
                            @foreach ($activeVersions as $version)
                                <span class="badge text-bg-secondary me-1">{{ strtoupper($version->locale) }}</span>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $category->posts_count }}</td>
                        <td class="text-end">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('admin.categories.edit', $category) }}"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('{{ __('Delete this category?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-body-secondary py-4">{{ __('No categories found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($categories->hasPages())
    <div class="mt-3">{{ $categories->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
