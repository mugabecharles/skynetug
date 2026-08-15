@extends('layouts.app')
@section('title', 'Payment Failed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <div class="mx-auto mb-4" style="width:90px;height:90px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-x-lg text-danger" style="font-size:2.5rem;"></i>
                </div>
                <h2 class="fw-bold mb-2">Payment Failed</h2>
                <p class="text-muted">
                    {{ session('error', 'Your payment could not be processed. No amount has been deducted from your account.') }}
                </p>
            </div>

            <div class="bg-light rounded-3 p-4 mb-4 text-start">
                <h6 class="fw-bold mb-3">What can you do?</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="bi bi-arrow-clockwise text-primary me-2"></i>Try again with the same or a different payment method</li>
                    <li class="mb-2"><i class="bi bi-phone text-success me-2"></i>Ensure your mobile money account has sufficient balance</li>
                    <li class="mb-2"><i class="bi bi-credit-card text-warning me-2"></i>Try paying with a card via Flutterwave or Pesapal</li>
                    <li class="mb-2"><i class="bi bi-headset text-sky me-2"></i>Contact our support team if the problem persists</li>
                </ul>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-sky px-4">
                    <i class="bi bi-arrow-left me-2"></i>Retry Payment
                </a>
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-headset me-2"></i>Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
