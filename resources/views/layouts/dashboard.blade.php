<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'Dashboard') — SkyNetug</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:      #0066FF;
            --primary-dark: #0050CC;
            --secondary:    #00C896;
            --dark-bg: #0A0F1E;
            --sidebar-w:    250px;
            --topbar-h:     60px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 14px;
            background: #F4F6FA;
            color: #1C2333;
        }

        /* ── Layout shell ───────────────────────── */
        .layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--dark-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px 14px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            text-decoration: none;
        }

        .sidebar-brand .logo-icon {
            width: 34px; height: 34px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff; font-weight: 800; flex-shrink: 0;
        }

        .sidebar-brand .logo-text {
            font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1.1;
        }
        .sidebar-brand .logo-text span { color: var(--secondary); }

        .sidebar-section {
            padding: 14px 12px 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
        }

        .sidebar a.nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            margin: 1px 8px;
            border-radius: 8px;
            color: rgba(255,255,255,.62);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }

        .sidebar a.nav-item i {
            font-size: 15px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar a.nav-item:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }

        .sidebar a.nav-item.active {
            background: var(--primary);
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar-user .avatar {
            width: 34px; height: 34px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 13px; flex-shrink: 0;
        }

        .sidebar-user .info .name  { color: #fff; font-size: 13px; font-weight: 600; }
        .sidebar-user .info .email { color: rgba(255,255,255,.4); font-size: 11px; }

        /* ── Main area ──────────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Top bar ────────────────────────────── */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #E8ECF0;
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 12px;
        }

        .topbar .page-title {
            font-size: 16px;
            font-weight: 700;
            color: #1C2333;
            margin: 0;
            flex: 1;
        }

        .topbar .btn-new-ticket {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar .btn-new-ticket:hover { background: var(--primary-dark); color: #fff; }

        /* ── Footer ─────────────────────────────── */
        .content {
            flex: 1;
            padding: 24px;
        }

        /* ── Flash ──────────────────────────────── */
        .flash-wrap { padding: 0 24px 0; }

        /* ── Footer ─────────────────────────────── */
        .site-footer {
            padding: 14px 24px;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
            border-top: 1px solid #E8ECF0;
        }

        /* ── Stat card ──────────────────────────── */
        .stat-card {
            background: #fff;
            border: 1px solid #E8ECF0;
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-value { font-size: 22px; font-weight: 800; line-height: 1.1; color: #1C2333; }
        .stat-label { font-size: 12px; color: #6B7280; margin-top: 2px; }

        /* ── Tables ─────────────────────────────── */
        .data-table { background: #fff; border-radius: 12px; border: 1px solid #E8ECF0; overflow: hidden; }
        .data-table table { margin: 0; }
        .data-table thead th { background: #F8FAFC; font-size: 12px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid #E8ECF0; padding: 10px 16px; }
        .data-table tbody td { padding: 12px 16px; border-bottom: 1px solid #F3F4F6; vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #FAFBFC; }

        /* ── Cards ──────────────────────────────── */
        .panel { background: #fff; border-radius: 12px; border: 1px solid #E8ECF0; }
        .panel-header { padding: 16px 20px; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h6 { margin: 0; font-weight: 700; font-size: 14px; }
        .panel-body { padding: 20px; }

        /* ── Badges ─────────────────────────────── */
        .badge-pill { border-radius: 20px; font-size: 11px; font-weight: 600; padding: 3px 10px; }

        /* ── Sidebar toggle for mobile ───────────── */
        .sidebar-toggle { display: none; background: none; border: none; font-size: 22px; padding: 0; cursor: pointer; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; }
            .sidebar-overlay.show { display: block; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="layout">
    <!-- ══ SIDEBAR ══════════════════════════════ -->
    <nav class="sidebar" id="sidebar">

        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard.index') }}" class="sidebar-brand">
            <div class="logo-icon">S</div>
            <div class="logo-text">Sky<span>Netug</span></div>
        </a>

        @if(auth()->check() && auth()->user()->isAdmin())
        {{-- ADMIN NAVIGATION --}}
        <div class="sidebar-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>

        <div class="sidebar-section">Management</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>Users
        </a>
        <a href="{{ route('admin.packages.index') }}" class="nav-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>Packages
        </a>
        <a href="{{ route('admin.servers.index') }}" class="nav-item {{ request()->routeIs('admin.servers.*') ? 'active' : '' }}">
            <i class="bi bi-hdd-rack"></i>Servers
        </a>
        <a href="{{ route('admin.hosting.index') }}" class="nav-item {{ request()->routeIs('admin.hosting.*') ? 'active' : '' }}">
            <i class="bi bi-globe2"></i>Hosting
        </a>
        <a href="{{ route('admin.domains.index') }}" class="nav-item {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
            <i class="bi bi-link-45deg"></i>Domains
        </a>
        <a href="{{ route('admin.tld-pricing.index') }}" class="nav-item {{ request()->routeIs('admin.tld-pricing.*') ? 'active' : '' }}">
            <i class="bi bi-currency-dollar"></i>Domain Pricing
        </a>

        <div class="sidebar-section">Billing</div>
        <a href="{{ route('admin.invoices.index') }}" class="nav-item {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>Invoices
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i>Payments
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i>Coupons
        </a>

        <div class="sidebar-section">Support & Growth</div>
        <a href="{{ route('admin.tickets.index') }}" class="nav-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
            <i class="bi bi-headset"></i>Support
        </a>
        <a href="{{ route('admin.affiliates.index') }}" class="nav-item {{ request()->routeIs('admin.affiliates.*') ? 'active' : '' }}">
            <i class="bi bi-share"></i>Affiliates
        </a>
        <a href="{{ route('admin.announcements.index') }}" class="nav-item {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i>Announcements
        </a>
        <a href="{{ route('admin.kb.index') }}" class="nav-item {{ request()->routeIs('admin.kb.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i>Knowledge Base
        </a>

        <div class="sidebar-section">Reports</div>
        <a href="{{ route('admin.reports.sales') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i>Reports
        </a>
        <a href="{{ route('admin.audit-logs') }}" class="nav-item {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i>Audit Logs
        </a>

        @else
        {{-- CUSTOMER NAVIGATION --}}
        <div class="sidebar-section">My Account</div>
        <a href="{{ route('dashboard.index') }}" class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-house"></i>Dashboard
        </a>
        <a href="{{ route('dashboard.hosting.index') }}" class="nav-item {{ request()->routeIs('dashboard.hosting.*') ? 'active' : '' }}">
            <i class="bi bi-globe2"></i>My Hosting
        </a>
        <a href="{{ route('dashboard.domains.index') }}" class="nav-item {{ request()->routeIs('dashboard.domains.*') ? 'active' : '' }}">
            <i class="bi bi-link-45deg"></i>My Domains
        </a>
        <a href="{{ route('dashboard.invoices.index') }}" class="nav-item {{ request()->routeIs('dashboard.invoices.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>Invoices
        </a>
        <a href="{{ route('dashboard.tickets.index') }}" class="nav-item {{ request()->routeIs('dashboard.tickets.*') ? 'active' : '' }}">
            <i class="bi bi-headset"></i>Support
        </a>
        <a href="{{ route('dashboard.affiliate.index') }}" class="nav-item {{ request()->routeIs('dashboard.affiliate.*') ? 'active' : '' }}">
            <i class="bi bi-share"></i>Affiliate
        </a>
        <a href="{{ route('dashboard.profile.index') }}" class="nav-item {{ request()->routeIs('dashboard.profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>Profile
        </a>
        @endif

        <div class="sidebar-footer">
            @auth
            <div class="sidebar-user">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                <div class="info">
                    <div class="name">{{ Str::limit(auth()->user()->name, 18) }}</div>
                    <div class="email">{{ Str::limit(auth()->user()->email, 22) }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width:100%;background:rgba(255,255,255,.07);border:none;border-radius:8px;padding:8px;color:rgba(255,255,255,.6);font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
            @endauth
        </div>
    </nav>

    <!-- ══ MAIN ════════════════════════════════ -->
    <div class="main">

        <!-- Top Bar -->
        <div class="topbar">
            <button class="sidebar-toggle" onclick="openSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>

            @if(!auth()->user()->isAdmin())
            <a href="{{ route('dashboard.tickets.create') }}" class="btn-new-ticket">
                <i class="bi bi-plus-lg"></i> New Ticket
            </a>
            @endif
        </div>

        <!-- Flash Messages -->
        <div class="flash-wrap pt-3">
            @foreach(['success'=>'success','error'=>'danger','warning'=>'warning','info'=>'info'] as $key => $type)
                @if(session($key))
                <div class="alert alert-{{ $type }} alert-dismissible fade show d-flex align-items-center gap-2 mb-2" style="border-radius:10px;border:none;">
                    <i class="bi bi-{{ $type == 'success' ? 'check-circle-fill' : ($type == 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill') }}"></i>
                    {{ session($key) }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
                @endif
            @endforeach
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-2" style="border-radius:10px;border:none;">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>

        <footer class="site-footer">
            &copy; {{ date('Y') }} SkyNetug Web Hosting &nbsp;·&nbsp; All rights reserved.
        </footer>
    </div>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
<script>
function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebarOverlay').classList.add('show'); }
function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('show'); }
setTimeout(() => { document.querySelectorAll('.flash-wrap .alert').forEach(el => bootstrap.Alert.getOrCreateInstance(el).close()); }, 5000);
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

// Pre-fill visitor info from logged-in user
Tawk_API = Tawk_API||{};
Tawk_API.onLoad = function(){
    Tawk_API.setAttributes({
        name:  '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
    }, function(error){});
};
</script>
<!--End of Tawk.to Script-->
</body>
</html>

