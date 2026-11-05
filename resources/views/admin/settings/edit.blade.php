@extends('layouts.admin')
@section('title', 'Site settings')
@section('content')
<h1 class="h3 mb-4">{{ __('Site settings') }}</h1>
<form method="POST" action="{{ route('admin.settings.update') }}" class="card border-secondary-subtle">@csrf @method('PUT')<div class="card-body d-grid gap-4">
<div><h2 class="h5">{{ __('General links') }}</h2><div class="row g-3"><div class="col-md-6"><label class="form-label">{{ __('Contact email') }}</label><input class="form-control" type="email" name="contact_email" value="{{ old('contact_email',$settings['contact_email'] ?? '') }}"></div><div class="col-md-6"><label class="form-label">{{ __('SaaS URL') }}</label><input class="form-control" type="url" name="saas_url" value="{{ old('saas_url',$settings['saas_url'] ?? '') }}"></div><div class="col-md-6"><label class="form-label">{{ __('Donation URL') }}</label><input class="form-control" type="url" name="donation_url" value="{{ old('donation_url',$settings['donation_url'] ?? '') }}"></div></div></div>
<hr>
<div>
    <h2 class="h5">{{ __('Book area') }}</h2>
    <p class="text-body-secondary small">{{ __('Enter each book edition manually. Empty languages are not published.') }}</p>
    <ul class="nav nav-tabs" role="tablist">
        @foreach (config('localization.locales') as $locale => $localeInfo)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" type="button" data-bs-toggle="tab" data-bs-target="#book-settings-{{ $locale }}" role="tab">
                    {{ $localeInfo['label'] ?? strtoupper($localeInfo['language']) }}
                </button>
            </li>
        @endforeach
    </ul>
    <div class="tab-content border border-top-0 border-secondary-subtle rounded-bottom p-3">
        @foreach (config('localization.locales') as $locale => $localeInfo)
            <div id="book-settings-{{ $locale }}" class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" role="tabpanel">
                <div class="row g-3">
                    @foreach (['title' => 'Title', 'synopsis' => 'Synopsis', 'cover_url' => 'Cover URL', 'sample_url' => 'Sample URL', 'purchase_url' => 'Purchase URL'] as $field => $label)
                        @php($key = \App\Models\SiteSetting::bookKey($field, $locale))
                        <div class="col-12 {{ str_ends_with($field, 'url') ? 'col-md-6' : '' }}">
                            <label class="form-label" for="{{ $key }}">{{ __($label) }} ({{ $localeInfo['label'] ?? $locale }})</label>
                            @if ($field === 'synopsis')
                                <textarea class="form-control" id="{{ $key }}" name="{{ $key }}" rows="5">{{ old($key, $settings[$key] ?? '') }}</textarea>
                            @else
                                <input class="form-control" id="{{ $key }}" name="{{ $key }}" type="{{ str_ends_with($field, 'url') ? 'url' : 'text' }}" value="{{ old($key, $settings[$key] ?? '') }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
<div><button class="btn btn-danger" type="submit"><i class="bi bi-check-lg me-1"></i>{{ __('Save settings') }}</button></div></div></form>
@endsection
