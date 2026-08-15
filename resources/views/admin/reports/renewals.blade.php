@extends('layouts.dashboard')
@section('page_title', 'Renewal Report')

@section('content')
<div class="d-flex gap-2 flex-wrap mb-4">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $r=>$l)
    <a href="{{ route('admin.reports.'.$r) }}" class="btn btn-sm {{ request()->routeIs('admin.reports.'.$r)?'btn-sky':'btn-outline-secondary' }}" style="border-radius:8px;">{{ $l }}</a>
    @endforeach
</div>

<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="d-flex gap-2 align-items-end">
        <div>
            <label class="form-label small fw-semibold mb-1">Look-ahead Days (1–90)</label>
            <input type="number" name="days" class="form-control form-control-sm" value="{{ $days }}" min="1" max="90" style="width:120px;">
        </div>
        <button class="btn btn-sky btn-sm">Apply</button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-server me-2 text-sky"></i>Hosting Due for Renewal <span class="badge bg-warning ms-2">{{ $hostingDue->count() }}</span></h6>
            @forelse($hostingDue as $acc)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2 small">
                <div>
                    <div class="fw-semibold">{{ $acc->domain }}</div>
                    <div class="text-muted">{{ $acc->user->name }} — {{ $acc->hostingPackage?->name }}</div>
                </div>
                <div class="text-end">
                    <div class="{{ \Carbon\Carbon::parse($acc->next_due_date)->diffInDays() < 7 ? 'text-danger fw-bold' : '' }}">
                        {{ \Carbon\Carbon::parse($acc->next_due_date)->format('d M Y') }}
                    </div>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($acc->next_due_date)->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted small">No hosting renewals due in next {{ $days }} days.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-link-45deg me-2 text-sky"></i>Domains Due for Renewal <span class="badge bg-warning ms-2">{{ $domainsDue->count() }}</span></h6>
            @forelse($domainsDue as $domain)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2 small">
                <div>
                    <div class="fw-semibold">{{ $domain->domain_name }}</div>
                    <div class="text-muted">{{ $domain->user->name }}</div>
                </div>
                <div class="text-end">
                    <div class="{{ \Carbon\Carbon::parse($domain->expiry_date)->diffInDays() < 7 ? 'text-danger fw-bold' : '' }}">
                        {{ \Carbon\Carbon::parse($domain->expiry_date)->format('d M Y') }}
                    </div>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($domain->expiry_date)->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <p class="text-muted small">No domain renewals due in next {{ $days }} days.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
