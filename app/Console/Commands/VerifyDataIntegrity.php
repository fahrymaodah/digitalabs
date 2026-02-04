<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserCourse;
use App\Models\LessonProgress;
use App\Models\Course;
use App\Models\User;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;

class VerifyDataIntegrity extends Command
{
    protected $signature = 'verify:data {--fix : Attempt to fix issues}';

    protected $description = 'Verify data integrity and relationships across all tables';

    protected array $issues = [];
    protected array $stats = [];

    public function handle(): int
    {
        $this->info('🔍 Verifying Data Integrity...');
        $this->newLine();

        $shouldFix = $this->option('fix');

        $this->checkOrphanedOrderItems();
        $this->checkOrphanedUserCourses();
        $this->checkOrphanedLessonProgress();
        $this->checkOrphanedCouponUsages();
        $this->checkOrphanedCommissions();
        $this->checkOrdersWithoutItems();
        $this->checkEnrollmentsWithoutOrders();
        $this->checkCouponCountSync();
        $this->checkUserCoursesFromOrders();
        $this->checkLessonProgressWithoutEnrollment();

        $this->showSummary();

        if (!empty($this->issues) && $shouldFix) {
            $this->newLine();
            $this->info('🔧 Attempting to fix issues...');
            $this->fixIssues();
        }

        return 0;
    }

    protected function checkOrphanedOrderItems(): void
    {
        $this->info('📋 Checking order_items...');
        
        // Order items without valid order
        $orphaned = OrderItem::whereNotIn('order_id', Order::pluck('id'))->count();
        if ($orphaned > 0) {
            $this->issues['orphaned_order_items'] = $orphaned;
            $this->warn("   ⚠️  Found {$orphaned} order items without valid order");
        } else {
            $this->line('   ✅ All order items have valid orders');
        }

        // Order items without valid course
        $noCourse = OrderItem::whereNotIn('course_id', Course::pluck('id'))->count();
        if ($noCourse > 0) {
            $this->issues['order_items_no_course'] = $noCourse;
            $this->warn("   ⚠️  Found {$noCourse} order items without valid course");
        } else {
            $this->line('   ✅ All order items have valid courses');
        }
    }

    protected function checkOrphanedUserCourses(): void
    {
        $this->info('📋 Checking user_courses (enrollments)...');
        
        $orphanedUsers = UserCourse::whereNotIn('user_id', User::pluck('id'))->count();
        if ($orphanedUsers > 0) {
            $this->issues['enrollments_no_user'] = $orphanedUsers;
            $this->warn("   ⚠️  Found {$orphanedUsers} enrollments without valid user");
        } else {
            $this->line('   ✅ All enrollments have valid users');
        }

        $orphanedCourses = UserCourse::whereNotIn('course_id', Course::pluck('id'))->count();
        if ($orphanedCourses > 0) {
            $this->issues['enrollments_no_course'] = $orphanedCourses;
            $this->warn("   ⚠️  Found {$orphanedCourses} enrollments without valid course");
        } else {
            $this->line('   ✅ All enrollments have valid courses');
        }
    }

    protected function checkOrphanedLessonProgress(): void
    {
        $this->info('📋 Checking lesson_progress...');
        
        $orphanedUsers = LessonProgress::whereNotIn('user_id', User::pluck('id'))->count();
        if ($orphanedUsers > 0) {
            $this->issues['progress_no_user'] = $orphanedUsers;
            $this->warn("   ⚠️  Found {$orphanedUsers} progress records without valid user");
        } else {
            $this->line('   ✅ All progress records have valid users');
        }

        $orphanedLessons = LessonProgress::whereNotIn('lesson_id', 
            \App\Models\Lesson::pluck('id')
        )->count();
        if ($orphanedLessons > 0) {
            $this->issues['progress_no_lesson'] = $orphanedLessons;
            $this->warn("   ⚠️  Found {$orphanedLessons} progress records without valid lesson");
        } else {
            $this->line('   ✅ All progress records have valid lessons');
        }
    }

    protected function checkOrphanedCouponUsages(): void
    {
        $this->info('📋 Checking coupon_usages...');
        
        $orphanedCoupons = CouponUsage::whereNotIn('coupon_id', Coupon::pluck('id'))->count();
        if ($orphanedCoupons > 0) {
            $this->issues['usage_no_coupon'] = $orphanedCoupons;
            $this->warn("   ⚠️  Found {$orphanedCoupons} usages without valid coupon");
        }

        $orphanedOrders = CouponUsage::whereNotIn('order_id', Order::pluck('id'))->count();
        if ($orphanedOrders > 0) {
            $this->issues['usage_no_order'] = $orphanedOrders;
            $this->warn("   ⚠️  Found {$orphanedOrders} usages without valid order");
        }

        if (empty($this->issues['usage_no_coupon']) && empty($this->issues['usage_no_order'])) {
            $this->line('   ✅ All coupon usages are valid');
        }
    }

    protected function checkOrphanedCommissions(): void
    {
        $this->info('📋 Checking affiliate_commissions...');
        
        $orphanedAff = AffiliateCommission::whereNotIn('affiliate_id', Affiliate::pluck('id'))->count();
        if ($orphanedAff > 0) {
            $this->issues['commission_no_affiliate'] = $orphanedAff;
            $this->warn("   ⚠️  Found {$orphanedAff} commissions without valid affiliate");
        } else {
            $this->line('   ✅ All commissions have valid affiliates');
        }
    }

    protected function checkOrdersWithoutItems(): void
    {
        $this->info('📋 Checking orders without items...');
        
        $ordersWithoutItems = Order::whereNotIn('id', OrderItem::pluck('order_id'))->count();
        if ($ordersWithoutItems > 0) {
            $this->issues['orders_no_items'] = $ordersWithoutItems;
            $this->warn("   ⚠️  Found {$ordersWithoutItems} orders without any items");
        } else {
            $this->line('   ✅ All orders have at least one item');
        }
    }

    protected function checkEnrollmentsWithoutOrders(): void
    {
        $this->info('📋 Checking enrollments without paid orders...');
        
        // Get user_ids with paid orders
        $usersWithOrders = Order::where('status', 'paid')->pluck('user_id')->unique();
        
        // Find enrollments where user doesn't have a paid order
        $enrollmentsNoOrder = UserCourse::whereNotIn('user_id', $usersWithOrders)->count();
        
        if ($enrollmentsNoOrder > 0) {
            $this->stats['enrollments_without_order'] = $enrollmentsNoOrder;
            $this->line("   ℹ️  {$enrollmentsNoOrder} enrollments without direct order (could be admin granted)");
        } else {
            $this->line('   ✅ All enrollments have corresponding paid orders');
        }
    }

    protected function checkCouponCountSync(): void
    {
        $this->info('📋 Checking coupon usage counts...');
        
        $coupons = Coupon::all();
        $outOfSync = 0;
        
        foreach ($coupons as $coupon) {
            $actualUsage = CouponUsage::where('coupon_id', $coupon->id)->count();
            $ordersWithCoupon = Order::where('coupon_id', $coupon->id)->count();
            
            if ($coupon->used_count != $actualUsage) {
                $this->warn("   ⚠️  {$coupon->code}: used_count={$coupon->used_count} but actual usages={$actualUsage}");
                $outOfSync++;
            }
            
            if ($actualUsage != $ordersWithCoupon) {
                $this->warn("   ⚠️  {$coupon->code}: usages={$actualUsage} but orders={$ordersWithCoupon}");
                $outOfSync++;
            }
        }
        
        if ($outOfSync == 0) {
            $this->line('   ✅ All coupon counts are in sync');
        } else {
            $this->issues['coupon_count_mismatch'] = $outOfSync;
        }
    }

    protected function checkUserCoursesFromOrders(): void
    {
        $this->info('📋 Checking if all paid orders have enrollments...');
        
        $paidOrderUserCourses = Order::where('status', 'paid')
            ->with('items')
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) use ($order) {
                    return ['user_id' => $order->user_id, 'course_id' => $item->course_id];
                });
            });
        
        $missingEnrollments = 0;
        foreach ($paidOrderUserCourses as $uc) {
            $exists = UserCourse::where('user_id', $uc['user_id'])
                ->where('course_id', $uc['course_id'])
                ->exists();
            if (!$exists) {
                $missingEnrollments++;
            }
        }
        
        if ($missingEnrollments > 0) {
            $this->issues['missing_enrollments'] = $missingEnrollments;
            $this->warn("   ⚠️  {$missingEnrollments} paid orders don't have corresponding enrollments");
        } else {
            $this->line('   ✅ All paid orders have corresponding enrollments');
        }
    }

    protected function checkLessonProgressWithoutEnrollment(): void
    {
        $this->info('📋 Checking lesson progress without enrollment...');
        
        $progressUserCourses = LessonProgress::with('lesson.topic')
            ->get()
            ->map(function ($progress) {
                return [
                    'user_id' => $progress->user_id,
                    'course_id' => $progress->lesson->topic->course_id ?? null,
                ];
            })
            ->filter(fn($item) => $item['course_id'] !== null)
            ->unique(fn($item) => $item['user_id'] . '-' . $item['course_id']);
        
        $noEnrollment = 0;
        foreach ($progressUserCourses as $uc) {
            $enrolled = UserCourse::where('user_id', $uc['user_id'])
                ->where('course_id', $uc['course_id'])
                ->exists();
            if (!$enrolled) {
                $noEnrollment++;
            }
        }
        
        if ($noEnrollment > 0) {
            $this->stats['progress_without_enrollment'] = $noEnrollment;
            $this->line("   ℹ️  {$noEnrollment} user-course combos have progress but no enrollment");
        } else {
            $this->line('   ✅ All lesson progress has corresponding enrollment');
        }
    }

    protected function showSummary(): void
    {
        $this->newLine();
        $this->info('📊 Summary:');
        
        if (empty($this->issues)) {
            $this->info('   ✅ No critical issues found!');
        } else {
            $this->warn('   ⚠️  Found ' . count($this->issues) . ' issue(s):');
            foreach ($this->issues as $key => $count) {
                $this->line("      - {$key}: {$count}");
            }
        }
        
        if (!empty($this->stats)) {
            $this->newLine();
            $this->line('   ℹ️  Additional notes:');
            foreach ($this->stats as $key => $count) {
                $this->line("      - {$key}: {$count}");
            }
        }
        
        // Show data counts
        $this->newLine();
        $this->info('📈 Data Counts:');
        $this->table(
            ['Entity', 'Count'],
            [
                ['Users', User::count()],
                ['Courses', Course::count()],
                ['Topics', \App\Models\Topic::count()],
                ['Lessons', \App\Models\Lesson::count()],
                ['Orders', Order::count()],
                ['Order Items', OrderItem::count()],
                ['Enrollments', UserCourse::count()],
                ['Lesson Progress', LessonProgress::count()],
                ['Coupons', Coupon::count()],
                ['Coupon Usages', CouponUsage::count()],
                ['Affiliates', Affiliate::count()],
                ['Commissions', AffiliateCommission::count()],
            ]
        );
    }

    protected function fixIssues(): void
    {
        // Fix coupon count mismatch
        if (isset($this->issues['coupon_count_mismatch'])) {
            $this->info('   Fixing coupon counts...');
            $coupons = Coupon::all();
            foreach ($coupons as $coupon) {
                $actualUsage = CouponUsage::where('coupon_id', $coupon->id)->count();
                $coupon->update(['used_count' => $actualUsage]);
            }
            $this->info('   ✅ Coupon counts fixed');
        }

        // Create missing coupon usages
        $ordersWithCouponNoUsage = Order::whereNotNull('coupon_id')
            ->whereNotIn('id', CouponUsage::pluck('order_id'))
            ->get();
        
        if ($ordersWithCouponNoUsage->count() > 0) {
            $this->info('   Creating missing coupon usages...');
            foreach ($ordersWithCouponNoUsage as $order) {
                CouponUsage::create([
                    'coupon_id' => $order->coupon_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount,
                ]);
            }
            $this->info('   ✅ Created ' . $ordersWithCouponNoUsage->count() . ' coupon usages');
        }

        $this->newLine();
        $this->info('🔧 Fix complete! Run verify:data again to confirm.');
    }
}
