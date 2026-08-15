@extends('layouts.dashboard')
@section('page_title', 'Edit TLD — ' . $tldPricing->tld)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tld-pricing.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Edit TLD: <span style="color:#0066FF;">{{ $tldPricing->tld }}</span></h4>
</div>

<div class="card border-0 shadow-sm" style="max-width:560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.tld-pricing.update', $tldPricing) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">TLD</label>
                <input type="text" class="form-control bg-light" value="{{ $tldPricing->tld }}" disabled>
            </div>

            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Register Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="register_price" step="0.01" min="0"
                               class="form-control @error('register_price') is-invalid @enderror"
                               value="{{ old('register_price', $tldPricing->register_price) }}" required>
                    </div>
                    @error('register_price')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Renewal Price (USD) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="renew_price" step="0.01" min="0"
                               class="form-control @error('renew_price') is-invalid @enderror"
                               value="{{ old('renew_price', $tldPricing->renew_price) }}" required>
                    </div>
                    @error('renew_price')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Transfer Price (USD)</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="transfer_price" step="0.01" min="0"
                           class="form-control"
                           value="{{ old('transfer_price', $tldPricing->transfer_price) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Sort Order</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', $tldPricing->sort_order) }}" min="0">
            </div>

            <div class="d-flex gap-4 mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           id="isActive" {{ old('is_active', $tldPricing->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_popular" value="1"
                           id="isPopular" {{ old('is_popular', $tldPricing->is_popular) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPopular">Mark as Popular</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
