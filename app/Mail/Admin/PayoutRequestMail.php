<?php

namespace App\Mail\Admin;

use App\Models\AffiliatePayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutRequestMail extends Mailable implements ShouldQueue
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
        $affiliateName = $this->payout->affiliate?->user?->name ?? 'Unknown';
        $amount = number_format($this->payout->amount, 0, ',', '.');
        
        return new Envelope(
            subject: '💰 Permintaan Pencairan Affiliate: Rp ' . $amount . ' - ' . $affiliateName,
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
            view: 'emails.admin.payout-request',
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
