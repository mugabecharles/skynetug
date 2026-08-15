@extends('layouts.dashboard')
@section('page_title', 'Add TLD Pricing')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tld-pricing.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Add New TLD</h4>
</div>

<div class="card border-0 shadow-sm" style="max-width:560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tld-pricing.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">TLD Extension <span class="text-danger">*</span></label>
                <input type="text" name="tld" class="form-control @error('tld') is-invalid @enderror"
                       placeholder=".com" value="{{ old('tld') }}" required>
                <div class="form-text">Include the dot — e.g. <code>.com</code>, <code>.co.ug</code></div>
                @error('tld')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Register Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="register_price" step="0.01" min="0"
                               class="form-control @error('register_price') is-invalid @enderror"
                               value="{{ old('register_price') }}" required>
                    </div>
                    @error('register_price')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Renewal Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="renew_price" step="0.01" min="0"
                               class="form-control @error('renew_price') is-invalid @enderror"
                               value="{{ old('renew_price') }}" required>
                    </div>
                    @error('renew_price')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Transfer Price (USD)</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="transfer_price" step="0.01" min="0"
                           class="form-control" value="{{ old('transfer_price') }}"
                           placeholder="Leave blank to match register price">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 99) }}" min="0">
                <div class="form-text">Lower numbers appear first.</div>
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active (show on site)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_popular" value="1"
                           id="isPopular" {{ old('is_popular') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPopular">Mark as Popular</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-1"></i> Add TLD
            </button>
        </form>
    </div>
</div>
@endsection
