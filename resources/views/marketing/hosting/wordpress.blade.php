@extends('layouts.app')
@section('title', 'WordPress Hosting Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#1a0a3e);padding:4rem 0 3rem;">
    <div class="container text-center">
        <i class="bi bi-wordpress" style="font-size:3rem;color:#0066FF;display:block;margin-bottom:1rem;"></i>
        <span class="section-badge" style="background:rgba(33,117,155,.2);color:#00C896;border:1px solid rgba(33,117,155,.3);">WordPress Hosting</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">WordPress Hosting Built for Speed</h1>
        <p class="text-white-50 mb-4" style="max-width:560px;margin:0 auto;">Pre-configured WordPress environment with auto-updates, staging, and daily backups. Get your site live in minutes.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap" style="font-size:.85rem;">
            @foreach(['✅ 1-Click WordPress Install','✅ Auto SSL','✅ Daily Backups','✅ cPanel Included','✅ 24/7 Support'] as $f)
                <span class="text-white-50">{{ $f }}</span>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @forelse($packages as $pkg)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4 h-100" style="transition:all .25s;position:relative;" onmouseover="this.style.boxShadow='0 12px 32px rgba(0,0,0,.1)';this.style.transform='translateY(-4px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                @if($pkg->is_featured)
                <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#0066FF;color:#fff;font-size:.72rem;font-weight:700;border-radius:20px;padding:.25rem 1rem;">⭐ Most Popular</div>
                @endif
                <div class="mb-2"><i class="bi bi-wordpress" style="color:#0066FF;font-size:1.5rem;"></i></div>
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <p class="text-muted small mb-3">{{ $pkg->description }}</p>
                <div class="mb-4">
                    <span style="font-size:2rem;font-weight:800;">$ {{ number_format($pkg->price_yearly/12, 2) }}</span>
                    <span class="text-muted small">/mo</span>
                    <div class="text-muted small">Billed $ {{ number_format($pkg->price_yearly, 2) }}/yr</div>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $pkg->disk_space_mb==0?'Unlimited':number_format($pkg->disk_space_mb/1024,0).' GB' }} SSD Storage</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Unlimited Bandwidth</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free SSL Certificate</li>
                    <li class="py-1"><i class="bi bi-check2 text-success me-2"></i>1-Click WordPress Install</li>
                    @if($pkg->backup_included)<li class="py-1"><i class="bi bi-check2 text-success me-2"></i>Free Daily Backups</li>@endif
                    @foreach((array)$pkg->features as $feat)<li class="py-1"><i class="bi bi-check2 text-success me-2"></i>{{ $feat }}</li>@endforeach
                </ul>
                <form method="POST" action="{{ route('cart.add.public') }}">
                    @csrf
                    <input type="hidden" name="type" value="hosting">
                    <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                    <input type="hidden" name="name" value="{{ $pkg->name }}">
                    <input type="hidden" name="billing_cycle" value="yearly">
                    <button type="submit" class="btn {{ $pkg->is_featured ? 'btn-sky' : 'btn-outline-primary' }} w-100 py-2 fw-semibold">
                        <i class="bi bi-cart-plus me-2"></i>Get Started
                    </button>
                </form>
            </div>
        </div>
        @empty
        @foreach([
            ['name'=>'WordPress Starter','price_yearly'=>240000,'disk'=>'10 GB','popular'=>false,'slug'=>'wp-starter'],
            ['name'=>'WordPress Business','price_yearly'=>480000,'disk'=>'30 GB','popular'=>true,'slug'=>'wp-business'],
            ['name'=>'Managed WordPress','price_yearly'=>840000,'disk'=>'Unlimited','popular'=>false,'slug'=>'wp-managed'],
        ] as $p)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4" style="position:relative;{{ $p['popular'] ? 'border-color:#0066FF;box-shadow:0 0 0 3px rgba(0,102,255,.1);' : '' }}">
                @if($p['popular'])<div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#0066FF;color:#fff;font-size:.72rem;font-weight:700;border-radius:20px;padding:.25rem 1rem;">⭐ Most Popular</div>@endif
                <div class="mb-2"><i class="bi bi-wordpress" style="color:#0066FF;font-size:1.5rem;"></i></div>
                <h5 class="fw-bold">{{ $p['name'] }}</h5>
                <div class="mb-4">
                    <span style="font-size:2rem;font-weight:800;">$ {{ number_format($p['price_yearly']/12, 2) }}</span>
                    <span class="text-muted small">/mo</span>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    @foreach(['bi-hdd'=>$p['disk'].' SSD Storage','bi-arrow-down-up'=>'Unlimited Bandwidth','bi-shield-check'=>'Free SSL','bi-lightning'=>'1-Click WordPress','bi-cloud-check'=>'Daily Backups','bi-headset'=>'24/7 Support'] as $icon=>$feat)
                    <li class="py-1"><i class="bi {{ $icon }} text-success me-2"></i>{{ $feat }}</li>
                    @endforeach
                </ul>
                <form method="POST" action="{{ route('cart.add.public') }}">
                    @csrf
                    <input type="hidden" name="type" value="hosting">
                    <input type="hidden" name="name" value="{{ $p['name'] }}">
                    <input type="hidden" name="billing_cycle" value="yearly">
                    <input type="hidden" name="price" value="{{ $p['price_yearly'] }}">
                    <button type="submit" class="btn {{ $p['popular'] ? 'btn-sky' : 'btn-outline-primary' }} w-100">
                        <i class="bi bi-cart-plus me-2"></i>Get Started
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
@endsection



