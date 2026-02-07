<?php

namespace App\Mail\Admin;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeSuperAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Super Admin - UnlimitedPlug',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.welcome-super-admin',
        );
    }
}
