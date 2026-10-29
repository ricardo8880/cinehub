@extends('layouts.public')

@section('title', __('Contact').' · '.config('site.name'))
@section('robots', 'noindex, follow')

@section('public-content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <h1 class="h2 mb-2">{{ __('Contact') }}</h1>
        <p class="text-body-secondary mb-4">{{ __('Use this form for questions, corrections or content reports.') }}</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('contact.store', ['locale' => request()->route('locale')]) }}" class="card border-secondary-subtle">
            @csrf
            <div class="card-body d-grid gap-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">{{ __('Name') }}</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" maxlength="120" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div>
                    <label class="form-label" for="subject">{{ __('Subject') }}</label>
                    <input class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" maxlength="160" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label" for="message">{{ __('Message') }}</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="7" maxlength="5000" required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-none" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
                <div><button class="btn btn-danger" type="submit"><i class="bi bi-send me-1"></i>{{ __('Send message') }}</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
