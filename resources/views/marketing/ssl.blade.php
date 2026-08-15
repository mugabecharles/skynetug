@extends('layouts.app')
@section('title', 'SSL Certificates Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:4rem 0 3rem;">
    <div class="container text-center">
        <i class="bi bi-shield-lock-fill fs-1 text-success d-block mb-3"></i>
        <h1 class="fw-bold text-white mb-3">SSL Certificates</h1>
        <p class="text-white-50">Secure your website, build trust, and rank higher on Google. Free SSL included with all hosting plans.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @forelse($sslPackages as $pkg)
        <div class="col-md-6 col-lg-3">
            <div class="bg-white rounded-3 border p-4" style="transition:all .2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <p class="text-muted small">{{ $pkg->description }}</p>
                <div class="fw-bold fs-4 text-sky mb-3">$ {{ number_format($pkg->price_yearly) }}<span class="text-muted fs-6">/yr</span></div>
                <a href="{{ route('order.hosting', $pkg->slug) }}" class="btn btn-sky w-100">Get Certificate</a>
            </div>
        </div>
        @empty
        @foreach([
            ['name'=>'Free SSL (Let\'s Encrypt)','price'=>0,'desc'=>'Included free with all hosting plans. Auto-renewed.'],
            ['name'=>'DV SSL','price'=>150000,'desc'=>'Domain Validation SSL for personal & small business sites.'],
            ['name'=>'OV SSL','price'=>350000,'desc'=>'Organisation Validation for business credibility.'],
            ['name'=>'Wildcard SSL','price'=>500000,'desc'=>'Covers your domain and all subdomains.'],
        ] as $ssl)
        <div class="col-md-6 col-lg-3">
            <div class="bg-white rounded-3 border p-4">
                <i class="bi bi-shield-check fs-3 text-success d-block mb-2"></i>
                <h5 class="fw-bold">{{ $ssl['name'] }}</h5>
                <p class="text-muted small">{{ $ssl['desc'] }}</p>
                <div class="fw-bold fs-4 text-sky mb-3">
                    {{ $ssl['price'] == 0 ? 'FREE' : '$ '.number_format($ssl['price']).'/yr' }}
                </div>
                <a href="{{ route('hosting.shared') }}" class="btn {{ $ssl['price']==0 ? 'btn-success' : 'btn-sky' }} w-100">
                    {{ $ssl['price']==0 ? 'Get With Hosting' : 'Buy Now' }}
                </a>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
@endsection


