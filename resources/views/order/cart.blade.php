@extends('layouts.app')
@section('title', 'Shopping Cart')

@push('styles')
<style>
.cart-item-card {
    background: #fff;
    border: 1.5px solid #e8ecf0;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    margin-bottom: .75rem;
    transition: border-color .2s;
}
.cart-item-card:hover { border-color: #c7d9ff; }
.cycle-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1.5px solid #e8ecf0;
    border-radius: 8px;
    padding: 4px 12px;
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    background: #fff;
    transition: all .15s;
    color: #374151;
}
.cycle-pill.active,
.cycle-pill:hover {
    border-color: #0066FF;
    color: #0066FF;
    background: #f0f4ff;
}
.cart-summary-box {
    background: #fff;
    border: 1.5px solid #e8ecf0;
    border-radius: 16px;
    padding: 1.5rem;
    position: sticky;
    top: 80px;
}
.cart-empty-state { text-align: center; padding: 5rem 1rem; }
.badge-type-hosting { background: #dbeafe; color: #1d4ed8; }
.badge-type-domain  { background: #d1fae5; color: #065f46; }
.badge-type-ssl     { background: #fef3c7; color: #92400e; }
.badge-type-email   { background: #f3e8ff; color: #6b21a8; }
.domain-missing { background: #fff7ed; border: 1.5px dashed #f59e0b; border-radius: 8px; padding: .5rem .85rem; font-size: .8rem; color: #92400e; }
</style>
@endpush

@section('content')

{{-- Hero bar --}}
<div style="background:linear-gradient(135deg,rgba(180,60,60,.18),rgba(200,100,60,.13));border-bottom:1px solid #e8ecf0;padding:2rem 0 1.5rem;">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-2">
            <a href="{{ route('home') }}" class="text-muted small text-decoration-none"><i class="bi bi-house me-1"></i>Home</a>
            <span class="text-muted">/</span>
            <span class="small fw-semibold" style="color:#e53935;">Shopping Cart</span>
        </div>
        <h4 class="fw-bold mb-0" style="color:#e53935;">
            My Cart
            @if(count($cart) > 0)
                <span class="badge" style="background:rgba(229,57,53,.15);color:#e53935;font-size:.65rem;border-radius:20px;padding:.3rem .8rem;vertical-align:middle;">
                    {{ count($cart) }} {{ Str::plural('item', count($cart)) }}
                </span>
            @endif
        </h4>
    </div>
</div>

<div class="container py-4">

@if(empty($cart))
{{-- ── EMPTY STATE ──────────────────────────────────────────────── --}}

{{-- Hero banner --}}
<div style="background:linear-gradient(135deg,rgba(180,60,60,.18),rgba(200,100,60,.13));border-bottom:1px solid #e8ecf0;padding:2.5rem 0;text-align:center;">
    <h3 class="fw-bold mb-1" style="color:#e53935;">My Cart</h3>
    <p class="text-muted mb-0" style="font-size:.9rem;">Your shopping cart is currently empty. Time to go shopping for low-priced domains and services...</p>
</div>

<div class="container py-5">
    {{-- Empty icon + message --}}
    <div class="text-center py-4 mb-5">
        <i class="bi bi-basket2" style="font-size:4rem;color:#e53935;"></i>
        <h5 class="fw-bold mt-3" style="color:#e53935;max-width:480px;margin:12px auto 0;">
            Your shopping cart is currently empty, time to go shopping for low-priced domains and services..
        </h5>
    </div>

    {{-- Service categories --}}
    <div class="text-center mb-4">
        <span style="font-size:.72rem;font-weight:800;letter-spacing:.15em;text-transform:uppercase;color:#999;">
            Everything you need from start-up to success
        </span>
    </div>

    <div class="row g-3">
        @foreach([
            ['icon'=>'bi-globe2',       'label'=>'Domains',               'url'=> route('domains')],
            ['icon'=>'bi-server',       'label'=>'cPanel Shared Hosting',  'url'=> route('hosting.shared')],
            ['icon'=>'bi-server',       'label'=>'Plesk Shared Hosting',   'url'=> route('hosting.shared')],
            ['icon'=>'bi-server',       'label'=>'cPanel Reseller Hosting','url'=> route('reseller')],
            ['icon'=>'bi-server',       'label'=>'Plesk Reseller Hosting', 'url'=> route('reseller')],
            ['icon'=>'bi-envelope',     'label'=>'Seamless Email Hosting', 'url'=> route('hosting.email')],
            ['icon'=>'bi-cloud-fill',   'label'=>'Cloud Servers',          'url'=> route('hosting.vps')],
            ['icon'=>'bi-shield-lock',  'label'=>'Sectigo SSL Certificates','url'=> route('ssl')],
        ] as $svc)
        <div class="col-6 col-md-3">
            <a href="{{ $svc['url'] }}" class="d-flex align-items-center gap-3 bg-white border rounded-3 p-3 text-decoration-none"
               style="transition:all .15s;color:#374151;"
               onmouseover="this.style.borderColor='#e53935';this.style.color='#e53935'"
               onmouseout="this.style.borderColor='';this.style.color='#374151'">
                <span style="width:36px;height:36px;border-radius:50%;border:2px solid #e53935;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi {{ $svc['icon'] }}" style="font-size:1rem;color:#e53935;"></i>
                </span>
                <span class="fw-semibold" style="font-size:.875rem;">{{ $svc['label'] }}</span>
            </a>
        </div>
        @endforeach
    </div>
</div>

@else
{{-- ── CART CONTENT ─────────────────────────────────────────────── --}}
<div class="row g-4">

    {{-- Left: Cart Items --}}
    <div class="col-lg-8">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-muted small">Review your items below</span>
            <div class="d-flex gap-2">
                <a href="{{ route('hosting.shared') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus me-1"></i>Add Hosting
                </a>
                <a href="{{ route('domains') }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-globe me-1"></i>Add Domain
                </a>
            </div>
        </div>

        @foreach($cart as $key => $item)
        <div class="cart-item-card" id="item-{{ $key }}">
            <div class="d-flex align-items-start gap-3">

                {{-- Icon --}}
                <div style="width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                     background:{{ $item['type']==='hosting' ? '#EFF6FF' : ($item['type']==='domain' ? '#ECFDF5' : '#FEF3C7') }};">
                    <i class="bi {{ $item['type']==='hosting' ? 'bi-server' : ($item['type']==='domain' ? 'bi-globe' : ($item['type']==='ssl' ? 'bi-shield-lock' : 'bi-envelope')) }}"
                       style="font-size:1.2rem;color:{{ $item['type']==='hosting' ? '#0066FF' : ($item['type']==='domain' ? '#00C896' : '#F59E0B') }};"></i>
                </div>

                {{-- Details --}}
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <span class="fw-bold">{{ $item['name'] }}</span>
                        <span class="badge badge-type-{{ $item['type'] }} small" style="border-radius:6px;padding:.2rem .6rem;font-size:.7rem;font-weight:600;text-transform:capitalize;">
                            {{ $item['type'] }}
                        </span>
                    </div>

                    {{-- Domain attached to this item --}}
                    @php $domainVal = $item['meta']['domain'] ?? null; @endphp
                    @if($domainVal)
                        <div class="text-muted small mb-2">
                            <i class="bi bi-globe me-1"></i>{{ $domainVal }}
                        </div>
                    @elseif($item['type'] === 'hosting')
                        {{-- Prompt user to add domain name --}}
                        <div class="domain-missing mb-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            No domain attached.
                            <a href="#" data-bs-toggle="modal" data-bs-target="#domainModal-{{ $key }}" class="fw-semibold text-warning">Add domain name</a>
                        </div>
                        {{-- Domain-entry modal --}}
                        <div class="modal fade" id="domainModal-{{ $key }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title fw-bold">Attach Domain to {{ $item['name'] }}</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('cart.attach.domain', $key) }}">
                                            @csrf
                                            @method('PATCH')
                                            <label class="form-label small fw-semibold">Domain Name <span class="text-danger">*</span></label>
                                            <input type="text" name="domain" class="form-control mb-3"
                                                   placeholder="yourdomain.com" required>
                                            <div class="form-text mb-3">
                                                Don't have a domain yet?
                                                <a href="{{ route('domains') }}" target="_blank">Search & add one</a> — then return here.
                                            </div>
                                            <button type="submit" class="btn btn-sky w-100">Save Domain</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Billing cycle changer (hosting only, db package) --}}
                    @if($item['type'] === 'hosting' && !empty($item['package_id']))
                    <form method="POST" action="{{ route('cart.update', $key) }}" class="d-inline" id="cycleForm-{{ $key }}">
                        @csrf
                        @method('PATCH')
                        <div class="d-flex gap-2 flex-wrap align-items-center mt-2">
                            <span class="text-muted small me-1">Billing:</span>
                            @foreach(['monthly'=>'Monthly','yearly'=>'Yearly','biennially'=>'2 Years'] as $cycleVal => $cycleLabel)
                            <button type="submit" name="billing_cycle" value="{{ $cycleVal }}"
                                    class="cycle-pill {{ ($item['billing_cycle'] ?? 'yearly') === $cycleVal ? 'active' : '' }}">
                                {{ $cycleLabel }}
                                @if($cycleVal === 'yearly') <span style="color:#00C896;font-size:.7rem;">Save 20%</span> @endif
                                @if($cycleVal === 'biennially') <span style="color:#00C896;font-size:.7rem;">Save 30%</span> @endif
                            </button>
                            @endforeach
                        </div>
                    </form>
                    @else
                    <div class="text-muted small mt-1">
                        {{ ucfirst($item['billing_cycle'] ?? 'yearly') }} billing
                    </div>
                    @endif
                </div>

                {{-- Price + Remove --}}
                <div class="text-end flex-shrink-0">
                    <div class="fw-bold text-sky fs-6">$ {{ number_format($item['price'], 2) }}</div>
                    <div class="text-muted" style="font-size:.75rem;">
                        @if(($item['billing_cycle'] ?? '') === 'monthly') /mo
                        @elseif(($item['billing_cycle'] ?? '') === 'biennially') /2 yrs
                        @else /yr @endif
                    </div>
                    <form method="POST" action="{{ route('cart.remove', $key) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" style="font-size:.8rem;"
                                onclick="return confirm('Remove this item?')">
                            <i class="bi bi-trash me-1"></i>Remove
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach

        {{-- Clear cart --}}
        <div class="text-end mt-1">
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-muted"
                        onclick="return confirm('Clear all items from cart?')">
                    <i class="bi bi-x-circle me-1"></i>Clear Cart
                </button>
            </form>
        </div>
    </div>

    {{-- Right: Order Summary --}}
    <div class="col-lg-4">
        <div class="cart-summary-box">
            <h6 class="fw-bold mb-4">Order Summary</h6>

            {{-- Line items --}}
            @foreach($cart as $item)
            <div class="d-flex justify-content-between align-items-center mb-2 small">
                <span class="text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $item['name'] }}
                </span>
                <span class="fw-semibold">$ {{ number_format($item['price'], 2) }}</span>
            </div>
            @endforeach

            <hr class="my-3">

            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Subtotal</span>
                <span>$ {{ number_format($total['subtotal'], 2) }}</span>
            </div>

            @if($total['discount'] > 0)
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-success">
                    <i class="bi bi-tag me-1"></i>Discount
                    @if($total['coupon'])
                        <span class="badge bg-success-subtle text-success" style="font-size:.7rem;">{{ $total['coupon']['code'] }}</span>
                    @endif
                </span>
                <span class="text-success fw-semibold">− $ {{ number_format($total['discount'], 2) }}</span>
            </div>
            @endif

            @if($total['tax'] > 0)
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Tax</span>
                <span>$ {{ number_format($total['tax'], 2) }}</span>
            </div>
            @endif

            <div class="d-flex justify-content-between fw-bold border-top pt-3 mt-1">
                <span class="fs-6">Total</span>
                <span class="text-sky fs-5">$ {{ number_format($total['total'], 2) }}</span>
            </div>

            {{-- Coupon section --}}
            <div class="mt-4">
                @if($total['coupon'])
                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 mb-2"
                     style="background:#ecfdf5;border:1px solid #a7f3d0;">
                    <span class="small fw-semibold text-success">
                        <i class="bi bi-tag-fill me-1"></i>{{ $total['coupon']['code'] }}
                    </span>
                    <form method="POST" action="{{ route('cart.coupon.remove') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" style="font-size:.75rem;">Remove</button>
                    </form>
                </div>
                @else
                <button class="btn btn-sm btn-link text-muted p-0 mb-2" id="couponToggle"
                        onclick="document.getElementById('couponFormWrap').style.display='block';this.style.display='none'">
                    <i class="bi bi-tag me-1"></i>Have a promo code?
                </button>
                <div id="couponFormWrap" style="display:none;">
                    <form method="POST" action="{{ route('cart.coupon') }}" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control form-control-sm"
                               placeholder="Promo code" required style="border-radius:8px;">
                        <button type="submit" class="btn btn-sm btn-sky px-3" style="white-space:nowrap;">Apply</button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Checkout --}}
            <div class="mt-4">
                @auth
                    {{-- Check if any hosting item is missing a domain --}}
                    @php
                        $missingDomain = collect($cart)->contains(fn($i) =>
                            $i['type'] === 'hosting' && empty($i['meta']['domain'])
                        );
                    @endphp
                    @if($missingDomain)
                    <div class="alert alert-warning py-2 small mb-3" style="border-radius:8px;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Please attach a domain to each hosting item before checking out.
                    </div>
                    @endif
                    <form method="POST" action="{{ route('order.checkout') }}" id="checkoutForm">
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
                        <button type="submit" class="btn btn-sky w-100 py-3 fw-bold fs-6"
                                {{ $missingDomain ? 'disabled' : '' }}>
                            <i class="bi bi-lock me-2"></i>Proceed to Checkout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(route('cart.index')) }}"
                       class="btn btn-sky w-100 py-3 fw-bold fs-6">
                        <i class="bi bi-lock me-2"></i>Sign In to Checkout
                    </a>
                @endauth

                <p class="text-muted text-center mt-3" style="font-size:.78rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Secure checkout · Activates within 5 minutes
                </p>

                <div class="d-flex justify-content-center gap-2 flex-wrap mt-2">
                    @foreach(['MTN MoMo','Airtel Money','Visa/MC','Pesapal'] as $pm)
                    <span style="border:1px solid #e8ecf0;border-radius:6px;padding:3px 10px;font-size:.7rem;color:#6B7280;">{{ $pm }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Trust badges --}}
        <div class="mt-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e8ecf0;">
            <div class="d-flex flex-column gap-2" style="font-size:.8rem;">
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="bi bi-lightning-fill text-warning"></i>
                    <span>Hosting activated within 5 minutes</span>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="bi bi-globe text-success"></i>
                    <span>Domain active within 24 hours</span>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="bi bi-arrow-counterclockwise text-primary"></i>
                    <span>7-day money-back guarantee</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endif

</div>

{{-- ── Domain upsell: hosting in cart but no domain item ──────────────── --}}
@php
    $hasHosting = collect($cart)->where('type','hosting')->isNotEmpty();
    $hasDomain  = collect($cart)->where('type','domain')->isNotEmpty();
@endphp
@if($hasHosting && !$hasDomain && !empty($cart))
<div class="container pb-4">
    <div class="p-4 rounded-3" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:1.5px dashed #7dd3fc;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-globe fs-3 text-primary"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Add a domain to complete your setup</h6>
                        <p class="text-muted small mb-0">.com from $ 35,000/yr &nbsp;·&nbsp; .ug from $ 55,000/yr &nbsp;·&nbsp; .co.ug from $ 25,000/yr</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('domains') }}" class="btn btn-outline-primary">
                    <i class="bi bi-search me-2"></i>Search Domains
                </a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
