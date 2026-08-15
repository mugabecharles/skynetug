@extends('layouts.dashboard')
@section('page_title', isset($coupon) ? 'Edit Coupon' : 'Create Coupon')

@section('content')
<div class="mb-4"><a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center"><div class="col-lg-6">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">{{ isset($coupon) ? 'Edit' : 'Create' }} Coupon</h6>
    <form method="POST" action="{{ isset($coupon) ? route('admin.coupons.update',$coupon->id) : route('admin.coupons.store') }}">
        @csrf @if(isset($coupon)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Coupon Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror"
                    value="{{ old('code',$coupon->code??'') }}" placeholder="SAVE20" {{ isset($coupon)?'readonly':'' }} required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$coupon->name??'') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Discount Type</label>
                <select name="type" class="form-select" id="couponType">
                    <option value="percentage" {{ old('type',$coupon->type??'percentage')=='percentage'?'selected':'' }}>Percentage (%)</option>
                    <option value="fixed"       {{ old('type',$coupon->type??'')=='fixed'?'selected':'' }}>Fixed Amount ($)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Value <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text" id="valuePrefix">%</span>
                    <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                        value="{{ old('value',$coupon->value??'') }}" min="0" step="0.01" required>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Usage Limit <span class="text-muted">(0 = unlimited)</span></label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit',$coupon->usage_limit??0) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Start Date</label>
                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at',$coupon->starts_at??'') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Expiry Date</label>
                <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at',$coupon->expires_at??'') }}">
            </div>
            @if(isset($coupon))
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active',$coupon->is_active)?'checked':'' }}><label class="form-check-label small" for="is_active">Active</label></div>
            </div>
            @endif
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4">{{ isset($coupon) ? 'Save Changes' : 'Create Coupon' }}</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div></div>

@push('scripts')
<script>
document.getElementById('couponType').addEventListener('change', function() {
    document.getElementById('valuePrefix').textContent = this.value === 'percentage' ? '%' : 'USD';
});
// Set on load
document.getElementById('valuePrefix').textContent = document.getElementById('couponType').value === 'percentage' ? '%' : 'USD';
</script>
@endpush
@endsection
