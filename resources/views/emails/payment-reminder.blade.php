@extends('emails.layout')
@section('content')
<div class="alert-warning">
    ⏰ Payment overdue — action required
</div>

<p class="greeting">Payment Reminder</p>

<p class="message">
    Hi {{ $invoice->user->name }},<br>
    Invoice <strong>{{ $invoice->invoice_number }}</strong> was due on
    <strong>{{ $invoice->date_due->format('d M Y') }}</strong> and is now
    <strong>{{ $daysOverdue }} {{ $daysOverdue === 1 ? 'day' : 'days' }} overdue</strong>.
    Please settle this invoice as soon as possible to avoid service suspension.
</p>

<div class="info-box">
    <table>
        <tr><td>Invoice #</td><td>{{ $invoice->invoice_number }}</td></tr>
        <tr><td>Original due date</td><td>{{ $invoice->date_due->format('d M Y') }}</td></tr>
        <tr><td>Days overdue</td><td style="color:#d97706; font-weight:700;">{{ $daysOverdue }} days</td></tr>
        <tr><td>Amount due</td><td style="color:#dc2626; font-weight:700; font-size:1rem;">UGX {{ number_format($invoice->total) }}</td></tr>
        @if($invoice->late_fee > 0)
        <tr><td>Late fee</td><td style="color:#dc2626;">+ UGX {{ number_format($invoice->late_fee) }}</td></tr>
        @endif
    </table>
</div>

@if($daysOverdue >= 7)
<div class="alert-danger">
    🚨 Your hosting service is at risk of suspension. Please pay immediately to
    keep your website online.
</div>
@endif

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn"
       style="background:#EF4444;">Pay Now →</a>
</p>
@endsection
