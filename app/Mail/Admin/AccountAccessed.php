<?php

namespace App\Mail\Admin;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountAccessed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin, public string $ipAddress, public string $time)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Access Alert - UnlimitedPlug',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.account-accessed',
        );
    }
}
