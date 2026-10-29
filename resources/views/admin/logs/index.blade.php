@extends('layouts.admin')
@section('title', 'Activity logs')
@section('content')
<h1 class="h3 mb-4">{{ __('Administrative activity') }}</h1>
<div class="card border-secondary-subtle"><div class="table-responsive"><table class="table table-dark table-hover align-middle mb-0"><thead><tr><th>{{ __('Date') }}</th><th>{{ __('Admin') }}</th><th>{{ __('Action') }}</th><th>{{ __('Route') }}</th><th>{{ __('Status') }}</th></tr></thead><tbody>@forelse($logs as $log)<tr><td class="text-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td><td>{{ $log->user?->email ?: '—' }}</td><td>{{ $log->method }}</td><td><code>{{ $log->route_name ?: $log->path }}</code></td><td>{{ $log->status_code }}</td></tr>@empty<tr><td colspan="5" class="text-body-secondary">{{ __('No activity recorded.') }}</td></tr>@endforelse</tbody></table></div></div><div class="mt-4">{{ $logs->links() }}</div>
@endsection
