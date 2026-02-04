<?php

namespace App\Http\Middleware;

use App\Models\Affiliate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferral
{
    /**
     * Handle an incoming request.
     *
     * Check for referral code in URL (?ref=CODE) and store in cookie for 30 days.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referralCode = $request->query('ref');

        if ($referralCode) {
            // Verify referral code exists and is from approved affiliate
            $affiliate = Affiliate::where('referral_code', $referralCode)
                ->where('status', 'approved')
                ->first();

            if ($affiliate) {
                // Store referral code in cookie for 30 days
                $response = $next($request);

                // Check if response can have cookies attached
                if ($response instanceof Response && method_exists($response, 'withCookie')) {
                    return $response->withCookie(
                        cookie('referral_code', $referralCode, 60 * 24 * 30) // 30 days
                    );
                }

                // For redirect responses
                if ($response->headers) {
                    $response->headers->setCookie(
                        cookie('referral_code', $referralCode, 60 * 24 * 30)
                    );
                }

                return $response;
            }
        }

        return $next($request);
    }
}
