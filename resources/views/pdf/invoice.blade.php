<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 13px; color: #0A0F1E; line-height: 1.5; }

        .page { padding: 40px; max-width: 800px; margin: 0 auto; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; padding-bottom: 24px; border-bottom: 2px solid #0066FF; }
        .brand-name { font-size: 26px; font-weight: 900; color: #0066FF; letter-spacing: -0.5px; }
        .brand-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .invoice-meta { text-align: right; }
        .invoice-number { font-size: 20px; font-weight: 700; color: #0A0F1E; }
        .invoice-label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Status Badge */
        .status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-unpaid { background: #fef3c7; color: #92400e; }
        .status-overdue { background: #fee2e2; color: #991b1b; }

        /* Addresses */
        .addresses { display: flex; justify-content: space-between; margin-bottom: 32px; }
        .address-block { width: 45%; }
        .address-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #6b7280; margin-bottom: 6px; }
        .address-name { font-weight: 700; font-size: 14px; margin-bottom: 3px; }
        .address-line { color: #6b7280; font-size: 12px; }

        /* Dates */
        .dates-row { display: flex; gap: 32px; margin-bottom: 28px; padding: 12px 16px; background: #f8fafc; border-radius: 8px; }
        .date-item { }
        .date-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .date-value { font-weight: 600; font-size: 13px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead th { background: #0066FF; color: #fff; padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 10px 14px; border-bottom: 1px solid #e8ecf0; font-size: 13px; }
        tbody td:last-child { text-align: right; font-weight: 600; }
        tbody tr:nth-child(even) td { background: #f8fafc; }

        /* Totals */
        .totals { margin-left: auto; width: 260px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid #e8ecf0; }
        .totals-row:last-child { border-bottom: none; font-weight: 700; font-size: 15px; color: #0066FF; padding-top: 10px; }
        .totals-label { color: #6b7280; }

        /* Payment Info */
        .payment-info { margin-top: 32px; padding: 16px; background: #f0f4ff; border-radius: 8px; border-left: 3px solid #0066FF; }
        .payment-info h4 { font-size: 12px; font-weight: 700; color: #0066FF; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .payment-info p { font-size: 12px; color: #374151; margin-bottom: 4px; }

        /* Footer */
        .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #e8ecf0; text-align: center; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="brand-name">SkyNetug</div>
            <div class="brand-sub">Web Hosting & Domain Registration</div>
            <div class="brand-sub" style="margin-top:4px;">Kampala, Uganda &nbsp;|&nbsp; support@skynetug.com</div>
            <div class="brand-sub">+256 700 000 000</div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-label">Invoice</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div style="margin-top:8px;">
                <span class="status-badge status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
            </div>
        </div>
    </div>

    {{-- Addresses --}}
    <div class="addresses">
        <div class="address-block">
            <div class="address-label">From</div>
            <div class="address-name">SkyNetug Ltd</div>
            <div class="address-line">Plot 1, Kampala Road</div>
            <div class="address-line">Kampala, Uganda</div>
            <div class="address-line">support@skynetug.com</div>
        </div>
        <div class="address-block" style="text-align:right;">
            <div class="address-label">Bill To</div>
            <div class="address-name">{{ $invoice->user->name }}</div>
            <div class="address-line">{{ $invoice->user->email }}</div>
            @if($invoice->user->phone)
            <div class="address-line">{{ $invoice->user->phone }}</div>
            @endif
            @if($invoice->user->city)
            <div class="address-line">{{ $invoice->user->city }}, {{ $invoice->user->country }}</div>
            @endif
        </div>
    </div>

    {{-- Dates --}}
    <div class="dates-row">
        <div class="date-item">
            <div class="date-label">Issue Date</div>
            <div class="date-value">{{ \Carbon\Carbon::parse($invoice->date_created)->format('d M Y') }}</div>
        </div>
        <div class="date-item">
            <div class="date-label">Due Date</div>
            <div class="date-value">{{ \Carbon\Carbon::parse($invoice->date_due)->format('d M Y') }}</div>
        </div>
        @if($invoice->date_paid)
        <div class="date-item">
            <div class="date-label">Paid On</div>
            <div class="date-value">{{ \Carbon\Carbon::parse($invoice->date_paid)->format('d M Y') }}</div>
        </div>
        @endif
        <div class="date-item">
            <div class="date-label">Currency</div>
            <div class="date-value">{{ $invoice->currency }}</div>
        </div>
    </div>

    {{-- Line Items --}}
    <table>
        <thead>
            <tr>
                <th style="width:50%;">Description</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">{{ number_format($item->amount) }}</td>
                <td>{{ number_format($item->amount * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="totals-row">
            <span class="totals-label">Subtotal</span>
            <span>{{ $invoice->currency }} {{ number_format($invoice->subtotal) }}</span>
        </div>
        @if($invoice->tax > 0)
        <div class="totals-row">
            <span class="totals-label">Tax</span>
            <span>{{ $invoice->currency }} {{ number_format($invoice->tax) }}</span>
        </div>
        @endif
        @if($invoice->credit > 0)
        <div class="totals-row">
            <span class="totals-label" style="color:#059669;">Discount / Credit</span>
            <span style="color:#059669;">- {{ $invoice->currency }} {{ number_format($invoice->credit) }}</span>
        </div>
        @endif
        <div class="totals-row">
            <span>Total Due</span>
            <span>{{ $invoice->currency }} {{ number_format($invoice->total) }}</span>
        </div>
    </div>

    {{-- Payment Methods --}}
    @if($invoice->status !== 'paid')
    <div class="payment-info" style="margin-top:24px;">
        <h4>How to Pay</h4>
        <p><strong>MTN Mobile Money:</strong> Dial *165# and send to 0700 000 000 (SkyNetug Ltd). Use invoice {{ $invoice->invoice_number }} as reference.</p>
        <p><strong>Airtel Money:</strong> Dial *185# and send to 0757 000 000. Use invoice number as reference.</p>
        <p><strong>Card / Online:</strong> Visit your dashboard at skynetug.com and click "Pay Now" on this invoice.</p>
    </div>
    @else
    <div class="payment-info" style="background:#d1fae5;border-color:#059669;margin-top:24px;">
        <h4 style="color:#059669;">Payment Received</h4>
        @foreach($invoice->payments->where('status','completed') as $pmt)
        <p>Paid {{ \Carbon\Carbon::parse($pmt->paid_at)->format('d M Y H:i') }} via {{ ucfirst(str_replace('_',' ',$pmt->gateway)) }} — Ref: {{ $pmt->gateway_transaction_ref ?? $pmt->transaction_id }}</p>
        @endforeach
    </div>
    @endif

    {{-- Notes --}}
    @if($invoice->notes)
    <div style="margin-top:20px;padding:12px 16px;background:#f8fafc;border-radius:8px;font-size:12px;color:#6b7280;">
        <strong>Notes:</strong> {{ $invoice->notes }}
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>SkyNetug Ltd &nbsp;·&nbsp; Kampala, Uganda &nbsp;·&nbsp; support@skynetug.com &nbsp;·&nbsp; +256 700 000 000</p>
        <p style="margin-top:4px;">Thank you for your business. This is a computer-generated invoice.</p>
    </div>

</div>
</body>
</html>

