<?php

namespace App\Mail\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order
    ) {
        // Load relationships needed for email
        $this->order->loadMissing(['user', 'items.course', 'affiliate.user', 'coupon']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $customerName = $this->order->user?->name ?? 'Unknown';
        $amount = number_format($this->order->total, 0, ',', '.');
        $status = $this->order->status === 'expired' ? 'Kadaluarsa' : 'Gagal';
        
        return new Envelope(
            subject: '❌ Pembayaran ' . $status . ': Rp ' . $amount . ' - ' . $customerName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure relationships are loaded for the view
        $this->order->loadMissing(['user', 'items.course', 'affiliate.user', 'coupon']);
        
        return new Content(
            view: 'emails.admin.payment-failed',
            with: [
                'order' => $this->order,
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
