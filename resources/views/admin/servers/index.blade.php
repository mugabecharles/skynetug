@extends('layouts.dashboard')
@section('page_title', 'Servers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="fw-bold mb-0">Servers</h5><p class="text-muted small mb-0">Manage hosting servers and WHM connections</p></div>
    <a href="{{ route('admin.servers.create') }}" class="btn btn-sky btn-sm"><i class="bi bi-plus me-1"></i>Add Server</a>
</div>

<div class="row g-4">
    @forelse($servers as $server)
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-bold mb-0">{{ $server->name }}</h6>
                    <p class="text-muted small mb-0">{{ $server->hostname }}</p>
                </div>
                @if($server->is_active)
                    <span class="badge bg-success-subtle text-success rounded-pill">Online</span>
                @else
                    <span class="badge bg-danger-subtle text-danger rounded-pill">Offline</span>
                @endif
            </div>
            <dl class="row small mb-3">
                <dt class="col-5 text-muted">IP Address</dt><dd class="col-7 font-monospace">{{ $server->ip_address }}</dd>
                <dt class="col-5 text-muted">Type</dt><dd class="col-7">{{ ucfirst($server->type) }}</dd>
                <dt class="col-5 text-muted">NS1</dt><dd class="col-7">{{ $server->ns1 ?? '—' }}</dd>
                <dt class="col-5 text-muted">NS2</dt><dd class="col-7">{{ $server->ns2 ?? '—' }}</dd>
                <dt class="col-5 text-muted">Max Accounts</dt><dd class="col-7">{{ number_format($server->max_accounts) }}</dd>
            </dl>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.servers.edit', $server->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;">Edit</a>
                <form method="POST" action="{{ route('admin.servers.destroy', $server->id) }}" onsubmit="return confirm('Remove this server?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;">Remove</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted bg-white rounded-3 border">
            <i class="bi bi-hdd-rack display-5 d-block mb-3 opacity-30"></i>
            <h6>No servers configured</h6>
            <p class="small">Add your first WHM/cPanel server to start provisioning hosting accounts.</p>
            <a href="{{ route('admin.servers.create') }}" class="btn btn-sky btn-sm">Add Server</a>
        </div>
    </div>
    @endforelse
</div>
@endsection
