@extends('layouts.dashboard')
@section('page_title', 'My Profile')

@section('content')
<div class="row g-4">
    {{-- Profile Info --}}
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4 mb-4">
            <h6 class="fw-bold mb-4">Personal Information</h6>
            <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Phone Number</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone) }}" placeholder="+256 700 000 000">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Company (optional)</label>
                        <input type="text" name="company" class="form-control"
                            value="{{ old('company', $user->company) }}" placeholder="Your company name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Country</label>
                        <select name="country" class="form-select">
                            @foreach(['UG'=>'🇺🇬 Uganda','KE'=>'🇰🇪 Kenya','TZ'=>'🇹🇿 Tanzania','RW'=>'🇷🇼 Rwanda','NG'=>'🇳🇬 Nigeria','GH'=>'🇬🇭 Ghana','US'=>'🇺🇸 United States','GB'=>'🇬🇧 United Kingdom'] as $code=>$name)
                            <option value="{{ $code }}" {{ old('country',$user->country)==$code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">City</label>
                        <input type="text" name="city" class="form-control"
                            value="{{ old('city', $user->city) }}" placeholder="Kampala">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Address</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $user->address) }}" placeholder="Street address">
                    </div>
                </div>
                <button type="submit" class="btn btn-sky mt-4"><i class="bi bi-save me-2"></i>Save Changes</button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-4">Change Password</h6>
            <form method="POST" action="{{ route('dashboard.profile.password') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min. 8 characters" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary mt-4"><i class="bi bi-shield-lock me-2"></i>Update Password</button>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Avatar --}}
        <div class="bg-white rounded-3 border p-4 mb-4 text-center">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                 style="width:80px;height:80px;background:#0066FF;color:#fff;font-size:2rem;font-weight:700;">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
            <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
            <p class="text-muted small mb-1">{{ $user->email }}</p>
            <span class="badge bg-primary-subtle text-primary">{{ ucfirst($user->role) }}</span>
            <p class="text-muted small mt-2 mb-0">Member since {{ $user->created_at->format('M Y') }}</p>
        </div>

        {{-- Two-Factor Auth --}}
        <div class="bg-white rounded-3 border p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-0">Two-Factor Auth</h6>
                    <p class="text-muted small mb-0">Extra security for your account</p>
                </div>
                @if($user->two_factor_enabled)
                    <span class="badge bg-success">Enabled</span>
                @else
                    <span class="badge bg-secondary">Disabled</span>
                @endif
            </div>
            @if($user->two_factor_enabled)
            <form method="POST" action="{{ route('dashboard.profile.2fa.disable') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-outline-danger w-100">Disable 2FA</button>
            </form>
            @else
            <form method="POST" action="{{ route('dashboard.profile.2fa.enable') }}" class="mt-2">
                @csrf
                <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-shield-check me-1"></i>Enable 2FA</button>
            </form>
            @endif
        </div>

        {{-- Account Stats --}}
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Account Summary</h6>
            <dl class="row small mb-0">
                <dt class="col-7 text-muted">Hosting Accounts</dt>
                <dd class="col-5 fw-semibold">{{ $user->hostingAccounts()->count() }}</dd>
                <dt class="col-7 text-muted">Domains</dt>
                <dd class="col-5 fw-semibold">{{ $user->domains()->count() }}</dd>
                <dt class="col-7 text-muted">Total Invoices</dt>
                <dd class="col-5 fw-semibold">{{ $user->invoices()->count() }}</dd>
                <dt class="col-7 text-muted">Support Tickets</dt>
                <dd class="col-5 fw-semibold">{{ $user->supportTickets()->count() }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
