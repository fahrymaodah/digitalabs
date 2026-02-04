<?php

namespace App\Listeners;

use App\Services\EmailService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;

        Log::info('Sending welcome email after verification', ['user_id' => $user->id]);

        $this->emailService->sendWelcomeEmail($user);
    }
}
