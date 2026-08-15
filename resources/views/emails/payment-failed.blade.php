@extends('emails.layout')
@section('content')
<div class="alert-danger">
    ❌ Payment unsuccessful
</div>

<p class="greeting">Payment Failed</p>

<p class="message">
    Hi {{ $invoice->user->name }},<br>
    Unfortunately, your payment attempt for invoice <strong>{{ $invoice->invoice_number }}</strong>
    was not successful. Your invoice remains unpaid and your services have not been activated.
</p>

<div class="info-box">
    <table>
        <tr><td>Invoice #</td><td>{{ $invoice->invoice_number }}</td></tr>
        <tr><td>Amount due</td><td>UGX {{ number_format($invoice->total) }}</td></tr>
        <tr><td>Due date</td><td>{{ $invoice->date_due->format('d M Y') }}</td></tr>
        <tr><td>Gateway</td><td>{{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}</td></tr>
    </table>
</div>

<p class="message">
    Common reasons for payment failure:
</p>
<ul style="color:#374151; font-size:.9rem; padding-left:20px; margin-bottom:20px;">
    <li>Insufficient funds in your mobile money or bank account</li>
    <li>Transaction timed out before approval</li>
    <li>Wrong PIN entered</li>
    <li>Network issues during the transaction</li>
</ul>

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn">Try Again →</a>
</p>

<p class="message" style="font-size:.88rem; color:#6B7280;">
    If you believe this is an error or need help, please
    <a href="{{ route('dashboard.tickets.create') }}" style="color:#0066FF;">open a support ticket</a>
    and we'll assist you immediately.
</p>
@endsection
