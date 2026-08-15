@extends('emails.layout')
@section('content')
<p class="greeting">Invoice {{ $invoice->invoice_number }}</p>

<p class="message">
    Hi {{ $invoice->user->name }},<br>
    A new invoice has been generated for your account. Please review the details below
    and complete payment before the due date to avoid any service interruption.
</p>

<div class="info-box">
    <table>
        <tr><td>Invoice #</td><td>{{ $invoice->invoice_number }}</td></tr>
        <tr><td>Type</td><td>{{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</td></tr>
        <tr><td>Date issued</td><td>{{ $invoice->date_created->format('d M Y') }}</td></tr>
        <tr><td>Due date</td><td>{{ $invoice->date_due->format('d M Y') }}</td></tr>
        <tr><td>Amount due</td><td style="color:#0066FF; font-size:1rem;">UGX {{ number_format($invoice->total) }}</td></tr>
    </table>
</div>

@if($invoice->invoiceItems->count())
<div class="info-box" style="margin-top:0;">
    <strong style="font-size:.85rem; color:#6B7280; display:block; margin-bottom:10px;">LINE ITEMS</strong>
    <table>
        @foreach($invoice->invoiceItems as $item)
        <tr>
            <td>{{ $item->description }}</td>
            <td style="text-align:right; font-weight:600;">UGX {{ number_format($item->amount * $item->quantity) }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

<p style="text-align:center; margin:28px 0 16px;">
    <a href="{{ route('dashboard.invoices.show', $invoice->id) }}" class="btn">Pay Invoice Now →</a>
</p>

<div class="alert-warning">
    ⚠️ Payment is due by <strong>{{ $invoice->date_due->format('d M Y') }}</strong>.
    Unpaid invoices may result in service suspension.
</div>
@endsection
