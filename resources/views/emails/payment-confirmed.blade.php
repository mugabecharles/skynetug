@extends('emails.layout')
@section('content')
<div class="alert-success">
    ✅ Payment received — Thank you!
</div>

<p class="greeting">Payment Confirmed</p>

<p class="message">
    Hi {{ $invoice->user->name }},<br>
    We have successfully received your payment for invoice
    <strong>{{ $invoice->invoice_number }}</strong>.
</p>

<div class="info-box">
    <table>
        <tr><td>Invoice #</td><td>{{ $invoice->invoice_number }}</td></tr>
        <tr><td>Amount paid</td><td style="color:#00C896; font-weight:700;">UGX {{ number_format($payment->amount) }}</td></tr>
        <tr><td>Payment method</td><td>{{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}</td></tr>
        <tr><td>Transaction ref</td><td style="font-family:monospace;">{{ $payment->transaction_id }}</td></tr>
        <tr><td>Date paid</td><td>{{ $payment->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</td></tr>
    </table>
</div>

<p class="message">
    Your service will be activated shortly. You can track your order and download
    your receipt from the dashboard.
</p>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn btn-green">Download Receipt →</a>
</p>
@endsection
