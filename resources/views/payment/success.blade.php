@extends('layouts.app')
@section('title', 'Payment Successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <div class="mx-auto mb-4" style="width:90px;height:90px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-2">Payment Successful!</h2>
                <p class="text-muted">Thank you for your payment. Your services are being activated.</p>
            </div>

            @if($payment)
            <div class="bg-white rounded-3 border p-4 mb-4 text-start">
                <h6 class="fw-bold mb-3">Payment Details</h6>
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted">Transaction ID</dt>
                    <dd class="col-7 font-monospace fw-semibold">{{ $payment->transaction_id }}</dd>
                    <dt class="col-5 text-muted">Amount Paid</dt>
                    <dd class="col-7 fw-bold">$ {{ number_format($payment->amount) }}</dd>
                    <dt class="col-5 text-muted">Payment Method</dt>
                    <dd class="col-7">{{ ucfirst(str_replace('_',' ',$payment->gateway)) }}</dd>
                    <dt class="col-5 text-muted">Date</dt>
                    <dd class="col-7">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : now()->format('d M Y H:i') }}</dd>
                </dl>
            </div>
            @endif

            <div class="bg-light rounded-3 p-4 mb-4">
                <h6 class="fw-bold mb-3">What Happens Next?</h6>
                <ul class="list-unstyled text-start small mb-0">
                    @foreach([
                        '✅ Your invoice has been marked as paid',
                        '⚡ Hosting account will be created within 5 minutes',
                        '📧 You will receive your login credentials by email',
                        '🌐 Your domain will be active within 24-48 hours (if registered)',
                    ] as $step)
                    <li class="mb-2">{{ $step }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('dashboard.index') }}" class="btn btn-sky px-4">
                    <i class="bi bi-grid me-2"></i>Go to Dashboard
                </a>
                <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-outline-primary px-4">
                    View Invoices
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
