@extends('layouts.app')
@section('title', 'Create Account')

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
    max-width: 520px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Create your account</h4>
            <p class="text-muted small">Join thousands of Ugandan businesses on SkyNetug</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            @if(request('ref'))
                <input type="hidden" name="ref" value="{{ request('ref') }}">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="John Mukasa" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Phone Number</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="+256 700 000 000">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Country</label>
                    <select name="country" class="form-select @error('country') is-invalid @enderror">
                        <option value="UG" {{ old('country', 'UG') == 'UG' ? 'selected' : '' }}>🇺🇬 Uganda</option>
                        <option value="KE" {{ old('country') == 'KE' ? 'selected' : '' }}>🇰🇪 Kenya</option>
                        <option value="TZ" {{ old('country') == 'TZ' ? 'selected' : '' }}>🇹🇿 Tanzania</option>
                        <option value="RW" {{ old('country') == 'RW' ? 'selected' : '' }}>🇷🇼 Rwanda</option>
                        <option value="NG" {{ old('country') == 'NG' ? 'selected' : '' }}>🇳🇬 Nigeria</option>
                        <option value="GH" {{ old('country') == 'GH' ? 'selected' : '' }}>🇬🇭 Ghana</option>
                        <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>🇺🇸 United States</option>
                        <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                        <option value="OTHER">Other</option>
                    </select>
                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 8 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Repeat password" required>
                </div>
            </div>

            <p class="text-muted small mt-3 mb-4">
                By registering, you agree to our <a href="#" class="text-sky">Terms of Service</a> and <a href="#" class="text-sky">Privacy Policy</a>.
            </p>

            <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            Already have an account? <a href="{{ route('login') }}" class="text-sky fw-semibold">Sign in</a>
        </p>
    </div>
</div>
@endsection
