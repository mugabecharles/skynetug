@extends('layouts.dashboard')
@section('page_title', 'Payment Report')

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

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Summary by Gateway & Status</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="font-size:.82rem;">
                    <thead class="table-light"><tr><th>Gateway</th><th>Status</th><th>Count</th><th>Total</th></tr></thead>
                    <tbody>
                        @forelse($summary as $row)
                        <tr>
                            <td>{{ ucfirst(str_replace('_',' ',$row->gateway)) }}</td>
                            <td>
                                @php $c=match($row->status){'completed'=>'success','pending'=>'warning','failed'=>'danger',default=>'secondary'}; @endphp
                                <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($row->status) }}</span>
                            </td>
                            <td>{{ $row->count }}</td>
                            <td>$ {{ number_format($row->total) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-2 text-muted">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Recent Transactions</h6>
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="font-size:.82rem;">
                    <thead class="table-light"><tr><th>Customer</th><th>Gateway</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach($payments->take(10) as $p)
                        <tr>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$p->gateway)) }}</td>
                            <td>$ {{ number_format($p->amount) }}</td>
                            <td>
                                @php $c=match($p->status){'completed'=>'success','pending'=>'warning','failed'=>'danger',default=>'secondary'}; @endphp
                                <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($p->status) }}</span>
                            </td>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
