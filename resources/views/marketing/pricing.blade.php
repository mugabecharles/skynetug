@extends('layouts.app')
@section('title', 'Hosting Pricing Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:4rem 0 3rem;">
    <div class="container text-center">
        <span class="section-badge" style="background:rgba(0,200,150,.15);color:#00C896;border:1px solid rgba(0,200,150,.3);">Pricing</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">Simple, Transparent Pricing</h1>
        <p class="text-white-50">No hidden fees. Pay monthly or yearly. Cancel anytime.</p>
    </div>
</div>

<div class="container py-5">
    {{-- Tabs --}}
    <ul class="nav nav-pills justify-content-center mb-5 gap-2" id="pricingTabs">
        <li class="nav-item"><button class="nav-link active px-4" data-bs-toggle="tab" data-bs-target="#shared">Shared Hosting</button></li>
        <li class="nav-item"><button class="nav-link px-4" data-bs-toggle="tab" data-bs-target="#wordpress">WordPress</button></li>
        <li class="nav-item"><button class="nav-link px-4" data-bs-toggle="tab" data-bs-target="#domains-tab">Domains</button></li>
    </ul>

    <div class="tab-content">
        {{-- Shared Hosting --}}
        <div class="tab-pane fade show active" id="shared">
            <div class="row g-4 justify-content-center">
                @forelse($sharedPackages as $pkg)
                <div class="col-md-6 col-lg-3">
                    <div style="border:1.5px solid {{ $pkg->is_featured ? '#0066FF' : '#e8ecf0' }};border-radius:20px;padding:2rem;background:#fff;transition:all .25s;position:relative;"
                         onmouseover="this.style.boxShadow='0 16px 40px rgba(0,102,255,.1)';this.style.transform='translateY(-4px)'"
                         onmouseout="this.style.boxShadow='';this.style.transform=''">
                        @if($pkg->is_featured)
                        <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);background:#0066FF;color:#fff;font-size:.72rem;font-weight:700;border-radius:20px;padding:.25rem 1rem;white-space:nowrap;">
                            ⭐ Most Popular
                        </div>
                        @endif
                        <h5 class="fw-bold">{{ $pkg->name }}</h5>
                        <p class="text-muted small">{{ $pkg->description }}</p>
                        <div class="mb-3">
                            <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_yearly/12, 2) }}</span>
                            <span class="text-muted small">/mo</span>
                            <div class="text-muted small">Billed yearly at $ {{ number_format($pkg->price_yearly, 2) }}</div>
                        </div>
                        <ul class="list-unstyled mb-4" style="font-size:.85rem;">
                            <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $pkg->disk_space_mb==0?'Unlimited':number_format($pkg->disk_space_mb/1024,0).' GB' }} SSD Storage</li>
                            <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Unlimited Bandwidth</li>
                            <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $pkg->email_accounts==0?'Unlimited':$pkg->email_accounts }} Email Accounts</li>
                            <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free SSL Certificate</li>
                            @if($pkg->softaculous_included)<li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Softaculous 1-Click Install</li>@endif
                        </ul>
                        <form method="POST" action="{{ route('cart.add.public') }}">
                            @csrf
                            <input type="hidden" name="type" value="hosting">
                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                            <input type="hidden" name="name" value="{{ $pkg->name }}">
                            <input type="hidden" name="billing_cycle" value="yearly">
                            <button type="submit" class="btn {{ $pkg->is_featured ? 'btn-sky' : 'btn-outline-primary' }} w-100 py-2 fw-semibold">
                                <i class="bi bi-cart-plus me-2"></i>Choose Plan
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-4">
                    <p>Hosting packages coming soon. <a href="{{ route('contact') }}">Contact us</a> for pricing.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- WordPress --}}
        <div class="tab-pane fade" id="wordpress">
            <div class="row g-4 justify-content-center">
                @forelse($wordpressPackages as $pkg)
                <div class="col-md-6 col-lg-4">
                    <div style="border:1.5px solid #e8ecf0;border-radius:20px;padding:2rem;background:#fff;">
                        <div class="mb-2"><i class="bi bi-wordpress fs-3" style="color:#0066FF;"></i></div>
                        <h5 class="fw-bold">{{ $pkg->name }}</h5>
                        <p class="text-muted small">{{ $pkg->description }}</p>
                        <div class="mb-3">
                            <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_yearly/12, 2) }}</span>
                            <span class="text-muted small">/mo</span>
                        </div>
                        <form method="POST" action="{{ route('cart.add.public') }}">
                            @csrf
                            <input type="hidden" name="type" value="hosting">
                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                            <input type="hidden" name="name" value="{{ $pkg->name }}">
                            <input type="hidden" name="billing_cycle" value="yearly">
                            <button type="submit" class="btn btn-sky w-100">
                                <i class="bi bi-cart-plus me-2"></i>Get Started
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-4"><p>WordPress packages coming soon.</p></div>
                @endforelse
            </div>
        </div>

        {{-- Domain Pricing --}}
        <div class="tab-pane fade" id="domains-tab">
            <div class="row g-3 justify-content-center">
                @foreach(['.com'=>35000,'.ug'=>55000,'.co.ug'=>45000,'.net'=>40000,'.org'=>38000,'.biz'=>42000,'.info'=>28000,'.ac.ug'=>45000,'.or.ug'=>45000] as $tld=>$price)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="text-center p-3 bg-white rounded-3 border">
                        <div class="fw-bold fs-5 text-sky">{{ $tld }}</div>
                        <div class="fw-bold mt-1">$ {{ number_format($price) }}</div>
                        <div class="text-muted small">/year</div>
                        <a href="{{ route('domains') }}" class="btn btn-sm btn-sky mt-2 w-100" style="font-size:.75rem;">Register</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- FAQ --}}
<div style="background:#f8fafc;padding:4rem 0;">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Frequently Asked Questions</h3>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faq">
                    @foreach([
                        ['q'=>'Can I pay with Mobile Money?','a'=>'Yes! We accept MTN Mobile Money and Airtel Money, as well as card payments via Flutterwave and Pesapal.'],
                        ['q'=>'Can I upgrade my plan later?','a'=>'Absolutely. You can upgrade your hosting plan at any time from your dashboard. You only pay the difference.'],
                        ['q'=>'Do you offer refunds?','a'=>'Yes, we offer a prorated refund within the first 7 days if you are not satisfied.'],
                        ['q'=>'How quickly is hosting activated?','a'=>'Hosting is activated automatically within 5 minutes of confirmed payment.'],
                        ['q'=>'Is free SSL included?','a'=>'Yes, all hosting plans include a free Let\'s Encrypt SSL certificate installed automatically.'],
                    ] as $i => $faq)
                    <div class="accordion-item border-0 mb-2 rounded-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i==0?'':'collapsed' }} rounded-3 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}">
                            <div class="accordion-body text-muted">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


