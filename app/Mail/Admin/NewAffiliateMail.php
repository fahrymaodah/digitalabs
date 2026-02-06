<?php

namespace App\Mail\Admin;

use App\Models\Affiliate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAffiliateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Affiliate $affiliate
    ) {
        // Load relationships needed for email
        $this->affiliate->loadMissing(['user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $affiliateName = $this->affiliate->user?->name ?? 'Unknown';
        
        return new Envelope(
            subject: '🤝 Pendaftaran Affiliate Baru: ' . $affiliateName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure relationships are loaded for the view
        $this->affiliate->loadMissing(['user']);
        
        return new Content(
            view: 'emails.admin.new-affiliate',
            with: [
                'affiliate' => $this->affiliate,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
