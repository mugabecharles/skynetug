@extends('layouts.dashboard')

@section('page_title', 'Hosting Details')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('hosting.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    <div>
        <h4 class="fw-bold mb-0">{{ $account->domain }}</h4>
        <small class="text-muted">{{ $account->package->name ?? 'Hosting Account' }}</small>
    </div>
    @if($account->status === 'active')
        <span class="badge badge-status-active ms-auto">Active</span>
    @elseif($account->status === 'suspended')
        <span class="badge badge-status-suspended ms-auto">Suspended</span>
    @else
        <span class="badge bg-secondary ms-auto">{{ ucfirst($account->status) }}</span>
    @endif
</div>

{{-- Overview Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;">
            <div class="text-muted small mb-1">Server</div>
            <div class="fw-bold">{{ $account->server->name ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;">
            <div class="text-muted small mb-1">Username</div>
            <div class="fw-bold font-monospace">{{ $account->cpanel_username ?? 'N/A' }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;">
            <div class="text-muted small mb-1">Renewal Date</div>
            <div class="fw-bold">{{ $account->expiry_date ? $account->expiry_date->format('d M Y') : 'N/A' }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm text-center p-3" style="border-radius:12px;">
            <div class="text-muted small mb-1">cPanel</div>
            <a href="{{ $account->cpanel_url ?? '#' }}" target="_blank" class="btn btn-sm text-white" style="background:#0066FF;border:none;border-radius:8px;font-size:0.75rem;">
                Open cPanel <i class="bi bi-box-arrow-up-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-transparent border-0 pt-3">
        <ul class="nav nav-tabs" id="hostingTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#usage">Usage</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#ssl">SSL Certs</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#emails">Email Accounts</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#backups">Backups</button></li>
        </ul>
    </div>

    <div class="card-body tab-content">

        {{-- Usage Tab --}}
        <div class="tab-pane fade show active" id="usage">
            <div class="row g-4 mt-1">
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3">Disk Usage</h6>
                    @php
                        $diskUsed = $account->disk_used ?? 0;
                        $diskLimit = $account->package->disk ?? 1;
                        $diskPct = $diskLimit > 0 ? min(100, round($diskUsed / $diskLimit * 100)) : 0;
                    @endphp
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $diskUsed }} MB used</span>
                        <span>{{ $diskLimit }} MB total</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:99px;">
                        <div class="progress-bar {{ $diskPct > 80 ? 'bg-danger' : 'bg-primary' }}" style="width:{{ $diskPct }}%;border-radius:99px;"></div>
                    </div>
                    <small class="text-muted">{{ $diskPct }}% used</small>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3">Bandwidth</h6>
                    @php
                        $bwUsed = $account->bandwidth_used ?? 0;
                        $bwLimit = $account->package->bandwidth ?? 1;
                        $bwPct = $bwLimit > 0 ? min(100, round($bwUsed / $bwLimit * 100)) : 0;
                    @endphp
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $bwUsed }} MB used</span>
                        <span>{{ $bwLimit === -1 ? 'Unlimited' : $bwLimit . ' MB' }}</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:99px;">
                        <div class="progress-bar {{ $bwPct > 80 ? 'bg-danger' : 'bg-success' }}" style="width:{{ $bwPct }}%;border-radius:99px;"></div>
                    </div>
                    <small class="text-muted">{{ $bwPct }}% used</small>
                </div>
            </div>
        </div>

        {{-- SSL Tab --}}
        <div class="tab-pane fade" id="ssl">
            <div class="table-responsive mt-2">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Domain</th>
                            <th>Type</th>
                            <th>Issued</th>
                            <th>Expires</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account->sslCertificates ?? [] as $ssl)
                        <tr>
                            <td class="fw-semibold small">{{ $ssl->domain }}</td>
                            <td class="small">{{ $ssl->type ?? 'Let\'s Encrypt' }}</td>
                            <td class="small">{{ $ssl->issued_at ? $ssl->issued_at->format('d M Y') : '-' }}</td>
                            <td class="small">{{ $ssl->expires_at ? $ssl->expires_at->format('d M Y') : '-' }}</td>
                            <td>
                                @if($ssl->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($ssl->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No SSL certificates found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Email Accounts Tab --}}
        <div class="tab-pane fade" id="emails">
            <div class="table-responsive mt-2">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Email Address</th>
                            <th>Quota</th>
                            <th>Usage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account->emailAccounts ?? [] as $email)
                        <tr>
                            <td class="fw-semibold small">{{ $email->email }}</td>
                            <td class="small">{{ $email->quota ?? 'Unlimited' }}</td>
                            <td class="small">{{ $email->usage ?? '0 MB' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No email accounts found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Backups Tab --}}
        <div class="tab-pane fade" id="backups">
            <div class="table-responsive mt-2">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Backup Date</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account->backups ?? [] as $backup)
                        <tr>
                            <td class="small">{{ $backup->created_at->format('d M Y H:i') }}</td>
                            <td class="small">{{ ucfirst($backup->type ?? 'Full') }}</td>
                            <td class="small">{{ $backup->size ?? 'N/A' }}</td>
                            <td>
                                @if($backup->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($backup->status === 'in_progress')
                                    <span class="badge bg-warning text-dark">In Progress</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($backup->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No backups available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
