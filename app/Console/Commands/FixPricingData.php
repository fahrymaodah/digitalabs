<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Course;

class FixPricingData extends Command
{
    protected $signature = 'fix:pricing {--dry-run : Show what would be updated without making changes}';

    protected $description = 'Fix pricing data (subtotal, discount, payment_fee) based on analysis';

    // Constants
    const COURSE_PRICE = 489000;
    const DUITKU_FEE_PERCENT = 0.0129; // ~1.29%
    const BANK_TRANSFER_FEE = 0; // Only unique code

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔧 Fixing Pricing Data...');
        if ($dryRun) {
            $this->warn('   Running in DRY-RUN mode - no changes will be made');
        }
        $this->newLine();

        // Get course price
        $course = Course::first();
        $coursePrice = $course->price ?? self::COURSE_PRICE;
        $this->info("Course Price: Rp " . number_format($coursePrice));

        // Get coupons
        $coupons = Coupon::all()->keyBy('id');
        foreach ($coupons as $coupon) {
            $this->line("Coupon {$coupon->code}: {$coupon->value}% off");
        }
        $this->newLine();

        // Process orders
        $orders = Order::with(['items', 'coupon'])->get();
        $updated = 0;
        $usagesCreated = 0;

        $this->info('Processing ' . $orders->count() . ' orders...');
        $this->newLine();

        foreach ($orders as $order) {
            $changes = $this->calculateOrderPricing($order, $coursePrice);
            
            if (!$dryRun) {
                // Update order
                $order->update([
                    'subtotal' => $changes['subtotal'],
                    'discount' => $changes['discount'],
                    'payment_fee' => $changes['payment_fee'],
                ]);

                // Update order items
                foreach ($order->items as $item) {
                    $item->update([
                        'original_price' => $coursePrice,
                    ]);
                }

                // Update/create coupon usage
                if ($order->coupon_id) {
                    $usage = CouponUsage::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'coupon_id' => $order->coupon_id,
                            'user_id' => $order->user_id,
                            'discount_amount' => $changes['discount'],
                        ]
                    );
                    if ($usage->wasRecentlyCreated) {
                        $usagesCreated++;
                    }
                }
                $updated++;
            } else {
                // Show sample
                if ($updated < 5 || ($order->coupon_id && $updated < 10)) {
                    $this->line("Order #{$order->id}:");
                    $this->line("   Payment: {$order->payment_method}");
                    $this->line("   Coupon: " . ($order->coupon ? $order->coupon->code : 'None'));
                    $this->line("   Current: subtotal=" . number_format($order->subtotal) . 
                                ", discount=" . number_format($order->discount) . 
                                ", total=" . number_format($order->total));
                    $this->line("   New:     subtotal=" . number_format($changes['subtotal']) . 
                                ", discount=" . number_format($changes['discount']) . 
                                ", fee=" . number_format($changes['payment_fee']) .
                                ", calc_total=" . number_format($changes['subtotal'] - $changes['discount'] + $changes['payment_fee']));
                    $this->newLine();
                }
                $updated++;
            }
        }

        // Update coupon used_count
        if (!$dryRun) {
            foreach ($coupons as $coupon) {
                $count = CouponUsage::where('coupon_id', $coupon->id)->count();
                $coupon->update(['used_count' => $count]);
                $this->line("Updated {$coupon->code} used_count to {$count}");
            }
        }

        $this->newLine();
        if ($dryRun) {
            $this->info("Would update {$updated} orders");
        } else {
            $this->info("✅ Updated {$updated} orders");
            $this->info("✅ Created {$usagesCreated} new coupon usages");
        }

        // Show summary
        $this->newLine();
        $this->showSummary($dryRun);

        return 0;
    }

    protected function calculateOrderPricing(Order $order, float $coursePrice): array
    {
        $total = $order->total;
        $paymentMethod = $order->payment_method;

        // Calculate payment fee (unique code + gateway fee)
        // Pattern from analysis:
        // - duitku: avg fee ~6,319 on 489K = 1.29%
        // - bank_transfer: avg fee ~56 (just unique code)
        
        // For orders WITHOUT coupon, we can calculate exact fee
        if (!$order->coupon_id) {
            $paymentFee = $total - $coursePrice;
            $discount = 0;
            $subtotal = $coursePrice;
        } else {
            // For orders WITH coupon:
            // We know: total = subtotal - discount + payment_fee
            // We know: subtotal = coursePrice (always)
            // We need to figure out discount and payment_fee
            
            // Based on analysis:
            // - ADOBESROCK orders pay ~279,800 (avg)
            // - This means actual discount is ~209,200 (42.8% off, not 80%)
            // - DIGITALABS orders pay ~489,650 (essentially no discount!)
            
            $subtotal = $coursePrice;
            
            // Estimate payment fee based on method
            if ($paymentMethod === 'duitku') {
                // For coupon orders with duitku, fee is applied on final price
                // Observed: duitku coupon orders are all exactly 279,800
                // So payment fee must be included in that
                // If base discounted = 279,800 and fee = 1.29%, then:
                // 279,800 = base * (1 + 0.0129) => base = 276,234
                // But that doesn't match pattern...
                
                // Simpler: assume unique code only (~800)
                $paymentFee = $total - floor($total / 1000) * 1000; // Extract unique code
                if ($paymentFee == 0) $paymentFee = 800; // default
            } else {
                // bank_transfer: unique code only
                $paymentFee = $total - floor($total / 1000) * 1000;
                if ($paymentFee < 100) $paymentFee = $total - 279000; // for coupon orders
            }
            
            // Calculate actual discount from known values
            // total = subtotal - discount + payment_fee
            // discount = subtotal + payment_fee - total
            $discount = $subtotal + $paymentFee - $total;
            
            // Sanity check: discount shouldn't be negative
            if ($discount < 0) {
                $paymentFee = $total - $subtotal; // Treat as no discount, all fee
                $discount = 0;
            }
        }

        return [
            'subtotal' => $subtotal,
            'discount' => max(0, $discount),
            'payment_fee' => max(0, $paymentFee),
        ];
    }

    protected function showSummary(bool $dryRun): void
    {
        $prefix = $dryRun ? '[DRY-RUN] ' : '';
        
        $this->info($prefix . 'Summary by Payment Method:');
        
        $summary = Order::selectRaw('
            payment_method,
            coupon_id,
            COUNT(*) as count,
            AVG(subtotal) as avg_subtotal,
            AVG(discount) as avg_discount,
            AVG(total) as avg_total
        ')
        ->groupBy('payment_method', 'coupon_id')
        ->get();

        $this->table(
            ['Payment', 'Coupon ID', 'Count', 'Avg Subtotal', 'Avg Discount', 'Avg Total'],
            $summary->map(fn($row) => [
                $row->payment_method,
                $row->coupon_id ?? 'NULL',
                $row->count,
                'Rp ' . number_format($row->avg_subtotal),
                'Rp ' . number_format($row->avg_discount),
                'Rp ' . number_format($row->avg_total),
            ])
        );
    }
}
