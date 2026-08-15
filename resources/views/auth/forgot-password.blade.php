@extends('layouts.app')
@section('title', 'Forgot Password')

@push('styles')
<style>
.auth-wrapper { min-height: calc(100vh - 140px); display: flex; align-items: center; justify-content: center; background: #f0f4ff; padding: 2rem 1rem; }
.auth-card { background: #fff; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 440px; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="mb-3" style="width:60px;height:60px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <i class="bi bi-key fs-4 text-sky"></i>
            </div>
            <h4 class="fw-bold mb-1">Forgot your password?</h4>
            <p class="text-muted small">Enter your email and we'll send you a reset link within 60 seconds.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success small"><i class="bi bi-check-circle me-2"></i>{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold">
                <i class="bi bi-envelope me-2"></i>Send Reset Link
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Remember your password? <a href="{{ route('login') }}" class="text-sky fw-semibold">Sign in</a>
        </p>
    </div>
</div>
@endsection
