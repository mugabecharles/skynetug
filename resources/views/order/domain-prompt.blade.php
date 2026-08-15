@extends('layouts.app')
@section('title', 'Complete Your Order')

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
.step-item.inactive { opacity: .5; }

/* Arrow divider */
.step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    right: -14px;
    top: 50%;
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
    font-weight: 700; font-size: .85rem;
    flex-shrink: 0;
}
.step-item.done   .step-num { background: #22c55e; color: #fff; }
.step-item.active .step-num { background: #0066FF; color: #fff; }
.step-item.inactive .step-num { background: #e5e7eb; color: #6b7280; }

.step-label { font-weight: 700; font-size: .9rem; line-height: 1.2; }
.step-sub   { font-size: .75rem; color: #6b7280; margin-top: 2px; }

/* ── Cart sidebar ─────────────────────────────────────────── */
.cart-sidebar {
    background: #fff;
    border: 1.5px solid #e8ecf0;
    border-radius: 14px;
    overflow: hidden;
    position: sticky;
    top: 80px;
}
.cart-sidebar-header {
    background: #f8fafc;
    border-bottom: 1px solid #e8ecf0;
    padding: 14px 20px;
    font-weight: 700;
    font-size: .9rem;
}
.cart-sidebar-body { padding: 16px 20px; }
.cart-line {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}
.cart-line:last-child { border-bottom: none; }
.cart-line-name  { font-weight: 600; font-size: .875rem; }
.cart-line-sub   { font-size: .75rem; color: #6b7280; margin-top: 2px; }
.cart-line-price { font-weight: 700; font-size: .9rem; color: #0066FF; white-space: nowrap; }
.cart-total-row  {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 20px;
    background: #eff6ff;
    border-top: 1.5px solid #bfdbfe;
    font-weight: 800;
}

/* ── Domain options ───────────────────────────────────────── */
.domain-option {
    border: 1.5px solid #e8ecf0;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    gap: 12px;
}
.domain-option:hover,
.domain-option.selected { border-color: #0066FF; background: #eff6ff; }
.domain-option input[type="radio"] { accent-color: #0066FF; width: 16px; height: 16px; flex-shrink: 0; }
.domain-option-label { font-weight: 600; font-size: .9rem; }
.domain-option-sub   { font-size: .78rem; color: #6b7280; margin-top: 2px; }

/* ── Domain input ─────────────────────────────────────────── */
.domain-input-wrap {
    display: flex;
    border: 1.5px solid #e8ecf0;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color .15s;
}
.domain-input-wrap:focus-within { border-color: #0066FF; }
.domain-input-wrap input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 16px;
    font-size: .95rem;
    background: transparent;
}
.domain-input-wrap .tld-select {
    border: none;
    border-left: 1px solid #e8ecf0;
    background: #f8fafc;
    padding: 0 12px;
    font-size: .875rem;
    font-weight: 600;
    color: #374151;
    outline: none;
    cursor: pointer;
}

/* ── Continue btn ─────────────────────────────────────────── */
.btn-continue {
    background: #22c55e;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 15px 24px;
    font-size: 1rem;
    font-weight: 700;
    width: 100%;
    cursor: pointer;
    transition: background .15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.btn-continue:hover { background: #16a34a; color: #fff; }
</style>
@endpush

@section('content')

{{-- Page header --}}
<div style="background:#fff;border-bottom:1px solid #e8ecf0;padding:1.5rem 0;text-align:center;">
    <h4 class="fw-bold mb-1" style="color:#e53935;">
        You're almost there! Complete your order, and discover awesomeness
    </h4>
</div>

<div class="container py-4">

    {{-- ── Steps bar ─────────────────────────────────────────── --}}
    <div class="steps-bar">
        <div class="step-item done">
            <div class="step-num"><i class="bi bi-check-lg"></i></div>
            <div>
                <div class="step-label">Hosting</div>
                <div class="step-sub">Choose your hosting options</div>
            </div>
        </div>
        <div class="step-item active">
            <div class="step-num">2</div>
            <div>
                <div class="step-label">Domain</div>
                <div class="step-sub">Enter domain information</div>
            </div>
        </div>
        <div class="step-item inactive">
            <div class="step-num">3</div>
            <div>
                <div class="step-label" style="color:#0066FF;">Review Order</div>
                <div class="step-sub">Verify order details</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── LEFT: Domain step ───────────────────────────────── --}}
        <div class="col-lg-8">
            <div class="bg-white rounded-3 border p-4">

                <div class="alert" style="background:#e8f4fd;border:1px solid #90cdf4;border-radius:8px;font-size:.875rem;color:#1e40af;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Bind a domain name to your new hosting account:</strong><br>
                    <span class="text-muted">We can host any domain and register .com, .net, .org, .biz, .us, .info, .ug etc</span>
                </div>

                <form method="POST" action="{{ route('cart.domain.prompt.submit') }}" id="domainForm">
                    @csrf

                    {{-- Domain option 1 --}}
                    <label class="domain-option selected" id="opt-register">
                        <input type="radio" name="domain_option" value="register" checked
                               onchange="switchOption('register')">
                        <div>
                            <div class="domain-option-label">Register a new domain name.</div>
                        </div>
                    </label>

                    {{-- Domain option 2 --}}
                    <label class="domain-option" id="opt-transfer">
                        <input type="radio" name="domain_option" value="transfer"
                               onchange="switchOption('transfer')">
                        <div>
                            <div class="domain-option-label">Transfer your existing domain name to us.</div>
                        </div>
                    </label>

                    {{-- Domain option 3 --}}
                    <label class="domain-option" id="opt-existing">
                        <input type="radio" name="domain_option" value="existing"
                               onchange="switchOption('existing')">
                        <div>
                            <div class="domain-option-label">I already have a domain name. I want to purchase hosting services only.</div>
                        </div>
                    </label>

                    <div class="mt-4 mb-2">
                        <label class="form-label fw-semibold">
                            Enter the domain you wish to purchase below.
                            <span class="text-danger">*</span>
                        </label>
                    </div>

                    {{-- Domain input with TLD selector --}}
                    <div class="domain-input-wrap mb-1 @error('domain') border-danger @enderror">
                        <input type="text" name="domain_name" id="domainName"
                               placeholder="eg. example"
                               value="{{ old('domain_name') }}"
                               autocomplete="off">
                        <select name="tld" class="tld-select" id="tldSelect">
                            @foreach(['.com','.net','.org','.biz','.info','.ug','.co.ug','.ac.ug','.or.ug'] as $tld)
                            <option value="{{ $tld }}">{{ $tld }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Hidden full domain field --}}
                    <input type="hidden" name="domain" id="fullDomain">

                    @error('domain')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    @error('domain_name')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="mt-4">
                        <button type="submit" class="btn-continue" onclick="buildDomain()">
                            <i class="bi bi-cart-check"></i>
                            Add Domain to Cart &amp; Continue
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="text-muted small text-decoration-none">
                            Skip — I'll configure domain later
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── RIGHT: Cart sidebar ─────────────────────────────── --}}
        <div class="col-lg-4">
            <div class="cart-sidebar">
                <div class="cart-sidebar-header">
                    <i class="bi bi-cart3 me-2 text-sky"></i>Current items in your cart
                </div>
                <div class="cart-sidebar-body">

                    {{-- The hosting plan just added --}}
                    @php
                        $pending  = session('pending_cart_item', []);
                        $cartItems = session('cart', []);
                        $sidebarTotal = 0;
                    @endphp

                    @if(!empty($pending))
                    <div class="cart-line">
                        <div>
                            <div class="cart-line-name">{{ strtoupper($pending['name'] ?? 'Hosting Plan') }}</div>
                            <div class="cart-line-sub">
                                Hosting —
                                {{ $pending['billing_cycle'] === 'monthly' ? '1 month' : ($pending['billing_cycle'] === 'biennially' ? '2 year' : '1 year') }}
                                subscription
                            </div>
                        </div>
                        <div class="cart-line-price">$ {{ number_format($pending['price'] ?? 0) }}</div>
                    </div>
                    @php $sidebarTotal += $pending['price'] ?? 0; @endphp

                    {{-- Free SSL --}}
                    <div class="cart-line">
                        <div>
                            <div class="cart-line-name">Let's Encrypt</div>
                            <div class="cart-line-sub">SSL certificate</div>
                        </div>
                        <div class="cart-line-price" style="color:#22c55e;">FREE</div>
                    </div>
                    @endif

                    {{-- Any other items already in cart --}}
                    @foreach($cartItems as $item)
                    <div class="cart-line">
                        <div>
                            <div class="cart-line-name">{{ $item['name'] }}</div>
                            <div class="cart-line-sub">{{ ucfirst($item['type']) }} · {{ ucfirst($item['billing_cycle'] ?? 'yearly') }}</div>
                        </div>
                        <div class="cart-line-price">$ {{ number_format($item['price']) }}</div>
                    </div>
                    @php $sidebarTotal += $item['price']; @endphp
                    @endforeach

                    @if(empty($pending) && empty($cartItems))
                    <p class="text-muted small text-center py-3">No items yet.</p>
                    @endif
                </div>

                {{-- Total --}}
                <div class="cart-total-row">
                    <span>Total :</span>
                    <span style="color:#0066FF;font-size:1.05rem;">$ {{ number_format($sidebarTotal) }}</span>
                </div>

                {{-- Empty cart --}}
                <div style="padding:10px 16px;">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Clear cart?')"
                                style="width:100%;background:#e53935;color:#fff;border:none;border-radius:8px;padding:10px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                            <i class="bi bi-trash"></i> Empty Cart
                            <span style="margin-left:auto;background:rgba(255,255,255,.2);border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:.8rem;">✕</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function switchOption(val) {
    ['register','transfer','existing'].forEach(o => {
        document.getElementById('opt-' + o).classList.toggle('selected', o === val);
    });
    const inp = document.getElementById('domainName');
    inp.placeholder = val === 'existing' ? 'yourdomain.com' : 'eg. example';
}

function buildDomain() {
    const name = document.getElementById('domainName').value.trim();
    const tld  = document.getElementById('tldSelect').value;
    const opt  = document.querySelector('input[name="domain_option"]:checked').value;
    // For "existing" option, use as-is (may already have TLD)
    if (opt === 'existing') {
        document.getElementById('fullDomain').value = name;
    } else {
        document.getElementById('fullDomain').value = name + tld;
    }
}

// Also build on form submit
document.getElementById('domainForm').addEventListener('submit', buildDomain);
</script>
@endpush
@endsection
