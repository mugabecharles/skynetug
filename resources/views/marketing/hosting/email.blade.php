@extends('layouts.app')
@section('title', 'Business Email Hosting Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:4rem 0 3rem;">
    <div class="container text-center">
        <i class="bi bi-envelope-at-fill" style="font-size:2.5rem;color:#00C896;display:block;margin-bottom:1rem;"></i>
        <h1 class="fw-bold text-white mb-3">Professional Business Email</h1>
        <p class="text-white-50 mb-4" style="max-width:560px;margin:0 auto;">Get yourname@yourdomain.com with reliable webmail, spam filtering, and generous storage. Make your business look professional.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center mb-5">
        @forelse($packages as $pkg)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4" style="transition:all .25s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <i class="bi bi-envelope-fill fs-3" style="color:#0066FF;display:block;margin-bottom:.75rem;"></i>
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <p class="text-muted small">{{ $pkg->description }}</p>
                <div class="mb-3">
                    <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_yearly/12, 2) }}</span>
                    <span class="text-muted small">/mo</span>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $pkg->email_accounts==0?'Unlimited':$pkg->email_accounts }} Email Accounts</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $pkg->disk_space_mb==0?'Unlimited':number_format($pkg->disk_space_mb/1024,0).' GB' }} Storage</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Webmail Access</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>IMAP / POP3 / SMTP</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Spam & Virus Filtering</li>
                </ul>
                <a href="{{ auth()->check() ? route('order.hosting',$pkg->slug) : route('register') }}" class="btn btn-sky w-100">Get Started</a>
            </div>
        </div>
        @empty
        @foreach([
            ['name'=>'Email Starter','accounts'=>5,'storage'=>'5 GB','price_yearly'=>120000],
            ['name'=>'Email Business','accounts'=>20,'storage'=>'20 GB','price_yearly'=>240000],
            ['name'=>'Email Unlimited','accounts'=>'Unlimited','storage'=>'Unlimited','price_yearly'=>480000],
        ] as $p)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4" style="transition:all .25s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <i class="bi bi-envelope-fill fs-3" style="color:#0066FF;display:block;margin-bottom:.75rem;"></i>
                <h5 class="fw-bold">{{ $p['name'] }}</h5>
                <div class="mb-3">
                    <span style="font-size:2rem;font-weight:800;">$ {{ number_format($p['price_yearly']/12, 2) }}</span>
                    <span class="text-muted small">/mo</span>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $p['accounts'] }} Email Accounts</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $p['storage'] }} Storage</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Webmail Access</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>IMAP / POP3 / SMTP</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Spam Filtering</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Email Forwarding</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-sky w-100">Get Started</a>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>

    <div class="text-center py-4 px-4 rounded-3" style="background:#f0f4ff;border:1px solid #dde8ff;">
        <h5 class="fw-bold mb-2">Already have hosting?</h5>
        <p class="text-muted small mb-3">Email hosting is included free with all shared hosting plans. Log in to cPanel to create your email accounts.</p>
        <a href="{{ route('login') }}" class="btn btn-sky btn-sm px-4">Login to Dashboard</a>
    </div>
</div>
@endsection


