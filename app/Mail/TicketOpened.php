<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketOpened extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public User $recipient,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->ticket->priority}] New Ticket: {$this->ticket->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-opened',
            with: [
                'ticket' => $this->ticket->load('user'),
            ],
        );
    }
}
