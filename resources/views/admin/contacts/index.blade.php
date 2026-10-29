@extends('layouts.admin')
@section('title', 'Contact messages')
@section('content')
<h1 class="h3 mb-4">{{ __('Contact messages') }}</h1>
<form class="row g-2 mb-3" method="get"><div class="col-auto"><select class="form-select" name="status"><option value="">{{ __('All statuses') }}</option>@foreach(['unread','read','closed'] as $value)<option value="{{ $value }}" @selected($status === $value)>{{ ucfirst($value) }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-outline-light">{{ __('Filter') }}</button></div></form>
<div class="d-grid gap-3">
@forelse($messages as $message)
<div class="card border-secondary-subtle"><div class="card-body"><div class="d-flex justify-content-between flex-wrap gap-2"><div><h2 class="h5 mb-1">{{ $message->subject }}</h2><div class="small text-body-secondary">{{ $message->name }} · {{ $message->email }} · {{ $message->created_at->format('Y-m-d H:i') }}</div></div><span class="badge text-bg-secondary">{{ $message->status }}</span></div><p class="mt-3">{{ $message->message }}</p><div class="d-flex gap-2"><form method="POST" action="{{ route('admin.contacts.update', $message) }}" class="d-flex gap-2">@csrf @method('PUT')<select class="form-select form-select-sm" name="status">@foreach(['unread','read','closed'] as $value)<option value="{{ $value }}" @selected($message->status === $value)>{{ ucfirst($value) }}</option>@endforeach</select><button class="btn btn-sm btn-outline-light">{{ __('Save') }}</button></form><form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" onsubmit="return confirm('{{ __('Delete this message?') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></div></div></div>
@empty<div class="alert alert-secondary">{{ __('No messages found.') }}</div>@endforelse
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection
