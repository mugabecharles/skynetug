@extends('layouts.dashboard')
@section('page_title', $account->domain)

@section('content')
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('admin.hosting.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a>
    @if($account->status==='active')
    <form method="POST" action="{{ route('admin.hosting.suspend',$account->id) }}" onsubmit="return confirm('Suspend this account?')">@csrf<button class="btn btn-sm btn-warning" style="border-radius:8px;">Suspend</button></form>
    @elseif($account->status==='suspended')
    <form method="POST" action="{{ route('admin.hosting.unsuspend',$account->id) }}">@csrf<button class="btn btn-sm btn-success" style="border-radius:8px;">Unsuspend</button></form>
    @endif
    <form method="POST" action="{{ route('admin.hosting.terminate',$account->id) }}" onsubmit="return confirm('TERMINATE this account? This is irreversible.')">
        @csrf <button class="btn btn-sm btn-danger" style="border-radius:8px;">Terminate</button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Account Details</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Domain</dt><dd class="col-7 fw-semibold">{{ $account->domain }}</dd>
                <dt class="col-5 text-muted">Username</dt><dd class="col-7 font-monospace">{{ $account->username }}</dd>
                <dt class="col-5 text-muted">Customer</dt><dd class="col-7"><a href="{{ route('admin.users.show',$account->user_id) }}">{{ $account->user->name }}</a></dd>
                <dt class="col-5 text-muted">Package</dt><dd class="col-7">{{ $account->hostingPackage?->name ?? '—' }}</dd>
                <dt class="col-5 text-muted">Server</dt><dd class="col-7">{{ $account->server?->name ?? '—' }}</dd>
                <dt class="col-5 text-muted">cPanel URL</dt><dd class="col-7"><a href="{{ $account->cpanel_url }}" target="_blank" class="small">Open cPanel</a></dd>
                @php $c=match($account->status){'active'=>'success','pending'=>'warning','suspended'=>'danger','terminated'=>'secondary',default=>'secondary'}; @endphp
                <dt class="col-5 text-muted">Status</dt><dd class="col-7"><span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($account->status) }}</span></dd>
                <dt class="col-5 text-muted">Registered</dt><dd class="col-7">{{ $account->registration_date ? \Carbon\Carbon::parse($account->registration_date)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Next Due</dt><dd class="col-7">{{ $account->next_due_date ? \Carbon\Carbon::parse($account->next_due_date)->format('d M Y') : '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Resource Usage</h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between small mb-1">
                    <span>Disk Usage</span>
                    <span>{{ number_format($account->disk_used_mb/1024,1) }} GB / {{ $account->hostingPackage?->disk_space_mb==0?'∞':number_format($account->hostingPackage?->disk_space_mb/1024,0).' GB' }}</span>
                </div>
                @php $diskPct = $account->hostingPackage?->disk_space_mb > 0 ? min(100, round($account->disk_used_mb/$account->hostingPackage->disk_space_mb*100)) : 0; @endphp
                <div class="progress" style="height:8px;border-radius:4px;">
                    <div class="progress-bar {{ $diskPct>80?'bg-danger':($diskPct>60?'bg-warning':'bg-primary') }}" style="width:{{ $diskPct }}%"></div>
                </div>
            </div>
            <div>
                <div class="d-flex justify-content-between small mb-1">
                    <span>Bandwidth Usage</span>
                    <span>{{ number_format($account->bandwidth_used_mb/1024,1) }} GB</span>
                </div>
                <div class="progress" style="height:8px;border-radius:4px;">
                    <div class="progress-bar bg-success" style="width:10%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
