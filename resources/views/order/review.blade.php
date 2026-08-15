@extends('layouts.app')
@section('title', 'Review Your Order')

@push('styles')
<style>
/* ── Steps bar ─────────────────────────────────────────────── */
.steps-bar {
    display: flex;
    align-items: stretch;
    background: #fff;
    border: 1px solid #e8ecf0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 2rem;
}
.step-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    border-right: 1px solid #e8ecf0;
    position: relative;
    background: #fff;
}
.step-item:last-child { border-right: none; }
.step-item.done   { background: #f0fdf4; }
.step-item.active { background: #eff6ff; }

.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    right: -14px; top: 50%;
    transform: translateY(-50%);
    width: 0; height: 0;
    border-top: 18px solid transparent;
    border-bottom: 18px solid transparent;
    border-left: 14px solid #e8ecf0;
    z-index: 2;
}
.step-item.done::after   { border-left-color: #bbf7d0; }
.step-item.active::after { border-left-color: #bfdbfe; }

.step-num {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .85rem; flex-shrink: 0;
}
.step-item.done   .step-num { background: #22c55e; color: #fff; }
.step-item.active .step-num { background: #0066FF; color: #fff; }

.step-label { font-weight: 700; font-size: .9rem; line-height: 1.2; }
.step-sub   { font-size: .75rem; color: #6b7280; margin-top: 2px; }

/* ── Order summary table ───────────────────────────────────── */
.order-table { width: 100%; border-collapse: collapse; }
.order-table td { padding: 14px 16px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.order-table tr:last-child td { border-bottom: none; }
.item-name { font-weight: 700; font-size: .95rem; color: #0A0F1E; }
.item-sub  { font-size: .78rem; color: #e53935; font-weight: 600; margin-top: 3px; }
.item-price { font-weight: 700; color: #e53935; font-size: .95rem; text-align: right; white-space: nowrap; }
.item-free  { font-weight: 700; color: #22c55e; font-size: .95rem; text-align: right; }

.total-row td {
    padding: 14px 16px;
    font-weight: 700;
    font-size: 1rem;
    color: #e53935;
    text-align: right;
    border-top: 2px solid #f3f4f6;
}

/* ── Payment panel ─────────────────────────────────────────── */
.payment-panel {
    background: #fff;
    border: 1.5px solid #e8ecf0;
    border-radius: 14px;
    padding: 24px;
    position: sticky;
    top: 80px;
}
.payment-methods {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
    margin: 20px 0;
}
.pm-badge {
    border: 1px solid #e8ecf0;
    border-radius: 8px;
    padding: 8px 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .8rem;
    font-weight: 600;
    color: #374151;
    background: #f8fafc;
}
.btn-checkout {
    width: 100%;
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 15px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .15s;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.btn-checkout:hover { background: #16a34a; }

/* ── Empty cart btn ────────────────────────────────────────── */
.btn-empty {
    width: 100%;
    background: #e53935;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 12px 16px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 12px;
}
.btn-empty:hover { background: #c62828; }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div style="background:#fff;border-bottom:1px solid #e8ecf0;padding:1.5rem 0;text-align:center;">
    <h4 class="fw-bold mb-1">My Cart</h4>
    <p class="text-muted mb-0" style="font-size:.9rem;">You're almost there! Complete your order</p>
</div>

<div class="container py-4">

    {{-- Subtitle --}}
    <p class="text-center fw-bold mb-4" style="color:#e53935;font-size:1.1rem;">
        Great ! Now review your order, then continue to checkout.
    </p>

    {{-- ── Steps bar ─────────────────────────────────────────── --}}
    <div class="steps-bar">
        <div class="step-item done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div>
                <div class="step-label">Hosting</div>
                <div class="step-sub">Choose your hosting options</div>
            </div>
        </div>
        <div class="step-item done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div>
                <div class="step-label">Domain</div>
                <div class="step-sub">Enter domain information</div>
            </div>
        </div>
        <div class="step-item active">
            <div class="step-num">3</div>
            <div>
                <div class="step-label" style="color:#0066FF;">Review Order</div>
                <div class="step-sub">Verify order details</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── LEFT: Order summary ─────────────────────────────── --}}
        <div class="col-lg-7">
            <div class="bg-white rounded-3 border overflow-hidden">

                <div style="padding:18px 20px;border-bottom:1px solid #f3f4f6;font-weight:700;font-size:1rem;text-align:center;">
                    Order Summary
                </div>

                <table class="order-table">
                    @foreach($cart as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ strtoupper($item['name']) }}</div>
                            <div class="item-sub">
                                {{ ucfirst($item['type']) }} —
                                {{ $item['billing_cycle'] === 'monthly' ? '1 month' : ($item['billing_cycle'] === 'biennially' ? '2 year' : '1 year') }}
                                subscription
                                @if(!empty($item['meta']['domain']))
                                    · {{ $item['meta']['domain'] }}
                                @endif
                            </div>
                        </td>
                        <td class="item-price">$ {{ number_format($item['price']) }}</td>
                    </tr>
                    @endforeach

                    {{-- Free SSL --}}
                    <tr>
                        <td>
                            <div class="item-name">Let's Encrypt</div>
                            <div class="item-sub">SSL certificate</div>
                        </td>
                        <td class="item-free">FREE</td>
                    </tr>

                    {{-- Discount if coupon --}}
                    @if($total['discount'] > 0)
                    <tr>
                        <td>
                            <div class="item-name">Discount
                                @if($total['coupon'])
                                <span class="badge bg-success-subtle text-success" style="font-size:.7rem;">{{ $total['coupon']['code'] }}</span>
                                @endif
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:700;color:#22c55e;">− $ {{ number_format($total['discount']) }}</td>
                    </tr>
                    @endif
                </table>

                {{-- Total --}}
                <table class="order-table">
                    <tr class="total-row">
                        <td style="text-align:left;color:#374151;">Total :</td>
                        <td>$ {{ number_format($total['total']) }}</td>
                    </tr>
                </table>

                {{-- Empty Cart --}}
                <div style="padding:12px 16px;">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button type="submit" class="btn-empty" onclick="return confirm('Clear your cart?')">
                            <span><i class="bi bi-trash me-2"></i>Empty Cart</span>
                            <span style="background:rgba(255,255,255,.25);border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:.8rem;">✕</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Coupon --}}
            <div class="mt-3 bg-white rounded-3 border p-3">
                @if($total['coupon'])
                <div class="d-flex align-items-center justify-content-between">
                    <span class="small fw-semibold text-success"><i class="bi bi-tag-fill me-1"></i>{{ $total['coupon']['code'] }} applied</span>
                    <form method="POST" action="{{ route('cart.coupon.remove') }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger p-0">Remove</button>
                    </form>
                </div>
                @else
                <div id="couponToggle">
                    <button type="button" class="btn btn-sm btn-link text-muted p-0"
                        onclick="document.getElementById('couponForm').style.display='flex';this.style.display='none'">
                        <i class="bi bi-tag me-1"></i>Have a promo code?
                    </button>
                    <div id="couponForm" style="display:none;">
                        <form method="POST" action="{{ route('cart.coupon') }}" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" class="form-control form-control-sm" placeholder="Promo code" required style="border-radius:8px;">
                            <button type="submit" class="btn btn-sm btn-sky px-3">Apply</button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── RIGHT: Payment panel ─────────────────────────────── --}}
        <div class="col-lg-5">
            <div class="payment-panel">
                <h5 class="fw-bold text-center mb-1">Payment Options</h5>
                <p class="text-muted text-center small mb-3">Choose your preferred payment method at checkout</p>

                {{-- Payment method logos --}}
                <div class="payment-methods">
                    <div class="pm-badge">
                        <span style="width:28px;height:20px;background:#FFD200;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:900;color:#000;">MTN</span>
                        MTN MoMo
                    </div>
                    <div class="pm-badge">
                        <span style="width:28px;height:20px;background:#FF0000;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:900;color:#fff;">AIR</span>
                        Airtel Money
                    </div>
                    <div class="pm-badge">
                        <i class="bi bi-credit-card text-primary"></i>
                        Visa / MC
                    </div>
                    <div class="pm-badge">
                        <i class="bi bi-bank text-info"></i>
                        Pesapal
                    </div>
                </div>

                <hr class="my-3">

                {{-- Order total --}}
                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <span class="text-muted">Order Total</span>
                    <span class="fw-bold fs-5" style="color:#0066FF;">$ {{ number_format($total['total']) }}</span>
                </div>

                {{-- Proceed to checkout --}}
                @auth
                <form method="POST" action="{{ route('order.checkout') }}">
                    @csrf
                    @foreach($cart as $key => $item)
                        @php $i = $loop->index; @endphp
                        <input type="hidden" name="items[{{ $i }}][type]"             value="{{ $item['type'] }}">
                        <input type="hidden" name="items[{{ $i }}][package_id]"       value="{{ $item['package_id'] ?? '' }}">
                        <input type="hidden" name="items[{{ $i }}][name]"             value="{{ $item['name'] }}">
                        <input type="hidden" name="items[{{ $i }}][billing_cycle]"    value="{{ $item['billing_cycle'] ?? 'yearly' }}">
                        <input type="hidden" name="items[{{ $i }}][price]"            value="{{ $item['price'] }}">
                        <input type="hidden" name="items[{{ $i }}][quantity]"         value="1">
                        <input type="hidden" name="items[{{ $i }}][meta][domain]"     value="{{ $item['meta']['domain'] ?? '' }}">
                    @endforeach
                    @if($total['coupon'])
                    <input type="hidden" name="coupon_code" value="{{ $total['coupon']['code'] }}">
                    @endif
                    <button type="submit" class="btn-checkout">
                        <span><i class="bi bi-lock me-2"></i>Proceed to Check Out</span>
                        <i class="bi bi-check-lg"></i>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}?redirect={{ urlencode(route('cart.review')) }}"
                   class="btn-checkout text-decoration-none">
                    <span><i class="bi bi-lock me-2"></i>Sign In to Checkout</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                @endauth

                <p class="text-muted text-center mt-3" style="font-size:.75rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Secure checkout · Activates within 5 minutes of payment
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
