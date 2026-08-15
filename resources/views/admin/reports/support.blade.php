@extends('layouts.dashboard')
@section('page_title', 'Support Report')

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
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3">{{ $total }}</div><div class="text-muted small">Total Tickets</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-warning">{{ $open }}</div><div class="text-muted small">Currently Open</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-success">{{ $resolved }}</div><div class="text-muted small">Resolved in Period</div></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">By Category</h6>
            @foreach($byCategory as $row)
            <div class="d-flex justify-content-between mb-2 small">
                <span>{{ ucfirst($row->category) }}</span><span class="fw-semibold">{{ $row->count }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-6">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">By Priority</h6>
            @foreach($byPriority as $row)
            @php $c=match($row->priority){'urgent'=>'danger','high'=>'warning','medium'=>'primary','low'=>'secondary',default=>'secondary'}; @endphp
            <div class="d-flex justify-content-between align-items-center mb-2 small">
                <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($row->priority) }}</span>
                <span class="fw-semibold">{{ $row->count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
