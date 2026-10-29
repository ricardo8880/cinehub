@extends('layouts.admin')
@section('title', 'Ad slots')
@section('content')
<h1 class="h3 mb-1">{{ __('Ad slots') }}</h1>
<p class="text-body-secondary mb-4">{{ __('Manage reserved advertising positions. AdSense is not enabled by this screen.') }}</p>

<div class="card border-secondary-subtle mb-4"><div class="card-body">
<form method="POST" action="{{ route('admin.ad-slots.store') }}" class="row g-3">@csrf
    <div class="col-md-3"><input class="form-control" name="key" placeholder="post-inline-1" required></div>
    <div class="col-md-3"><input class="form-control" name="label" placeholder="{{ __('Label') }}" required></div>
    <div class="col-md-3"><input class="form-control" name="position" placeholder="{{ __('Position') }}" required></div>
    <div class="col-md-2"><select class="form-select" name="type"><option value="reserved">reserved</option><option value="internal">internal</option><option value="adsense">adsense</option></select></div>
    <div class="col-md-1"><button class="btn btn-danger w-100"><i class="bi bi-plus-lg"></i></button></div>
</form>
</div></div>

<div class="card border-secondary-subtle"><div class="table-responsive"><table class="table table-dark align-middle mb-0">
<thead><tr><th>{{ __('Key') }}</th><th>{{ __('Label') }}</th><th>{{ __('Position') }}</th><th>{{ __('Type') }}</th><th>{{ __('Active') }}</th><th></th></tr></thead>
<tbody>
@forelse($slots as $slot)
<tr>
    <td><input class="form-control form-control-sm" form="slot-{{ $slot->id }}" name="key" value="{{ $slot->key }}" required></td>
    <td><input class="form-control form-control-sm" form="slot-{{ $slot->id }}" name="label" value="{{ $slot->label }}" required></td>
    <td><input class="form-control form-control-sm" form="slot-{{ $slot->id }}" name="position" value="{{ $slot->position }}" required></td>
    <td><select class="form-select form-select-sm" form="slot-{{ $slot->id }}" name="type">@foreach(['reserved','internal','adsense'] as $type)<option value="{{ $type }}" @selected($slot->type===$type)>{{ $type }}</option>@endforeach</select></td>
    <td><input type="hidden" form="slot-{{ $slot->id }}" name="is_active" value="0"><input class="form-check-input" form="slot-{{ $slot->id }}" type="checkbox" name="is_active" value="1" @checked($slot->is_active)></td>
    <td class="text-nowrap"><button class="btn btn-sm btn-outline-light" form="slot-{{ $slot->id }}"><i class="bi bi-check-lg"></i></button> <button class="btn btn-sm btn-outline-danger" form="delete-slot-{{ $slot->id }}"><i class="bi bi-trash"></i></button></td>
</tr>
@empty
<tr><td colspan="6" class="text-body-secondary">{{ __('No ad slots configured.') }}</td></tr>
@endforelse
</tbody></table></div></div>

@foreach($slots as $slot)
<form id="slot-{{ $slot->id }}" method="POST" action="{{ route('admin.ad-slots.update',$slot) }}">@csrf @method('PUT')</form>
<form id="delete-slot-{{ $slot->id }}" method="POST" action="{{ route('admin.ad-slots.destroy',$slot) }}" onsubmit="return confirm('{{ __('Delete this slot?') }}')">@csrf @method('DELETE')</form>
@endforeach
@endsection
