<?php

namespace App\Observers;

use App\Models\Affiliate;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class AffiliateObserver
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the Affiliate "created" event.
     * Send admin notification for new affiliate registration
     */
    public function created(Affiliate $affiliate): void
    {
        // Send admin notification for new affiliate
        $this->emailService->sendAdminNewAffiliateEmail($affiliate);
        Log::info('New affiliate admin notification triggered', ['affiliate_id' => $affiliate->id]);
    }

    /**
     * Handle the Affiliate "updated" event.
     * Send approval email when status changes to approved
     */
    public function updated(Affiliate $affiliate): void
    {
        // Check if status changed to approved
        if ($affiliate->isDirty('status')) {
            $newStatus = $affiliate->status;
            $oldStatus = $affiliate->getOriginal('status');

            Log::info('Affiliate status changed', [
                'affiliate_id' => $affiliate->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            // Send approval email only when status changes TO approved
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $this->emailService->sendAffiliateApprovedEmail($affiliate);
                Log::info('Affiliate approved email triggered', ['affiliate_id' => $affiliate->id]);
            }
        }
    }
}
