@extends('layouts.dashboard')
@section('page_title', 'Coupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Coupons</h5><p class="text-muted small mb-0">Manage discount codes and promotions</p></div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>Create Coupon</a>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Used / Limit</th><th>Expires</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td class="font-monospace fw-bold text-sky">{{ $coupon->code }}</td>
                    <td>{{ $coupon->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($coupon->type) }}</span></td>
                    <td>{{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$ ' . number_format($coupon->value) }}</td>
                    <td>{{ $coupon->usage_count }} / {{ $coupon->usage_limit == 0 ? '∞' : $coupon->usage_limit }}</td>
                    <td class="small {{ $coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast() ? 'text-danger' : 'text-muted' }}">
                        {{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y') : '—' }}
                    </td>
                    <td>
                        @if($coupon->is_active && (!$coupon->expires_at || !\Carbon\Carbon::parse($coupon->expires_at)->isPast()))
                            <span class="badge bg-success-subtle text-success rounded-pill">Active</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill">Inactive</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('Delete this coupon?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No coupons yet. <a href="{{ route('admin.coupons.create') }}">Create one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $coupons->links() }}</div>
</div>
@endsection
