@extends('layouts.dashboard')
@section('page_title', 'Add New User')

@section('content')
<div class="mb-4"><a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">Create New User</h6>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Phone</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    @foreach(['customer','super_admin','billing_manager','technical_admin','support_agent','sales_manager'] as $r)
                    <option value="{{ $r }}" {{ old('role','customer')==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Country</label>
                <select name="country" class="form-select">
                    @foreach(['UG'=>'Uganda','KE'=>'Kenya','TZ'=>'Tanzania','RW'=>'Rwanda','NG'=>'Nigeria','US'=>'United States','GB'=>'United Kingdom'] as $code=>$name)
                    <option value="{{ $code }}" {{ old('country','UG')==$code?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-person-plus me-2"></i>Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection
