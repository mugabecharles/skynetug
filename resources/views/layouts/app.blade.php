<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkyNetug') | SkyNetug Web Hosting</title>
    <meta name="description" content="@yield('meta_description', 'SkyNetug - Fast, Reliable & Affordable Web Hosting and Domain Registration')">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sky-primary:   #0066FF;
            --sky-secondary: #00C896;
            --sky-dark:      #0A0F1E;
            --sky-mid:       #0D1433;
            --sky-gray:      #6B7280;
            --topbar-bg:     #0A0F1E;
        }
        * { font-family: 'Inter', sans-serif; }
        html, body { margin:0; padding:0; border:0; outline:0; line-height:0; }
        body { background:#fff; color:#1C2333; line-height:normal; font-size:14px; }
        /* Kill any whitespace text node rendered before the first element */
        body > :first-child,
        .topbar-strip { margin-top:-9px !important; position:relative; top:0; }

        /* ── TOP BAR ─────────────────────────────── */
        .topbar-strip {
            background: var(--topbar-bg);
            padding: 7px 0;
            font-size: 12.5px;
            color: rgba(255,255,255,.7);
            border-bottom: 1px solid rgba(255,255,255,.06);
            margin-top: -1px; /* absorb any whitespace gap */
        }
        .topbar-strip a { color:rgba(255,255,255,.7); text-decoration:none; transition:color .15s; }
        .topbar-strip a:hover { color:#fff; }
        .topbar-strip .sep { margin:0 10px; opacity:.3; }

        /* ── MAIN NAV ────────────────────────────── */
        .mainnav {
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .mainnav .container { display:flex; align-items:center; height:62px; gap:8px; }

        /* Brand */
        .brand-wrap { display:flex; align-items:center; gap:10px; text-decoration:none; margin-right:20px; flex-shrink:0; }
        .brand-icon { width:38px; height:38px; background:var(--sky-primary); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900; font-size:18px; flex-shrink:0; }
        .brand-text { line-height:1.1; }
        .brand-text .t1 { font-size:1.1rem; font-weight:800; color:#0A0F1E; letter-spacing:-.3px; }
        .brand-text .t1 span { color:var(--sky-primary); }
        .brand-text .t2 { font-size:10px; color:#9CA3AF; font-weight:500; letter-spacing:.3px; text-transform:uppercase; }

        /* Nav links */
        .nav-links { display:flex; align-items:center; gap:2px; flex:1; }
        .nav-links .nl { color:#374151; font-size:13.5px; font-weight:500; padding:8px 12px; border-radius:7px; text-decoration:none; display:flex; align-items:center; gap:4px; transition:background .15s, color .15s; white-space:nowrap; }
        .nav-links .nl:hover { background:#F3F4F6; color:#0066FF; }
        .nav-links .nl.active { color:#0066FF; background:#EFF6FF; }

        /* Dropdown */
        .nl-dropdown { position:relative; }
        .nl-dropdown:hover .nl-menu { display:block; }
        .nl-menu { display:none; position:absolute; top:calc(100% + 4px); left:0; background:#fff; border:1px solid #E8ECF0; border-radius:12px; box-shadow:0 10px 32px rgba(0,0,0,.1); min-width:200px; padding:6px; z-index:9999; }
        .nl-menu a { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; color:#374151; font-size:13px; font-weight:500; text-decoration:none; transition:background .12s; }
        .nl-menu a:hover { background:#F8FAFC; color:#0066FF; }
        .nl-menu a i { width:16px; color:#0066FF; }

        /* Right side */
        .nav-right { display:flex; align-items:center; gap:8px; margin-left:auto; flex-shrink:0; }
        .cart-btn { display:flex; align-items:center; gap:6px; border:1.5px solid #E8ECF0; border-radius:8px; padding:6px 12px; color:#374151; font-size:13px; font-weight:600; text-decoration:none; transition:all .15s; }
        .cart-btn:hover { border-color:#0066FF; color:#0066FF; }
        .cart-badge { background:#0066FF; color:#fff; border-radius:20px; padding:1px 7px; font-size:11px; font-weight:700; }
        .btn-signin { background:transparent; border:1.5px solid #374151; color:#374151; border-radius:8px; padding:7px 16px; font-size:13px; font-weight:600; text-decoration:none; transition:all .15s; white-space:nowrap; }
        .btn-signin:hover { border-color:#0066FF; color:#0066FF; }
        .btn-getstarted { background:#0066FF; color:#fff; border:none; border-radius:8px; padding:8px 18px; font-size:13px; font-weight:700; text-decoration:none; transition:background .15s; white-space:nowrap; }
        .btn-getstarted:hover { background:#0050CC; color:#fff; }

        /* Mobile toggle */
        .mob-toggle { display:none; background:none; border:none; font-size:22px; color:#374151; cursor:pointer; padding:4px; }
        .mob-menu { display:none; position:fixed; inset:0; background:#fff; z-index:2000; overflow-y:auto; padding:20px; }
        .mob-menu.open { display:block; }

        @media (max-width:991px) {
            .nav-links, .nav-right .btn-signin, .nav-right .btn-getstarted { display:none; }
            .mob-toggle { display:block; }
        }

        /* ── Buttons ─────────────────────────────── */
        .btn-sky { background:var(--sky-primary); color:#fff; border:none; border-radius:8px; font-weight:600; padding:.55rem 1.4rem; transition:all .2s; }
        .btn-sky:hover { background:#0050CC; color:#fff; transform:translateY(-1px); box-shadow:0 6px 20px rgba(0,102,255,.3); }
        .btn-sky-outline { border:1.5px solid #0066FF; color:#0066FF; background:transparent; border-radius:8px; font-weight:600; padding:.55rem 1.4rem; transition:all .2s; }
        .btn-sky-outline:hover { background:#0066FF; color:#fff; }
        .text-sky { color:var(--sky-primary)!important; }
        .text-green { color:var(--sky-secondary)!important; }
        .section-badge { display:inline-block; background:rgba(0,102,255,.08); color:var(--sky-primary); border-radius:20px; padding:.3rem 1rem; font-size:.8rem; font-weight:600; margin-bottom:1rem; border:1px solid rgba(0,102,255,.15); }
        .tld-badge { display:inline-block; background:#EFF6FF; color:var(--sky-primary); border-radius:6px; padding:.4rem .9rem; font-size:.82rem; font-weight:700; border:1px solid #dde8ff; }

        /* ── Footer ─────────────────────────────── */
        .footer-sky { background:var(--sky-dark); color:rgba(255,255,255,.7); padding:3.5rem 0 1.5rem; }
        .footer-sky h6 { color:#fff; font-weight:700; text-transform:uppercase; font-size:.72rem; letter-spacing:.8px; margin-bottom:1rem; }
        .footer-sky a { color:rgba(255,255,255,.6); text-decoration:none; font-size:.875rem; line-height:2.1; transition:color .2s; display:block; }
        .footer-sky a:hover { color:#fff; }
        .footer-sky .border-top { border-color:rgba(255,255,255,.08)!important; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══ TOP BAR STRIP ══════════════════════════════ --}}
<div style="background:#1a1a2a;padding:12px 0;font-size:12px;color:rgba(255,255,255,.7);border-bottom:1px solid rgba(255,255,255,.06);">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <span style="display:flex;align-items:center;gap:5px;">
                <i class="bi bi-telephone-fill" style="font-size:11px;color:rgba(255,255,255,.5);"></i>
                <a href="tel:+256761178087" style="color:rgba(255,255,255,.7);text-decoration:none;">+(256) 761 178 087 (24/7/365)</a>
            </span>
            <span style="color:rgba(255,255,255,.2);">|</span>
            <span style="display:flex;align-items:center;gap:5px;">
                <i class="bi bi-envelope-fill" style="font-size:11px;color:rgba(255,255,255,.5);"></i>
                <a href="mailto:support@skynetug.com" style="color:rgba(255,255,255,.7);text-decoration:none;">support@skynetug.com</a>
            </span>
            <span style="color:rgba(255,255,255,.2);">|</span>
            <span style="display:flex;align-items:center;gap:5px;">
                <i class="bi bi-question-circle" style="font-size:11px;color:rgba(255,255,255,.5);"></i>
                <a href="{{ route('kb.index') }}" style="color:rgba(255,255,255,.7);text-decoration:none;">Knowledge Base</a>
            </span>
        </div>
        <div class="d-flex align-items-center gap-3">
            @guest
                <a href="{{ route('login') }}" style="color:rgba(255,255,255,.7);text-decoration:none;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-box-arrow-in-right" style="font-size:12px;"></i> Sign In
                </a>
                <span style="color:rgba(255,255,255,.2);">|</span>
                <a href="{{ route('contact') }}" style="color:rgba(255,255,255,.7);text-decoration:none;display:flex;align-items:center;gap:4px;">
                    <i class="bi bi-flag" style="font-size:12px;"></i> Report Abuse
                </a>
            @else
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" style="color:rgba(255,255,255,.7);text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="bi bi-speedometer2" style="font-size:12px;"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('dashboard.index') }}" style="color:rgba(255,255,255,.7);text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="bi bi-grid" style="font-size:12px;"></i> My Dashboard
                    </a>
                @endif
                <span style="color:rgba(255,255,255,.2);">|</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.7);font-size:12px;cursor:pointer;padding:0;display:flex;align-items:center;gap:4px;">
                        <i class="bi bi-box-arrow-right" style="font-size:12px;"></i> Sign Out
                    </button>
                </form>
            @endguest
        </div>
    </div>
</div>

{{-- ══ MAIN NAVIGATION ════════════════════════════ --}}
<header class="mainnav">
    <div class="container">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="brand-wrap">
            <div class="brand-icon">S</div>
            <div class="brand-text">
                <div class="t1">Sky<span>Netug</span></div>
                <div class="t2">Web Hosting</div>
            </div>
        </a>

        {{-- Nav Links --}}
        <nav class="nav-links">
            {{-- Home --}}
            <a href="{{ route('home') }}" class="nl {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

            {{-- Email Hosting --}}
            <div class="nl-dropdown">
                <a href="{{ route('hosting.email') }}" class="nl {{ request()->routeIs('hosting.email') ? 'active' : '' }}">
                    Email Hosting <i class="bi bi-chevron-down" style="font-size:10px;opacity:.6;"></i>
                </a>
                <div class="nl-menu">
                    <a href="{{ route('hosting.email') }}"><i class="bi bi-envelope"></i>Business Email</a>
                    <a href="{{ route('pricing') }}"><i class="bi bi-grid"></i>Compare Plans</a>
                </div>
            </div>

            {{-- Shared Hosting --}}
            <div class="nl-dropdown">
                <a href="{{ route('hosting.shared') }}" class="nl {{ request()->routeIs('hosting.shared') || request()->routeIs('hosting.wordpress') ? 'active' : '' }}">
                    Shared Hosting <i class="bi bi-chevron-down" style="font-size:10px;opacity:.6;"></i>
                </a>
                <div class="nl-menu">
                    <a href="{{ route('hosting.shared') }}"><i class="bi bi-server"></i>Shared Hosting</a>
                    <a href="{{ route('hosting.wordpress') }}"><i class="bi bi-wordpress"></i>WordPress Hosting</a>
                    <a href="{{ route('pricing') }}"><i class="bi bi-grid"></i>All Plans & Pricing</a>
                </div>
            </div>

            {{-- Reseller / VPS --}}
            <div class="nl-dropdown">
                <a href="{{ route('hosting.vps') }}" class="nl {{ request()->routeIs('hosting.vps') ? 'active' : '' }}">
                    Cloud Servers <i class="bi bi-chevron-down" style="font-size:10px;opacity:.6;"></i>
                </a>
                <div class="nl-menu">
                    <a href="{{ route('hosting.vps') }}"><i class="bi bi-cpu"></i>VPS Hosting</a>
                    <a href="{{ route('contact') }}"><i class="bi bi-building"></i>Dedicated Servers</a>
                </div>
            </div>

            {{-- Reseller Hosting --}}
            <a href="{{ route('reseller') }}" class="nl {{ request()->routeIs('reseller') ? 'active' : '' }}">Reseller Hosting</a>

            {{-- About Us --}}
            <a href="{{ route('about') }}" class="nl {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>

            {{-- Contact Us --}}
            <a href="{{ route('contact') }}" class="nl {{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
        </nav>

        {{-- Right Side --}}
        <div class="nav-right">
            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="cart-btn" id="navCartBtn">
                <i class="bi bi-cart3"></i>
                @php $cartCount = count(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
                <span>Cart</span>
            </a>

            {{-- Currency Switcher --}}
            <div class="dropdown">
                <button class="cart-btn dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;gap:5px;">
                    <i class="bi bi-currency-exchange" style="font-size:13px;"></i>
                    <span id="activeCurrency">USD $</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius:10px;min-width:140px;">
                    @foreach([
                        ['code'=>'USD','symbol'=>'$','label'=>'USD — US Dollar'],
                        ['code'=>'UGX','symbol'=>'UGX','label'=>'UGX — Uganda Shilling'],
                        ['code'=>'KES','symbol'=>'KSh','label'=>'KES — Kenya Shilling'],
                        ['code'=>'TZS','symbol'=>'TSh','label'=>'TZS — Tanzania Shilling'],
                        ['code'=>'GBP','symbol'=>'£','label'=>'GBP — British Pound'],
                        ['code'=>'EUR','symbol'=>'€','label'=>'EUR — Euro'],
                    ] as $cur)
                    <li>
                        <button class="dropdown-item d-flex align-items-center gap-2 currency-opt"
                                data-code="{{ $cur['code'] }}" data-symbol="{{ $cur['symbol'] }}"
                                style="font-size:13px;">
                            <span style="font-weight:700;width:32px;color:#0066FF;">{{ $cur['symbol'] }}</span>
                            {{ $cur['label'] }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Hamburger --}}
            <button class="mob-toggle" onclick="document.getElementById('mobMenu').classList.add('open')">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</header>

{{-- Mobile Menu --}}
<div class="mob-menu" id="mobMenu">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="brand-wrap" onclick="document.getElementById('mobMenu').classList.remove('open')">
            <div class="brand-icon">S</div>
            <div class="brand-text"><div class="t1">Sky<span>Netug</span></div></div>
        </a>
        <button onclick="document.getElementById('mobMenu').classList.remove('open')" style="background:none;border:none;font-size:26px;color:#374151;cursor:pointer;"><i class="bi bi-x"></i></button>
    </div>
    <nav style="display:flex;flex-direction:column;gap:4px;">
        @foreach([
            ['Home',            route('home')],
            ['Email Hosting',   route('hosting.email')],
            ['Shared Hosting',  route('hosting.shared')],
            ['WordPress',       route('hosting.wordpress')],
            ['VPS / Cloud',     route('hosting.vps')],
            ['Reseller Hosting', route('reseller')],
            ['About Us',        route('about')],
            ['Website Design',  route('design')],
            ['Pricing',         route('pricing')],
            ['Knowledge Base',  route('kb.index')],
            ['Contact Us', route('contact')],
        ] as [$label, $url])
        <a href="{{ $url }}" style="padding:12px 16px;color:#374151;text-decoration:none;border-radius:8px;font-weight:500;border-bottom:1px solid #F3F4F6;" onclick="document.getElementById('mobMenu').classList.remove('open')">{{ $label }}</a>
        @endforeach
        <div class="d-flex gap-2 mt-4">
            @guest
                <a href="{{ route('login') }}" class="btn-signin flex-fill text-center" style="padding:12px;">Sign In</a>
                <a href="{{ route('register') }}" class="btn-getstarted flex-fill text-center" style="padding:12px;">Get Started</a>
            @endguest
        </div>
    </nav>
</div>


{{-- Flash Messages --}}
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:10px;border:none;">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius:10px;border:none;">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif
@if($errors->any())
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" style="border-radius:10px;border:none;">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@yield('content')

{{-- ══ FOOTER ══════════════════════════════════ --}}
<footer class="footer-sky">
    <div class="container">
        <div class="row g-4 pb-4">
            <div class="col-lg-4">
                <a href="{{ route('home') }}" class="brand-wrap mb-3 d-inline-flex" style="text-decoration:none;">
                    <div class="brand-icon">S</div>
                    <div class="brand-text ms-2">
                        <div class="t1" style="color:#fff;font-size:1.1rem;">Sky<span style="color:#0066FF;">Netug</span></div>
                        <div class="t2" style="color:rgba(255,255,255,.4);">Web Hosting</div>
                    </div>
                </a>
                <p style="font-size:.875rem;line-height:1.7;color:rgba(255,255,255,.6);">Fast, reliable, and affordable web hosting and domain registration. Pay with mobile money or card.</p>
                <div class="d-flex gap-2 mt-3">
                    @foreach(['bi-facebook','bi-twitter-x','bi-instagram','bi-linkedin'] as $icon)
                    <a href="#" style="width:34px;height:34px;background:rgba(255,255,255,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);font-size:14px;text-decoration:none;" onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='rgba(255,255,255,.08)'">
                        <i class="bi {{ $icon }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Hosting</h6>
                <a href="{{ route('hosting.shared') }}">Shared Hosting</a>
                <a href="{{ route('hosting.wordpress') }}">WordPress</a>
                <a href="{{ route('hosting.vps') }}">VPS Hosting</a>
                <a href="{{ route('hosting.email') }}">Email Hosting</a>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Services</h6>
                <a href="{{ route('domains') }}">Domains</a>
                <a href="{{ route('ssl') }}">SSL Certificates</a>
                <a href="{{ route('design') }}">Website Design</a>
                <a href="{{ route('pricing') }}">Pricing</a>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Support</h6>
                <a href="{{ route('kb.index') }}">Knowledge Base</a>
                <a href="{{ route('contact') }}">Contact Us</a>
                @auth
                <a href="{{ route('dashboard.tickets.create') }}">Open Ticket</a>
                @endauth
            </div>
            <div class="col-lg-2 col-6">
                <h6>Company</h6>
                <a href="{{ route('about') }}">About Us</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Refund Policy</a>
            </div>
        </div>
        <div class="border-top pt-3 d-flex flex-column flex-md-row justify-content-between align-items-center" style="gap:8px;">
            <p class="mb-0" style="font-size:.8rem;color:rgba(255,255,255,.4);">&copy; {{ date('Y') }} SkyNetug Ltd. All rights reserved.</p>
            <p class="mb-0" style="font-size:.8rem;color:rgba(255,255,255,.4);">🇺🇬 Kampala, Uganda &nbsp;|&nbsp; support@skynetug.com</p>
        </div>
    </div>
</footer>

<script src="/js/bootstrap.bundle.min.js"></script>
<script>
// ── Exchange rates relative to USD ────────────────────────────
const RATES = {
    USD: { rate: 1,       symbol: '$',   code: 'USD', label: 'USD $' },
    UGX: { rate: 3750,    symbol: 'UGX', code: 'UGX', label: 'UGX' },
    KES: { rate: 129,     symbol: 'KSh', code: 'KES', label: 'KES KSh' },
    TZS: { rate: 2640,    symbol: 'TSh', code: 'TZS', label: 'TZS TSh' },
    GBP: { rate: 0.79,    symbol: '£',   code: 'GBP', label: 'GBP £' },
    EUR: { rate: 0.92,    symbol: '€',   code: 'EUR', label: 'EUR €' },
};

let currentCode = sessionStorage.getItem('currency') || 'USD';

// Format number based on currency
function formatPrice(usdAmount, code) {
    const cur = RATES[code];
    const converted = usdAmount * cur.rate;
    // Large currencies (UGX, TZS) show no decimals
    const decimals = (cur.rate >= 100) ? 0 : 2;
    const formatted = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(converted);
    return cur.symbol + ' ' + formatted;
}

// Convert all price elements on the page
function convertAllPrices(code) {
    const cur = RATES[code];

    // Elements with data-usd attribute
    document.querySelectorAll('[data-usd]').forEach(el => {
        const usd = parseFloat(el.dataset.usd);
        if (!isNaN(usd)) {
            el.textContent = formatPrice(usd, code);
        }
    });

    // Cart button total
    const cartTotal = document.getElementById('cartTotal');
    if (cartTotal) {
        const usd = parseFloat(cartTotal.dataset.usd || 0);
        cartTotal.textContent = formatPrice(usd, code);
    }

    // Update billed lines (data-usd-billed)
    document.querySelectorAll('[data-usd-billed]').forEach(el => {
        const usd = parseFloat(el.dataset.usdBilled);
        if (!isNaN(usd)) {
            el.textContent = 'Billed ' + formatPrice(usd, code) + '/yr';
        }
    });

    // Adjust price font size based on currency length
    // Large rate currencies (UGX, TZS, KES) produce long numbers
    document.querySelectorAll('.hplan-price').forEach(el => {
        el.classList.remove('compact', 'very-compact');
        if (cur.rate >= 1000) {
            el.classList.add('very-compact');   // UGX, TZS
        } else if (cur.rate >= 100) {
            el.classList.add('compact');         // KES
        }
    });
}

// Switch currency
function switchCurrency(code) {
    currentCode = code;
    const cur = RATES[code];
    // Update button label
    document.getElementById('activeCurrency').textContent = cur.label;
    // Highlight active option
    document.querySelectorAll('.currency-opt').forEach(b => {
        b.style.fontWeight = b.dataset.code === code ? '700' : '400';
        b.style.color = b.dataset.code === code ? '#0066FF' : '';
    });
    // Save
    sessionStorage.setItem('currency', code);
    // Convert
    convertAllPrices(code);
}

// Wire up buttons
document.querySelectorAll('.currency-opt').forEach(btn => {
    btn.addEventListener('click', function() {
        switchCurrency(this.dataset.code);
    });
});

// Run on page load
switchCurrency(currentCode);
</script>
@stack('scripts')

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6a71f672079e2c1d4161d046/1jv6ilc0g';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();

// Pre-fill visitor info if logged in
@auth
Tawk_API.onLoad = function(){
    Tawk_API.setAttributes({
        name:  '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
    }, function(error){});
};
@endauth
</script>
<!--End of Tawk.to Script-->
</body>
</html>


