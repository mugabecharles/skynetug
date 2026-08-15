@extends('layouts.app')
@section('title', 'Payment Pending')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <div id="pendingState">
                <div class="mb-4">
                    <div class="mx-auto mb-4" style="width:90px;height:90px;background:#FFF3CD;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <div class="spinner-border text-warning" style="width:3rem;height:3rem;" role="status"></div>
                    </div>
                    <h2 class="fw-bold mb-2">Waiting for Payment</h2>
                    <p class="text-muted">A payment prompt has been sent to your MTN Mobile Money number.</p>
                </div>

                <div class="bg-warning bg-opacity-10 border border-warning rounded-3 p-4 mb-4 text-start">
                    <h6 class="fw-bold mb-3"><i class="bi bi-phone-fill me-2 text-warning"></i>Complete Payment on Your Phone</h6>
                    <ol class="small text-muted mb-0 ps-3">
                        <li class="mb-2">Check your phone for an MTN Mobile Money prompt</li>
                        <li class="mb-2">Enter your MTN PIN to approve the payment</li>
                        <li class="mb-2">This page will automatically update when confirmed</li>
                    </ol>
                </div>

                <div class="mb-4">
                    <div style="background:#f8fafc;border-radius:10px;padding:16px;">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Transaction ID</span>
                            <code>{{ $payment?->transaction_id ?? request('transaction_id') }}</code>
                        </div>
                        @if($payment)
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Amount</span>
                            <span class="fw-bold">{{ $payment->currency }} {{ number_format($payment->amount) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <p class="text-muted small" id="statusMsg">Checking payment status...</p>
                <p class="text-muted small">Page auto-refreshes every 5 seconds</p>

                <div class="d-flex gap-2 justify-content-center mt-3">
                    <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
                        Check Later
                    </a>
                    <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-outline-primary btn-sm">
                        Contact Support
                    </a>
                </div>
            </div>

            {{-- Success state (shown when payment confirmed) --}}
            <div id="successState" style="display:none;">
                <div class="mb-4">
                    <div class="mx-auto mb-4" style="width:90px;height:90px;background:#D1FAE5;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-2 text-success">Payment Confirmed!</h2>
                    <p class="text-muted">Your payment has been received. Your services are being activated.</p>
                </div>
                <a href="{{ route('dashboard.index') }}" class="btn btn-sky px-4">
                    <i class="bi bi-grid me-2"></i>Go to Dashboard
                </a>
            </div>

            {{-- Failed state --}}
            <div id="failedState" style="display:none;">
                <div class="mx-auto mb-4" style="width:90px;height:90px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-x-lg text-danger" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-2 text-danger">Payment Failed</h2>
                <p class="text-muted">The payment was declined or timed out.</p>
                <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-sky px-4">
                    Try Again
                </a>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
const transactionId = '{{ $payment?->transaction_id ?? request("transaction_id") }}';
let pollCount = 0;
const maxPolls = 60; // 5 minutes (60 × 5 seconds)

function checkStatus() {
    if (pollCount >= maxPolls) {
        document.getElementById('statusMsg').textContent = 'Payment timed out. Please check your dashboard.';
        return;
    }

    pollCount++;
    document.getElementById('statusMsg').textContent = 'Checking... (attempt ' + pollCount + ')';

    fetch('{{ route("payment.check-status") }}?transaction_id=' + transactionId, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'SUCCESSFUL') {
            document.getElementById('pendingState').style.display = 'none';
            document.getElementById('successState').style.display = 'block';
            setTimeout(() => window.location.href = data.redirect || '{{ route("dashboard.index") }}', 2000);

        } else if (data.status === 'FAILED') {
            document.getElementById('pendingState').style.display = 'none';
            document.getElementById('failedState').style.display = 'block';

        } else {
            // Still pending — poll again in 5 seconds
            setTimeout(checkStatus, 5000);
        }
    })
    .catch(() => {
        setTimeout(checkStatus, 5000);
    });
}

// Start polling after 3 seconds
setTimeout(checkStatus, 3000);
</script>
@endpush
@endsection
