<?php

namespace App\Mail\Admin;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReviewReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Review $review)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Review Received - ' . $this->review->reviewable->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-review-received',
        );
    }
}
