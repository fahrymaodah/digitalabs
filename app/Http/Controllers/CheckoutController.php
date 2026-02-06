<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Affiliate;
use App\Services\DuitkuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected DuitkuService $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Show checkout page for a course
     */
    public function show(Course $course)
    {
        // Check if user already owns this course
        $user = Auth::user();

        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return redirect()
                ->route('filament.user.pages.my-courses')
                ->with('info', 'Anda sudah memiliki akses ke course ini.');
        }

        // Get payment methods
        $amount = (int) $course->price;
        $paymentMethodsResult = $this->duitkuService->getPaymentMethods($amount);

        $paymentMethods = [];
        if ($paymentMethodsResult['success']) {
            $paymentMethods = $paymentMethodsResult['payment_methods'];
        }

        // Get referral from cookie (for affiliate tracking)
        $referralCode = request()->cookie('referral_code');
        $affiliate = null;
        if ($referralCode) {
            $affiliate = Affiliate::where('referral_code', $referralCode)
                ->where('status', 'approved')
                ->first();
        }

        return view('checkout.show', compact('course', 'paymentMethods', 'affiliate'));
    }

    /**
     * Process checkout - create order and redirect to payment
     */
    public function process(Request $request, Course $course)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        // Check if user already owns this course
        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return redirect()
                ->route('filament.user.pages.my-courses')
                ->with('info', 'Anda sudah memiliki akses ke course ini.');
        }

        try {
            DB::beginTransaction();

            $subtotal = $course->price;
            $discount = 0;
            $coupon = null;

            // Apply coupon if provided
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    })
                    ->first();

                if ($coupon) {
                    // Check usage limit
                    if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                        return back()->with('error', 'Kupon sudah mencapai batas penggunaan.');
                    }

                    // Check per-user usage limit
                    if ($coupon->usage_limit_per_user) {
                        $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                            ->where('user_id', $user->id)
                            ->count();

                        if ($userUsageCount >= $coupon->usage_limit_per_user) {
                            return back()->with('error', 'Anda sudah menggunakan kupon ini sebanyak ' . $coupon->usage_limit_per_user . ' kali.');
                        }
                    }

                    // Check minimum order amount
                    if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                        return back()->with('error', 'Minimal pembelian untuk kupon ini adalah Rp ' . number_format($coupon->min_order_amount, 0, ',', '.'));
                    }

                    // Calculate discount
                    if ($coupon->type === 'percentage') {
                        $discount = $subtotal * ($coupon->value / 100);
                        
                        // Apply max discount if set
                        if ($coupon->max_discount && $discount > $coupon->max_discount) {
                            $discount = $coupon->max_discount;
                        }
                    } else {
                        $discount = $coupon->value;
                    }

                    // Ensure discount doesn't exceed subtotal
                    $discount = min($discount, $subtotal);
                }
            }

            $total = $subtotal - $discount;

            // Get affiliate from cookie
            $affiliateId = null;
            $referralCode = $request->cookie('referral_code');
            if ($referralCode) {
                $affiliate = Affiliate::where('referral_code', $referralCode)
                    ->where('status', 'approved')
                    ->first();
                if ($affiliate && $affiliate->user_id !== $user->id) {
                    $affiliateId = $affiliate->id;
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'affiliate_id' => $affiliateId,
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $course->id,
                'price' => $course->price,
                'quantity' => 1,
            ]);

            // Record coupon usage
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);

                $coupon->increment('used_count');
            }

            // Create Duitku invoice
            $result = $this->duitkuService->createInvoice($order, $request->payment_method);

            if (!$result['success']) {
                DB::rollBack();
                Log::error('Duitku createInvoice failed', ['result' => $result]);

                return back()->with('error', 'Gagal membuat pembayaran: ' . ($result['message'] ?? 'Unknown error'));
            }

            DB::commit();

            // Redirect to Duitku payment page
            return redirect()->away($result['payment_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout process error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Return page after payment (success/failed)
     */
    public function return(Request $request)
    {
        $merchantOrderId = $request->get('merchantOrderId');
        $resultCode = $request->get('resultCode');
        $reference = $request->get('reference');

        $order = Order::where('order_number', $merchantOrderId)->first();

        if (!$order) {
            return redirect('/dashboard')
                ->with('error', 'Order tidak ditemukan.');
        }

        // Check payment status from Duitku
        $statusResult = $this->duitkuService->checkStatus($merchantOrderId);

        if ($statusResult['success'] && $statusResult['status_code'] === '00') {
            // Payment successful
            // If webhook didn't update yet, do it here as fallback
            if ($order->status !== 'paid') {
                $order->markAsPaid();
                $order->update([
                    'duitku_reference' => $reference,
                ]);
            }
            
            return redirect('/dashboard/my-courses')
                ->with('success', 'Pembayaran berhasil! Selamat belajar.');
        }

        if ($statusResult['success'] && $statusResult['status_code'] === '01') {
            // Still pending
            return redirect('/dashboard/orders')
                ->with('info', 'Pembayaran masih dalam proses. Silakan selesaikan pembayaran.');
        }

        // Failed or expired
        return redirect('/dashboard/orders')
            ->with('error', 'Pembayaran gagal atau kadaluarsa.');
    }

    /**
     * Handle payment redirect - check status first before sending to Duitku
     * This prevents showing Duitku page for already-paid orders
     */
    public function pay(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect('/dashboard/orders')
                ->with('error', 'Order tidak ditemukan.');
        }

        // If already paid, redirect to my courses
        if ($order->status === 'paid') {
            return redirect('/dashboard/my-courses')
                ->with('success', 'Order ini sudah dibayar. Selamat belajar!');
        }

        // If expired or failed, redirect to orders page
        if (in_array($order->status, ['expired', 'failed', 'cancelled'])) {
            return redirect('/dashboard/orders')
                ->with('error', 'Order ini sudah tidak valid. Status: ' . $order->status);
        }

        // If pending and has payment URL, check status from Duitku first
        if ($order->status === 'pending') {
            $statusResult = $this->duitkuService->checkStatus($orderNumber);

            if ($statusResult['success'] && $statusResult['status_code'] === '00') {
                // Payment successful but our webhook hasn't updated yet
                $order->markAsPaid();
                $order->update([
                    'duitku_reference' => $statusResult['reference'] ?? null,
                ]);

                return redirect('/dashboard/my-courses')
                    ->with('success', 'Pembayaran berhasil! Selamat belajar.');
            }

            // Still pending or failed - redirect to payment page
            if ($order->duitku_payment_url) {
                return redirect()->away($order->duitku_payment_url);
            }
        }

        // Fallback - redirect to orders page
        return redirect('/dashboard/orders')
            ->with('info', 'Silakan lakukan pembayaran untuk order ini.');
    }

    /**
     * Apply coupon (AJAX)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon tidak valid atau sudah kadaluarsa.',
            ]);
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon sudah mencapai batas penggunaan.',
            ]);
        }

        // Check if user already used this coupon
        if ($coupon->usage_limit_per_user) {
            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah menggunakan kupon ini sebanyak ' . $coupon->usage_limit_per_user . ' kali.',
                ]);
            }
        }

        // Check minimum order amount
        $amount = $request->amount;
        if ($coupon->min_order_amount && $amount < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal pembelian untuk kupon ini adalah Rp ' . number_format($coupon->min_order_amount, 0, ',', '.'),
            ]);
        }

        // Calculate discount
        if ($coupon->type === 'percentage') {
            $discount = $amount * ($coupon->value / 100);
            
            // Apply max discount if set
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            // Fixed amount discount
            $discount = $coupon->value;
        }

        $discount = min($discount, $amount);
        $total = max(0, $amount - $discount);

        return response()->json([
            'success' => true,
            'discount' => round($discount),
            'total' => round($total),
            'message' => 'Kupon berhasil diterapkan! Diskon ' . ($coupon->type === 'percentage' ? $coupon->value . '%' : 'Rp ' . number_format($coupon->value, 0, ',', '.')),
        ]);
    }
}
