<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the Order "created" event.
     * Send order created email
     */
    public function created(Order $order): void
    {
        // Send order created email
        $this->emailService->sendOrderCreatedEmail($order);
        Log::info('Order created email triggered', ['order_id' => $order->id]);
    }

    /**
     * Handle the Order "updated" event.
     * Send payment status emails
     */
    public function updated(Order $order): void
    {
        // Check if status changed
        if ($order->isDirty('status')) {
            $newStatus = $order->status;
            $oldStatus = $order->getOriginal('status');

            Log::info('Order status changed', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            match ($newStatus) {
                'paid' => $this->emailService->sendPaymentSuccessEmail($order),
                'failed', 'cancelled' => $this->emailService->sendPaymentFailedEmail($order),
                'expired' => $this->emailService->sendPaymentFailedEmail($order, 'Waktu pembayaran telah habis'),
                default => null,
            };
        }
    }
}
