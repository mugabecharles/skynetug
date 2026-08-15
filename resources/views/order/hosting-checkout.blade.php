@extends('layouts.app')
@section('title', 'Order ' . $package->name)

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:2.5rem 0 2rem;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size:.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('hosting.shared') }}" class="text-white-50">Hosting</a></li>
                <li class="breadcrumb-item active text-white">Order</li>
            </ol>
        </nav>
        <h4 class="fw-bold text-white mb-0">Order: {{ $package->name }}</h4>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center g-5">

        {{-- Order Form --}}
        <div class="col-lg-7">
            <form method="POST" action="{{ route('order.checkout') }}" id="checkoutForm">
                @csrf

                {{-- Package Selection is pre-filled --}}
                <input type="hidden" name="items[0][type]" value="hosting">
                <input type="hidden" name="items[0][package_id]" value="{{ $package->id }}">
                <input type="hidden" name="items[0][name]" value="{{ $package->name }}">
                <input type="hidden" name="items[0][quantity]" value="1">

                {{-- Domain for hosting --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-globe me-2 text-sky"></i>Domain Name</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Your Domain <span class="text-danger">*</span></label>
                        <input type="text" name="items[0][meta][domain]" class="form-control"
                            placeholder="yourdomain.com" required>
                        <div class="form-text">Enter the domain you want to host. If you don't have one yet, <a href="{{ route('domains') }}">register it first</a>.</div>
                    </div>
                </div>

                {{-- Billing cycle --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-sky"></i>Billing Cycle</h6>
                    <div class="row g-3">
                        @foreach([
                            ['value'=>'monthly',    'label'=>'Monthly',  'price'=>$package->price_monthly,    'badge'=>''],
                            ['value'=>'yearly',     'label'=>'Yearly',   'price'=>$package->price_yearly,     'badge'=>'Save 20%'],
                            ['value'=>'biennially', 'label'=>'2 Years',  'price'=>$package->price_biennially, 'badge'=>'Save 30%'],
                        ] as $cycle)
                        @if($cycle['price'] > 0)
                        <div class="col-md-4">
                            <label class="d-block border rounded-3 p-3 text-center" style="cursor:pointer;" id="cycle-{{ $cycle['value'] }}">
                                <input type="radio" name="items[0][billing_cycle]" value="{{ $cycle['value'] }}"
                                    class="d-none cycle-radio"
                                    {{ $cycle['value'] === 'yearly' ? 'checked' : '' }}>
                                <div class="fw-bold">{{ $cycle['label'] }}</div>
                                <div class="text-sky fw-bold fs-5 mt-1">$ {{ number_format($cycle['price']) }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $cycle['value'] === 'monthly' ? 'per month' : 'total' }}
                                </div>
                                @if($cycle['badge'])
                                <span class="badge bg-success mt-1" style="font-size:.7rem;">{{ $cycle['badge'] }}</span>
                                @endif
                            </label>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="bg-white rounded-3 border p-4 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2 text-sky"></i>Promo Code</h6>
                    <div class="input-group" style="max-width:360px;">
                        <input type="text" name="coupon_code" id="couponCode" class="form-control" placeholder="Enter coupon code">
                        <button type="button" class="btn btn-outline-primary" onclick="applyCoupon()">Apply</button>
                    </div>
                    <div id="couponMsg" class="small mt-2"></div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-sky w-100 py-3 fw-bold fs-5">
                    <i class="bi bi-lock me-2"></i>Proceed to Payment
                </button>
                <p class="text-muted text-center small mt-2">
                    <i class="bi bi-shield-check me-1 text-success"></i>Secure checkout. Activates within 5 minutes of payment.
                </p>
            </form>
        </div>

        {{-- Order Summary --}}
        <div class="col-lg-4">
            <div class="bg-white rounded-3 border p-4 mb-4" style="position:sticky;top:80px;">
                <h6 class="fw-bold mb-3">Order Summary</h6>

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-semibold">{{ $package->name }}</div>
                        <div class="text-muted small">{{ ucfirst($package->type) }} Hosting</div>
                    </div>
                    <span class="fw-bold text-sky" id="summaryPrice">$ {{ number_format($package->price_yearly) }}</span>
                </div>

                <hr>

                <ul class="list-unstyled mb-3" style="font-size:.875rem;">
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>
                        {{ $package->disk_space_mb == 0 ? 'Unlimited' : number_format($package->disk_space_mb/1024,0).' GB' }} SSD Storage
                    </li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Unlimited Bandwidth</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>
                        {{ $package->email_accounts == 0 ? 'Unlimited' : $package->email_accounts }} Email Accounts
                    </li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free SSL Certificate</li>
                    @if($package->softaculous_included)
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>1-Click WordPress Install</li>
                    @endif
                    @if($package->backup_included)
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free Daily Backups</li>
                    @endif
                    @foreach((array)$package->features as $feat)
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $feat }}</li>
                    @endforeach
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>24/7 Support</li>
                </ul>

                <div class="p-3 rounded-3" style="background:#f0f9ff;border:1px solid #bae6fd;">
                    <div class="d-flex gap-2 align-items-center">
                        <i class="bi bi-phone-fill text-primary"></i>
                        <span class="small fw-semibold">Pay with Mobile Money</span>
                    </div>
                    <p class="text-muted small mb-0 mt-1">MTN Mobile Money &amp; Airtel Money accepted</p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
// Highlight selected billing cycle
const cycleRadios = document.querySelectorAll('.cycle-radio');
const prices = {
    monthly:    {{ $package->price_monthly }},
    yearly:     {{ $package->price_yearly }},
    biennially: {{ $package->price_biennially }},
};

function updateCycleUI() {
    cycleRadios.forEach(r => {
        const label = document.getElementById('cycle-' + r.value);
        if (r.checked) {
            label.style.borderColor = '#0066FF';
            label.style.background  = '#f0f4ff';
            document.getElementById('summaryPrice').textContent = '$ ' + prices[r.value].toLocaleString();
        } else {
            label.style.borderColor = '';
            label.style.background  = '';
        }
    });
}

cycleRadios.forEach(r => r.addEventListener('change', updateCycleUI));
updateCycleUI();

function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    const msg  = document.getElementById('couponMsg');
    if (!code) return;
    msg.textContent = 'Validating...';
    msg.className   = 'small mt-2 text-muted';
    // Coupon validation happens server-side on checkout submission
    msg.textContent = 'Coupon "' + code + '" will be applied at checkout.';
    msg.className   = 'small mt-2 text-success';
}
</script>
@endpush
@endsection

