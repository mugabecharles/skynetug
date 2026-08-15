@extends('layouts.dashboard')
@section('page_title', $server->name)

@section('content')
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('admin.servers.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a>
    <a href="{{ route('admin.servers.edit', $server->id) }}" class="btn btn-sm btn-sky" style="border-radius:8px;"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6 class="fw-bold mb-0">{{ $server->name }}</h6>
                @if($server->is_active)
                    <span class="badge bg-success-subtle text-success rounded-pill">Online</span>
                @else
                    <span class="badge bg-danger-subtle text-danger rounded-pill">Offline</span>
                @endif
            </div>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Hostname</dt><dd class="col-7 font-monospace">{{ $server->hostname }}</dd>
                <dt class="col-5 text-muted">IP Address</dt><dd class="col-7 font-monospace">{{ $server->ip_address }}</dd>
                <dt class="col-5 text-muted">Type</dt><dd class="col-7">{{ ucfirst($server->type) }}</dd>
                <dt class="col-5 text-muted">Username</dt><dd class="col-7">{{ $server->username ?? 'root' }}</dd>
                <dt class="col-5 text-muted">Max Accounts</dt><dd class="col-7">{{ number_format($server->max_accounts) }}</dd>
                <dt class="col-5 text-muted">NS1</dt><dd class="col-7 font-monospace small">{{ $server->ns1 ?? '—' }}</dd>
                <dt class="col-5 text-muted">NS2</dt><dd class="col-7 font-monospace small">{{ $server->ns2 ?? '—' }}</dd>
            </dl>
        </div>
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Quick Links</h6>
            @if($server->hostname)
            <div class="d-grid gap-2">
                <a href="https://{{ $server->hostname }}:2087" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-grid-3x3-gap me-1"></i>Open WHM</a>
                <a href="https://{{ $server->hostname }}:2083" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-cpu me-1"></i>Open cPanel</a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Hosting Accounts on This Server</h6>
            @php $accounts = $server->hostingAccounts()->with('user')->latest()->take(20)->get(); @endphp
            @if($accounts->isEmpty())
                <p class="text-muted small">No hosting accounts on this server yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" style="font-size:.85rem;">
                    <thead class="table-light"><tr><th>Domain</th><th>Username</th><th>Customer</th><th>Status</th><th>Due Date</th></tr></thead>
                    <tbody>
                        @foreach($accounts as $acc)
                        <tr>
                            <td class="fw-semibold">{{ $acc->domain }}</td>
                            <td class="font-monospace small">{{ $acc->username }}</td>
                            <td>{{ $acc->user->name }}</td>
                            <td>
                                @php $c=match($acc->status){'active'=>'success','suspended'=>'danger','pending'=>'warning',default=>'secondary'}; @endphp
                                <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($acc->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $acc->next_due_date ? \Carbon\Carbon::parse($acc->next_due_date)->format('d M Y') : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <a href="{{ route('admin.hosting.index', ['server' => $server->id]) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:.8rem;">
                    View All Accounts <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
