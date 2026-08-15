@extends('layouts.dashboard')
@section('page_title', 'Tax Report')

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
    <div class="col-12"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold" style="font-size:2.5rem;">$ {{ number_format($total) }}</div><div class="text-muted">Total Tax Collected in Period</div></div></div>
</div>

<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-3">Tax by Country</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light"><tr><th>Country</th><th class="text-end">Tax Collected</th></tr></thead>
            <tbody>
                @forelse($byCountry as $row)
                <tr>
                    <td>{{ $row->country ?? 'Unknown' }}</td>
                    <td class="text-end fw-semibold">$ {{ number_format($row->total) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center py-3 text-muted">No tax collected in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
