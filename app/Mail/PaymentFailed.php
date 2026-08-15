<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public Payment $payment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Failed — Invoice ' . $this->invoice->invoice_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-failed',
            with: [
                'invoice' => $this->invoice->load('user'),
                'payment' => $this->payment,
            ],
        );
    }
}
