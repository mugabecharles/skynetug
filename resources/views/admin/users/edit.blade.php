@extends('layouts.dashboard')
@section('page_title', 'Edit User: ' . $user->name)

@section('content')
<div class="mb-4"><a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">Edit User</h6>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">New Password <span class="text-muted">(leave blank to keep)</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Phone</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Role</label>
                <select name="role" class="form-select">
                    @foreach(['customer','super_admin','billing_manager','technical_admin','support_agent','sales_manager'] as $r)
                    <option value="{{ $r }}" {{ old('role',$user->role)==$r?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active',$user->is_active)?'selected':'' }}>Active</option>
                    <option value="0" {{ !old('is_active',$user->is_active)?'selected':'' }}>Disabled</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-save me-2"></i>Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection
