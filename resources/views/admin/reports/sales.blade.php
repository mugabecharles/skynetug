@extends('layouts.dashboard')
@section('page_title', 'Sales Report')

@push('styles')
<style>.report-nav .nav-link{border-radius:8px;font-size:.85rem;padding:.4rem .9rem;}.report-nav .nav-link.active{background:#0066FF;color:#fff;}</style>
@endpush

@section('content')
{{-- Report Nav --}}
<div class="d-flex gap-2 flex-wrap mb-4 report-nav">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $route=>$label)
    <a href="{{ route('admin.reports.'.$route) }}" class="nav-link {{ request()->routeIs('admin.reports.'.$route) ? 'active' : 'text-muted border' }}">{{ $label }}</a>
    @endforeach
</div>

{{-- Date Filter --}}
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="d-flex gap-2 align-items-end flex-wrap">
        <div><label class="form-label small fw-semibold mb-1">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
        <div><label class="form-label small fw-semibold mb-1">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
        <button class="btn btn-sky btn-sm">Apply</button>
    </form>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="bg-white rounded-3 border p-4 text-center">
            <div class="fw-bold fs-3 text-sky">$ {{ number_format($revenue) }}</div>
            <div class="text-muted small">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-3 border p-4 text-center">
            <div class="fw-bold fs-3">{{ number_format($orderCount) }}</div>
            <div class="text-muted small">Orders Placed</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-3 border p-4 text-center">
            <div class="fw-bold fs-3 text-success">$ {{ $orderCount > 0 ? number_format($revenue/$orderCount) : 0 }}</div>
            <div class="text-muted small">Avg. Order Value</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Revenue by Gateway --}}
    <div class="col-lg-5">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Revenue by Payment Gateway</h6>
            @forelse($byGateway as $gw)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                <span class="small">{{ ucfirst(str_replace('_',' ',$gw->gateway)) }}</span>
                <div class="text-end">
                    <div class="fw-semibold small">$ {{ number_format($gw->total) }}</div>
                    <div class="text-muted" style="font-size:.72rem;">{{ $gw->count }} transactions</div>
                </div>
            </div>
            @empty
            <p class="text-muted small">No data for selected period.</p>
            @endforelse
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="col-lg-7">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Monthly Revenue (12 months)</h6>
            <canvas id="salesChart" height="120"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const monthly = @json($byMonth);
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: monthly.map(d => d.month),
        datasets: [{
            label: 'Revenue ($)',
            data: monthly.map(d => d.total),
            borderColor: '#0066FF', backgroundColor: 'rgba(0,102,255,.1)',
            tension: 0.4, fill: true, borderWidth: 2, pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => '$ ' + Number(c.raw).toLocaleString() } } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '$ ' + (v/1000).toFixed(0) + 'K' } } }
    }
});
</script>
@endpush
@endsection
