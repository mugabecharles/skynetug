@extends('layouts.dashboard')
@section('page_title', 'Invoices')

@section('content')
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Invoice # or email…" value="{{ request('search') }}"></div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                @foreach(['unpaid','paid','overdue','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
        <div class="col-md-2"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
        <div class="col-md-1"><button class="btn btn-sky btn-sm w-100">Filter</button></div>
        <div class="col-md-1"><a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a></div>
    </form>
</div>

<div class="bg-white rounded-3 border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
            <thead class="table-light">
                <tr><th>Invoice #</th><th>Customer</th><th>Type</th><th>Amount</th><th>Status</th><th>Date</th><th>Due</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->user->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$inv->type)) }}</span></td>
                    <td class="fw-semibold">$ {{ number_format($inv->total) }}</td>
                    <td>
                        @php $c=match($inv->status){'paid'=>'success','unpaid'=>'warning','overdue'=>'danger','cancelled'=>'secondary',default=>'secondary'}; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($inv->status) }}</span>
                    </td>
                    <td class="small text-muted">{{ \Carbon\Carbon::parse($inv->date_created)->format('d M Y') }}</td>
                    <td class="small {{ $inv->status=='overdue'?'text-danger fw-semibold':'' }}">{{ \Carbon\Carbon::parse($inv->date_due)->format('d M Y') }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.invoices.show',$inv->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;font-size:.75rem;">View</a>
                        @if($inv->status=='paid')
                        <form method="POST" action="{{ route('admin.invoices.refund',$inv->id) }}" onsubmit="return confirm('Process refund for this invoice?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">Refund</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $invoices->links() }}</div>
</div>
@endsection
