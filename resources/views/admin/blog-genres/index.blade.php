@extends('layouts.admin')
@section('title', 'Blog genres')
@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div><h1 class="h3 mb-1">{{ __('Blog genres') }}</h1><p class="text-body-secondary mb-0">{{ __('Genres shown publicly inside the Blog.') }}</p></div>
    <a class="btn btn-danger" href="{{ route('admin.blog-genres.create') }}"><i class="bi bi-plus-lg me-1"></i>{{ __('New Blog genre') }}</a>
</div>
<form class="row g-2 mb-3" method="GET"><div class="col-12 col-md-6"><input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search Blog genres') }}"></div><div class="col-auto"><button class="btn btn-outline-light" type="submit"><i class="bi bi-search me-1"></i>{{ __('Search') }}</button></div></form>
<div class="card border-secondary-subtle"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead><tr><th>{{ __('Genre') }}</th><th>{{ __('Blog categories') }}</th><th class="text-center">{{ __('Articles') }}</th><th>{{ __('Status') }}</th><th class="text-end">{{ __('Actions') }}</th></tr></thead>
<tbody>
@forelse ($genres as $genre)
    @php($name = $genre->localization($defaultLocale)?->name ?? $genre->localizations->first()?->name ?? '#'.$genre->id)
    <tr>
        <td><strong>{{ $name }}</strong></td>
        <td>@foreach ($genre->categories as $category)<span class="badge text-bg-secondary me-1">{{ $category->name }}</span>@endforeach</td>
        <td class="text-center">{{ $genre->posts_count }}</td>
        <td><span class="badge {{ $genre->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $genre->is_active ? __('Active') : __('Inactive') }}</span></td>
        <td class="text-end"><a class="btn btn-outline-light btn-sm" href="{{ route('admin.blog-genres.edit', $genre) }}"><i class="bi bi-pencil"></i></a> <form class="d-inline" method="POST" action="{{ route('admin.blog-genres.destroy', $genre) }}" onsubmit="return confirm('{{ __('Delete this Blog genre?') }}')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash"></i></button></form></td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center text-body-secondary py-4">{{ __('No Blog genres found.') }}</td></tr>
@endforelse
</tbody></table></div></div>
@if ($genres->hasPages())<div class="mt-3">{{ $genres->links('pagination::bootstrap-5') }}</div>@endif
@endsection
