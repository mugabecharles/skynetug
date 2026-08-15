@extends('layouts.dashboard')
@section('page_title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;"><i class="bi bi-arrow-left me-1"></i>Back</a>
    @if($invoice->status=='paid')
    <form method="POST" action="{{ route('admin.invoices.refund',$invoice->id) }}" onsubmit="return confirm('Process refund?')">
        @csrf <button class="btn btn-sm btn-outline-danger" style="border-radius:8px;">Process Refund</button>
    </form>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-0">{{ $invoice->invoice_number }}</h5>
                    <p class="text-muted small mb-0">{{ $invoice->user->name }} — {{ $invoice->user->email }}</p>
                </div>
                @php $c=match($invoice->status){'paid'=>'success','unpaid'=>'warning','overdue'=>'danger','cancelled'=>'secondary',default=>'secondary'}; @endphp
                <span class="badge bg-{{ $c }} fs-6 px-3 py-2">{{ strtoupper($invoice->status) }}</span>
            </div>
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light"><tr><th>Description</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-end">{{ $item->quantity }}</td>
                        <td class="text-end">$ {{ number_format($item->amount) }}</td>
                        <td class="text-end fw-semibold">$ {{ number_format($item->amount * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end text-muted">Subtotal</td><td class="text-end">$ {{ number_format($invoice->subtotal) }}</td></tr>
                    @if($invoice->tax>0)<tr><td colspan="3" class="text-end text-muted">Tax</td><td class="text-end">$ {{ number_format($invoice->tax) }}</td></tr>@endif
                    <tr class="fw-bold fs-5"><td colspan="3" class="text-end">Total</td><td class="text-end">$ {{ number_format($invoice->total) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-3">Invoice Details</h6>
            <dl class="row small mb-0">
                <dt class="col-5 text-muted">Date</dt><dd class="col-7">{{ \Carbon\Carbon::parse($invoice->date_created)->format('d M Y') }}</dd>
                <dt class="col-5 text-muted">Due Date</dt><dd class="col-7">{{ \Carbon\Carbon::parse($invoice->date_due)->format('d M Y') }}</dd>
                <dt class="col-5 text-muted">Date Paid</dt><dd class="col-7">{{ $invoice->date_paid ? \Carbon\Carbon::parse($invoice->date_paid)->format('d M Y') : '—' }}</dd>
                <dt class="col-5 text-muted">Currency</dt><dd class="col-7">{{ $invoice->currency }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
