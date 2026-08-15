@extends('layouts.app')
@section('title', 'VPS Hosting Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:4rem 0 3rem;">
    <div class="container text-center">
        <i class="bi bi-cpu-fill" style="font-size:2.5rem;color:#00C896;display:block;margin-bottom:1rem;"></i>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,3rem);">VPS Hosting — Full Root Access</h1>
        <p class="text-white-50 mb-4" style="max-width:560px;margin:0 auto;">Dedicated virtual resources with guaranteed CPU, RAM, and SSD. Full root access, scalable on demand.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @forelse($packages as $pkg)
        <div class="col-md-6 col-lg-3">
            <div class="bg-white rounded-3 border p-4" style="transition:all .25s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <p class="text-muted small">{{ $pkg->description }}</p>
                <div class="mb-3">
                    <span style="font-size:1.8rem;font-weight:800;">$ {{ number_format($pkg->price_monthly) }}</span>
                    <span class="text-muted small">/mo</span>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    @foreach((array)$pkg->features as $feat)
                    <li class="py-1"><i class="bi bi-check2-circle text-success me-2"></i>{{ $feat }}</li>
                    @endforeach
                    <li class="py-1"><i class="bi bi-check2-circle text-success me-2"></i>Full Root Access</li>
                    <li class="py-1"><i class="bi bi-check2-circle text-success me-2"></i>SSD NVMe Storage</li>
                    <li class="py-1"><i class="bi bi-check2-circle text-success me-2"></i>Dedicated IP Address</li>
                </ul>
                <a href="{{ auth()->check() ? route('order.hosting',$pkg->slug) : route('register') }}" class="btn btn-sky w-100">Order Now</a>
            </div>
        </div>
        @empty
        @foreach([
            ['name'=>'VPS Starter',    'cpu'=>'1 vCPU', 'ram'=>'1 GB RAM',  'disk'=>'25 GB SSD',  'bw'=>'1 TB',   'price'=>120000],
            ['name'=>'VPS Business',   'cpu'=>'2 vCPU', 'ram'=>'2 GB RAM',  'disk'=>'50 GB SSD',  'bw'=>'2 TB',   'price'=>220000],
            ['name'=>'VPS Pro',        'cpu'=>'4 vCPU', 'ram'=>'4 GB RAM',  'disk'=>'100 GB SSD', 'bw'=>'4 TB',   'price'=>420000],
            ['name'=>'VPS Enterprise', 'cpu'=>'8 vCPU', 'ram'=>'8 GB RAM',  'disk'=>'200 GB SSD', 'bw'=>'8 TB',   'price'=>800000],
        ] as $p)
        <div class="col-md-6 col-lg-3">
            <div class="bg-white rounded-3 border p-4" style="transition:all .25s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)';this.style.transform='translateY(-3px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
                <i class="bi bi-cpu fs-3 text-sky d-block mb-2"></i>
                <h5 class="fw-bold">{{ $p['name'] }}</h5>
                <div class="mb-3">
                    <span style="font-size:1.8rem;font-weight:800;">$ {{ number_format($p['price']) }}</span>
                    <span class="text-muted small">/mo</span>
                </div>
                <ul class="list-unstyled mb-4" style="font-size:.875rem;">
                    @foreach(['bi-cpu'=>$p['cpu'],'bi-memory'=>$p['ram'],'bi-hdd'=>$p['disk'],'bi-arrow-down-up'=>$p['bw'].' Bandwidth'] as $icon=>$spec)
                    <li class="py-1 d-flex align-items-center gap-2">
                        <i class="bi {{ $icon }}" style="color:#0066FF;width:16px;"></i><span>{{ $spec }}</span>
                    </li>
                    @endforeach
                    <li class="py-1"><i class="bi bi-shield-check text-success me-2"></i>Free SSL</li>
                    <li class="py-1"><i class="bi bi-terminal text-success me-2"></i>Full Root Access</li>
                    <li class="py-1"><i class="bi bi-globe2 text-success me-2"></i>Dedicated IP</li>
                </ul>
                <a href="{{ route('contact') }}" class="btn btn-sky w-100">Order Now</a>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>

    <div class="row g-4 mt-4">
        @foreach([
            ['icon'=>'bi-cpu','title'=>'Dedicated Resources','desc'=>'Guaranteed CPU and RAM allocated exclusively to you — no shared contention.'],
            ['icon'=>'bi-hdd-fill','title'=>'NVMe SSD Storage','desc'=>'Ultra-fast NVMe solid-state drives for blazing I/O performance.'],
            ['icon'=>'bi-terminal','title'=>'Full Root Access','desc'=>'Complete server control via SSH. Install any software you need.'],
            ['icon'=>'bi-arrow-up-circle','title'=>'Easy Scaling','desc'=>'Upgrade your VPS resources in minutes as your business grows.'],
        ] as $f)
        <div class="col-md-6 col-lg-3 text-center">
            <div class="p-3 bg-light rounded-3">
                <i class="bi {{ $f['icon'] }} fs-2 text-sky d-block mb-2"></i>
                <h6 class="fw-bold">{{ $f['title'] }}</h6>
                <p class="text-muted small mb-0">{{ $f['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection


