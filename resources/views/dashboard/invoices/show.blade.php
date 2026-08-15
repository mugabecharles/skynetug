@extends('layouts.dashboard')
@section('page_title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="mb-4 d-flex gap-2">
    <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    @if($invoice->status == 'paid')
        <a href="{{ route('dashboard.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
            <i class="bi bi-download me-1"></i>Download PDF
        </a>
    @endif
</div>

<div class="row g-4">
    {{-- Invoice Detail --}}
    <div class="col-lg-8">
        <div class="bg-white rounded-3 border p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-0">{{ $invoice->invoice_number }}</h5>
                    <p class="text-muted small mb-0">Issued {{ \Carbon\Carbon::parse($invoice->date_created)->format('d M Y') }}</p>
                </div>
                @php $c = match($invoice->status) { 'paid'=>'success','unpaid'=>'warning','overdue'=>'danger','cancelled'=>'secondary', default=>'secondary' }; @endphp
                <span class="badge bg-{{ $c }} fs-6 px-3 py-2">{{ strtoupper($invoice->status) }}</span>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <p class="text-muted small mb-1">FROM</p>
                    <p class="fw-semibold mb-0">SkyNetug Ltd</p>
                    <p class="small text-muted">Kampala, Uganda<br>support@skynetug.com</p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">TO</p>
                    <p class="fw-semibold mb-0">{{ auth()->user()->name }}</p>
                    <p class="small text-muted">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
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
                    <tr class="border-top">
                        <td colspan="3" class="text-end text-muted">Subtotal</td>
                        <td class="text-end">$ {{ number_format($invoice->subtotal) }}</td>
                    </tr>
                    @if($invoice->tax > 0)
                    <tr>
                        <td colspan="3" class="text-end text-muted">Tax</td>
                        <td class="text-end">$ {{ number_format($invoice->tax) }}</td>
                    </tr>
                    @endif
                    @if($invoice->credit > 0)
                    <tr>
                        <td colspan="3" class="text-end text-success">Credit Applied</td>
                        <td class="text-end text-success">- $ {{ number_format($invoice->credit) }}</td>
                    </tr>
                    @endif
                    <tr class="fw-bold fs-5">
                        <td colspan="3" class="text-end">Total Due</td>
                        <td class="text-end">$ {{ number_format($invoice->total) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($invoice->notes)
            <div class="mt-3 p-3 bg-light rounded-3">
                <p class="small text-muted mb-0"><strong>Notes:</strong> {{ $invoice->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Payment History --}}
        @if($invoice->payments->isNotEmpty())
        <div class="bg-white rounded-3 border p-4 mt-4">
            <h6 class="fw-bold mb-3">Payment History</h6>
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr><th>Date</th><th>Method</th><th>Ref</th><th>Amount</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : '—' }}</td>
                        <td>{{ ucfirst(str_replace('_',' ',$payment->gateway)) }}</td>
                        <td class="font-monospace small">{{ $payment->gateway_transaction_ref ?? $payment->transaction_id }}</td>
                        <td>$ {{ number_format($payment->amount) }}</td>
                        <td>
                            @php $pc = match($payment->status) { 'completed'=>'success','pending'=>'warning','failed'=>'danger', default=>'secondary' }; @endphp
                            <span class="badge bg-{{ $pc }}-subtle text-{{ $pc }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Pay Now Panel --}}
    <div class="col-lg-4">
        @if(in_array($invoice->status, ['unpaid','overdue']))
        <div class="bg-white rounded-3 border p-4">
            <h6 class="fw-bold mb-1">Pay This Invoice</h6>
            <p class="text-muted small mb-4">Amount due: <span class="fw-bold text-dark">$ {{ number_format($invoice->total) }}</span></p>

            <form method="POST" action="{{ route('payment.initiate') }}" id="payForm">
                @csrf
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Select Payment Method</label>
                    <div class="d-grid gap-2">
                        @foreach([
                            ['mtn_mobile_money', 'MTN Mobile Money', 'bi-phone', '#FFD200', '#000'],
                            ['airtel_money',     'Airtel Money',     'bi-phone', '#FF0000', '#fff'],
                            ['flutterwave',      'Card / Bank (Flutterwave)', 'bi-credit-card', '#F5A623', '#fff'],
                            ['pesapal',          'Pesapal',          'bi-bank',  '#1E7BCC', '#fff'],
                        ] as [$val, $label, $icon, $bg, $fg])
                        <label class="d-flex align-items-center gap-3 border rounded-3 p-3 cursor-pointer" style="cursor:pointer;" id="gw-{{ $val }}">
                            <input type="radio" name="gateway" value="{{ $val }}" class="gateway-radio" style="accent-color:#0066FF;">
                            <span class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                  style="width:32px;height:32px;background:{{ $bg }};color:{{ $fg }};">
                                <i class="bi {{ $icon }} small"></i>
                            </span>
                            <span class="fw-semibold small">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Phone for mobile money --}}
                <div class="mb-4" id="phoneField" style="display:none;">
                    <label class="form-label small fw-semibold">Mobile Money Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="256700000000">
                    <div class="form-text">Enter number in international format (256...)</div>
                </div>

                <button type="submit" class="btn btn-sky w-100 py-2 fw-semibold" id="payBtn" disabled>
                    <i class="bi bi-lock me-2"></i>Pay $ {{ number_format($invoice->total) }}
                </button>
            </form>
        </div>
        @else
        <div class="bg-success-subtle border border-success rounded-3 p-4 text-center">
            <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>
            <h6 class="fw-bold text-success">Invoice Paid</h6>
            <p class="small text-muted mb-3">Paid on {{ $invoice->date_paid ? \Carbon\Carbon::parse($invoice->date_paid)->format('d M Y') : 'N/A' }}</p>
            <a href="{{ route('dashboard.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download me-1"></i>Download Receipt
            </a>
        </div>
        @endif

        <div class="bg-white rounded-3 border p-4 mt-3">
            <h6 class="fw-bold mb-2 small">Need help?</h6>
            <p class="text-muted small mb-2">If you have issues with this invoice, please contact support.</p>
            <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-sm btn-outline-primary w-100">Open Support Ticket</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const radios = document.querySelectorAll('.gateway-radio');
const phoneField = document.getElementById('phoneField');
const payBtn = document.getElementById('payBtn');
const mobileGateways = ['mtn_mobile_money','airtel_money'];

radios.forEach(r => {
    r.addEventListener('change', () => {
        payBtn.disabled = false;
        phoneField.style.display = mobileGateways.includes(r.value) ? 'block' : 'none';
        document.querySelectorAll('label[id^="gw-"]').forEach(l => l.style.borderColor = '');
        document.getElementById('gw-' + r.value).style.borderColor = '#0066FF';
    });
});
</script>
@endpush
@endsection
