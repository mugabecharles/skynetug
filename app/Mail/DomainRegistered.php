<?php

namespace App\Mail;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DomainRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Domain $domain) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Domain Registered — ' . $this->domain->domain_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.domain-registered',
            with: [
                'domain' => $this->domain->load('user'),
            ],
        );
    }
}
