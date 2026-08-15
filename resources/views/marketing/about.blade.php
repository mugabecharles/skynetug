@extends('layouts.app')
@section('title', 'About Us — SkyNetUG')
@section('meta_description', 'Learn about SkyNetUG — Uganda\'s trusted web hosting reseller providing affordable, reliable hosting and domain registration with local support.')

@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,#0A0F1E,#0D1433);padding:4rem 0 3rem;">
    <div class="container text-center">
        <span style="display:inline-block;background:rgba(0,200,150,.15);color:#00C896;border:1px solid rgba(0,200,150,.3);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:1rem;">About Us</span>
        <h1 class="fw-bold text-white mb-3" style="font-size:clamp(1.8rem,4vw,2.8rem);">SkyNetUG Reliable Hosting<br>&amp; Domain Registration</h1>
        <p style="color:rgba(255,255,255,.65);max-width:620px;margin:0 auto;font-size:1rem;line-height:1.75;">
            A trusted Ugandan web hosting reseller dedicated to helping individuals, startups,
            businesses, organizations, and developers build a strong and reliable online presence.
        </p>
    </div>
</div>

{{-- Welcome / Intro --}}
<section style="padding:4rem 0; background:#fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <p style="font-size:1.05rem;line-height:1.85;color:#374151;">
                    Founded with a vision to make professional web hosting accessible and affordable in Uganda and beyond,
                    <strong>SkyNetUG</strong> partners with industry-leading infrastructure providers to deliver fast,
                    secure, and dependable hosting solutions backed by local customer support and personalized service.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Who We Are --}}
<section style="padding:3rem 0 4rem; background:#f8fafc;">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <span style="display:inline-block;background:rgba(0,102,255,.08);color:#0066FF;border:1px solid rgba(0,102,255,.15);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:.75rem;">Who We Are</span>
                <h2 style="font-size:1.75rem;font-weight:800;color:#0A0F1E;margin-bottom:1rem;">A Customer-Focused<br>Hosting Company</h2>
                <p style="color:#6B7280;line-height:1.8;font-size:.95rem;">
                    SkyNetUG is a customer-focused hosting reseller company that combines enterprise-grade
                    hosting infrastructure with a local understanding of our clients' needs, ensuring that
                    every website we host receives the performance, security, and attention it deserves.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    @foreach([
                        ['icon'=>'bi-server',           'label'=>'Shared Web Hosting'],
                        ['icon'=>'bi-building',         'label'=>'Business Hosting'],
                        ['icon'=>'bi-link-45deg',       'label'=>'Domain Registration & Renewal'],
                        ['icon'=>'bi-envelope-at',      'label'=>'Email Hosting'],
                        ['icon'=>'bi-shield-lock',      'label'=>'SSL Certificates'],
                        ['icon'=>'bi-arrow-left-right', 'label'=>'Website Migration'],
                        ['icon'=>'bi-headset',          'label'=>'Technical Support'],
                        ['icon'=>'bi-lightbulb',        'label'=>'Hosting Consultation'],
                    ] as $svc)
                    <div class="col-6 col-md-6">
                        <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#fff;border:1px solid #e8ecf0;border-radius:10px;">
                            <i class="bi {{ $svc['icon'] }}" style="color:#0066FF;font-size:1.1rem;flex-shrink:0;"></i>
                            <span style="font-size:.875rem;font-weight:600;color:#1C2333;">{{ $svc['label'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section style="padding:4rem 0; background:#fff;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div style="background:linear-gradient(135deg,#0066FF,#0D1433);border-radius:16px;padding:2.5rem;height:100%;">
                    <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                        <i class="bi bi-bullseye" style="color:#fff;font-size:1.4rem;"></i>
                    </div>
                    <h3 style="color:#fff;font-weight:800;font-size:1.25rem;margin-bottom:.75rem;">Our Mission</h3>
                    <p style="color:rgba(255,255,255,.8);line-height:1.8;margin:0;font-size:.95rem;">
                        To provide reliable, secure, and affordable web hosting and domain services that
                        empower businesses and individuals to succeed online with confidence.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div style="background:linear-gradient(135deg,#00C896,#0A5c44);border-radius:16px;padding:2.5rem;height:100%;">
                    <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                        <i class="bi bi-eye" style="color:#fff;font-size:1.4rem;"></i>
                    </div>
                    <h3 style="color:#fff;font-weight:800;font-size:1.25rem;margin-bottom:.75rem;">Our Vision</h3>
                    <p style="color:rgba(255,255,255,.85);line-height:1.8;margin:0;font-size:.95rem;">
                        To become one of Uganda's most trusted and customer-centered web hosting brands,
                        known for reliability, transparency, innovation, and exceptional support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section style="padding:4rem 0; background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <span style="display:inline-block;background:rgba(0,102,255,.08);color:#0066FF;border:1px solid rgba(0,102,255,.15);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:.75rem;">Why Choose SkyNetUG?</span>
            <h2 style="font-size:1.9rem;font-weight:800;color:#0A0F1E;">What Sets Us Apart</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'bi-hdd-rack-fill',  'color'=>'#EFF6FF', 'ic'=>'#0066FF', 'title'=>'Reliable Infrastructure',   'desc'=>'We use high-performance hosting servers designed for speed, uptime, and stability, ensuring your website remains available when your customers need it.'],
                ['icon'=>'bi-people-fill',    'color'=>'#ECFDF5', 'ic'=>'#059669', 'title'=>'Local Support',              'desc'=>'Our support team understands the local business environment and provides timely assistance through friendly and professional communication.'],
                ['icon'=>'bi-tag-fill',       'color'=>'#FFF7ED', 'ic'=>'#EA580C', 'title'=>'Affordable Pricing',        'desc'=>'We offer competitively priced hosting and domain packages without compromising on quality or security.'],
                ['icon'=>'bi-shield-fill-check','color'=>'#F5F3FF','ic'=>'#7C3AED','title'=>'Secure Hosting',             'desc'=>'Your website is protected with modern security measures, regular monitoring, and SSL support to keep your data safe.'],
                ['icon'=>'bi-arrow-up-circle-fill','color'=>'#FFF1F2','ic'=>'#E11D48','title'=>'Easy Scalability',        'desc'=>'Whether you are launching your first website or managing multiple business websites, our solutions can grow with your needs.'],
                ['icon'=>'bi-hand-thumbs-up-fill','color'=>'#F0FDF4','ic'=>'#15803D','title'=>'Transparent Service',      'desc'=>'Honest pricing, clear communication, and no hidden fees. We build long-term partnerships based on trust.'],
            ] as $item)
            <div class="col-md-6 col-lg-4">
                <div style="background:#fff;border:1px solid #e8ecf0;border-radius:14px;padding:1.75rem;height:100%;transition:box-shadow .2s;"
                     onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,.07)'"
                     onmouseout="this.style.boxShadow=''">
                    <div style="width:48px;height:48px;background:{{ $item['color'] }};border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="bi {{ $item['icon'] }}" style="color:{{ $item['ic'] }};font-size:1.3rem;"></i>
                    </div>
                    <h5 style="font-weight:700;font-size:1rem;color:#0A0F1E;margin-bottom:.5rem;">{{ $item['title'] }}</h5>
                    <p style="color:#6B7280;font-size:.875rem;line-height:1.7;margin:0;">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Our Commitment --}}
<section style="padding:4rem 0; background:#fff;">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span style="display:inline-block;background:rgba(0,102,255,.08);color:#0066FF;border:1px solid rgba(0,102,255,.15);border-radius:20px;padding:.3rem 1rem;font-size:.8rem;font-weight:600;margin-bottom:.75rem;">Our Commitment</span>
                <h2 style="font-size:1.75rem;font-weight:800;color:#0A0F1E;margin-bottom:1rem;">More Than Just Server Space</h2>
                <p style="color:#6B7280;line-height:1.8;font-size:.95rem;margin-bottom:1.5rem;">
                    At SkyNetUG, we believe that hosting is more than just server space. It is the foundation
                    of your digital identity. We are committed to delivering:
                </p>
                <ul style="list-style:none;padding:0;margin:0;">
                    @foreach([
                        'Honest and transparent service',
                        'Consistent uptime and performance',
                        'Responsive technical support',
                        'Continuous improvement of our services',
                        'Long-term partnerships with our clients',
                    ] as $item)
                    <li style="display:flex;align-items:center;gap:10px;padding:.55rem 0;border-bottom:1px solid #f3f4f6;font-size:.9rem;color:#374151;">
                        <i class="bi bi-check-circle-fill" style="color:#00C896;flex-shrink:0;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-6">
                <div style="background:linear-gradient(135deg,#0A0F1E,#0D1433);border-radius:16px;padding:2.5rem;">
                    <h3 style="color:#fff;font-weight:800;font-size:1.2rem;margin-bottom:1rem;">
                        <i class="bi bi-geo-alt-fill me-2" style="color:#00C896;"></i>Serving Businesses Across Uganda
                    </h3>
                    <p style="color:rgba(255,255,255,.75);line-height:1.8;font-size:.9rem;margin-bottom:1.5rem;">
                        We proudly support entrepreneurs, SMEs, schools, NGOs, churches, institutions,
                        e-commerce stores, and digital professionals who need a dependable hosting partner.
                        Our goal is to simplify the technical side of hosting so that our clients can focus
                        on growing their businesses.
                    </p>
                    <div class="row g-2">
                        @foreach(['Entrepreneurs','SMEs','Schools','NGOs','Churches','E-commerce','Institutions','Developers'] as $client)
                        <div class="col-6">
                            <div style="display:flex;align-items:center;gap:6px;font-size:.82rem;color:rgba(255,255,255,.7);">
                                <i class="bi bi-check2" style="color:#00C896;"></i>{{ $client }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#0066FF,#0050CC);padding:4rem 0;">
    <div class="container text-center">
        <h2 style="color:#fff;font-size:1.8rem;font-weight:800;margin-bottom:1rem;">
            Let's Build Your Online Presence
        </h2>
        <p style="color:rgba(255,255,255,.85);font-size:1rem;max-width:600px;margin:0 auto 2rem;line-height:1.75;">
            Whether you need a new domain name, reliable web hosting, or assistance moving your website
            to a better platform, SkyNetUG is ready to help.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('hosting.shared') }}" style="background:#fff;color:#0066FF;border-radius:8px;padding:12px 32px;font-weight:700;text-decoration:none;font-size:.95rem;transition:opacity .15s;" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                <i class="bi bi-server me-2"></i>View Hosting Plans
            </a>
            <a href="{{ route('contact') }}" style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,.6);border-radius:8px;padding:12px 32px;font-weight:700;text-decoration:none;font-size:.95rem;transition:all .15s;" onmouseover="this.style.borderColor='#fff'" onmouseout="this.style.borderColor='rgba(255,255,255,.6)'">
                <i class="bi bi-headset me-2"></i>Contact Us
            </a>
        </div>
        <p style="color:rgba(255,255,255,.6);font-size:.85rem;margin-top:2rem;font-style:italic;">
            SkyNetUG — Reliable Hosting. Trusted Support. Stronger Online Presence.
        </p>
    </div>
</section>

@endsection
