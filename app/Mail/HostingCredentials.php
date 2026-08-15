<?php

namespace App\Mail;

use App\Models\HostingAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HostingCredentials extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public HostingAccount $account,
        public string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Hosting Account is Ready — ' . $this->account->domain,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hosting-credentials',
            with: [
                'account'  => $this->account->load(['user', 'server']),
                'password' => $this->password,
            ],
        );
    }
}
