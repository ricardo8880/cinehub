@extends('layouts.admin')
@section('title', 'Genres')
@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('Genres') }}</h1>
        <p class="text-body-secondary mb-0">{{ __('Create genres and choose which categories can use them.') }}</p>
    </div>
    <a class="btn btn-danger" href="{{ route('admin.genres.create') }}"><i class="bi bi-plus-lg me-1"></i>{{ __('New genre') }}</a>
</div>

<form class="row g-2 mb-4" method="GET" action="{{ route('admin.genres.index') }}">
    <div class="col-12 col-md-6">
        <input class="form-control" name="q" value="{{ $search }}" placeholder="{{ __('Search genres') }}">
    </div>
    <div class="col-auto"><button class="btn btn-outline-light" type="submit">{{ __('Search') }}</button></div>
</form>

<div class="card border-secondary-subtle">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead><tr><th>{{ __('Genre') }}</th><th>{{ __('Categories') }}</th><th>{{ __('Posts') }}</th><th>{{ __('Status') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
            <tbody>
                @forelse ($genres as $genre)
                    <tr>
                        <td>@php($localizedGenre = $genre->localization($defaultLocale) ?? $genre->localizations->first())<div class="fw-semibold">{{ $localizedGenre?->name ?? $genre->name }}</div><div class="text-body-secondary small">{{ $localizedGenre?->slug ?? $genre->slug }}</div></td>
                        <td>
                            @forelse ($genre->categories as $category)
                                <span class="badge text-bg-secondary me-1">{{ $category->localization($defaultLocale)?->name ?? $category->name }}</span>
                            @empty
                                <span class="text-body-secondary">—</span>
                            @endforelse
                        </td>
                        <td>{{ number_format($genre->posts_count) }}</td>
                        <td>@if ($genre->is_active)<span class="badge text-bg-success">{{ __('Active') }}</span>@else<span class="badge text-bg-secondary">{{ __('Inactive') }}</span>@endif</td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('admin.genres.edit', $genre) }}"><i class="bi bi-pencil"></i></a>
                            <form class="d-inline" method="POST" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('{{ __('Delete this genre?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-body-secondary">{{ __('No genres found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if ($genres->hasPages())
    <div class="mt-3">{{ $genres->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
