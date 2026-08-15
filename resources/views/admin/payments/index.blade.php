@extends('layouts.dashboard')
@section('page_title', 'Payments')

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Payments','value'=>'$ '.number_format($stats['total']??0),'icon'=>'bi-cash-coin','color'=>'#059669'],
        ['label'=>'This Month','value'=>'$ '.number_format($stats['month']??0),'icon'=>'bi-graph-up','color'=>'#0066FF'],
        ['label'=>'Pending','value'=>number_format($stats['pending']??0),'icon'=>'bi-hourglass-split','color'=>'#f59e0b'],
        ['label'=>'Failed','value'=>number_format($stats['failed']??0),'icon'=>'bi-x-circle','color'=>'#dc2626'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="bg-white rounded-3 border p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;background:{{ $s['color'] }}18;">
                <i class="bi {{ $s['icon'] }}" style="color:{{ $s['color'] }};font-size:1.1rem;"></i>
            </div>
            <div><div class="fw-bold">{{ $s['value'] }}</div><div class="text-muted" style="font-size:.72rem;">{{ $s['label'] }}</div></div>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Transaction ref or email…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="gateway" class="form-select form-select-sm">
                <option value="">All Gateways</option>
                @foreach(['mtn_mobile_money','airtel_money','flutterwave','pesapal','manual'] as $g)
                <option value="{{ $g }}" {{ request('gateway')==$g?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$g)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['completed','pending','failed','refunded'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
        <div class="col-md-1"><button class="btn btn-sky btn-sm w-100">Filter</button></div>
        <div class="col-md-1"><a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Transaction ID</th><th>Customer</th><th>Gateway</th><th>Amount</th><th>Status</th><th>Invoice</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $pmt)
                <tr>
                    <td class="font-monospace small">{{ $pmt->transaction_id }}</td>
                    <td>{{ $pmt->user->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$pmt->gateway)) }}</span></td>
                    <td class="fw-semibold">$ {{ number_format($pmt->amount) }}</td>
                    <td>
                        @php $c=match($pmt->status){'completed'=>'success','pending'=>'warning','failed'=>'danger','refunded'=>'secondary',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($pmt->status) }}</span>
                    </td>
                    <td>
                        @if($pmt->invoice)
                            <a href="{{ route('admin.invoices.show',$pmt->invoice_id) }}" class="small">{{ $pmt->invoice->invoice_number }}</a>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td class="small text-muted">{{ $pmt->paid_at ? \Carbon\Carbon::parse($pmt->paid_at)->format('d M Y H:i') : $pmt->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $payments->links() }}</div>
</div>
@endsection
