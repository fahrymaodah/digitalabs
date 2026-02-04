<?php

namespace App\Mail\Affiliate;

use App\Models\AffiliateCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCommissionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public AffiliateCommission $commission,
        public int $totalCommissions = 0,
        public float $pendingBalance = 0,
        public float $totalEarnings = 0
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 Komisi Baru: Rp ' . number_format($this->commission->amount, 0, ',', '.'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.affiliate.new-commission',
            with: [
                'commission' => $this->commission,
                'totalCommissions' => $this->totalCommissions,
                'pendingBalance' => $this->pendingBalance,
                'totalEarnings' => $this->totalEarnings,
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
