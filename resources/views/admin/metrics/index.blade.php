@extends('layouts.admin')
@section('title', 'Metrics')
@section('content')
<h1 class="h3 mb-4">{{ __('Metrics') }}</h1>
<div class="row g-3 mb-4">
@foreach([['Published posts',$publishedPosts,'bi-file-earmark-check'],['Views · 30 days',$views30,'bi-eye'],['Likes · 30 days',$likes30,'bi-heart'],['Approved comments · 30 days',$comments30,'bi-chat-left-text']] as [$label,$value,$icon])
<div class="col-12 col-sm-6 col-xl-3"><div class="card h-100 border-secondary-subtle"><div class="card-body d-flex justify-content-between align-items-center"><div><div class="text-body-secondary small">{{ __($label) }}</div><div class="fs-3 fw-bold">{{ $value }}</div></div><i class="bi {{ $icon }} fs-2 text-body-secondary"></i></div></div></div>
@endforeach
</div>
<div class="card border-secondary-subtle"><div class="card-header">{{ __('Top posts by views') }}</div><div class="table-responsive"><table class="table table-dark table-hover align-middle mb-0"><thead><tr><th>{{ __('Post') }}</th><th>{{ __('Views') }}</th><th>{{ __('Likes') }}</th></tr></thead><tbody>@forelse($topPosts as $post)<tr><td>{{ optional($post->localizations->firstWhere('locale',$defaultLocale))->title ?? '#'.$post->id }}</td><td>{{ $post->views_count }}</td><td>{{ $post->likes_count }}</td></tr>@empty<tr><td colspan="3" class="text-body-secondary">{{ __('No data yet.') }}</td></tr>@endforelse</tbody></table></div></div>
@endsection
