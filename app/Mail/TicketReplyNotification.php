<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReplyNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public TicketReply $reply,
        public string $recipientName,
        public bool $isStaffReply,
    ) {}

    public function envelope(): Envelope
    {
        $prefix = $this->isStaffReply ? 'Support Reply' : 'Customer Reply';
        return new Envelope(
            subject: "[{$prefix}] Ticket #{$this->ticket->ticket_number}: {$this->ticket->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-reply',
            with: [
                'ticket'        => $this->ticket->load('user'),
                'reply'         => $this->reply->load('user'),
                'recipientName' => $this->recipientName,
                'isStaffReply'  => $this->isStaffReply,
            ],
        );
    }
}
