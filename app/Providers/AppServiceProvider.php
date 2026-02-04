<?php

namespace App\Providers;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Order;
use App\Models\User;
use App\Observers\AffiliateCommissionObserver;
use App\Observers\AffiliateObserver;
use App\Observers\AffiliatePayoutObserver;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Model Observers for Email Notifications
        User::observe(UserObserver::class);
        Order::observe(OrderObserver::class);
        Affiliate::observe(AffiliateObserver::class);
        AffiliateCommission::observe(AffiliateCommissionObserver::class);
        AffiliatePayout::observe(AffiliatePayoutObserver::class);

        // Configure Rate Limiters
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Global API rate limiter (60 requests per minute)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Login rate limiter (5 attempts per minute per IP)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.',
                ], 429);
            });
        });

        // Registration rate limiter (3 registrations per hour per IP)
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function () {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan registrasi. Silakan coba lagi nanti.',
                ], 429);
            });
        });

        // Contact form rate limiter (5 submissions per hour per IP)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip())->response(function () {
                return back()->with('error', 'Terlalu banyak pesan dikirim. Silakan coba lagi nanti.');
            });
        });

        // Checkout rate limiter (50 checkouts per hour per user - increased for staging)
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perHour(50)->by($request->user()?->id ?: $request->ip());
        });

        // Webhook rate limiter (100 requests per minute - for payment callbacks)
        RateLimiter::for('webhook', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });

        // OAuth rate limiter (10 attempts per minute per IP)
        RateLimiter::for('oauth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Coupon apply rate limiter (30 attempts per minute per user - increased for testing)
        RateLimiter::for('coupon', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}
