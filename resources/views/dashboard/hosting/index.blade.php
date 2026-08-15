@extends('layouts.dashboard')

@section('page_title', 'My Hosting')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">My Hosting Accounts</h4>
        <p class="text-muted small mb-0">Manage your hosting services</p>
    </div>
    <a href="{{ route('order.hosting') }}" class="btn text-white" style="background:#0066FF;border:none;border-radius:10px;">
        <i class="bi bi-plus-circle me-1"></i>Order Hosting
    </a>
</div>

<div class="row g-3">
    @forelse($hostingAccounts ?? [] as $account)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(0,102,255,0.1);">
                        <i class="bi bi-globe2" style="color:#0066FF;font-size:1.4rem;"></i>
                    </div>
                    @if($account->status === 'active')
                        <span class="badge badge-status-active">Active</span>
                    @elseif($account->status === 'suspended')
                        <span class="badge badge-status-suspended">Suspended</span>
                    @elseif($account->status === 'pending')
                        <span class="badge badge-status-pending">Pending</span>
                    @else
                        <span class="badge badge-status-expired">{{ ucfirst($account->status) }}</span>
                    @endif
                </div>

                <h5 class="fw-bold mb-2">{{ $account->domain }}</h5>
                <p class="text-muted small mb-3">{{ $account->package->name ?? 'N/A' }}</p>

                <div class="mb-3 pb-3 border-bottom">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <div class="text-muted">Server</div>
                            <div class="fw-semibold">{{ $account->server->name ?? 'Unassigned' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Expires</div>
                            <div class="fw-semibold">{{ $account->expiry_date ? $account->expiry_date->format('d M Y') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('hosting.show', $account->id) }}" class="btn btn-sm btn-outline-secondary flex-fill" style="border-radius:8px;">
                        <i class="bi bi-eye me-1"></i>View
                    </a>
                    @if($account->cpanel_url)
                    <a href="{{ $account->cpanel_url }}" target="_blank" class="btn btn-sm text-white flex-fill" style="background:#0066FF;border:none;border-radius:8px;">
                        <i class="bi bi-box-arrow-up-right me-1"></i>cPanel
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body text-center py-5">
                <i class="bi bi-globe2 display-1 text-muted opacity-25"></i>
                <h5 class="fw-bold mt-3 mb-2">No Hosting Accounts Yet</h5>
                <p class="text-muted mb-4">Get started with one of our hosting packages</p>
                <a href="{{ route('order.hosting') }}" class="btn text-white" style="background:#0066FF;border:none;border-radius:10px;">
                    <i class="bi bi-plus-circle me-1"></i>Order Hosting
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@endsection
