@extends('layouts.app')
@section('title', 'Reset Password')

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
            <h4 class="fw-bold mb-1">Set new password</h4>
            <p class="text-muted small">Choose a strong password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $email ?? '') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">New Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Min. 8 characters" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold small">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>

            <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold">
                <i class="bi bi-check-lg me-2"></i>Reset Password
            </button>
        </form>
    </div>
</div>
@endsection
