@extends('layouts.admin')
@section('title', 'Suggestions')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1">{{ __('Suggestions') }}</h1><p class="text-body-secondary mb-0">{{ __('Review topic suggestions sent by visitors.') }}</p></div></div>
<form class="row g-2 mb-3" method="get"><div class="col-auto"><select class="form-select" name="status"><option value="">{{ __('All statuses') }}</option>@foreach(['pending','reviewed','accepted','rejected'] as $value)<option value="{{ $value }}" @selected($status === $value)>{{ ucfirst($value) }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-outline-light">{{ __('Filter') }}</button></div></form>
<div class="d-grid gap-3">
@forelse($suggestions as $suggestion)
<div class="card border-secondary-subtle"><div class="card-body"><div class="d-flex justify-content-between gap-3 flex-wrap"><div><h2 class="h5 mb-1">{{ $suggestion->subject }}</h2><div class="small text-body-secondary">{{ $suggestion->email ?: __('No email') }} · {{ $suggestion->created_at->format('Y-m-d H:i') }}</div></div><span class="badge text-bg-secondary">{{ $suggestion->status }}</span></div><p class="mt-3 mb-3">{{ $suggestion->message }}</p><div class="d-flex gap-2 flex-wrap"><form method="POST" action="{{ route('admin.suggestions.update', $suggestion) }}" class="d-flex gap-2">@csrf @method('PUT')<select class="form-select form-select-sm" name="status">@foreach(['pending','reviewed','accepted','rejected'] as $value)<option value="{{ $value }}" @selected($suggestion->status === $value)>{{ ucfirst($value) }}</option>@endforeach</select><button class="btn btn-sm btn-outline-light">{{ __('Save') }}</button></form><form method="POST" action="{{ route('admin.suggestions.destroy', $suggestion) }}" onsubmit="return confirm('{{ __('Delete this suggestion?') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></div></div></div>
@empty<div class="alert alert-secondary">{{ __('No suggestions found.') }}</div>@endforelse
</div>
<div class="mt-4">{{ $suggestions->links() }}</div>
@endsection
