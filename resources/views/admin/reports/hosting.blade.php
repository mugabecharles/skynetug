@extends('layouts.dashboard')
@section('page_title', 'Hosting Report')

@section('content')
<div class="d-flex gap-2 flex-wrap mb-4">
    @foreach(['sales'=>'Sales','payments'=>'Payments','customers'=>'Customers','hosting'=>'Hosting','domains'=>'Domains','support'=>'Support','affiliates'=>'Affiliates','renewals'=>'Renewals','tax'=>'Tax'] as $r=>$l)
    <a href="{{ route('admin.reports.'.$r) }}" class="btn btn-sm {{ request()->routeIs('admin.reports.'.$r)?'btn-sky':'btn-outline-secondary' }}" style="border-radius:8px;">{{ $l }}</a>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3">{{ $total }}</div><div class="text-muted small">Total Accounts</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-success">{{ $active }}</div><div class="text-muted small">Active</div></div></div>
    <div class="col-md-4"><div class="bg-white rounded-3 border p-4 text-center"><div class="fw-bold fs-3 text-danger">{{ $suspended }}</div><div class="text-muted small">Suspended</div></div></div>
</div>

<div class="bg-white rounded-3 border p-4">
    <h6 class="fw-bold mb-3">Accounts by Status & Package</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size:.85rem;">
            <thead class="table-light"><tr><th>Package</th><th>Server</th><th>Status</th><th>Count</th></tr></thead>
            <tbody>
                @forelse($accounts as $row)
                <tr>
                    <td>{{ $row->hostingPackage?->name ?? 'Unknown' }}</td>
                    <td>{{ $row->server?->name ?? '—' }}</td>
                    <td>
                        @php $c=match($row->status){'active'=>'success','suspended'=>'danger','pending'=>'warning',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }}">{{ ucfirst($row->status) }}</span>
                    </td>
                    <td class="fw-semibold">{{ $row->count }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-3 text-muted">No data available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
