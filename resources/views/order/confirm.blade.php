@extends('layouts.app')
@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="text-center mb-4">
                <div class="mb-3" style="width:64px;height:64px;background:#f0f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <i class="bi bi-cart-check fs-2 text-sky"></i>
                </div>
                <h4 class="fw-bold">Order Summary</h4>
                <p class="text-muted">Order <strong>{{ $order->order_number }}</strong> — please review and complete payment</p>
            </div>

            <div class="row g-4">
                {{-- Order Items --}}
                <div class="col-lg-7">
                    <div class="bg-white rounded-3 border p-4">
                        <h6 class="fw-bold mb-3">Items in Your Order</h6>
                        @foreach($order->orderItems as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div>
                                <p class="fw-semibold mb-0 small">{{ $item->item_name }}</p>
                                <p class="text-muted mb-0" style="font-size:.75rem;">{{ ucfirst($item->billing_cycle) }} billing · {{ $item->service_start }} → {{ $item->service_end }}</p>
                            </div>
                            <span class="fw-semibold small">$ {{ number_format($item->total) }}</span>
                        </div>
                        @endforeach

                        <div class="pt-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Subtotal</span><span>$ {{ number_format($order->subtotal) }}</span>
                            </div>
                            @if($order->discount > 0)
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-success">Discount</span><span class="text-success">- $ {{ number_format($order->discount) }}</span>
                            </div>
                            @endif
                            @if($order->tax > 0)
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Tax</span><span>$ {{ number_format($order->tax) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-1">
                                <span>Total</span><span class="text-sky fs-5">$ {{ number_format($order->total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="col-lg-5">
                    @if($invoice)
                    <div class="bg-white rounded-3 border p-4">
                        <h6 class="fw-bold mb-1">Complete Payment</h6>
                        <p class="text-muted small mb-4">Invoice <strong>{{ $invoice->invoice_number }}</strong> · Due {{ $invoice->date_due }}</p>

                        <form method="POST" action="{{ route('payment.initiate') }}">
                            @csrf
                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Payment Method</label>
                                <div class="d-grid gap-2">
                                    @foreach([
                                        ['mtn_mobile_money','MTN Mobile Money','#FFD200','#000','bi-phone'],
                                        ['airtel_money','Airtel Money','#FF0000','#fff','bi-phone'],
                                        ['flutterwave','Card / Bank','#F5A623','#fff','bi-credit-card'],
                                        ['pesapal','Pesapal','#1E7BCC','#fff','bi-bank'],
                                    ] as [$val,$label,$bg,$fg,$icon])
                                    <label class="d-flex align-items-center gap-3 border rounded-3 p-2" style="cursor:pointer;" id="gw-{{ $val }}">
                                        <input type="radio" name="gateway" value="{{ $val }}" class="gateway-radio" style="accent-color:#0066FF;">
                                        <span class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                              style="width:28px;height:28px;background:{{ $bg }};color:{{ $fg }};font-size:.8rem;">
                                            <i class="bi {{ $icon }}"></i>
                                        </span>
                                        <span class="fw-semibold small">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div id="phoneField" style="display:none;" class="mb-3">
                                <label class="form-label small fw-semibold">Mobile Money Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="256700000000">
                            </div>

                            <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold" id="payBtn" disabled>
                                <i class="bi bi-lock me-2"></i>Pay $ {{ number_format($order->total) }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const mobileGws = ['mtn_mobile_money','airtel_money'];
document.querySelectorAll('.gateway-radio').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('payBtn').disabled = false;
        document.getElementById('phoneField').style.display = mobileGws.includes(r.value) ? 'block' : 'none';
        document.querySelectorAll('label[id^="gw-"]').forEach(l => l.style.borderColor = '');
        document.getElementById('gw-' + r.value).style.borderColor = '#0066FF';
    });
});
</script>
@endpush
@endsection
