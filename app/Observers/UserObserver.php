<?php

namespace App\Observers;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Handle the User "created" event.
     * Send welcome email to new users
     */
    public function created(User $user): void
    {
        // Only send welcome email if user has verified email or doesn't require verification
        // For users who need to verify, we'll send after verification
        if ($user->email_verified_at || !$user->hasVerifiedEmail()) {
            // Skip for now - will be sent after email verification
            // or through registration flow
        }

        Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);
    }

    /**
     * Handle the User "verified" event.
     * Send welcome email after email verification
     */
    public function verified(User $user): void
    {
        $this->emailService->sendWelcomeEmail($user);
        Log::info('Welcome email triggered after verification', ['user_id' => $user->id]);
    }
}
