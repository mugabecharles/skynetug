<?php

namespace App\Mail;

use App\Models\HostingAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountSuspended extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public HostingAccount $account) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔴 Your hosting account has been suspended — ' . $this->account->domain,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-suspended',
            with: [
                'account' => $this->account->load('user'),
            ],
        );
    }
}
