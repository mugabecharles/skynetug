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
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#apps">
                <i class="bi bi-grid me-1"></i>App Installer
            </button></li>
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

{{-- ── Softaculous App Installer Tab ─────────────────────────────────── --}}
<div class="tab-pane fade" id="apps">
    <div class="mt-3">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Installed apps --}}
        <h6 class="fw-bold mb-3">Installed Applications</h6>

        @if(count($installedApps) > 0)
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Application</th>
                        <th>Version</th>
                        <th>URL</th>
                        <th>Installed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installedApps as $app)
                    <tr>
                        <td class="fw-semibold">{{ $app['app_name'] }}</td>
                        <td><span class="badge bg-secondary">v{{ $app['version'] }}</span></td>
                        <td>
                            @if($app['install_url'])
                            <a href="{{ $app['install_url'] }}" target="_blank" class="small text-primary">
                                {{ $app['install_url'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                            @else
                            <span class="text-muted small">{{ $app['install_dir'] ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $app['installed_at'] ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('dashboard.hosting.apps.upgrade', [$account->id, $app['install_id']]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary"
                                        onclick="return confirm('Upgrade {{ $app['app_name'] }} to latest version?')"
                                        title="Upgrade to latest version">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Update
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('dashboard.hosting.apps.remove', [$account->id, $app['install_id']]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Remove {{ $app['app_name'] }}? This cannot be undone.')"
                                        title="Remove application">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 mb-4" style="background:#f8fafc;border-radius:10px;border:1.5px dashed #e2e8f0;">
            <i class="bi bi-grid" style="font-size:2.5rem;color:#cbd5e1;"></i>
            <p class="text-muted mt-2 mb-0">No applications installed yet. Install one below.</p>
        </div>
        @endif

        {{-- Install new app --}}
        @if($account->status === 'active')
        <h6 class="fw-bold mb-3">Install a New Application</h6>

        <div class="row g-3 mb-4" id="appCatalog">
            @php
                $catalog = app(\App\Services\SoftaculousService::class)->listAvailable($account->username);
            @endphp
            @foreach($catalog as $app)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border h-100" style="border-radius:10px;cursor:pointer;"
                     onclick="openInstallModal({{ $app['sid'] }}, '{{ addslashes($app['name']) }}')"
                     title="Install {{ $app['name'] }}">
                    <div class="card-body text-center p-3">
                        <div style="width:48px;height:48px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                            <i class="bi bi-box-seam" style="font-size:1.4rem;color:#0066FF;"></i>
                        </div>
                        <div class="fw-semibold" style="font-size:.88rem;">{{ $app['name'] }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ ucfirst($app['type'] ?? '') }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

    </div>
</div>

{{-- ── Install Modal ──────────────────────────────────────────────────── --}}
<div class="modal fade" id="installModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-grid me-2 text-primary"></i>
                    Install <span id="modalAppName"></span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('dashboard.hosting.apps.install', $account->id) }}" id="installForm">
                @csrf
                <input type="hidden" name="softid" id="modalSoftId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Site Name <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control" placeholder="My WordPress Site" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Install Directory <span class="text-muted">(leave blank for root)</span></label>
                        <div class="input-group">
                            <span class="input-group-text text-muted small">{{ $account->domain }}/</span>
                            <input type="text" name="directory" class="form-control" placeholder="blog">
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Admin Username <span class="text-danger">*</span></label>
                        <input type="text" name="admin_username" class="form-control" placeholder="admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Admin Password <span class="text-danger">*</span></label>
                        <input type="password" name="admin_password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Admin Email <span class="text-danger">*</span></label>
                        <input type="email" name="admin_email" class="form-control"
                               value="{{ auth()->user()->email }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-download me-1"></i>Install Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openInstallModal(sid, name) {
    document.getElementById('modalSoftId').value  = sid;
    document.getElementById('modalAppName').textContent = name;
    new bootstrap.Modal(document.getElementById('installModal')).show();
}
// Auto-open apps tab if there's a flash message
@if(session('success') || session('error'))
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('[data-bs-target="#apps"]').click();
});
@endif
</script>
@endpush

@endsection
