@extends('layouts.app')
@section('title', 'Transfer Your Domain to SkyNetug')

@push('styles')
<style>
.transfer-hero { background:linear-gradient(135deg,#0A0F1E,#0D1433); padding:4rem 0 3rem; }
.step-circle { width:40px;height:40px;border-radius:50%;background:#0066FF;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.95rem;flex-shrink:0; }
.step-line { width:2px;background:#e8ecf0;flex:1;margin:4px 0; }
.transfer-card { background:#fff;border:1.5px solid #e8ecf0;border-radius:16px;padding:2rem; }
.info-box { background:#f0f4ff;border:1px solid #dde8ff;border-radius:10px;padding:1.25rem; }
.warn-box { background:#FEF3C7;border:1px solid #F59E0B;border-radius:10px;padding:1.25rem; }
.tld-price-badge { display:inline-block;background:#f8fafc;border:1px solid #e8ecf0;border-radius:8px;padding:.4rem .9rem;font-size:.82rem;font-weight:600;margin:.25rem; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="transfer-hero">
    <div class="container text-center">
        <i class="bi bi-arrow-left-right" style="font-size:2.5rem;color:#0066FF;display:block;margin-bottom:1rem;"></i>
        <span class="section-badge" style="background:rgba(0,102,255,.15);color:#60a5fa;border:1px solid rgba(0,102,255,.3);">Domain Transfer</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">Transfer Your Domain to SkyNetug</h1>
        <p class="text-white-50 mb-0" style="max-width:560px;margin:0 auto;">
            Move your existing domain to SkyNetug and manage everything in one place —
            hosting, email, DNS, and renewals.
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">

        {{-- Left: Transfer Form --}}
        <div class="col-lg-7">

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius:10px;">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="transfer-card">
                <h5 class="fw-bold mb-1">Initiate Domain Transfer</h5>
                <p class="text-muted small mb-4">Enter your domain name and the EPP/Auth code from your current registrar.</p>

                <form method="POST" action="{{ route('domains.transfer.submit') }}" id="transferForm">
                    @csrf

                    {{-- Domain name --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Domain Name <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text" style="background:#f8fafc;">
                                <i class="bi bi-globe text-muted"></i>
                            </span>
                            <input type="text" name="domain" class="form-control @error('domain') is-invalid @enderror"
                                   placeholder="example.com" required
                                   value="{{ old('domain', $domain) }}"
                                   style="border-radius:0 8px 8px 0;">
                            @error('domain')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">Enter the full domain name including extension, e.g. <code>mysite.com</code> or <code>mybiz.co.ug</code></div>
                    </div>

                    {{-- EPP Code --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">EPP / Authorization Code <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:#f8fafc;">
                                <i class="bi bi-key text-muted"></i>
                            </span>
                            <input type="text" name="epp_code" class="form-control @error('epp_code') is-invalid @enderror"
                                   placeholder="e.g. Auth#Code123!"
                                   value="{{ old('epp_code') }}"
                                   required minlength="6"
                                   style="border-radius:0 8px 8px 0; font-family:monospace;">
                            @error('epp_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            This code is provided by your current registrar. It is sometimes called the
                            <strong>Auth Code</strong>, <strong>Transfer Key</strong>, or <strong>Auth-Info Code</strong>.
                        </div>
                    </div>

                    <div class="warn-box mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning mt-1 flex-shrink-0"></i>
                            <div style="font-size:.875rem;">
                                <strong>Before you transfer, make sure:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>Your domain is <strong>unlocked</strong> at your current registrar</li>
                                    <li>The domain was <strong>registered more than 60 days ago</strong></li>
                                    <li>Your domain is <strong>not expired</strong></li>
                                    <li>You have the <strong>correct EPP code</strong> from your registrar</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-sky w-100 py-3 fw-bold fs-6">
                        <i class="bi bi-cart-plus me-2"></i>Add Transfer to Cart
                    </button>

                    <p class="text-muted text-center small mt-3">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Your EPP code is encrypted and only used to initiate the transfer.
                    </p>
                </form>
            </div>

        </div>

        {{-- Right: Info Panel --}}
        <div class="col-lg-5">

            {{-- Transfer pricing --}}
            <div class="transfer-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2 text-sky"></i>Transfer Pricing</h6>
                <p class="text-muted small mb-3">Domain transfers include a 1-year renewal.</p>
                <div>
                    @foreach($pricing as $tld => $data)
                    <span class="tld-price-badge">
                        {{ $tld }}
                        <span class="text-sky fw-bold ms-1">$ {{ number_format($data['transfer'] ?? $data['register'], 2) }}</span>
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- How it works --}}
            <div class="transfer-card mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-list-ol me-2 text-sky"></i>How the Transfer Works</h6>
                <div class="d-flex flex-column gap-0">
                    @foreach([
                        ['Unlock your domain at your current registrar and get the EPP code.', 'bi-unlock'],
                        ['Enter your domain and EPP code in the form and add to cart.', 'bi-cart-plus'],
                        ['Complete payment on the next page.', 'bi-credit-card'],
                        ['We submit the transfer request to the registry. You will receive an email to approve it.', 'bi-envelope-check'],
                        ['Once approved (usually 5–7 days), your domain is active in your SkyNetug dashboard.', 'bi-check-circle-fill'],
                    ] as $i => $step)
                    <div class="d-flex gap-3">
                        <div class="d-flex flex-column align-items-center">
                            <div class="step-circle">{{ $i + 1 }}</div>
                            @if($i < 4)<div class="step-line" style="min-height:28px;"></div>@endif
                        </div>
                        <div class="pb-3">
                            <div style="font-size:.875rem;color:#374151;padding-top:.5rem;">
                                <i class="bi {{ $step[1] }} text-sky me-1"></i>
                                {{ $step[0] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Benefits --}}
            <div class="info-box">
                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-star me-2"></i>Why Transfer to SkyNetug?</h6>
                <ul class="list-unstyled mb-0" style="font-size:.875rem;">
                    @foreach([
                        'Free DNS management (A, MX, CNAME, TXT records)',
                        'Free WHOIS privacy protection',
                        'Auto-renewal with 30-day email reminders',
                        'Domain lock to prevent unauthorized transfers',
                        'Manage alongside your hosting in one dashboard',
                        'Pay with MTN Mobile Money or Airtel Money',
                        '24/7 local support in Uganda',
                    ] as $benefit)
                    <li class="d-flex align-items-start gap-2 py-1">
                        <i class="bi bi-check2-circle text-success mt-1 flex-shrink-0"></i>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>

    {{-- FAQ --}}
    <div class="mt-5">
        <h4 class="fw-bold text-center mb-4">Frequently Asked Questions</h4>
        <div class="row g-3 justify-content-center">
            @foreach([
                ['Where do I get the EPP code?', 'Log into your current registrar\'s control panel and look for "Transfer", "Auth Code", "EPP Code", or "Transfer Key" in the domain settings. If you can\'t find it, contact your registrar\'s support.'],
                ['How long does a transfer take?', 'Most transfers complete within 5–7 days. The registry must approve the transfer and you may receive a confirmation email from your current registrar.'],
                ['Will my website go down during transfer?', 'No. Your website stays online throughout the transfer. DNS propagation only changes if you update your nameservers after the transfer is complete.'],
                ['What if my domain is locked?', 'You need to unlock it at your current registrar first. Look for "Registrar Lock" or "Domain Lock" in your domain settings and disable it before requesting the EPP code.'],
                ['Does transfer renew my domain?', 'Yes — all transfers include a 1-year renewal added to your current expiry date. You never lose time on your domain.'],
                ['Can I transfer .ug or .co.ug domains?', 'Yes. We support .ug, .co.ug, .ac.ug, .or.ug, .com, .net, .org, and more. Contact us if your TLD is not listed.'],
            ] as $faq)
            <div class="col-md-6">
                <div class="bg-white border rounded-3 p-3">
                    <h6 class="fw-semibold mb-2" style="font-size:.9rem;">{{ $faq[0] }}</h6>
                    <p class="text-muted mb-0" style="font-size:.85rem;">{{ $faq[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
