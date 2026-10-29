@extends('layouts.public')

@section('title', __('Suggest a topic').' · '.config('site.name'))
@section('robots', 'noindex, follow')

@section('public-content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <h1 class="h2 mb-2">{{ __('Suggest a topic') }}</h1>
        <p class="text-body-secondary mb-4">{{ __('Send an idea for a future CineHub publication.') }}</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('suggestions.store', ['locale' => request()->route('locale')]) }}" class="card border-secondary-subtle">
            @csrf
            <div class="card-body d-grid gap-3">
                <div>
                    <label class="form-label" for="subject">{{ __('Subject') }}</label>
                    <input class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" maxlength="160" required>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label" for="email">{{ __('Email (optional)') }}</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label" for="message">{{ __('Details') }}</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="7" maxlength="5000" required>{{ old('message') }}</textarea>
                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-none" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>
                <div><button class="btn btn-danger" type="submit"><i class="bi bi-send me-1"></i>{{ __('Send suggestion') }}</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
