@extends('layouts.dashboard')
@section('page_title', 'Affiliate Program')

@section('content')
@if(!$affiliate)
{{-- Not enrolled --}}
<div class="row justify-content-center">
    <div class="col-lg-6 text-center py-5">
        <div class="mb-4" style="width:80px;height:80px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
            <i class="bi bi-share fs-2 text-sky"></i>
        </div>
        <h4 class="fw-bold mb-2">Join Our Affiliate Program</h4>
        <p class="text-muted mb-4">Earn <strong>10% commission</strong> for every customer you refer to SkyNetug. Share your unique link and get paid when they purchase hosting or domains.</p>
        <div class="row g-3 mb-4">
            @foreach(['Earn 10% per referral','No limit on earnings','Paid via Mobile Money','Real-time tracking'] as $b)
            <div class="col-6"><div class="p-3 bg-light rounded-3 small fw-semibold"><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $b }}</div></div>
            @endforeach
        </div>
        <form method="POST" action="{{ route('dashboard.affiliate.enroll') }}">
            @csrf
            <button class="btn btn-sky btn-lg px-5">
                <i class="bi bi-person-plus me-2"></i>Enroll Now — It's Free
            </button>
        </form>
    </div>
</div>

@else
{{-- Enrolled --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Referrals',  'value'=>$affiliate->total_referrals,                          'icon'=>'bi-people',       'color'=>'#0066FF'],
        ['label'=>'Total Earned',     'value'=>'$ '.number_format($affiliate->total_earned),       'icon'=>'bi-cash-coin',    'color'=>'#00C896'],
        ['label'=>'Available Balance','value'=>'$ '.number_format($affiliate->balance),            'icon'=>'bi-wallet2',      'color'=>'#f59e0b'],
        ['label'=>'Total Paid Out',   'value'=>'$ '.number_format($affiliate->total_paid),         'icon'=>'bi-check-circle', 'color'=>'#6366f1'],
    ] as $stat)
    <div class="col-6 col-md-3">
        <div class="bg-white rounded-3 border p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:44px;height:44px;background:{{ $stat['color'] }}18;">
                    <i class="bi {{ $stat['icon'] }}" style="color:{{ $stat['color'] }};font-size:1.1rem;"></i>
                </div>
                <div>
                    <div class="fw-bold lh-1 mb-1">{{ $stat['value'] }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $stat['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Referral Link --}}
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-link me-2 text-sky"></i>Your Referral Link</h6>
            <div class="input-group mb-2">
                <input type="text" class="form-control font-monospace" id="refLink"
                    value="{{ url('/register?ref=' . $affiliate->referral_code) }}" readonly>
                <button class="btn btn-sky" onclick="copyRefLink()"><i class="bi bi-clipboard me-1"></i>Copy</button>
            </div>
            <p class="text-muted small mb-0">Share this link. When someone registers and purchases a service, you earn {{ $affiliate->commission_rate }}% commission.</p>
        </div>

        {{-- Referrals Table --}}
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Recent Referrals</h6>
            @if($affiliate->referrals->isEmpty())
                <p class="text-muted small">No referrals yet. Start sharing your link!</p>
            @else
            <table class="table table-sm align-middle mb-0" style="font-size:0.85rem;">
                <thead class="table-light">
                    <tr><th>Customer</th><th>Date</th><th>Commission</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($affiliate->referrals->take(10) as $ref)
                    <tr>
                        <td>{{ $ref->referredUser?->name ?? 'Anonymous' }}</td>
                        <td>{{ $ref->created_at->format('d M Y') }}</td>
                        <td>$ {{ number_format($ref->commission) }}</td>
                        <td>
                            @php $rc = match($ref->status) { 'approved'=>'success','paid'=>'success','pending'=>'warning', default=>'secondary' }; @endphp
                            <span class="badge bg-{{ $rc }}-subtle text-{{ $rc }} rounded-pill">{{ ucfirst($ref->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Payout Panel --}}
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4 mb-4">
            <h6 class="fw-bold mb-1">Request Payout</h6>
            <p class="text-muted small mb-3">Minimum payout: <strong>$ 50,000</strong></p>
            @if($affiliate->balance >= 50000)
            <form method="POST" action="{{ route('dashboard.affiliate.payout') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Payment Method</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="mtn_mobile_money">MTN Mobile Money</option>
                        <option value="airtel_money">Airtel Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <button class="btn btn-sky w-100 btn-sm py-2">
                    Request $ {{ number_format($affiliate->balance) }}
                </button>
            </form>
            @else
            <div class="alert alert-warning small p-2 mb-0">
                You need $ {{ number_format(50000 - $affiliate->balance) }} more to reach the minimum payout threshold.
            </div>
            @endif
        </div>

        {{-- Payout History --}}
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Payout History</h6>
            @forelse($affiliate->payouts->take(5) as $payout)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2 small">
                <div>
                    <div class="fw-semibold">$ {{ number_format($payout->amount) }}</div>
                    <div class="text-muted">{{ $payout->created_at->format('d M Y') }}</div>
                </div>
                @php $payc = match($payout->status) { 'paid'=>'success','pending'=>'warning','approved'=>'info','rejected'=>'danger', default=>'secondary' }; @endphp
                <span class="badge bg-{{ $payc }}-subtle text-{{ $payc }}">{{ ucfirst($payout->status) }}</span>
            </div>
            @empty
            <p class="text-muted small">No payouts yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
function copyRefLink() {
    const el = document.getElementById('refLink');
    el.select();
    navigator.clipboard.writeText(el.value).then(() => {
        const btn = el.nextElementSibling;
        btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied!';
        setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copy', 2000);
    });
}
</script>
@endpush
@endsection
