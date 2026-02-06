<?php

use App\Http\Controllers\AffiliatePublicController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Sitemap Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-courses.xml', [SitemapController::class, 'courses'])->name('sitemap.courses');
Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');

// Course Pages
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{courseSlug}/watch/{lessonUuid}', [CourseController::class, 'watch'])->name('courses.watch');

// Affiliate Public Page
Route::get('/affiliate', [AffiliatePublicController::class, 'index'])->name('affiliate.index');

// Blog Pages
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('page.about');
Route::get('/contact', [PageController::class, 'contact'])->name('page.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->middleware('throttle:contact')->name('page.contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('page.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('page.terms');
Route::get('/refund', [PageController::class, 'refund'])->name('page.refund');

// Auth redirects for convenience
Route::get('/login', fn() => redirect()->route('filament.user.auth.login'))->name('login');
Route::get('/register', fn() => redirect()->route('filament.user.auth.register'))->name('register');

// Google OAuth Routes (with rate limiting)
Route::middleware('throttle:oauth')->group(function () {
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Checkout Routes (requires auth with 'user' guard - same as Filament User Panel)
Route::middleware(['auth:user', 'throttle:checkout'])->group(function () {
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->middleware('throttle:coupon')->name('checkout.apply-coupon');
    Route::get('/checkout/return', [CheckoutController::class, 'return'])->name('checkout.return');
    Route::get('/checkout/pay/{orderNumber}', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/{course:uuid}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{course:uuid}', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Webhook Routes (CSRF excluded in bootstrap/app.php, with rate limiting)
Route::post('/webhook/duitku', [WebhookController::class, 'duitku'])->middleware('throttle:webhook')->name('webhook.duitku');

// Development/Testing Routes (remove in production)
if (config('app.debug')) {
    Route::get('/dev/mark-order-paid/{orderNumber}', function ($orderNumber) {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        
        if ($order->status === 'paid') {
            return response()->json(['status' => 'already_paid', 'order_number' => $order->order_number]);
        }

        $order->markAsPaid();

        return response()->json([
            'status' => 'ok',
            'order_number' => $order->order_number,
            'message' => 'Order marked as paid',
            'user' => $order->user->name,
            'total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'courses_granted' => $order->user->courses->count(),
        ]);
    })->name('dev.mark-paid');

    // Email Preview Routes
    Route::get('/dev/email/{type}', function ($type) {
        // Get real affiliate or create demo one
        $affiliateModel = \App\Models\Affiliate::first() ?? \App\Models\Affiliate::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 1,
                'referral_code' => 'DEMO2024',
                'commission_rate' => 20,
                'status' => 'pending',
                'bank_name' => 'BCA',
                'bank_account_number' => '1234567890',
                'bank_account_name' => 'Demo User',
            ]
        );
        $affiliateModel->load('user');
        
        // Get order with affiliate or fallback to first order
        $orderModel = \App\Models\Order::whereNotNull('affiliate_id')->first() ?? \App\Models\Order::first();
        
        if (!$orderModel) {
            return 'No orders found in database. Please create an order first.';
        }
        
        // Temporarily assign affiliate for testing
        if (!$orderModel->affiliate_id) {
            $orderModel->affiliate_id = $affiliateModel->id;
            $orderModel->save();
        }
        
        $orderModel->load(['user', 'items.course', 'coupon', 'affiliate.user', 'commission']);

        // Create demo payout
        $payoutModel = \App\Models\AffiliatePayout::create([
            'affiliate_id' => $affiliateModel->id,
            'amount' => 500000,
            'status' => 'pending',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Demo User',
        ]);
        $payoutModel->load('affiliate.user');

        // Create demo objects for non-db emails
        $user = $orderModel->user;

        $course = new \stdClass();
        $course->title = 'Riset dan Produksi Asset di Microstock';
        $course->slug = 'riset-dan-produksi-asset-di-microstock';
        $course->instructor = 'Digitalabs Team';
        $course->lessons_count = 45;
        $course->duration = 'Lifetime Access';

        $order = new \stdClass();
        $order->id = $orderModel->id;
        $order->order_number = $orderModel->order_number;
        $order->user = $user;
        $order->course = $course;
        $order->original_price = 599000;
        $order->discount_amount = 100000;
        $order->total_price = 499000;
        $order->payment_url = null;
        $order->duitku_payment_url = $orderModel->duitku_payment_url ?? 'https://sandbox.duitku.com/topup/topupdirectv2.aspx?ref=EXAMPLE123';
        $order->coupon = $orderModel->coupon;

        $affiliate = new \stdClass();
        $affiliate->id = $affiliateModel->id;
        $affiliate->user = $affiliateModel->user;
        $affiliate->referral_code = $affiliateModel->referral_code;
        $affiliate->commission_rate = $affiliateModel->commission_rate;
        $affiliate->status = $affiliateModel->status;
        $affiliate->bank_name = $affiliateModel->bank_name;
        $affiliate->bank_account_number = $affiliateModel->bank_account_number;
        $affiliate->bank_account_name = $affiliateModel->bank_account_name;

        $commission = new \stdClass();
        $commission->id = 1;
        $commission->affiliate = $affiliate;
        $commission->order = $order;
        $commission->amount = 99800;
        $commission->created_at = now();

        $payout = new \stdClass();
        $payout->id = $payoutModel->id;
        $payout->affiliate = $affiliate;
        $payout->affiliate_id = $affiliateModel->id;
        $payout->amount = 500000;
        $payout->processed_at = now();

        // http://localhost/dev/email/{type}

        return match ($type) {
            'welcome' => view('emails.user.welcome', ['user' => $user]),
            'order-created' => view('emails.order.created', ['order' => $order]),
            'payment-success' => view('emails.order.payment-success', ['order' => $order]),
            'payment-failed' => view('emails.order.payment-failed', ['order' => $order, 'reason' => 'Saldo tidak mencukupi']),
            'affiliate-approved' => view('emails.affiliate.approved', ['affiliate' => $affiliate]),
            'new-commission' => view('emails.affiliate.new-commission', [
                'commission' => $commission,
                'totalCommissions' => 15,
                'pendingBalance' => 750000,
                'totalEarnings' => 2500000,
            ]),
            'payout-completed' => view('emails.affiliate.payout-completed', ['payout' => $payout]),
            
            // Admin Emails (using real data)
            'admin-payment-success' => view('emails.admin.payment-success', ['order' => $orderModel]),
            'admin-payment-failed' => view('emails.admin.payment-failed', ['order' => $orderModel]),
            'admin-new-affiliate' => view('emails.admin.new-affiliate', ['affiliate' => $affiliateModel]),
            'admin-payout-request' => view('emails.admin.payout-request', ['payout' => $payoutModel]),
            
            default => abort(404, 'Email type not found'),
        };
    })->name('dev.email-preview');
}
