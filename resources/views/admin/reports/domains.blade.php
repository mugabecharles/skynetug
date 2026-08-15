@extends('layouts.dashboard')
@section('page_title', 'Domain Report')

@section('content')
<div class="d-flex gap-2 flex-wrap mb-4">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $r=>$l)
    <a href="{{ route('admin.reports.'.$r) }}" class="btn btn-sm {{ request()->routeIs('admin.reports.'.$r)?'btn-sky':'btn-outline-secondary' }}" style="border-radius:8px;">{{ $l }}</a>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3">{{ $total }}</div><div class="text-muted small">Total Domains</div></div></div>
    <div class="col-md-6"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-success">{{ $active }}</div><div class="text-muted small">Active Domains</div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Domains by TLD</h6>
            @foreach($byTld as $row)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <span class="fw-bold text-sky">{{ $row->tld }}</span>
                <span class="badge bg-light text-dark border">{{ $row->count }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Expiring Soon <span class="badge bg-danger ms-2">{{ $expiringSoon->total() }}</span></h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" style="font-size:.85rem;">
                    <thead class="table-light"><tr><th>Domain</th><th>Customer</th><th>Expires</th><th>Days Left</th></tr></thead>
                    <tbody>
                        @forelse($expiringSoon as $d)
                        <tr>
                            <td class="fw-semibold">{{ $d->domain_name }}</td>
                            <td>{{ $d->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->expiry_date)->format('d M Y') }}</td>
                            <td class="{{ \Carbon\Carbon::parse($d->expiry_date)->diffInDays() < 7 ? 'text-danger fw-bold' : 'text-warning' }}">
                                {{ \Carbon\Carbon::parse($d->expiry_date)->diffInDays() }} days
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">No domains expiring soon.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $expiringSoon->links() }}</div>
        </div>
    </div>
</div>
@endsection
