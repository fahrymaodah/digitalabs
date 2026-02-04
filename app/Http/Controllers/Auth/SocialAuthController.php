<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('filament.user.auth.login')
                ->with('error', 'Failed to authenticate with Google. Please try again.');
        }

        // Find existing user by Google ID or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update Google ID if not set (user registered with email first)
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'provider' => 'google',
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            }

            // Mark email as verified if not already
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'provider' => 'google',
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)), // Random password
                'email_verified_at' => now(), // Auto-verify email from Google
            ]);

            // Send welcome email for new Google users
            try {
                app(EmailService::class)->sendWelcomeEmail($user);
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email for Google user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Login the user with 'user' guard
        Auth::guard('user')->login($user, true);

        // Regenerate session untuk prevent CSRF issues
        request()->session()->regenerate();

        // Jika phone belum diisi, redirect ke profile untuk melengkapi info
        if (!$user->phone) {
            return redirect('/dashboard/profile')->with('message', 'Please complete your profile information');
        }

        return redirect('/dashboard');
    }
}
