@extends('layouts.dashboard')
@section('page_title', 'Invoices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">My Invoices</h5>
        <p class="text-muted small mb-0">View and pay your invoices</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-3 border p-3 mb-4">
    <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
        <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['unpaid','paid','overdue','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
    </form>
</div>

<div class="bg-white rounded-3 border">
    @if($invoices->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt display-5 d-block mb-3 opacity-30"></i>
            <h6>No invoices found</h6>
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td class="fw-semibold">{{ $invoice->invoice_number }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_',' ',$invoice->type)) }}</span></td>
                    <td class="fw-semibold">$ {{ number_format($invoice->total) }}</td>
                    <td>
                        @php $c = match($invoice->status) { 'paid'=>'success','unpaid'=>'warning','overdue'=>'danger','cancelled'=>'secondary', default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $c }}-subtle text-{{ $c }} rounded-pill">{{ ucfirst($invoice->status) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($invoice->date_created)->format('d M Y') }}</td>
                    <td>
                        @php $due = \Carbon\Carbon::parse($invoice->date_due); @endphp
                        <span class="{{ $invoice->status == 'overdue' ? 'text-danger fw-semibold' : '' }}">
                            {{ $due->format('d M Y') }}
                        </span>
                    </td>
                    <td class="d-flex gap-1">
                        @if(in_array($invoice->status, ['unpaid','overdue']))
                            <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn btn-sm btn-sky" style="border-radius:6px;font-size:0.75rem;">Pay Now</a>
                        @endif
                        <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:0.75rem;">View</a>
                        @if($invoice->status == 'paid')
                            <a href="{{ route('dashboard.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:0.75rem;" title="Download PDF">
                                <i class="bi bi-download"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
