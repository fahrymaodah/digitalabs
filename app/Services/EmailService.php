<?php

namespace App\Services;

use App\Mail\Affiliate\AffiliateApprovedMail;
use App\Mail\Affiliate\NewCommissionMail;
use App\Mail\Affiliate\PayoutCompletedMail;
use App\Mail\Order\OrderCreatedMail;
use App\Mail\Order\PaymentFailedMail;
use App\Mail\Order\PaymentSuccessMail;
use App\Mail\User\WelcomeMail;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            Log::info('Welcome email sent', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send order created email
     */
    public function sendOrderCreatedEmail(Order $order): void
    {
        try {
            $order->load(['user', 'course', 'coupon']);
            Mail::to($order->user->email)->send(new OrderCreatedMail($order));
            Log::info('Order created email sent', [
                'order_id' => $order->id,
                'user_email' => $order->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order created email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment success email
     */
    public function sendPaymentSuccessEmail(Order $order): void
    {
        try {
            $order->load(['user', 'course']);
            Mail::to($order->user->email)->send(new PaymentSuccessMail($order));
            Log::info('Payment success email sent', [
                'order_id' => $order->id,
                'user_email' => $order->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment success email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment failed email
     */
    public function sendPaymentFailedEmail(Order $order, ?string $reason = null): void
    {
        try {
            $order->load(['user', 'course']);
            Mail::to($order->user->email)->send(new PaymentFailedMail($order, $reason));
            Log::info('Payment failed email sent', [
                'order_id' => $order->id,
                'user_email' => $order->user->email,
                'reason' => $reason
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send affiliate approved email
     */
    public function sendAffiliateApprovedEmail(Affiliate $affiliate): void
    {
        try {
            $affiliate->load('user');
            Mail::to($affiliate->user->email)->send(new AffiliateApprovedMail($affiliate));
            Log::info('Affiliate approved email sent', [
                'affiliate_id' => $affiliate->id,
                'user_email' => $affiliate->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send affiliate approved email', [
                'affiliate_id' => $affiliate->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send new commission email to affiliate
     */
    public function sendNewCommissionEmail(AffiliateCommission $commission): void
    {
        try {
            $commission->load(['affiliate.user', 'order.course']);
            
            $affiliate = $commission->affiliate;
            $totalCommissions = $affiliate->commissions()->count();
            $pendingBalance = $affiliate->commissions()
                ->where('status', 'pending')
                ->sum('amount');
            $totalEarnings = $affiliate->commissions()
                ->where('status', 'paid')
                ->sum('amount');

            Mail::to($affiliate->user->email)->send(new NewCommissionMail(
                $commission,
                $totalCommissions,
                $pendingBalance,
                $totalEarnings
            ));

            Log::info('New commission email sent', [
                'commission_id' => $commission->id,
                'affiliate_id' => $affiliate->id,
                'user_email' => $affiliate->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send new commission email', [
                'commission_id' => $commission->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payout completed email
     */
    public function sendPayoutCompletedEmail(AffiliatePayout $payout): void
    {
        try {
            $payout->load('affiliate.user');
            Mail::to($payout->affiliate->user->email)->send(new PayoutCompletedMail($payout));
            Log::info('Payout completed email sent', [
                'payout_id' => $payout->id,
                'affiliate_id' => $payout->affiliate_id,
                'user_email' => $payout->affiliate->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payout completed email', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
