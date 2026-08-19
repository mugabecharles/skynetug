@extends('layouts.app')

@section('title', 'Web Hosting & Domain Registration Uganda')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #0A0F1E 0%, #0D1433 55%, #0A0F1E 100%);
        padding: 4.5rem 0 3.5rem;
        position: relative;
        overflow: hidden;
        min-height: 580px;
    }

    /* Glow blobs */
    .hero-section::before {
        content: '';
        position: absolute;
        top: -40%;
        right: 5%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(0,102,255,0.14) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -25%;
        left: -8%;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(0,200,150,0.09) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(0,200,150,0.15);
        color: #00C896;
        border: 1px solid rgba(0,200,150,0.3);
        border-radius: 20px;
        padding: 0.35rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.18;
        letter-spacing: -1px;
        margin-bottom: 1.2rem;
    }

    .hero-title .highlight {
        background: linear-gradient(90deg, #0066FF, #00C896);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.7);
        max-width: 520px;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    /* Hero image column */
    .hero-image-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-image-wrap img {
        width: 100%;
        max-width: 560px;
        border-radius: 20px;
        box-shadow: 0 32px 80px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.06);
        display: block;
    }

    /* Floating badge on the image */
    .hero-float-badge {
        position: absolute;
        bottom: -16px;
        left: 20px;
        background: #fff;
        border-radius: 14px;
        padding: 10px 18px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .hero-float-badge .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #00C896;
        flex-shrink: 0;
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 0 rgba(0,200,150,0.4); }
        50% { box-shadow: 0 0 0 6px rgba(0,200,150,0); }
    }

    .domain-search-box {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        max-width: 580px;
    }

    .domain-search-box .form-control {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
        border-radius: 10px 0 0 10px;
        font-size: 1rem;
        padding: 0.7rem 1.1rem;
    }

    .domain-search-box .form-control::placeholder { color: rgba(255,255,255,0.4); }
    .domain-search-box .form-control:focus {
        background: rgba(255,255,255,0.12);
        border-color: var(--sky-primary);
        color: #fff;
        box-shadow: none;
    }

    .stat-item { text-align: center; }
    .stat-item .number {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }
    .stat-item .label { font-size: 0.8rem; color: rgba(255,255,255,0.5); margin-top: 0.25rem; }

    .feature-icon {
        width: 52px;
        height: 52px;
        background: rgba(0,102,255,0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--sky-primary);
        margin-bottom: 1rem;
    }

    .pricing-card {
        border: 1.5px solid #e8ecf0;
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.25s;
        background: #fff;
        position: relative;
    }

    .pricing-card:hover {
        border-color: var(--sky-primary);
        box-shadow: 0 16px 40px rgba(0,102,255,0.1);
        transform: translateY(-4px);
    }

    .pricing-card.popular {
        border-color: var(--sky-primary);
        box-shadow: 0 0 0 4px rgba(0,102,255,0.08);
    }

    .popular-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--sky-primary);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 0.25rem 1rem;
    }

    .price-amount {
        font-size: 2.4rem;
        font-weight: 800;
        color: #0A0F1E;
        line-height: 1;
    }

    .price-period {
        font-size: 0.85rem;
        color: #6b7280;
    }

    .feature-check {
        color: var(--sky-secondary);
        font-size: 0.9rem;
        margin-right: 0.5rem;
    }

    .feature-list li {
        padding: 0.35rem 0;
        font-size: 0.9rem;
        color: #374151;
    }

    .tld-badge {
        display: inline-block;
        background: #f0f4ff;
        color: var(--sky-primary);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 700;
        margin: 0.25rem;
        border: 1px solid #dde8ff;
    }

    .testimonial-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e8ecf0;
    }

    .stars { color: #f59e0b; font-size: 0.9rem; }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center g-5">

            {{-- LEFT: Text + Search --}}
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Uganda's Fastest Growing Hosting Provider
                </div>

                <h1 class="hero-title">
                    Reliable Hosting &amp;<br>
                    <span class="highlight">Domain Registration</span><br>
                    Built for Uganda
                </h1>

                <p class="hero-subtitle">
                    Launch your website with lightning-fast servers, free SSL, 99.99% uptime,
                    and 24/7 Ugandan support. Pay with MTN Mobile Money or Airtel Money.
                </p>

                {{-- Domain Search --}}
                <div class="domain-search-box mb-4">
                    <p class="text-white mb-2" style="font-size: 0.8rem; font-weight: 700; opacity: 0.7; letter-spacing:.5px;">
                        <i class="bi bi-search me-1"></i> FIND YOUR DOMAIN
                    </p>
                    <form action="{{ route('domains.search') }}" method="GET" id="domainSearchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" name="domain" id="domainInput"
                                placeholder="yourname.com, .ug, .co.ug..." required>
                            <button class="btn btn-sky px-4" type="submit">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach(['.com', '.ug', '.co.ug', '.net', '.org'] as $tld)
                            <span class="tld-badge">{{ $tld }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('hosting.shared') }}" class="btn btn-sky px-4 py-2">
                        <i class="bi bi-server me-2"></i>View Hosting Plans
                    </a>
                    <a href="{{ route('pricing') }}" class="btn btn-sky-outline px-4 py-2">See All Pricing</a>
                </div>

                {{-- Stats row --}}
                <div class="d-flex gap-4 mt-4 pt-2">
                    <div class="stat-item">
                        <div class="number">500+</div>
                        <div class="label">Websites Hosted</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">99.9%</div>
                        <div class="label">Uptime Guarantee</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">24/7</div>
                        <div class="label">Expert Support</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Hero image --}}
            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                <div class="hero-image-wrap">
                    <img src="{{ asset('images/hero-mockup.png') }}"
                         alt="SkyNetug - Reliable Hosting and Domain Registration in Uganda"
                         loading="eager"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">

                    {{-- Fallback when image not yet placed --}}
                    <div style="display:none;width:520px;height:360px;border-radius:20px;background:rgba(0,102,255,0.08);border:1px solid rgba(255,255,255,0.1);align-items:center;justify-content:center;flex-direction:column;gap:12px;">
                        <i class="bi bi-display" style="font-size:3rem;color:rgba(255,255,255,0.2);"></i>
                        <span style="color:rgba(255,255,255,0.3);font-size:.85rem;">
                            Drop <code style="color:rgba(0,200,150,.6);">public/images/hero-mockup.png</code> here
                        </span>
                    </div>

                    {{-- Live badge overlay --}}
                    <div class="hero-float-badge">
                        <div class="dot"></div>
                        <div>
                            <div style="font-weight:700;color:#0A0F1E;font-size:.82rem;">All Systems Online</div>
                            <div style="color:#6b7280;font-size:.72rem;">99.9% uptime this month</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Trust Bar --}}
<div style="background: #f8fafc; border-bottom: 1px solid #e8ecf0; padding: 1rem 0;">
    <div class="container">
        <div class="row align-items-center text-center g-3">
            <div class="col-6 col-md-3">
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-shield-check text-success me-1"></i> Free SSL Certificate
                </span>
            </div>
            <div class="col-6 col-md-3">
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-phone text-sky me-1"></i> MTN & Airtel Money Accepted
                </span>
            </div>
            <div class="col-6 col-md-3">
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-cloud-arrow-up text-primary me-1"></i> Daily Backups
                </span>
            </div>
            <div class="col-6 col-md-3">
                <span class="text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-headset text-warning me-1"></i> 24/7 Ugandan Support
                </span>
            </div>
        </div>
    </div>
</div>

{{-- About Banner --}}
<section style="background:#f8fafc; border-top:1px solid #e8ecf0; border-bottom:1px solid #e8ecf0; padding:2.5rem 0; text-align:center;">
    <div class="container-fluid px-4">
        <h3 style="font-size:1.35rem;font-weight:800;color:#0A0F1E;margin-bottom:.6rem;">
            Our web-hosting packages are the <span style="color:#0066FF;">cheapest in the land</span>
        </h3>
        <p style="color:#6B7280;font-size:.95rem;line-height:1.7;margin:0 auto;max-width:760px;">
            SkyNetug is a mid-sized web hosting company providing the cheapest hosting and domain
            registration plans in Uganda and East Africa. We deliver enterprise-grade reliability
            at prices every business can afford — with 24/7 local support and instant provisioning.
        </p>
    </div>
</section>

{{-- Hosting Plans --}}
<section style="padding:4rem 0; background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Hosting Plans</span>
            <h2 style="font-size:2rem;font-weight:800;color:#0A0F1E;">Choose Your Hosting Plan</h2>
            <p style="color:#6B7280;">No setup fees. Instant activation. 30-day money-back guarantee.</p>
        </div>

        <style>
        .hplan-card {
            background: #fff;
            border: 1.5px solid #e8ecf0;
            border-radius: 18px;
            padding: 2rem 1.75rem 1.75rem;
            position: relative;
            transition: box-shadow .2s, transform .2s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .hplan-card:hover {
            box-shadow: 0 12px 40px rgba(0,102,255,.1);
            transform: translateY(-4px);
        }
        .hplan-card.popular {
            border: 2px solid #0066FF;
            box-shadow: 0 0 0 4px rgba(0,102,255,.08);
        }
        .hplan-badge {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%);
            background: #0066FF;
            color: #fff;
            font-size: .8rem;
            font-weight: 700;
            border-radius: 20px;
            padding: .35rem 1.1rem;
            white-space: nowrap;
        }
        .hplan-name {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0A0F1E;
            margin-bottom: .25rem;
        }
        .hplan-desc {
            color: #9CA3AF;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }
        .hplan-price {
            font-size: 2.6rem;
            font-weight: 800;
            color: #0A0F1E;
            line-height: 1;
            margin-bottom: .2rem;
            word-break: break-all;
            overflow-wrap: break-word;
        }
        .hplan-price.compact {
            font-size: 1.4rem;
        }
        .hplan-price.very-compact {
            font-size: 1.1rem;
        }
        .hplan-price sup {
            font-size: 1.4rem;
            vertical-align: super;
            font-weight: 700;
        }
        .hplan-price .per {
            font-size: .95rem;
            font-weight: 400;
            color: #6B7280;
        }
        .hplan-billed {
            font-size: .82rem;
            color: #9CA3AF;
            margin-bottom: 1.5rem;
        }
        .hplan-features {
            list-style: none;
            padding: 0;
            margin: 0 0 .75rem;
            flex: 1;
        }
        .hplan-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .5rem 0;
            font-size: .9rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }
        .hplan-features li:last-child { border-bottom: none; }
        .hplan-check {
            color: #00C896;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .hplan-btn {
            display: block;
            width: 100%;
            background: #0066FF;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 0;
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            transition: background .15s;
            cursor: pointer;
        }
        .hplan-btn:hover { background: #0050CC; color: #fff; }
        .hplan-card form { margin: 0; }
        </style>

        <div class="row g-4 justify-content-center">
            @forelse($featuredPackages as $plan)
            <div class="col-md-6 col-lg-3">
                <div style="padding-top:22px;">
                    <div class="hplan-card {{ $plan->slug === 'business' ? 'popular' : '' }}">

                        @if($plan->slug === 'business')
                        <div class="hplan-badge">⭐ Most Popular</div>
                        @endif

                        <div class="hplan-name">{{ $plan->name }}</div>
                        <div class="hplan-desc">{{ $plan->description }}</div>

                        {{-- Price display — updates when cycle is selected --}}
                        <div class="hplan-price" id="price-display-{{ $plan->id }}">
                            <span data-usd="{{ number_format($plan->price_yearly, 2) }}">$ {{ number_format($plan->price_yearly, 2) }}</span><span class="per"> /yr</span>
                        </div>
                        <div class="hplan-billed" id="billed-display-{{ $plan->id }}">
                            Billed ${{ number_format($plan->price_yearly, 2) }}/yr &nbsp;·&nbsp; ${{ number_format($plan->price_yearly / 12, 2) }}/mo
                        </div>

                        <ul class="hplan-features">
                            {{-- DB fields only — no hardcoded items --}}
                            @if($plan->disk_space_mb > 0)
                            <li><i class="bi bi-hdd hplan-check"></i>
                                {{ $plan->disk_space_mb == 0 ? 'Unlimited' : number_format($plan->disk_space_mb / 1024, 0) . ' GB' }} Storage
                            </li>
                            @endif
                            @if($plan->bandwidth_mb == 0)
                            <li><i class="bi bi-arrow-down-up hplan-check"></i>Unlimited Bandwidth</li>
                            @elseif($plan->bandwidth_mb > 0)
                            <li><i class="bi bi-arrow-down-up hplan-check"></i>{{ number_format($plan->bandwidth_mb / 1024, 0) }} GB Bandwidth</li>
                            @endif
                            @if($plan->email_accounts !== null)
                            <li><i class="bi bi-envelope hplan-check"></i>
                                {{ $plan->email_accounts == 0 ? 'Unlimited' : $plan->email_accounts }} Email Accounts
                            </li>
                            @endif
                            @if($plan->ssl_included)
                            <li><i class="bi bi-shield-check hplan-check"></i>Free SSL Certificate</li>
                            @endif
                            @if($plan->softaculous_included)
                            <li><i class="bi bi-lightning hplan-check"></i>1-Click WordPress Install</li>
                            @endif
                            @if($plan->backup_included)
                            <li><i class="bi bi-cloud-check hplan-check"></i>Free Daily Backups</li>
                            @endif
                            @if($plan->addon_domains > 0)
                            <li><i class="bi bi-check2 hplan-check"></i>{{ $plan->addon_domains }} Addon Domain{{ $plan->addon_domains > 1 ? 's' : '' }}</li>
                            @endif
                            @if($plan->subdomains == 0)
                            <li><i class="bi bi-check2 hplan-check"></i>Unlimited Subdomains</li>
                            @elseif($plan->subdomains > 0)
                            <li><i class="bi bi-check2 hplan-check"></i>{{ $plan->subdomains }} Subdomains</li>
                            @endif
                            @if($plan->databases > 0)
                            <li><i class="bi bi-database hplan-check"></i>{{ $plan->databases == 0 ? 'Unlimited' : $plan->databases }} Database{{ $plan->databases > 1 ? 's' : '' }}</li>
                            @endif
                            {{-- Custom features from the features JSON field --}}
                            @foreach((array) $plan->features as $feat)
                            @if(!empty(trim($feat)))
                            <li><i class="bi bi-check2 hplan-check"></i>{{ $feat }}</li>
                            @endif
                            @endforeach
                        </ul>

                        {{-- Billing cycle dropdown --}}
                        @php
                            $cycles = [];
                            if ($plan->price_monthly > 0)    $cycles[] = ['label'=>'1 Month  — $ '.number_format($plan->price_monthly, 2),    'cycle'=>'monthly',    'total'=>$plan->price_monthly,       'monthly'=>$plan->price_monthly];
                            if ($plan->price_monthly > 0)    $cycles[] = ['label'=>'3 Months — $ '.number_format($plan->price_monthly*3, 2),   'cycle'=>'monthly',    'total'=>$plan->price_monthly*3,     'monthly'=>$plan->price_monthly];
                            if ($plan->price_monthly > 0)    $cycles[] = ['label'=>'6 Months — $ '.number_format($plan->price_monthly*6, 2),   'cycle'=>'monthly',    'total'=>$plan->price_monthly*6,     'monthly'=>$plan->price_monthly];
                            if ($plan->price_yearly > 0)     $cycles[] = ['label'=>'12 Months — $ '.number_format($plan->price_yearly, 2).' (Save 20%)',  'cycle'=>'yearly',     'total'=>$plan->price_yearly,        'monthly'=>$plan->price_yearly/12];
                            if ($plan->price_biennially > 0) $cycles[] = ['label'=>'24 Months — $ '.number_format($plan->price_biennially, 2).' (Save 30%)', 'cycle'=>'biennially', 'total'=>$plan->price_biennially,    'monthly'=>$plan->price_biennially/24];
                        @endphp

                        <div class="mt-auto">
                            <hr style="border-color:#f3f4f6;margin:0 0 8px;">
                            <select id="cycle-select-{{ $plan->id }}"
                                    onchange="updatePlanCycleSelect({{ $plan->id }}, this)"
                                    style="width:100%;border:1.5px solid #e8ecf0;border-radius:10px;padding:12px 16px;font-size:.9rem;color:#374151;background:#fff url('data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'><path fill=\'%236B7280\' d=\'M1 1l5 5 5-5\'/></svg>') no-repeat right 14px center;appearance:none;-webkit-appearance:none;cursor:pointer;margin-bottom:12px;font-weight:500;">
                                <option value="" disabled selected>Choose preferred billing cycle</option>
                                @foreach($cycles as $cy)
                                <option value="{{ $cy['cycle'] }}"
                                        data-total="{{ number_format($cy['total'], 2) }}"
                                        data-monthly="{{ number_format($cy['monthly'], 2) }}"
                                        data-cycle="{{ $cy['cycle'] }}"
                                        {{ $cy['cycle'] === 'yearly' ? 'selected' : '' }}>
                                    {{ $cy['label'] }}
                                </option>
                                @endforeach
                            </select>

                            <form method="POST" action="{{ route('cart.add.public') }}">
                                @csrf
                                <input type="hidden" name="type"          value="hosting">
                                <input type="hidden" name="package_id"    value="{{ $plan->id }}">
                                <input type="hidden" name="name"          value="{{ $plan->name }}">
                                <input type="hidden" name="billing_cycle" id="cycle-input-{{ $plan->id }}" value="yearly">
                                <button type="submit"
                                        style="width:100%;background:#0066FF;color:#fff;border:none;border-radius:10px;padding:13px 18px;font-size:.9rem;font-weight:700;display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:background .15s;"
                                        onmouseover="this.style.background='#0050CC'"
                                        onmouseout="this.style.background='#0066FF'">
                                    <span>Add to Cart</span>
                                    <i class="bi bi-basket" style="font-size:1rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Fallback if no featured packages in DB --}}
            <div class="col-12 text-center py-5">
                <p class="text-muted">No hosting plans configured yet. <a href="{{ route('admin.packages.create') }}">Add packages</a> in the admin panel.</p>
            </div>
            @endforelse
        </div>

        @push('scripts')
        <script>
        function updatePlanCycle(planId, radio) {
            const total   = parseFloat(radio.dataset.total);
            const monthly = parseFloat(radio.dataset.monthly);
            const cycle   = radio.dataset.cycle;
            const label   = radio.dataset.label;

            // Update price
            const priceEl = document.getElementById('price-display-' + planId);
            if (priceEl) {
                const span = priceEl.querySelector('[data-usd]');
                const per  = priceEl.querySelector('.per');
                if (span) { span.dataset.usd = total.toFixed(2); span.textContent = '$ ' + total.toFixed(2); }
                if (per)  { per.textContent  = cycle === 'monthly' ? ' /mo' : (cycle === 'biennially' ? ' /2yr' : ' /yr'); }
            }

            // Update billed line
            const billedEl = document.getElementById('billed-display-' + planId);
            if (billedEl) billedEl.textContent = 'Billed $' + total.toFixed(2) + ' · $' + monthly.toFixed(2) + '/mo';

            // Update hidden cycle input
            const inp = document.getElementById('cycle-input-' + planId);
            if (inp) inp.value = cycle;

            // Highlight selected row
            document.querySelectorAll('[name="cycle_' + planId + '"]').forEach(r => {
                r.closest('label').style.background = r.checked ? '#eff6ff' : '';
            });
        }

        // Set initial highlight on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[name^="cycle_"]:checked').forEach(r => {
                r.closest('label').style.background = '#eff6ff';
            });
        });
        </script>
        @endpush

        <div class="text-center mt-4">
            <a href="{{ route('hosting.shared') }}" style="color:#0066FF;font-size:.9rem;text-decoration:none;font-weight:600;">
                View all hosting plans <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- Everything You Need Section --}}
<section style="padding:3.5rem 0; background:#fff; border-top:1px solid #f0f0f0; border-bottom:1px solid #f0f0f0;">
    <div class="container">
        {{-- Label --}}
        <div class="d-flex align-items-center gap-3 mb-4 justify-content-center">
            <div style="flex:1;max-width:200px;height:1px;background:#e0e0e0;"></div>
            <span style="font-size:.72rem;font-weight:800;letter-spacing:.15em;text-transform:uppercase;color:#555;white-space:nowrap;">
                Everything You Need From Start-Up to Success
            </span>
            <div style="flex:1;max-width:200px;height:1px;background:#e0e0e0;"></div>
        </div>

        {{-- Service Tiles --}}
        <div class="row g-3">
            @foreach([
                ['icon'=>'bi-globe2',          'label'=>'Domains',                 'color'=>'#e02020'],
                ['icon'=>'bi-server',           'label'=>'cPanel Shared Hosting',   'color'=>'#e02020'],
                ['icon'=>'bi-hdd-stack',        'label'=>'Plesk Shared Hosting',    'color'=>'#e02020'],
                ['icon'=>'bi-server',           'label'=>'cPanel Reseller Hosting', 'color'=>'#e02020'],
                ['icon'=>'bi-hdd-stack-fill',   'label'=>'Plesk Reseller Hosting',  'color'=>'#e02020'],
                ['icon'=>'bi-envelope-at-fill', 'label'=>'Seamless Email Hosting',  'color'=>'#e02020'],
                ['icon'=>'bi-cpu-fill',         'label'=>'Cloud Servers',           'color'=>'#e02020'],
                ['icon'=>'bi-shield-lock-fill', 'label'=>'Sectigo SSL Certificates','color'=>'#e02020'],
            ] as $svc)
            <div class="col-6 col-md-3">
                <div style="display:flex;align-items:center;gap:12px;border:1px solid #e8ecf0;border-radius:10px;padding:14px 18px;background:#fff;transition:all .2s;cursor:default;"
                     onmouseover="this.style.borderColor='#e02020';this.style.boxShadow='0 4px 16px rgba(224,32,32,.08)'"
                     onmouseout="this.style.borderColor='#e8ecf0';this.style.boxShadow=''">
                    <i class="bi {{ $svc['icon'] }}" style="font-size:1.5rem;color:{{ $svc['color'] }};flex-shrink:0;"></i>
                    <span style="font-weight:600;font-size:.875rem;color:#1C2333;">{{ $svc['label'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Why Choose SkyNetug --}}
<section style="padding: 5rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Why SkyNetug</span>
            <h2 style="font-size: 2.2rem; font-weight: 800; color: #0A0F1E;">Built for Ugandan Businesses</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon' => 'bi-lightning-charge', 'title' => 'Ultra-Fast Speed', 'desc' => 'LiteSpeed-powered servers with NVMe SSD storage for blazing-fast load times under 1 second.'],
                ['icon' => 'bi-shield-lock', 'title' => 'Enterprise Security', 'desc' => 'Cloudflare DDoS protection, free SSL, ModSecurity WAF, and daily automated backups.'],
                ['icon' => 'bi-phone', 'title' => 'Mobile Money Payments', 'desc' => 'Pay easily with MTN Mobile Money or Airtel Money — no bank card needed.'],
                ['icon' => 'bi-headset', 'title' => '24/7 Local Support', 'desc' => 'Our Ugandan support team is available around the clock via ticket, live chat, and email.'],
                ['icon' => 'bi-arrow-repeat', 'title' => 'Auto-Renewals', 'desc' => 'Never lose your hosting or domain. Automated billing and renewal reminders keep your site live.'],
                ['icon' => 'bi-mortarboard', 'title' => '1-Click Installer', 'desc' => 'Install WordPress, Joomla, or 400+ apps in seconds with Softaculous auto-installer.'],
            ] as $feat)
            <div class="col-md-4">
                <div class="p-3">
                    <div class="feature-icon">
                        <i class="bi {{ $feat['icon'] }}"></i>
                    </div>
                    <h6 style="font-weight: 700; margin-bottom: 0.5rem;">{{ $feat['title'] }}</h6>
                    <p class="text-muted small" style="line-height: 1.6;">{{ $feat['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section style="background: #f8fafc; padding: 5rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Testimonials</span>
            <h2 style="font-size: 2rem; font-weight: 800; color: #0A0F1E;">What Our Customers Say</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['name' => 'John Kiggundu', 'role' => 'CEO, KampalaShop Ltd', 'text' => 'SkyNetug made it easy to launch our e-commerce site. The support team responded in minutes and helped us set everything up.', 'rating' => 5],
                ['name' => 'Sarah Atuhaire', 'role' => 'Blogger & Content Creator', 'text' => 'I love that I can pay with MTN Mobile Money! The hosting is fast and I\'ve had zero downtime in 8 months.', 'rating' => 5],
                ['name' => 'Dr. Moses Tumwine', 'role' => 'Director, Makerere Medical Centre', 'text' => 'Our hospital website has been running flawlessly. The cPanel is easy to use and their team is very professional.', 'rating' => 5],
            ] as $review)
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-2">
                        @for($i = 0; $i < $review['rating']; $i++) ★ @endfor
                    </div>
                    <p class="mb-3" style="font-size: 0.9rem; line-height: 1.7; color: #374151;">"{{ $review['text'] }}"</p>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 38px; height: 38px; background: var(--sky-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.9rem;">
                            {{ substr($review['name'], 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.85rem;">{{ $review['name'] }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $review['role'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background: linear-gradient(135deg, #0066FF, #0040aa); padding: 4rem 0;">
    <div class="container text-center">
        <h2 style="color: #fff; font-size: 2rem; font-weight: 800; margin-bottom: 1rem;">
            Ready to Get Started?
        </h2>
        <p style="color: rgba(255,255,255,0.85); font-size: 1.05rem; margin-bottom: 2rem;">
            Join 500+ Ugandan businesses already hosting with SkyNetug.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('hosting.shared') }}" class="btn btn-light px-5 py-2 fw-bold">
                <i class="bi bi-server me-2"></i>Start Hosting
            </a>
            <a href="{{ route('domains') }}" class="btn btn-sky-outline px-5 py-2">
                Find Your Domain
            </a>
        </div>
    </div>
</section>

@endsection



