@extends('layouts.dashboard')
@section('page_title', 'Admin Dashboard')

@push('styles')
<style>
.chart-wrap { position:relative; height:220px; }
</style>
@endpush

@section('content')

{{-- ── Stat Cards ────────────────────────────────── --}}
<div class="row g-3 mb-4">

    @php
    $cards = [
        ['label'=>'Total Customers',  'value'=>number_format($stats['total_customers']),              'icon'=>'bi-people-fill',    'color'=>'#0066FF', 'bg'=>'#EFF6FF'],
        ['label'=>'Total Orders',     'value'=>number_format($stats['total_orders']),                 'icon'=>'bi-cart-fill',      'color'=>'#7C3AED', 'bg'=>'#F5F3FF'],
        ['label'=>'Monthly Revenue',  'value'=>'$ '.number_format($stats['monthly_revenue']),       'icon'=>'bi-graph-up-arrow', 'color'=>'#059669', 'bg'=>'#ECFDF5'],
        ['label'=>'Total Revenue',    'value'=>'$ '.number_format($stats['total_revenue']),         'icon'=>'bi-cash-coin',      'color'=>'#D97706', 'bg'=>'#FFFBEB'],
        ['label'=>'Active Domains',   'value'=>number_format($stats['active_domains']),               'icon'=>'bi-link-45deg',     'color'=>'#0891B2', 'bg'=>'#E0F7FA'],
        ['label'=>'Active Hosting',   'value'=>number_format($stats['active_hosting']),               'icon'=>'bi-server',         'color'=>'#15803D', 'bg'=>'#F0FDF4'],
        ['label'=>'Pending Tickets',  'value'=>number_format($stats['pending_tickets']),              'icon'=>'bi-headset',        'color'=>'#DC2626', 'bg'=>'#FEF2F2'],
        ['label'=>'Unpaid Invoices',  'value'=>number_format($stats['unpaid_invoices']),              'icon'=>'bi-receipt',        'color'=>'#B45309', 'bg'=>'#FFFBEB'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $card['bg'] }}; color:{{ $card['color'] }};">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-value">{{ $card['value'] }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Charts + Servers ─────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="panel h-100">
            <div class="panel-header">
                <h6><i class="bi bi-bar-chart-line me-2" style="color:#0066FF;"></i>Monthly Revenue (Last 12 Months)</h6>
                <a href="{{ route('admin.reports.sales') }}" style="font-size:12px;color:#0066FF;text-decoration:none;">Full Report →</a>
            </div>
            <div class="panel-body">
                <div class="chart-wrap">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Server Status --}}
    <div class="col-lg-4">
        <div class="panel h-100">
            <div class="panel-header">
                <h6><i class="bi bi-hdd-rack me-2" style="color:#0066FF;"></i>Server Status</h6>
                <a href="{{ route('admin.servers.index') }}" style="font-size:12px;color:#0066FF;text-decoration:none;">Manage →</a>
            </div>
            <div class="panel-body">
                @forelse($servers as $server)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div style="font-weight:600;font-size:13px;">{{ $server->name }}</div>
                        <div style="font-size:11px;color:#9CA3AF;">{{ $server->hostname }}</div>
                    </div>
                    <span class="badge badge-pill {{ $server->is_active ? 'text-success' : 'text-danger' }}"
                          style="background:{{ $server->is_active ? '#ECFDF5' : '#FEF2F2' }}; font-size:11px;">
                        <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>
                        {{ $server->is_active ? 'Online' : 'Offline' }}
                    </span>
                </div>
                @empty
                <div class="text-center py-3" style="color:#9CA3AF;">
                    <i class="bi bi-hdd-rack d-block mb-1" style="font-size:2rem;opacity:.3;"></i>
                    <div style="font-size:12px;">No servers configured</div>
                    <a href="{{ route('admin.servers.create') }}" style="font-size:12px;">Add Server</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Quick Stats Row ──────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Recent Activity --}}
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-header">
                <h6><i class="bi bi-clock-history me-2" style="color:#0066FF;"></i>Recent Activity</h6>
                <a href="{{ route('admin.audit-logs') }}" style="font-size:12px;color:#0066FF;text-decoration:none;">View All →</a>
            </div>
            <div class="data-table">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Resource</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $log)
                        <tr>
                            <td style="font-weight:500;">{{ $log->user?->name ?? 'System' }}</td>
                            <td><span class="badge badge-pill" style="background:#F3F4F6;color:#374151;font-size:11px;">{{ $log->action_type }}</span></td>
                            <td style="color:#6B7280;">{{ $log->resource_type }}{{ $log->resource_id ? ' #'.$log->resource_id : '' }}</td>
                            <td style="color:#9CA3AF;font-size:12px;">{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:20px;color:#9CA3AF;">No activity yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-header">
                <h6><i class="bi bi-lightning-charge me-2" style="color:#0066FF;"></i>Quick Actions</h6>
            </div>
            <div class="panel-body">
                <div class="row g-2">
                    @foreach([
                        ['route'=>'admin.users.create',      'icon'=>'bi-person-plus',   'label'=>'Add User',        'color'=>'#EFF6FF','ic'=>'#0066FF'],
                        ['route'=>'admin.packages.create',   'icon'=>'bi-box-seam',      'label'=>'Add Package',     'color'=>'#F5F3FF','ic'=>'#7C3AED'],
                        ['route'=>'admin.servers.create',    'icon'=>'bi-hdd-rack',      'label'=>'Add Server',      'color'=>'#ECFDF5','ic'=>'#059669'],
                        ['route'=>'admin.announcements.create','icon'=>'bi-megaphone',   'label'=>'Announce',        'color'=>'#FFF7ED','ic'=>'#EA580C'],
                        ['route'=>'admin.coupons.create',    'icon'=>'bi-tag',           'label'=>'New Coupon',      'color'=>'#FFF1F2','ic'=>'#E11D48'],
                        ['route'=>'admin.kb.create',         'icon'=>'bi-file-earmark-text','label'=>'New KB Article','color'=>'#F0FDF4','ic'=>'#15803D'],
                    ] as $action)
                    <div class="col-6">
                        <a href="{{ route($action['route']) }}" style="text-decoration:none;">
                            <div style="background:{{ $action['color'] }};border-radius:10px;padding:12px 14px;display:flex;align-items:center;gap:10px;transition:opacity .15s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                                <i class="bi {{ $action['icon'] }}" style="color:{{ $action['ic'] }};font-size:18px;"></i>
                                <span style="font-size:13px;font-weight:600;color:#1C2333;">{{ $action['label'] }}</span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const labels  = chartData.map(d => months[d.month-1]+' '+String(d.year).slice(-2));
const values  = chartData.map(d => d.total);

const ctx = document.getElementById('revenueChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue ($)',
                data: values,
                backgroundColor: 'rgba(0,102,255,.15)',
                borderColor: '#0066FF',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: c => '$ ' + Number(c.raw).toLocaleString() } }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.04)' },
                    ticks: { callback: v => v >= 1000000 ? '$ '+(v/1000000).toFixed(1)+'M' : v >= 1000 ? '$ '+(v/1000).toFixed(0)+'K' : v }
                },
                x: { grid: { display: false } }
            }
        }
    });
}
</script>
@endpush
@endsection
