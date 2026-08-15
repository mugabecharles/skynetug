@extends('layouts.dashboard')
@section('page_title', 'Affiliate Report')

@section('content')
<div class="d-flex gap-2 flex-wrap mb-4">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $r=>$l)
    <a href="{{ route('admin.reports.'.$r) }}" class="btn btn-sm {{ request()->routeIs('admin.reports.'.$r)?'btn-sky':'btn-outline-secondary' }}" style="border-radius:8px;">{{ $l }}</a>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3">{{ $affiliates->total() }}</div><div class="text-muted small">Total Affiliates</div></div></div>
    <div class="col-md-6"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-success">$ {{ number_format($totalCommissions) }}</div><div class="text-muted small">Total Commissions Earned</div></div></div>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light"><tr><th>Affiliate</th><th>Referrals</th><th>Commissions</th><th>Balance</th><th>Paid Out</th><th>Pending Payouts</th></tr></thead>
            <tbody>
                @forelse($affiliates as $aff)
                <tr>
                    <td><div class="fw-semibold">{{ $aff->user->name }}</div><div class="text-muted small">{{ $aff->user->email }}</div></td>
                    <td>{{ $aff->total_referrals }}</td>
                    <td>$ {{ number_format($aff->total_earned) }}</td>
                    <td class="fw-semibold">$ {{ number_format($aff->balance) }}</td>
                    <td>$ {{ number_format($aff->total_paid) }}</td>
                    <td>{{ $aff->payouts->where('status','pending')->count() }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No affiliates yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $affiliates->links() }}</div>
</div>
@endsection
