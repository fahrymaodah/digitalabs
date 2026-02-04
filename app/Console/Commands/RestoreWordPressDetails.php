<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\CouponUsage;

class RestoreWordPressDetails extends Command
{
    protected $signature = 'restore:wp-details {--dry-run : Preview without making changes}';
    protected $description = 'Restore detailed data from WordPress: lesson durations, order pricing, coupon usage';

    private $dryRun = false;

    public function handle()
    {
        $this->dryRun = $this->option('dry-run');

        if ($this->dryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be modified');
        }

        $this->info('🚀 Starting WordPress Details Restoration');
        $this->newLine();

        // 1. Restore lesson durations
        $this->restoreLessonDurations();

        // 2. Restore coupon details
        $this->restoreCouponDetails();

        // 3. Restore order details (subtotal, discount, payment fee, original price)
        $this->restoreOrderDetails();

        // 4. Restore coupon usage
        $this->restoreCouponUsage();

        $this->newLine();
        $this->info('✅ Restoration Complete!');
    }

    private function restoreLessonDurations()
    {
        $this->info('📖 Restoring lesson durations...');

        // Get all WordPress lessons with video meta
        $wpLessons = DB::connection('wordpress')
            ->table('wp_posts as p')
            ->join('wp_postmeta as pm', function ($join) {
                $join->on('p.ID', '=', 'pm.post_id')
                    ->where('pm.meta_key', '=', '_video');
            })
            ->where('p.post_type', 'lesson')
            ->where('p.post_status', 'publish')
            ->select('p.ID', 'p.post_title', 'pm.meta_value')
            ->get();

        $updated = 0;
        foreach ($wpLessons as $wpLesson) {
            $videoData = @unserialize($wpLesson->meta_value);
            if (!$videoData) continue;

            $durationSec = $videoData['duration_sec'] ?? 0;

            // Find matching lesson by title
            $lesson = Lesson::where('title', $wpLesson->post_title)->first();
            if ($lesson && $durationSec > 0) {
                if (!$this->dryRun) {
                    $lesson->update(['duration' => $durationSec]);
                }
                $updated++;
                $this->line("   ✓ {$lesson->title}: {$durationSec}s ({$this->formatDuration($durationSec)})");
            }
        }

        $this->info("   ✅ Lesson durations updated: {$updated}");
    }

    private function restoreCouponDetails()
    {
        $this->info('🎟️  Restoring coupon details...');

        $wpCoupons = DB::connection('wordpress')
            ->table('wp_sejolisa_coupons')
            ->get();

        $updated = 0;
        foreach ($wpCoupons as $wpCoupon) {
            $discountData = @unserialize($wpCoupon->discount);
            if (!$discountData) continue;

            $coupon = Coupon::where('code', $wpCoupon->code)->first();
            if ($coupon) {
                $discountValue = $discountData['value'] ?? 0;
                $discountType = $discountData['type'] ?? 'fixed';

                if (!$this->dryRun) {
                    $coupon->update([
                        'value' => $discountValue,
                        'type' => $discountType === 'percentage' ? 'percentage' : 'fixed',
                        'used_count' => $wpCoupon->usage ?? 0,
                        'usage_limit' => $wpCoupon->limit_use > 0 ? $wpCoupon->limit_use : null,
                        'is_active' => $wpCoupon->status !== 'inactive',
                    ]);
                }
                $updated++;
                $this->line("   ✓ {$coupon->code}: {$discountValue}" . ($discountType === 'percentage' ? '%' : ' fixed') . " (used: {$wpCoupon->usage}x)");
            }
        }

        $this->info("   ✅ Coupons updated: {$updated}");
    }

    private function restoreOrderDetails()
    {
        $this->info('🛒 Restoring order details (pricing, discounts, fees)...');

        // Get all WordPress orders with meta_data
        $wpOrders = DB::connection('wordpress')
            ->table('wp_sejolisa_orders')
            ->where('status', 'completed')
            ->get();

        $ordersUpdated = 0;
        $itemsUpdated = 0;

        foreach ($wpOrders as $wpOrder) {
            $meta = @unserialize($wpOrder->meta_data);
            
            // Get coupon info from meta
            $couponCode = $meta['coupon']['coupon'] ?? null;
            $discount = (float) ($meta['coupon']['discount'] ?? 0);
            $duitkuFee = (float) ($meta['duitku']['duitku_fee'] ?? 0);

            // Grand total from WordPress is the FINAL amount paid
            $grandTotal = (float) $wpOrder->grand_total;
            
            // Calculate original price (subtotal = grand_total + discount)
            $subtotal = $grandTotal + $discount;

            // Find matching Laravel order
            $order = Order::where('order_number', 'WP-' . str_pad($wpOrder->ID, 6, '0', STR_PAD_LEFT))->first();
            
            if ($order) {
                if (!$this->dryRun) {
                    $order->update([
                        'subtotal' => $subtotal,
                        'discount' => $discount,
                        'payment_fee' => $duitkuFee,
                        'total' => $grandTotal,
                    ]);

                    // Update order item with correct original price (same as subtotal)
                    $orderItem = OrderItem::where('order_id', $order->id)->first();
                    if ($orderItem) {
                        $orderItem->update([
                            'price' => $subtotal,
                            'original_price' => $subtotal,
                        ]);
                        $itemsUpdated++;
                    }

                    // Link coupon if used
                    if ($couponCode) {
                        $coupon = Coupon::where('code', $couponCode)->first();
                        if ($coupon && !$order->coupon_id) {
                            $order->update(['coupon_id' => $coupon->id]);
                        }
                    }
                }
                $ordersUpdated++;
            }
        }

        $this->info("   ✅ Orders updated: {$ordersUpdated}");
        $this->info("   ✅ Order items updated: {$itemsUpdated}");
    }

    private function restoreCouponUsage()
    {
        $this->info('📊 Restoring coupon usage records...');

        // Get all orders with coupon from WordPress
        $wpOrders = DB::connection('wordpress')
            ->table('wp_sejolisa_orders')
            ->whereNotNull('coupon_id')
            ->where('coupon_id', '>', 0)
            ->where('status', 'completed')
            ->get();

        $created = 0;
        foreach ($wpOrders as $wpOrder) {
            $meta = @unserialize($wpOrder->meta_data);
            $couponCode = $meta['coupon']['coupon'] ?? null;
            $discount = $meta['coupon']['discount'] ?? 0;

            if (!$couponCode) continue;

            // Find Laravel order and coupon
            $order = Order::where('order_number', 'WP-' . str_pad($wpOrder->ID, 6, '0', STR_PAD_LEFT))->first();
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($order && $coupon) {
                // Check if usage record already exists
                $exists = CouponUsage::where('coupon_id', $coupon->id)
                    ->where('order_id', $order->id)
                    ->exists();

                if (!$exists && !$this->dryRun) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $order->user_id,
                        'order_id' => $order->id,
                        'discount_amount' => $discount,
                        'used_at' => $order->created_at,
                    ]);
                    $created++;
                }
            }
        }

        $this->info("   ✅ Coupon usage records created: {$created}");
    }

    private function formatDuration($seconds)
    {
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%d:%02d', $minutes, $secs);
    }
}
