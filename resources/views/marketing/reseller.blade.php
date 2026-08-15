@extends('layouts.app')
@section('title', 'cPanel Reseller Hosting Uganda')
@section('meta_description', 'Start your own web hosting business in Uganda with SkyNetug cPanel Reseller Hosting. White-label, affordable, with WHM control panel.')

@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,#0A0F1E,#0D1433);padding:4rem 0 3rem;">
    <div class="container text-center">
        <span style="display:inline-block;background:rgba(0,200,150,.15);color:#00C896;border:1px solid rgba(0,200,150,.3);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:1rem;">Reseller Hosting</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,2.8rem);">
            cPanel Reseller Hosting<br>
            <span style="color:#0066FF;">Start Your Own Hosting Business</span>
        </h1>
        <p style="color:rgba(255,255,255,.7);max-width:620px;margin:0 auto 2rem;font-size:1rem;line-height:1.75;">
            Become a web hosting reseller in Uganda. Sell hosting under your own brand with full
            WHM control, automated billing, and SkyNetug's reliable infrastructure behind you.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('contact') }}" style="background:#0066FF;color:#fff;border-radius:8px;padding:12px 28px;font-weight:700;text-decoration:none;font-size:.95rem;">
                <i class="bi bi-envelope me-2"></i>Get a Custom Quote
            </a>
            <a href="{{ route('pricing') }}" style="background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.4);border-radius:8px;padding:12px 28px;font-weight:600;text-decoration:none;font-size:.95rem;">
                View All Plans
            </a>
        </div>
    </div>
</div>

{{-- Reseller Plans --}}
<section style="padding:4rem 0;background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <span style="display:inline-block;background:rgba(0,102,255,.08);color:#0066FF;border:1px solid rgba(0,102,255,.15);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:.75rem;">Reseller Plans</span>
            <h2 style="font-size:1.9rem;font-weight:800;color:#0A0F1E;">Choose Your Reseller Plan</h2>
            <p style="color:#6B7280;">All plans include WHM, WHMCS billing, white-label branding, and 24/7 support.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach([
                [
                    'name'     => 'Reseller Starter',
                    'price'    => '29.99',
                    'popular'  => false,
                    'accounts' => '25 cPanel Accounts',
                    'disk'     => '25 GB SSD Storage',
                    'bw'       => '250 GB Bandwidth',
                    'features' => ['WHM Control Panel','White-label Branding','Overselling Allowed','Free SSL per Account','WHMCS Compatible','24/7 Support'],
                ],
                [
                    'name'     => 'Reseller Business',
                    'price'    => '59.99',
                    'popular'  => true,
                    'accounts' => '50 cPanel Accounts',
                    'disk'     => '60 GB SSD Storage',
                    'bw'       => '600 GB Bandwidth',
                    'features' => ['WHM Control Panel','White-label Branding','Overselling Allowed','Free SSL per Account','WHMCS Compatible','Priority Support'],
                ],
                [
                    'name'     => 'Reseller Pro',
                    'price'    => '99.99',
                    'popular'  => false,
                    'accounts' => 'Unlimited cPanel Accounts',
                    'disk'     => '150 GB SSD Storage',
                    'bw'       => 'Unlimited Bandwidth',
                    'features' => ['WHM Control Panel','White-label Branding','Overselling Allowed','Free SSL per Account','WHMCS Compatible','Dedicated Support Manager'],
                ],
            ] as $plan)
            <div class="col-md-6 col-lg-4">
                <div style="border:{{ $plan['popular'] ? '2px solid #0066FF' : '1.5px solid #e8ecf0' }};border-radius:18px;padding:2rem;background:#fff;position:relative;transition:box-shadow .2s;height:100%;display:flex;flex-direction:column;{{ $plan['popular'] ? 'box-shadow:0 0 0 4px rgba(0,102,255,.08);' : '' }}"
                     onmouseover="this.style.boxShadow='0 12px 40px rgba(0,102,255,.1)'"
                     onmouseout="this.style.boxShadow='{{ $plan['popular'] ? '0 0 0 4px rgba(0,102,255,.08)' : '' }}'">

                    @if($plan['popular'])
                    <div style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:#0066FF;color:#fff;font-size:.78rem;font-weight:700;border-radius:20px;padding:.3rem 1.1rem;white-space:nowrap;">⭐ Most Popular</div>
                    @endif

                    <h4 style="font-weight:800;color:#0A0F1E;margin-bottom:.25rem;">{{ $plan['name'] }}</h4>

                    <div style="margin:1rem 0;">
                        <span data-usd="{{ $plan['price'] }}" style="font-size:2.4rem;font-weight:800;color:#0A0F1E;">$ {{ $plan['price'] }}</span>
                        <span style="color:#6B7280;font-size:.9rem;">/mo</span>
                    </div>

                    <div style="background:#f8fafc;border-radius:10px;padding:12px 16px;margin-bottom:1.25rem;">
                        <div style="font-weight:700;color:#0066FF;font-size:.9rem;margin-bottom:4px;"><i class="bi bi-people me-2"></i>{{ $plan['accounts'] }}</div>
                        <div style="font-size:.85rem;color:#374151;"><i class="bi bi-hdd me-2 text-muted"></i>{{ $plan['disk'] }}</div>
                        <div style="font-size:.85rem;color:#374151;"><i class="bi bi-arrow-down-up me-2 text-muted"></i>{{ $plan['bw'] }}</div>
                    </div>

                    <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                        @foreach($plan['features'] as $feat)
                        <li style="display:flex;align-items:center;gap:8px;padding:.45rem 0;border-bottom:1px solid #f3f4f6;font-size:.875rem;color:#374151;">
                            <i class="bi bi-check2" style="color:#00C896;font-size:1rem;flex-shrink:0;"></i>{{ $feat }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('contact') }}"
                       style="display:block;width:100%;background:{{ $plan['popular'] ? '#0066FF' : '#f8fafc' }};color:{{ $plan['popular'] ? '#fff' : '#0066FF' }};border:{{ $plan['popular'] ? 'none' : '1.5px solid #0066FF' }};border-radius:10px;padding:13px 0;font-size:.95rem;font-weight:700;text-align:center;text-decoration:none;transition:background .15s;">
                        Get Started
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Why Resell with SkyNetug --}}
<section style="padding:4rem 0;background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-size:1.75rem;font-weight:800;color:#0A0F1E;">Why Resell with SkyNetug?</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'bi-shield-fill-check','color'=>'#EFF6FF','ic'=>'#0066FF','title'=>'Full WHM Access',          'desc'=>'Get complete WHM control to create, manage, and suspend cPanel accounts for your clients.'],
                ['icon'=>'bi-brush-fill',        'color'=>'#F5F3FF','ic'=>'#7C3AED','title'=>'White-label Branding',    'desc'=>'Brand the hosting completely as your own. Your company name, your logo, your business.'],
                ['icon'=>'bi-cpu-fill',          'color'=>'#ECFDF5','ic'=>'#059669','title'=>'Reliable Infrastructure', 'desc'=>'Your clients get the same fast, secure servers that power all SkyNetug hosting accounts.'],
                ['icon'=>'bi-headset',           'color'=>'#FFF7ED','ic'=>'#EA580C','title'=>'24/7 Reseller Support',  'desc'=>'Dedicated support channel for resellers. We help you so you can help your clients.'],
                ['icon'=>'bi-cash-coin',         'color'=>'#FFF1F2','ic'=>'#E11D48','title'=>'Competitive Margins',    'desc'=>'Buy at wholesale and sell at your own prices. Keep 100% of the profit difference.'],
                ['icon'=>'bi-arrow-up-circle',   'color'=>'#F0FDF4','ic'=>'#15803D','title'=>'Instant Scalability',    'desc'=>'Start small and upgrade your reseller plan as your client base grows. No migration needed.'],
            ] as $item)
            <div class="col-md-6 col-lg-4">
                <div style="background:#fff;border:1px solid #e8ecf0;border-radius:14px;padding:1.5rem;height:100%;">
                    <div style="width:46px;height:46px;background:{{ $item['color'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="bi {{ $item['icon'] }}" style="color:{{ $item['ic'] }};font-size:1.2rem;"></i>
                    </div>
                    <h6 style="font-weight:700;margin-bottom:.4rem;color:#0A0F1E;">{{ $item['title'] }}</h6>
                    <p style="color:#6B7280;font-size:.875rem;line-height:1.7;margin:0;">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#0066FF,#0050CC);padding:3.5rem 0;">
    <div class="container text-center">
        <h2 style="color:#fff;font-size:1.7rem;font-weight:800;margin-bottom:1rem;">Ready to Start Your Hosting Business?</h2>
        <p style="color:rgba(255,255,255,.85);max-width:560px;margin:0 auto 2rem;line-height:1.75;">
            Contact us today for a custom reseller package tailored to your business needs.
        </p>
        <a href="{{ route('contact') }}" style="background:#fff;color:#0066FF;border-radius:8px;padding:12px 32px;font-weight:700;text-decoration:none;font-size:.95rem;">
            <i class="bi bi-envelope me-2"></i>Contact Us Now
        </a>
    </div>
</section>

@endsection
