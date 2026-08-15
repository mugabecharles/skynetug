@extends('layouts.app')
@section('title', 'Website Design Services Uganda')

@section('content')
<div style="background:linear-gradient(135deg,#0A0F1E,#0A0F1E);padding:4rem 0 3rem;">
    <div class="container text-center">
        <h1 class="fw-bold text-white mb-3">Professional Website Design</h1>
        <p class="text-white-50">Custom websites built for Ugandan businesses. Mobile-first, fast, and affordable.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @forelse($designPackages as $pkg)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4">
                <h5 class="fw-bold">{{ $pkg->name }}</h5>
                <p class="text-muted small">{{ $pkg->description }}</p>
                <div class="fw-bold fs-4 text-sky mb-3">$ {{ number_format($pkg->price_yearly) }}</div>
                <a href="{{ route('contact') }}" class="btn btn-sky w-100">Get a Quote</a>
            </div>
        </div>
        @empty
        @foreach([
            ['name'=>'Corporate Website','pages'=>5,'price'=>1500000,'desc'=>'Professional corporate website with about, services, team, and contact pages.'],
            ['name'=>'School Website','pages'=>8,'price'=>2000000,'desc'=>'Complete school website with admissions, academics, news, and gallery.'],
            ['name'=>'E-commerce Website','pages'=>15,'price'=>4000000,'desc'=>'Full online store with product listings, cart, and mobile money checkout.'],
            ['name'=>'NGO / Non-Profit','pages'=>6,'price'=>1800000,'desc'=>'Compelling NGO site with projects, donations, and impact reporting.'],
            ['name'=>'Hotel / Restaurant','pages'=>7,'price'=>2200000,'desc'=>'Beautiful hotel site with rooms, gallery, booking form, and menus.'],
            ['name'=>'Portfolio Website','pages'=>4,'price'=>1000000,'desc'=>'Showcase your work with a stunning personal or creative portfolio.'],
        ] as $d)
        <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-3 border p-4" style="transition:all .2s;" onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow=''">
                <h5 class="fw-bold">{{ $d['name'] }}</h5>
                <p class="text-muted small">{{ $d['desc'] }}</p>
                <ul class="list-unstyled small mb-3">
                    <li><i class="bi bi-check2 text-success me-1"></i>{{ $d['pages'] }} pages</li>
                    <li><i class="bi bi-check2 text-success me-1"></i>Mobile-responsive design</li>
                    <li><i class="bi bi-check2 text-success me-1"></i>Free hosting (1 year)</li>
                    <li><i class="bi bi-check2 text-success me-1"></i>Free domain (1 year)</li>
                    <li><i class="bi bi-check2 text-success me-1"></i>SSL certificate</li>
                </ul>
                <div class="fw-bold fs-5 text-sky mb-3">$ {{ number_format($d['price']) }}</div>
                <a href="{{ route('contact') }}" class="btn btn-sky w-100">Get a Quote</a>
            </div>
        </div>
        @endforeach
        @endforelse
    </div>
</div>
@endsection


