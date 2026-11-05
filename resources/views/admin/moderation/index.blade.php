@extends('layouts.admin')
@section('title', __('Moderation rules'))
@section('content')
<h1 class="h3 mb-2">{{ __('Moderation rules') }}</h1>
<p class="text-body-secondary">{{ __('Add targeted phrases for automatic blocking or private review. Avoid banning everyday swear words by themselves: context matters.') }}</p>
<div class="card border-secondary-subtle mb-4"><div class="card-body">
<h2 class="h5 mb-3">{{ __('Add a rule') }}</h2>
<form class="row g-2" method="post" action="{{ route('admin.moderation.store') }}">@csrf
<div class="col-12 col-lg-5"><input class="form-control" name="phrase" maxlength="150" placeholder="{{ __('Word or exact phrase') }}" required></div>
<div class="col-5 col-lg-2"><select name="locale" class="form-select"><option value="*">{{ __('All languages') }}</option>@foreach(config('localization.locales') as $code => $region)<option value="{{ $code }}">{{ $code }}</option>@endforeach</select></div>
<div class="col-4 col-lg-2"><select name="classification" class="form-select"><option value="review">{{ __('Review') }}</option><option value="blocked">{{ __('Block') }}</option></select></div>
<input type="hidden" name="is_active" value="1"><div class="col-3 col-lg-3"><button class="btn btn-danger w-100" type="submit">{{ __('Add') }}</button></div>
</form></div></div>
<div class="card border-secondary-subtle"><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>{{ __('Phrase') }}</th><th>{{ __('Language') }}</th><th>{{ __('Action') }}</th><th>{{ __('Active') }}</th><th></th></tr></thead><tbody>
@forelse($terms as $term)
<tr><td colspan="5"><div class="d-flex flex-wrap gap-2 align-items-center">
<form id="edit-term-{{ $term->id }}" class="d-flex flex-wrap flex-grow-1 gap-2" method="post" action="{{ route('admin.moderation.update', $term) }}">@csrf @method('PUT')
<input name="phrase" value="{{ $term->phrase }}" required maxlength="150" class="form-control form-control-sm flex-grow-1" style="max-width:360px">
<select name="locale" class="form-select form-select-sm" style="width:auto"><option value="*" @selected($term->locale === '*')>{{ __('All languages') }}</option>@foreach(config('localization.locales') as $code => $region)<option value="{{ $code }}" @selected($term->locale === $code)>{{ $code }}</option>@endforeach</select>
<select name="classification" class="form-select form-select-sm" style="width:auto"><option value="review" @selected($term->classification === 'review')>{{ __('Review') }}</option><option value="blocked" @selected($term->classification === 'blocked')>{{ __('Block') }}</option></select>
<select name="is_active" class="form-select form-select-sm" style="width:auto"><option value="1" @selected($term->is_active)>{{ __('Active') }}</option><option value="0" @selected(! $term->is_active)>{{ __('Inactive') }}</option></select>
<button class="btn btn-sm btn-outline-success" type="submit">{{ __('Save') }}</button>
</form>
<form method="post" action="{{ route('admin.moderation.destroy', $term) }}" onsubmit="return confirm('{{ __('Delete this rule?') }}')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" type="submit" aria-label="{{ __('Delete') }}"><i class="bi bi-trash"></i></button></form>
</div></td></tr>
@empty<tr><td colspan="5" class="text-center text-body-secondary">{{ __('No custom rules yet. Built-in rules are still active.') }}</td></tr>@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $terms->links('pagination::bootstrap-5') }}</div>
@endsection
