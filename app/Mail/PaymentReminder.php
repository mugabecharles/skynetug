<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $daysOverdue;

    public function __construct(public Invoice $invoice)
    {
        $this->daysOverdue = (int) now()->diffInDays($invoice->date_due);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⏰ Invoice {$this->invoice->invoice_number} is {$this->daysOverdue} days overdue",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'invoice'     => $this->invoice->load('user'),
                'daysOverdue' => $this->daysOverdue,
            ],
        );
    }
}
