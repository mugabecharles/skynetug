@extends('layouts.dashboard')
@section('page_title', isset($server) ? 'Edit Server' : 'Add Server')

@section('content')
<div class="mb-4"><a href="{{ route('admin.servers.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="row justify-content-center"><div class="col-lg-7">
<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-4">{{ isset($server) ? 'Edit' : 'Add' }} Hosting Server</h6>
    <form method="POST" action="{{ isset($server) ? route('admin.servers.update',$server->id) : route('admin.servers.store') }}">
        @csrf @if(isset($server)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Server Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$server->name??'') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Hostname / IP <span class="text-danger">*</span></label>
                <input type="text" name="hostname" class="form-control @error('hostname') is-invalid @enderror" value="{{ old('hostname',$server->hostname??'') }}" placeholder="server1.skynetug.com" required>
                @error('hostname')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">IP Address <span class="text-danger">*</span></label>
                <input type="text" name="ip_address" class="form-control @error('ip_address') is-invalid @enderror" value="{{ old('ip_address',$server->ip_address??'') }}" placeholder="192.168.1.1" required>
                @error('ip_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Server Type</label>
                <select name="type" class="form-select">
                    @foreach(['shared','vps','dedicated'] as $t)
                    <option value="{{ $t }}" {{ old('type',$server->type??'shared')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">WHM Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username',$server->username??'root') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Max Accounts</label>
                <input type="number" name="max_accounts" class="form-control" value="{{ old('max_accounts',$server->max_accounts??500) }}" min="1">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold small">WHM API Token</label>
                <textarea name="api_hash" class="form-control" rows="3" placeholder="Paste your WHM API token here">{{ old('api_hash',$server->api_hash??'') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Nameserver 1</label>
                <input type="text" name="ns1" class="form-control" value="{{ old('ns1',$server->ns1??'') }}" placeholder="ns1.skynetug.com">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Nameserver 2</label>
                <input type="text" name="ns2" class="form-control" value="{{ old('ns2',$server->ns2??'') }}" placeholder="ns2.skynetug.com">
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active',$server->is_active??true)?'checked':'' }}>
                    <label class="form-check-label small" for="is_active">Server is active</label>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-sky px-4"><i class="bi bi-save me-2"></i>{{ isset($server) ? 'Save Changes' : 'Add Server' }}</button>
            <a href="{{ route('admin.servers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</div></div>
@endsection
