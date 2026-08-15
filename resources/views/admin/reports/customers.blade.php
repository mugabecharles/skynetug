@extends('layouts.dashboard')
@section('page_title', 'Customer Report')

@section('content')
<div class="d-flex gap-2 flex-wrap mb-4">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $r=>$l)
    <a href="{{ route('admin.reports.'.$r) }}" class="btn btn-sm {{ request()->routeIs('admin.reports.'.$r)?'btn-sky':'btn-outline-secondary' }}" style="border-radius:8px;">{{ $l }}</a>
    @endforeach
</div>

<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="d-flex gap-2 align-items-end flex-wrap">
        <div><label class="form-label small fw-semibold mb-1">From</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}"></div>
        <div><label class="form-label small fw-semibold mb-1">To</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}"></div>
        <button class="btn btn-sky btn-sm">Apply</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-sky">{{ number_format($total) }}</div><div class="text-muted small">Total Customers</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-success">+{{ number_format($new) }}</div><div class="text-muted small">New in Period</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3">{{ $total > 0 ? number_format(($new/$total)*100,1).'%' : '0%' }}</div><div class="text-muted small">Growth Rate</div></div></div>
</div>

<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-3">New Registrations per Month</h6>
    <canvas id="custChart" height="80"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const d = @json($byMonth);
new Chart(document.getElementById('custChart'), {
    type: 'bar',
    data: { labels: d.map(r=>r.month), datasets: [{ label:'New Customers', data: d.map(r=>r.count), backgroundColor:'rgba(0,102,255,.2)', borderColor:'#0066FF', borderWidth:2, borderRadius:4 }] },
    options: { responsive:true, plugins:{ legend:{display:false} }, scales:{ y:{ beginAtZero:true, ticks:{stepSize:1} } } }
});
</script>
@endpush
@endsection
