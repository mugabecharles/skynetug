@extends('layouts.dashboard')
@section('page_title', 'Affiliate Program')

@section('content')
<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Affiliate</th><th>Referrals</th><th>Total Earned</th><th>Balance</th><th>Total Paid</th><th>Status</th><th>Pending Payouts</th></tr>
            </thead>
            <tbody>
                @forelse($affiliates as $aff)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $aff->user->name }}</div>
                        <div class="text-muted small">{{ $aff->user->email }}</div>
                        <div class="font-monospace" style="font-size:.72rem;color:#0066FF;">{{ $aff->referral_code }}</div>
                    </td>
                    <td>{{ $aff->total_referrals }}</td>
                    <td>$ {{ number_format($aff->total_earned) }}</td>
                    <td class="fw-semibold">$ {{ number_format($aff->balance) }}</td>
                    <td>$ {{ number_format($aff->total_paid) }}</td>
                    <td>
                        @if($aff->status==='active')<span class="badge bg-success-subtle text-success rounded-pill">Active</span>
                        @else<span class="badge bg-secondary-subtle text-secondary rounded-pill">Suspended</span>@endif
                    </td>
                    <td>
                        @php $pendingPayouts = $aff->payouts->where('status','pending'); @endphp
                        @if($pendingPayouts->count())
                        <div class="d-flex flex-column gap-1">
                            @foreach($pendingPayouts as $payout)
                            <div class="d-flex align-items-center gap-2">
                                <span class="small">$ {{ number_format($payout->amount) }}</span>
                                <form method="POST" action="{{ route('admin.affiliates.payout.approve',$payout->id) }}">
                                    @csrf
                                    <button class="btn btn-xs btn-success" style="font-size:.7rem;padding:.2rem .6rem;border-radius:6px;">Approve</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <span class="text-muted small">None</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No affiliates enrolled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $affiliates->links() }}</div>
</div>
@endsection
