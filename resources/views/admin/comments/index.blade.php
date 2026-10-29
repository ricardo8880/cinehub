@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('Comments') }}</h1>
        <p class="text-body-secondary mb-0">{{ __('Review public comments before they appear on the site.') }}</p>
    </div>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-12 col-md-5">
        <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="{{ __('Search comments') }}">
    </div>
    <div class="col-8 col-md-3">
        <select class="form-select" name="status">
            <option value="">{{ __('All statuses') }}</option>
            <option value="pending" @selected($status === 'pending')>{{ __('Pending') }}</option>
            <option value="approved" @selected($status === 'approved')>{{ __('Approved') }}</option>
            <option value="spam" @selected($status === 'spam')>{{ __('Spam') }}</option>
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
                    <th>{{ __('Comment') }}</th>
                    <th>{{ __('Post') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th class="text-end">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comments as $comment)
                    @php($postContent = $comment->post?->localization($defaultLocale))
                    @php($statusClass = match($comment->status) { 'approved' => 'success', 'spam' => 'danger', default => 'warning' })
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $comment->author_name ?: __('Anonymous') }}</div>
                            @if ($comment->author_email)
                                <div class="small text-body-secondary">{{ $comment->author_email }}</div>
                            @endif
                            <div class="mt-2">{{ $comment->body }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $postContent?->title ?? __('Untitled post') }}</div>
                            <div class="small text-body-secondary">#{{ $comment->post_id }}</div>
                        </td>
                        <td><span class="badge text-bg-{{ $statusClass }}">{{ ucfirst($comment->status) }}</span></td>
                        <td class="text-nowrap">{{ $comment->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="text-end text-nowrap">
                            @if ($comment->status !== 'approved')
                                <form class="d-inline" method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-outline-success btn-sm" type="submit" title="{{ __('Approve') }}"><i class="bi bi-check-lg"></i></button>
                                </form>
                            @endif
                            @if ($comment->status !== 'pending')
                                <form class="d-inline" method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="pending">
                                    <button class="btn btn-outline-warning btn-sm" type="submit" title="{{ __('Pending') }}"><i class="bi bi-clock"></i></button>
                                </form>
                            @endif
                            @if ($comment->status !== 'spam')
                                <form class="d-inline" method="POST" action="{{ route('admin.comments.update', $comment) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="spam">
                                    <button class="btn btn-outline-danger btn-sm" type="submit" title="{{ __('Mark as spam') }}"><i class="bi bi-shield-x"></i></button>
                                </form>
                            @endif
                            <form class="d-inline" method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('{{ __('Delete this comment?') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit" title="{{ __('Delete') }}"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-body-secondary py-4">{{ __('No comments found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($comments->hasPages())
    <div class="mt-3">{{ $comments->links('pagination::bootstrap-5') }}</div>
@endif
@endsection
