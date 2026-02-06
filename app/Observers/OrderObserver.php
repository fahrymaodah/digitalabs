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
                'paid' => $this->handlePaymentSuccess($order),
                'failed', 'cancelled' => $this->handlePaymentFailed($order),
                'expired' => $this->handlePaymentExpired($order),
                default => null,
            };
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess(Order $order): void
    {
        // Send customer email
        $this->emailService->sendPaymentSuccessEmail($order);
        
        // Send admin notification
        $this->emailService->sendAdminPaymentSuccessEmail($order);
    }

    /**
     * Handle failed/cancelled payment
     */
    protected function handlePaymentFailed(Order $order): void
    {
        // Send customer email
        $this->emailService->sendPaymentFailedEmail($order);
        
        // Send admin notification
        $this->emailService->sendAdminPaymentFailedEmail($order);
    }

    /**
     * Handle expired payment
     */
    protected function handlePaymentExpired(Order $order): void
    {
        // Send customer email
        $this->emailService->sendPaymentFailedEmail($order, 'Waktu pembayaran telah habis');
        
        // Send admin notification
        $this->emailService->sendAdminPaymentFailedEmail($order);
    }
}
