<?php

namespace App\Observers;

use App\Models\AffiliateCommission;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class AffiliateCommissionObserver
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the AffiliateCommission "created" event.
     * Send new commission email to affiliate
     */
    public function created(AffiliateCommission $commission): void
    {
        $this->emailService->sendNewCommissionEmail($commission);
        Log::info('New commission email triggered', [
            'commission_id' => $commission->id,
            'affiliate_id' => $commission->affiliate_id,
        ]);
    }
}
