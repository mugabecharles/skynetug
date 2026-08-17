@extends('layouts.dashboard')
@section('page_title', isset($package) ? 'Edit Package' : 'Add Package')

@section('content')
<div class="mb-4"><a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center"><div class="col-lg-8">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">{{ isset($package) ? 'Edit' : 'Create' }} Hosting Package</h6>
    <form method="POST" action="{{ isset($package) ? route('admin.packages.update',$package->id) : route('admin.packages.store') }}">
        @csrf @if(isset($package)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Package Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$package->name??'') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug',$package->slug??'') }}" required placeholder="e.g. business-hosting">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select">
                    @foreach(['shared','wordpress','vps','email','ssl','backup','design'] as $t)
                    <option value="{{ $t }}" {{ old('type',$package->type??'shared')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Description</label>
                <input type="text" name="description" class="form-control" value="{{ old('description',$package->description??'') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Monthly Price ($)</label>
                <input type="number" name="price_monthly" class="form-control" value="{{ old('price_monthly',$package->price_monthly??0) }}" min="0" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Yearly Price ($)</label>
                <input type="number" name="price_yearly" class="form-control" value="{{ old('price_yearly',$package->price_yearly??0) }}" min="0" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Biennial Price ($)</label>
                <input type="number" name="price_biennially" class="form-control" value="{{ old('price_biennially',$package->price_biennially??0) }}" min="0" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Disk Space (MB) <span class="text-muted">0=Unlimited</span></label>
                <input type="number" name="disk_space_mb" class="form-control" value="{{ old('disk_space_mb',$package->disk_space_mb??0) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Email Accounts <span class="text-muted">0=Unlimited</span></label>
                <input type="number" name="email_accounts" class="form-control" value="{{ old('email_accounts',$package->email_accounts??0) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Databases <span class="text-muted">0=Unlimited</span></label>
                <input type="number" name="databases" class="form-control" value="{{ old('databases',$package->databases??0) }}" min="0">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">Additional Features <span class="text-muted">(one per line)</span></label>
                <textarea name="features_text" class="form-control" rows="4" placeholder="Free domain&#10;Free SSL&#10;cPanel included">{{ old('features_text', isset($package) ? implode("\n",(array)$package->features) : '') }}</textarea>
            </div>
            <div class="col-12">
                <div class="d-flex gap-4 flex-wrap">
                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active',$package->is_active??true)?'checked':'' }}><label class="form-check-label small" for="is_active">Active</label></div>
                    <div class="form-check"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" {{ old('is_featured',$package->is_featured??false)?'checked':'' }}><label class="form-check-label small" for="is_featured">Featured (show on homepage)</label></div>
                    <div class="form-check"><input type="checkbox" name="ssl_included" value="1" class="form-check-input" id="ssl_incl" {{ old('ssl_included',$package->ssl_included??false)?'checked':'' }}><label class="form-check-label small" for="ssl_incl">SSL Included</label></div>
                    <div class="form-check"><input type="checkbox" name="softaculous_included" value="1" class="form-check-input" id="soft_incl" {{ old('softaculous_included',$package->softaculous_included??false)?'checked':'' }}><label class="form-check-label small" for="soft_incl">Softaculous</label></div>
                    <div class="form-check"><input type="checkbox" name="backup_included" value="1" class="form-check-input" id="bkp_incl" {{ old('backup_included',$package->backup_included??false)?'checked':'' }}><label class="form-check-label small" for="bkp_incl">Backup Included</label></div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-save me-2"></i>{{ isset($package) ? 'Save Changes' : 'Create Package' }}</button>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div></div>
@endsection
