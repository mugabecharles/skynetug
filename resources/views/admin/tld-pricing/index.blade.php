@extends('layouts.dashboard')
@section('page_title', 'Domain TLD Pricing')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Domain TLD Pricing</h4>
        <p class="text-muted mb-0 small">Set registration and renewal prices for each domain extension.</p>
    </div>
    <a href="{{ route('admin.tld-pricing.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add TLD
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('admin.tld-pricing.bulk-update') }}">
            @csrf
            <table class="table table-hover mb-0 align-middle">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="ps-4">TLD</th>
                        <th>Register Price (USD)</th>
                        <th>Renewal Price (USD)</th>
                        <th class="text-center">Popular</th>
                        <th class="text-center">Active</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tlds as $i => $tld)
                    <tr>
                        <td class="ps-4">
                            <input type="hidden" name="tlds[{{ $i }}][id]" value="{{ $tld->id }}">
                            <span class="fw-bold fs-5" style="color:#0066FF;">{{ $tld->tld }}</span>
                            @if($tld->is_popular)
                            <span class="badge bg-warning-subtle text-warning ms-1" style="font-size:.7rem;">Popular</span>
                            @endif
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="max-width:160px;">
                                <span class="input-group-text">$</span>
                                <input type="number" name="tlds[{{ $i }}][register_price]"
                                       value="{{ $tld->register_price }}"
                                       class="form-control" step="0.01" min="0" required>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="max-width:160px;">
                                <span class="input-group-text">$</span>
                                <input type="number" name="tlds[{{ $i }}][renew_price]"
                                       value="{{ $tld->renew_price }}"
                                       class="form-control" step="0.01" min="0" required>
                            </div>
                        </td>
                        <td class="text-center">
                            {{ $tld->is_popular ? '⭐' : '—' }}
                        </td>
                        <td class="text-center">
                            <input type="hidden" name="tlds[{{ $i }}][is_active]" value="0">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input" type="checkbox"
                                       name="tlds[{{ $i }}][is_active]" value="1"
                                       {{ $tld->is_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.tld-pricing.edit', $tld) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.tld-pricing.destroy', $tld) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete {{ $tld->tld }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            No TLDs configured yet.
                            <a href="{{ route('admin.tld-pricing.create') }}">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($tlds->count())
            <div class="p-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-lg me-1"></i> Save All Prices
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
