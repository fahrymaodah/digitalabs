<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\CouponUsage;

class SyncCouponData extends Command
{
    protected $signature = 'sync:coupons {--dry-run : Show what would be synced without actually syncing}';

    protected $description = 'Sync coupon usage data from WordPress orders to Laravel';

    protected array $couponIdMap = [];
    protected array $stats = [
        'orders_updated' => 0,
        'usages_created' => 0,
        'discounts_updated' => 0,
    ];

    public function handle(): int
    {
        $this->info('🔄 Syncing Coupon Data from WordPress');
        $this->newLine();

        // Check WordPress database connection
        try {
            DB::connection('wordpress')->getPdo();
            $this->info('✅ WordPress database connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Cannot connect to WordPress database: ' . $e->getMessage());
            return 1;
        }

        $isDryRun = $this->option('dry-run');
        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be modified');
        }
        $this->newLine();

        // Build coupon ID map (WordPress ID -> Laravel ID)
        $this->buildCouponIdMap();

        // Show current status
        $this->showCurrentStatus();
        $this->newLine();

        if (!$isDryRun) {
            DB::transaction(function () {
                $this->syncOrderCouponIds();
                $this->calculateDiscounts();
                $this->createCouponUsages();
                $this->updateCouponUsedCounts();
            });
        } else {
            $this->analyzeChanges();
        }

        $this->showSummary();

        return 0;
    }

    protected function buildCouponIdMap(): void
    {
        $this->info('📋 Building coupon ID map...');
        
        $wpCoupons = DB::connection('wordpress')
            ->table('wp_sejolisa_coupons')
            ->get();

        foreach ($wpCoupons as $wpCoupon) {
            $laravelCoupon = Coupon::where('code', $wpCoupon->code)->first();
            if ($laravelCoupon) {
                $this->couponIdMap[$wpCoupon->ID] = $laravelCoupon->id;
                $this->info("   Mapped WP ID {$wpCoupon->ID} ({$wpCoupon->code}) -> Laravel ID {$laravelCoupon->id}");
            }
        }
        $this->newLine();
    }

    protected function showCurrentStatus(): void
    {
        $this->info('📊 Current Status:');
        
        $coupons = Coupon::all();
        foreach ($coupons as $coupon) {
            $ordersWithCoupon = Order::where('coupon_id', $coupon->id)->count();
            $usageRecords = CouponUsage::where('coupon_id', $coupon->id)->count();
            
            $this->line("   {$coupon->code}:");
            $this->line("     - Stored used_count: {$coupon->used_count}");
            $this->line("     - Orders with coupon_id: {$ordersWithCoupon}");
            $this->line("     - Usage records: {$usageRecords}");
        }

        // Count orders with discount but no coupon
        $ordersWithoutCoupon = Order::whereNull('coupon_id')
            ->where('discount', '>', 0)
            ->count();
        $this->line("   Orders with discount but no coupon: {$ordersWithoutCoupon}");
    }

    protected function syncOrderCouponIds(): void
    {
        $this->info('🔗 Syncing order coupon IDs...');

        $wpOrders = DB::connection('wordpress')
            ->table('wp_sejolisa_orders')
            ->where('status', 'completed')
            ->where('coupon_id', '>', 0)
            ->select('ID', 'coupon_id', 'grand_total')
            ->get();

        $bar = $this->output->createProgressBar($wpOrders->count());
        $bar->start();

        foreach ($wpOrders as $wpOrder) {
            $orderNumber = 'WP-' . str_pad($wpOrder->ID, 6, '0', STR_PAD_LEFT);
            $laravelCouponId = $this->couponIdMap[$wpOrder->coupon_id] ?? null;

            if ($laravelCouponId) {
                $updated = Order::where('order_number', $orderNumber)
                    ->whereNull('coupon_id')
                    ->update(['coupon_id' => $laravelCouponId]);

                if ($updated) {
                    $this->stats['orders_updated']++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ Orders updated with coupon_id: {$this->stats['orders_updated']}");
    }

    protected function calculateDiscounts(): void
    {
        $this->info('💰 Calculating discounts...');

        // Get course price as the base price
        $course = \App\Models\Course::first();
        $coursePrice = $course ? $course->price : 489000;

        // Get all orders with coupons
        $ordersWithCoupons = Order::whereNotNull('coupon_id')
            ->with('coupon')
            ->get();

        foreach ($ordersWithCoupons as $order) {
            if (!$order->coupon) continue;

            $totalPaid = $order->total;
            
            // Subtotal is the course price (original price before any discount)
            // Discount is the difference between course price and what was paid
            // But only if the total is less than course price
            
            if ($totalPaid < $coursePrice) {
                $discountAmount = $coursePrice - $totalPaid;
                
                // Verify this makes sense with coupon percentage
                $couponValue = $order->coupon->value;
                $expectedDiscountedPrice = $coursePrice * (1 - $couponValue / 100);
                
                // If the total is close to expected discounted price (within 1000), use course price as subtotal
                if (abs($totalPaid - $expectedDiscountedPrice) < 1000) {
                    $order->update([
                        'subtotal' => $coursePrice,
                        'discount' => $discountAmount,
                    ]);
                    $this->stats['discounts_updated']++;
                } else {
                    // Total doesn't match coupon expectation, might be a different base price
                    // Just set subtotal = total and discount = 0 (we don't know the original price)
                    $order->update([
                        'subtotal' => $totalPaid,
                        'discount' => 0,
                    ]);
                }
            } else {
                // Total >= course price, no discount
                $order->update([
                    'subtotal' => $totalPaid,
                    'discount' => 0,
                ]);
            }
        }

        $this->info("   ✅ Discounts calculated: {$this->stats['discounts_updated']}");
    }

    protected function createCouponUsages(): void
    {
        $this->info('📝 Creating coupon usage records...');

        // Delete existing usages first (without truncate to avoid transaction issues)
        CouponUsage::query()->delete();

        $ordersWithCoupons = Order::whereNotNull('coupon_id')
            ->with('coupon')
            ->get();

        foreach ($ordersWithCoupons as $order) {
            CouponUsage::create([
                'coupon_id' => $order->coupon_id,
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'discount_amount' => $order->discount,
            ]);
            $this->stats['usages_created']++;
        }

        $this->info("   ✅ Usage records created: {$this->stats['usages_created']}");
    }

    protected function updateCouponUsedCounts(): void
    {
        $this->info('🔢 Updating coupon used_count...');

        $coupons = Coupon::all();
        foreach ($coupons as $coupon) {
            $actualUsage = CouponUsage::where('coupon_id', $coupon->id)->count();
            $coupon->update(['used_count' => $actualUsage]);
            $this->info("   {$coupon->code}: used_count = {$actualUsage}");
        }
    }

    protected function analyzeChanges(): void
    {
        $this->info('📊 Analyzing changes that would be made:');

        $wpOrders = DB::connection('wordpress')
            ->table('wp_sejolisa_orders')
            ->where('status', 'completed')
            ->where('coupon_id', '>', 0)
            ->select('ID', 'coupon_id')
            ->get();

        $wouldUpdate = 0;
        foreach ($wpOrders as $wpOrder) {
            $orderNumber = 'WP-' . str_pad($wpOrder->ID, 6, '0', STR_PAD_LEFT);
            $laravelCouponId = $this->couponIdMap[$wpOrder->coupon_id] ?? null;

            if ($laravelCouponId) {
                $exists = Order::where('order_number', $orderNumber)
                    ->whereNull('coupon_id')
                    ->exists();
                if ($exists) {
                    $wouldUpdate++;
                }
            }
        }

        $this->line("   - Orders that would get coupon_id: {$wouldUpdate}");
        $this->line("   - Coupon usage records that would be created: {$wouldUpdate}");
        $this->line("   - Discounts that would be calculated: {$wouldUpdate}");
    }

    protected function showSummary(): void
    {
        $this->newLine();
        $this->info('📈 Sync Summary:');
        
        $table = [];
        foreach ($this->stats as $key => $value) {
            $table[] = [ucwords(str_replace('_', ' ', $key)), $value];
        }
        
        $this->table(['Metric', 'Count'], $table);

        // Show final status
        $this->newLine();
        $this->info('📊 Final Status:');
        
        $coupons = Coupon::all();
        foreach ($coupons as $coupon) {
            $ordersWithCoupon = Order::where('coupon_id', $coupon->id)->count();
            $usageRecords = CouponUsage::where('coupon_id', $coupon->id)->count();
            
            $this->line("   {$coupon->code}:");
            $this->line("     - used_count: {$coupon->used_count}");
            $this->line("     - Orders: {$ordersWithCoupon}");
            $this->line("     - Usages: {$usageRecords}");
        }
    }
}
