<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceGenerated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice ' . $this->invoice->invoice_number . ' — UGX ' . number_format($this->invoice->total),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-generated',
            with: [
                'invoice' => $this->invoice->load(['user', 'invoiceItems']),
            ],
        );
    }
}
