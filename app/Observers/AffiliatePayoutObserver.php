<?php

namespace App\Observers;

use App\Models\AffiliatePayout;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class AffiliatePayoutObserver
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the AffiliatePayout "created" event.
     * Send notification email to admin for new payout requests
     */
    public function created(AffiliatePayout $payout): void
    {
        // Send admin notification for new payout request
        if ($payout->status === 'pending') {
            $this->emailService->sendAdminPayoutRequestEmail($payout);
            Log::info('Admin payout request email triggered', ['payout_id' => $payout->id]);
        }
    }

    /**
     * Handle the AffiliatePayout "updated" event.
     * Send payout completed email when status changes to completed/paid
     */
    public function updated(AffiliatePayout $payout): void
    {
        // Check if status changed to completed/paid
        if ($payout->isDirty('status')) {
            $newStatus = $payout->status;
            $oldStatus = $payout->getOriginal('status');

            Log::info('Payout status changed', [
                'payout_id' => $payout->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // Send completion email when payout is completed/paid
            if (in_array($newStatus, ['completed', 'paid']) && !in_array($oldStatus, ['completed', 'paid'])) {
                $this->emailService->sendPayoutCompletedEmail($payout);
                Log::info('Payout completed email triggered', ['payout_id' => $payout->id]);
            }
        }
    }
}
