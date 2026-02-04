<?php

namespace App\Mail\Affiliate;

use App\Models\AffiliatePayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public AffiliatePayout $payout
    ) {
        // Load relationships needed for email
        $this->payout->loadMissing(['affiliate.user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏦 Pencairan Rp ' . number_format($this->payout->amount, 0, ',', '.') . ' Berhasil!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure relationships are loaded for the view
        $this->payout->loadMissing(['affiliate.user']);
        
        return new Content(
            view: 'emails.affiliate.payout-completed',
            with: [
                'payout' => $this->payout,
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
