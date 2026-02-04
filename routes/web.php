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
        $user = \App\Models\User::first() ?? new \App\Models\User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $course = new \stdClass();
        $course->title = 'Riset dan Produksi Asset di Microstock';
        $course->slug = 'riset-dan-produksi-asset-di-microstock';
        $course->instructor = 'Digitalabs Team';
        $course->lessons_count = 45;
        $course->duration = 'Lifetime Access';

        $order = new \stdClass();
        $order->id = 1;
        $order->order_number = 'ORD-' . date('Ymd') . '-001';
        $order->user = $user;
        $order->course = $course;
        $order->original_price = 599000;
        $order->discount_amount = 100000;
        $order->total_price = 499000;
        $order->payment_url = null; // Will use duitku_payment_url in real orders
        $order->duitku_payment_url = 'https://sandbox.duitku.com/topup/topupdirectv2.aspx?ref=EXAMPLE123';
        $order->coupon = null;

        $affiliate = new \stdClass();
        $affiliate->id = 1;
        $affiliate->user = $user;
        $affiliate->referral_code = 'SARAH2024';
        $affiliate->commission_rate = 20;
        $affiliate->status = 'approved';
        $affiliate->bank_name = 'BCA';
        $affiliate->bank_account_number = '1234567890';
        $affiliate->bank_account_name = 'Sarah Marketing';

        $commission = new \stdClass();
        $commission->id = 1;
        $commission->affiliate = $affiliate;
        $commission->order = $order;
        $commission->amount = 99800;
        $commission->created_at = now();

        $payout = new \stdClass();
        $payout->id = 1;
        $payout->affiliate = $affiliate;
        $payout->affiliate_id = 1;
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
            default => abort(404, 'Email type not found'),
        };
    })->name('dev.email-preview');
}
