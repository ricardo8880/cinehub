@extends('layouts.admin')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4">
        <div class="card border-secondary-subtle shadow-sm">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill fs-1 text-danger"></i>
                    <h1 class="h4 mt-2 mb-1">{{ __('Administration') }}</h1>
                    <p class="text-body-secondary mb-0">{{ __('Restricted access') }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                    </div>

                    <button class="btn btn-danger w-100" type="submit">
                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('Sign in') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
