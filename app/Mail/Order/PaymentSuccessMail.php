<?php

namespace App\Mail\Order;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order
    ) {
        // Load relationships needed for email
        $this->order->loadMissing(['user', 'items.course', 'affiliate.user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Reload relationships after queue deserialization
        $this->order->loadMissing(['items.course']);
        
        $courseTitle = $this->order->course?->title ?? 'Course';
        
        return new Envelope(
            subject: '✅ Pembayaran Berhasil - ' . $courseTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure relationships are loaded for the view
        $this->order->loadMissing(['user', 'items.course', 'coupon', 'affiliate.user']);
        
        return new Content(
            view: 'emails.order.payment-success',
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
