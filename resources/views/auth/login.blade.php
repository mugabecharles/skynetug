@extends('layouts.app')
@section('title', 'Sign In')

@push('styles')
<style>
.auth-wrapper {
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4ff;
    padding: 2rem 1rem;
}
.auth-card {
    background: #fff;
    border-radius: 20px;
    padding: 2.5rem;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Welcome back</h4>
            <p class="text-muted small">Sign in to your SkyNetug account</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-semibold small">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sky small">Forgot password?</a>
                </div>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small" for="remember">Remember me for 30 days</label>
            </div>

            <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Don't have an account? <a href="{{ route('register') }}" class="text-sky fw-semibold">Create one</a>
        </p>
    </div>
</div>
@endsection
