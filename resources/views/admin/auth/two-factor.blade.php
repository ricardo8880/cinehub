@extends('layouts.admin')

@section('title', 'Two-factor authentication')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4">
        <div class="card border-secondary-subtle shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill fs-1 text-danger"></i>
                    <h1 class="h4 mt-2 mb-1">{{ __('Two-factor authentication') }}</h1>
                </div>
                @if ($setupSecret)
                    <p class="small">{{ __('Set up an authenticator app before accessing the admin area. Add a time-based account using this key:') }}</p>
                    <div class="p-3 border rounded text-center font-monospace user-select-all text-break mb-3" aria-label="Authenticator setup key">{{ $setupSecret }}</div>
                    <p class="small text-body-secondary">{{ __('Store this key safely. It will not be displayed again after activation.') }}</p>
                @else
                    <p class="text-body-secondary">{{ __('Enter the six-digit code from your authenticator app.') }}</p>
                @endif
                <form method="POST" action="{{ route('admin.two-factor.verify') }}">
                    @csrf
                    <label for="code" class="form-label">{{ __('Verification code') }}</label>
                    <input id="code" name="code" class="form-control mb-3" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required autofocus>
                    <button class="btn btn-danger w-100" type="submit">{{ __('Verify and continue') }}</button>
                </form>
                <a href="{{ route('admin.login') }}" class="d-block text-center small mt-3">{{ __('Return to sign in') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
