@extends('layouts.admin')
@section('title', 'Site settings')
@section('content')
<h1 class="h3 mb-4">{{ __('Site settings') }}</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" class="card border-secondary-subtle">@csrf @method('PUT')<div class="card-body d-grid gap-4">
<div><h2 class="h5">{{ __('General links') }}</h2><div class="row g-3"><div class="col-md-6"><label class="form-label">{{ __('Contact email') }}</label><input class="form-control" type="email" name="contact_email" value="{{ old('contact_email',$settings['contact_email'] ?? '') }}"></div><div class="col-md-6"><label class="form-label">{{ __('SaaS URL') }}</label><input class="form-control" type="url" name="saas_url" value="{{ old('saas_url',$settings['saas_url'] ?? '') }}"></div><div class="col-md-6"><label class="form-label">{{ __('Donation URL') }}</label><input class="form-control" type="url" name="donation_url" value="{{ old('donation_url',$settings['donation_url'] ?? '') }}"></div></div></div>
<hr>
<div><h2 class="h5">{{ __('Book area') }}</h2><div class="row g-3"><div class="col-12"><label class="form-label">{{ __('Title') }}</label><input class="form-control" name="book_title" value="{{ old('book_title',$settings['book_title'] ?? '') }}"></div><div class="col-12"><label class="form-label">{{ __('Synopsis') }}</label><textarea class="form-control" rows="5" name="book_synopsis">{{ old('book_synopsis',$settings['book_synopsis'] ?? '') }}</textarea></div>@foreach(['book_cover_url'=>'Cover URL','book_sample_url'=>'Sample URL','book_purchase_url'=>'Purchase URL'] as $key=>$label)<div class="col-md-6"><label class="form-label">{{ __($label) }}</label><input class="form-control" type="url" name="{{ $key }}" value="{{ old($key,$settings[$key] ?? '') }}"></div>@endforeach</div></div>
<div><button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save settings') }}</button></div></div></form>
@endsection
