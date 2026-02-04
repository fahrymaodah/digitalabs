<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateWordPressData extends Command
{
    protected $signature = 'migrate:wordpress 
                            {--dry-run : Show what would be migrated without actually migrating}
                            {--fresh : Clear existing data before migration}';

    protected $description = 'Migrate data from WordPress (Sejoli + Tutor LMS) to Laravel';

    protected array $stats = [
        'users' => 0,
        'affiliates_table' => 0,
        'courses' => 0,
        'topics' => 0,
        'lessons' => 0,
        'orders' => 0,
        'order_items' => 0,
        'affiliate_commissions' => 0,
        'enrollments' => 0,
        'lesson_progress' => 0,
        'coupons' => 0,
    ];

    protected array $userIdMap = [];
    protected array $orderIdMap = [];
    protected array $affiliateIdMap = []; // Maps WP affiliate user_id to affiliates table id

    public function handle(): int
    {
        $this->info('🚀 Starting WordPress to Laravel Migration');
        $this->newLine();

        // Check WordPress database connection
        try {
            DB::connection('wordpress')->getPdo();
            $this->info('✅ WordPress database connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Cannot connect to WordPress database: ' . $e->getMessage());
            $this->info('Make sure wordpress_backup database exists in MySQL');
            return 1;
        }

        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN MODE - No data will be modified');
            $this->newLine();
        }

        if ($this->option('fresh') && !$this->option('dry-run')) {
            if ($this->confirm('⚠️  This will delete ALL existing data. Continue?')) {
                $this->clearExistingData();
            } else {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        $this->showWordPressStats();
        $this->newLine();

        if (!$this->option('dry-run')) {
            DB::transaction(function () {
                $this->migrateUsers();
                $this->migrateAffiliates(); // Migrate affiliates before orders
                $this->migrateCourse();
                $this->migrateTopics();
                $this->migrateLessons();
                $this->migrateCoupons();
                $this->migrateOrders();
                $this->migrateAffiliateCommissions();
                $this->migrateEnrollments();
                $this->migrateLessonProgress();
            });
        }

        $this->showMigrationSummary();

        return 0;
    }

    protected function showWordPressStats(): void
    {
        $this->info('📊 WordPress Data Summary:');
        
        $stats = [
            'Total Users' => DB::connection('wordpress')->table('wp_users')->count(),
            'Paying Customers' => DB::connection('wordpress')
                ->table('wp_users as u')
                ->join('wp_sejolisa_orders as o', function ($join) {
                    $join->on('u.ID', '=', 'o.user_id')
                        ->where('o.status', '=', 'completed');
                })
                ->distinct()
                ->count('u.ID'),
            'Courses' => DB::connection('wordpress')
                ->table('wp_posts')
                ->where('post_type', 'courses')
                ->where('post_status', 'publish')
                ->count(),
            'Sections (Topics)' => DB::connection('wordpress')
                ->table('wp_posts')
                ->where('post_type', 'topics')
                ->count(),
            'Lessons' => DB::connection('wordpress')
                ->table('wp_posts')
                ->where('post_type', 'lesson')
                ->count(),
            'Completed Orders' => DB::connection('wordpress')
                ->table('wp_sejolisa_orders')
                ->where('status', 'completed')
                ->count(),
            'Total Revenue' => 'Rp ' . number_format(
                DB::connection('wordpress')
                    ->table('wp_sejolisa_orders')
                    ->where('status', 'completed')
                    ->sum('grand_total'),
                0, ',', '.'
            ),
            'Affiliate Commissions' => DB::connection('wordpress')
                ->table('wp_sejolisa_affiliates')
                ->count(),
        ];

        foreach ($stats as $label => $value) {
            $this->line("   {$label}: <comment>{$value}</comment>");
        }
    }

    protected function clearExistingData(): void
    {
        $this->warn('🗑️  Clearing existing data...');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('affiliate_commissions')->truncate();
        DB::table('affiliates')->truncate();
        DB::table('lesson_progress')->truncate();
        DB::table('user_courses')->truncate();
        DB::table('coupon_usages')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('coupons')->truncate();
        DB::table('lessons')->truncate();
        DB::table('topics')->truncate();
        DB::table('courses')->truncate();
        DB::table('users')->where('id', '>', 1)->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->info('✅ Existing data cleared');
    }

    protected function migrateUsers(): void
    {
        $this->info('👥 Migrating users...');
        
        // Get paying customers
        $wpUsers = DB::connection('wordpress')
            ->table('wp_users as u')
            ->join('wp_sejolisa_orders as o', function ($join) {
                $join->on('u.ID', '=', 'o.user_id')
                    ->where('o.status', '=', 'completed');
            })
            ->select('u.ID', 'u.user_login', 'u.user_email', 'u.display_name', 'u.user_registered')
            ->distinct()
            ->get();

        $bar = $this->output->createProgressBar($wpUsers->count());
        $bar->start();

        foreach ($wpUsers as $wpUser) {
            $exists = DB::table('users')->where('email', $wpUser->user_email)->exists();
            
            if (!$exists) {
                $newId = DB::table('users')->insertGetId([
                    'name' => $wpUser->display_name ?: $wpUser->user_login,
                    'email' => $wpUser->user_email,
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => $wpUser->user_registered,
                    'created_at' => $wpUser->user_registered,
                    'updated_at' => now(),
                ]);
                
                $this->userIdMap[$wpUser->ID] = $newId;
                $this->stats['users']++;
            } else {
                $existingUser = DB::table('users')->where('email', $wpUser->user_email)->first();
                $this->userIdMap[$wpUser->ID] = $existingUser->id;
            }
            
            $bar->advance();
        }

        // Also migrate affiliates
        $affiliateIds = DB::connection('wordpress')
            ->table('wp_sejolisa_affiliates')
            ->select('affiliate_id')
            ->distinct()
            ->pluck('affiliate_id');

        foreach ($affiliateIds as $affiliateId) {
            if (isset($this->userIdMap[$affiliateId])) continue;

            $wpAffiliate = DB::connection('wordpress')
                ->table('wp_users')
                ->where('ID', $affiliateId)
                ->first();

            if ($wpAffiliate) {
                $exists = DB::table('users')->where('email', $wpAffiliate->user_email)->exists();
                
                if (!$exists) {
                    $newId = DB::table('users')->insertGetId([
                        'name' => $wpAffiliate->display_name ?: $wpAffiliate->user_login,
                        'email' => $wpAffiliate->user_email,
                        'password' => Hash::make(Str::random(16)),
                        'email_verified_at' => $wpAffiliate->user_registered,
                        'created_at' => $wpAffiliate->user_registered,
                        'updated_at' => now(),
                    ]);
                    
                    $this->userIdMap[$affiliateId] = $newId;
                    $this->stats['users']++;
                } else {
                    $existingUser = DB::table('users')->where('email', $wpAffiliate->user_email)->first();
                    $this->userIdMap[$affiliateId] = $existingUser->id;
                }
            }
        }

        $bar->finish();
        $this->newLine();
    }

    protected function migrateAffiliates(): void
    {
        $this->info('👥 Migrating affiliates to affiliates table...');

        // Get all unique affiliate IDs from WordPress
        $wpAffiliateUserIds = DB::connection('wordpress')
            ->table('wp_sejolisa_affiliates')
            ->select('affiliate_id')
            ->distinct()
            ->pluck('affiliate_id');

        foreach ($wpAffiliateUserIds as $wpAffiliateUserId) {
            $userId = $this->userIdMap[$wpAffiliateUserId] ?? null;
            if (!$userId) continue;

            // Check if already exists in affiliates table
            $exists = DB::table('affiliates')->where('user_id', $userId)->exists();
            
            if (!$exists) {
                // Calculate total earnings from WordPress
                $totalEarnings = DB::connection('wordpress')
                    ->table('wp_sejolisa_affiliates')
                    ->where('affiliate_id', $wpAffiliateUserId)
                    ->sum('commission');

                $paidEarnings = DB::connection('wordpress')
                    ->table('wp_sejolisa_affiliates')
                    ->where('affiliate_id', $wpAffiliateUserId)
                    ->where('paid_status', 1)
                    ->sum('commission');

                $pendingEarnings = $totalEarnings - $paidEarnings;

                $affiliateId = DB::table('affiliates')->insertGetId([
                    'user_id' => $userId,
                    'referral_code' => 'REF-' . strtoupper(Str::random(8)),
                    'commission_rate' => 20.00, // 20% from ~97,800 / 489,000
                    'total_earnings' => $totalEarnings,
                    'pending_earnings' => $pendingEarnings,
                    'paid_earnings' => $paidEarnings,
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->affiliateIdMap[$wpAffiliateUserId] = $affiliateId;
                $this->stats['affiliates_table']++;
            } else {
                $existingAffiliate = DB::table('affiliates')->where('user_id', $userId)->first();
                $this->affiliateIdMap[$wpAffiliateUserId] = $existingAffiliate->id;
            }
        }

        $this->info("   ✅ Affiliates created: {$this->stats['affiliates_table']}");
    }

    protected function migrateCourse(): void
    {
        $this->info('📚 Migrating courses...');

        $wpCourse = DB::connection('wordpress')
            ->table('wp_posts')
            ->where('post_type', 'courses')
            ->where('post_status', 'publish')
            ->first();

        if ($wpCourse) {
            $exists = DB::table('courses')->where('slug', Str::slug($wpCourse->post_title))->exists();
            
            if (!$exists) {
                // Construct description with requirements and what you'll learn
                $description = $wpCourse->post_content;
                $content = "## Apa yang akan dipelajari\n\n";
                $content .= "- Cara riset produk microstock yang laku di pasaran\n";
                $content .= "- Teknik produksi asset video berkualitas\n";
                $content .= "- Optimasi upload ke berbagai platform microstock\n";
                $content .= "- Strategi monetisasi jangka panjang\n\n";
                $content .= "## Persyaratan\n\n";
                $content .= "- Laptop/PC dengan Adobe After Effects\n";
                $content .= "- Koneksi internet stabil\n";
                $content .= "- Akun di platform microstock (Adobe Stock, Shutterstock, dll)\n";

                DB::table('courses')->insert([
                    'title' => $wpCourse->post_title,
                    'slug' => Str::slug($wpCourse->post_title),
                    'description' => $wpCourse->post_excerpt ?: 'Belajar riset dan produksi asset untuk microstock',
                    'content' => $content,
                    'thumbnail' => null,
                    'price' => 489000,
                    'sale_price' => null,
                    'preview_url' => null,
                    'total_duration' => 0,
                    'total_lessons' => 32,
                    'status' => 'published',
                    'access_type' => 'lifetime',
                    'access_days' => null,
                    'order' => 1,
                    'created_at' => $wpCourse->post_date,
                    'updated_at' => $wpCourse->post_modified,
                ]);
                
                $this->stats['courses']++;
            }
        }
        
        $this->info("   ✅ Courses migrated: {$this->stats['courses']}");
    }

    protected function migrateTopics(): void
    {
        $this->info('📑 Migrating topics...');

        $wpTopics = DB::connection('wordpress')
            ->table('wp_posts')
            ->where('post_type', 'topics')
            ->orderBy('menu_order')
            ->get();

        $course = DB::table('courses')->first();
        if (!$course) {
            $this->error('No course found!');
            return;
        }

        $order = 1;
        foreach ($wpTopics as $topic) {
            $exists = DB::table('topics')
                ->where('course_id', $course->id)
                ->where('title', $topic->post_title)
                ->exists();

            if (!$exists) {
                DB::table('topics')->insert([
                    'course_id' => $course->id,
                    'title' => $topic->post_title,
                    'description' => $topic->post_content,
                    'order' => $order,
                    'created_at' => $topic->post_date,
                    'updated_at' => $topic->post_modified,
                ]);
                
                $this->stats['topics']++;
            }
            $order++;
        }
        
        $this->info("   ✅ Topics migrated: {$this->stats['topics']}");
    }

    protected function migrateLessons(): void
    {
        $this->info('📖 Migrating lessons...');

        // Build topic title to ID map
        $topicMap = DB::table('topics')->pluck('id', 'title')->toArray();

        $wpLessons = DB::connection('wordpress')
            ->table('wp_posts as l')
            ->join('wp_posts as t', function ($join) {
                $join->on('l.post_parent', '=', 't.ID')
                    ->where('t.post_type', '=', 'topics');
            })
            ->where('l.post_type', 'lesson')
            ->select('l.*', 't.post_title as topic_title')
            ->orderBy('t.menu_order')
            ->orderBy('l.menu_order')
            ->get();

        $bar = $this->output->createProgressBar($wpLessons->count());
        $bar->start();

        $globalOrder = 1;
        foreach ($wpLessons as $lesson) {
            $topicId = $topicMap[$lesson->topic_title] ?? null;
            if (!$topicId) {
                $bar->advance();
                continue;
            }

            // Get video meta
            $videoMeta = DB::connection('wordpress')
                ->table('wp_postmeta')
                ->where('post_id', $lesson->ID)
                ->where('meta_key', '_video')
                ->value('meta_value');

            $videoUrl = null;
            $videoSource = null;

            if ($videoMeta) {
                $videoData = @unserialize($videoMeta);
                if ($videoData) {
                    $videoSource = $videoData['source'] ?? null;
                    $videoUrl = match ($videoSource) {
                        'youtube' => $videoData['source_youtube'] ?? null,
                        'vimeo' => $videoData['source_vimeo'] ?? null,
                        default => $videoData['source_external_url'] ?? null,
                    };
                }
            }

            $exists = DB::table('lessons')
                ->where('topic_id', $topicId)
                ->where('title', $lesson->post_title)
                ->exists();

            if (!$exists) {
                DB::table('lessons')->insert([
                    'topic_id' => $topicId,
                    'title' => $lesson->post_title,
                    'description' => $lesson->post_content,
                    'youtube_url' => $videoUrl ?: '',
                    'duration' => 0,
                    'order' => $globalOrder,
                    'is_free' => $globalOrder <= 2,
                    'created_at' => $lesson->post_date,
                    'updated_at' => $lesson->post_modified,
                ]);
                
                $this->stats['lessons']++;
            }
            $globalOrder++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    protected function migrateCoupons(): void
    {
        $this->info('🎟️  Migrating coupons...');

        $wpCoupons = DB::connection('wordpress')
            ->table('wp_sejolisa_coupons')
            ->get();

        foreach ($wpCoupons as $coupon) {
            $code = strtoupper(trim($coupon->code));
            $exists = DB::table('coupons')->where('code', $code)->exists();

            if (!$exists) {
                // Parse serialized discount data
                $discountData = @unserialize($coupon->discount);
                $type = 'percentage';
                $value = 0;

                if ($discountData && is_array($discountData)) {
                    $type = $discountData['type'] ?? 'percentage';
                    $value = $discountData['value'] ?? 0;
                }

                DB::table('coupons')->insert([
                    'code' => $code,
                    'description' => "Migrated from WordPress - {$value}% off",
                    'type' => $type === 'percentage' ? 'percentage' : 'fixed',
                    'value' => $value,
                    'min_order_amount' => null,
                    'max_discount' => null,
                    'usage_limit' => $coupon->limit_use > 0 ? $coupon->limit_use : null,
                    'usage_limit_per_user' => 1,
                    'used_count' => $coupon->usage ?? 0,
                    'starts_at' => null,
                    'expires_at' => ($coupon->limit_date && $coupon->limit_date !== '0000-00-00 00:00:00') ? $coupon->limit_date : null,
                    'is_active' => $coupon->status !== 'inactive',
                    'created_at' => $coupon->created_at,
                    'updated_at' => $coupon->updated_at !== '0000-00-00 00:00:00' ? $coupon->updated_at : now(),
                ]);
                
                $this->stats['coupons']++;
            }
        }
        
        $this->info("   ✅ Coupons migrated: {$this->stats['coupons']}");
    }

    protected function migrateOrders(): void
    {
        $this->info('🛒 Migrating orders...');

        $wpOrders = DB::connection('wordpress')
            ->table('wp_sejolisa_orders')
            ->where('status', 'completed')
            ->get();

        $course = DB::table('courses')->first();
        if (!$course) {
            $this->error('No course found!');
            return;
        }

        $bar = $this->output->createProgressBar($wpOrders->count());
        $bar->start();

        foreach ($wpOrders as $wpOrder) {
            $userId = $this->userIdMap[$wpOrder->user_id] ?? null;
            if (!$userId) {
                $bar->advance();
                continue;
            }

            $orderNumber = 'WP-' . str_pad($wpOrder->ID, 6, '0', STR_PAD_LEFT);
            $exists = DB::table('orders')->where('order_number', $orderNumber)->exists();

            if (!$exists) {
                $paymentMethod = match ($wpOrder->payment_gateway) {
                    'duitku' => 'duitku',
                    'manual' => 'bank_transfer',
                    default => 'other',
                };

                $paidAt = $wpOrder->updated_at !== '0000-00-00 00:00:00' 
                    ? $wpOrder->updated_at 
                    : $wpOrder->created_at;

                // Check if this order has affiliate - use affiliateIdMap for affiliates table ID
                $affiliateId = null;
                if ($wpOrder->affiliate_id && isset($this->affiliateIdMap[$wpOrder->affiliate_id])) {
                    $affiliateId = $this->affiliateIdMap[$wpOrder->affiliate_id];
                }

                $orderId = DB::table('orders')->insertGetId([
                    'order_number' => $orderNumber,
                    'user_id' => $userId,
                    'affiliate_id' => $affiliateId,
                    'subtotal' => $wpOrder->grand_total,
                    'discount' => 0,
                    'total' => $wpOrder->grand_total,
                    'payment_method' => $paymentMethod,
                    'status' => 'paid',
                    'paid_at' => $paidAt,
                    'created_at' => $wpOrder->created_at,
                    'updated_at' => $paidAt,
                ]);

                $this->orderIdMap[$wpOrder->ID] = $orderId;
                $this->stats['orders']++;

                // Create order item
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'course_id' => $course->id,
                    'price' => $wpOrder->grand_total,
                    'discount' => 0,
                    'created_at' => $wpOrder->created_at,
                    'updated_at' => $paidAt,
                ]);
                
                $this->stats['order_items']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    protected function migrateAffiliateCommissions(): void
    {
        $this->info('💰 Migrating affiliate commissions...');

        $wpAffiliates = DB::connection('wordpress')
            ->table('wp_sejolisa_affiliates as a')
            ->join('wp_sejolisa_orders as o', 'a.order_id', '=', 'o.ID')
            ->where('o.status', 'completed')
            ->select('a.*', 'o.user_id as buyer_id')
            ->get();

        foreach ($wpAffiliates as $aff) {
            // Use affiliateIdMap to get the affiliates table ID
            $affiliateTableId = $this->affiliateIdMap[$aff->affiliate_id] ?? null;
            $orderId = $this->orderIdMap[$aff->order_id] ?? null;

            if (!$affiliateTableId || !$orderId) continue;

            $exists = DB::table('affiliate_commissions')
                ->where('order_id', $orderId)
                ->where('affiliate_id', $affiliateTableId)
                ->exists();

            if (!$exists) {
                $status = match ($aff->status) {
                    'added', 'approved' => 'approved',
                    'paid' => 'paid',
                    default => 'pending',
                };

                // Get order amount
                $order = DB::table('orders')->find($orderId);
                $orderAmount = $order ? $order->total : 489000;

                DB::table('affiliate_commissions')->insert([
                    'affiliate_id' => $affiliateTableId,
                    'order_id' => $orderId,
                    'order_amount' => $orderAmount,
                    'commission_rate' => round(($aff->commission / $orderAmount) * 100, 2),
                    'commission_amount' => $aff->commission,
                    'status' => $status,
                    'approved_at' => in_array($status, ['approved', 'paid']) ? ($aff->updated_at !== '0000-00-00 00:00:00' ? $aff->updated_at : now()) : null,
                    'paid_at' => $aff->paid_status == 1 ? $aff->updated_at : null,
                    'created_at' => $aff->created_at,
                    'updated_at' => $aff->updated_at !== '0000-00-00 00:00:00' ? $aff->updated_at : now(),
                ]);
                
                $this->stats['affiliate_commissions']++;
            }
        }
        
        $this->info("   ✅ Affiliate commissions migrated: {$this->stats['affiliate_commissions']}");
    }

    protected function migrateEnrollments(): void
    {
        $this->info('🎓 Migrating enrollments...');

        $course = DB::table('courses')->first();
        if (!$course) return;

        $completedOrders = DB::table('orders')
            ->where('status', 'paid')
            ->select('id', 'user_id', 'created_at')
            ->get()
            ->unique('user_id');

        foreach ($completedOrders as $order) {
            $exists = DB::table('user_courses')
                ->where('user_id', $order->user_id)
                ->where('course_id', $course->id)
                ->exists();

            if (!$exists) {
                DB::table('user_courses')->insert([
                    'user_id' => $order->user_id,
                    'course_id' => $course->id,
                    'order_id' => $order->id,
                    'purchased_at' => $order->created_at,
                    'expires_at' => null, // Lifetime access
                    'created_at' => $order->created_at,
                    'updated_at' => now(),
                ]);
                
                $this->stats['enrollments']++;
            }
        }
        
        $this->info("   ✅ Enrollments created: {$this->stats['enrollments']}");
    }

    protected function migrateLessonProgress(): void
    {
        $this->info('📖 Migrating lesson progress...');

        // Build lesson post ID to our lesson ID map
        $wpLessons = DB::connection('wordpress')
            ->table('wp_posts')
            ->where('post_type', 'lesson')
            ->select('ID', 'post_title')
            ->get();

        // Get our lessons by title
        $ourLessons = DB::table('lessons')->pluck('id', 'title')->toArray();

        $lessonIdMap = [];
        foreach ($wpLessons as $wpLesson) {
            if (isset($ourLessons[$wpLesson->post_title])) {
                $lessonIdMap[$wpLesson->ID] = $ourLessons[$wpLesson->post_title];
            }
        }

        // Get lesson completions from wp_usermeta
        // Format: meta_key = '_tutor_completed_lesson_id_<lesson_post_id>'
        $completions = DB::connection('wordpress')
            ->table('wp_usermeta')
            ->where('meta_key', 'like', '_tutor_completed_lesson_id_%')
            ->get();

        $bar = $this->output->createProgressBar($completions->count());
        $bar->start();

        foreach ($completions as $completion) {
            // Extract lesson post ID from meta_key
            $wpLessonId = (int) str_replace('_tutor_completed_lesson_id_', '', $completion->meta_key);
            
            $userId = $this->userIdMap[$completion->user_id] ?? null;
            $lessonId = $lessonIdMap[$wpLessonId] ?? null;

            if (!$userId || !$lessonId) {
                $bar->advance();
                continue;
            }

            $exists = DB::table('lesson_progress')
                ->where('user_id', $userId)
                ->where('lesson_id', $lessonId)
                ->exists();

            if (!$exists) {
                // meta_value is the timestamp when completed
                $completedAt = is_numeric($completion->meta_value) 
                    ? date('Y-m-d H:i:s', (int) $completion->meta_value)
                    : now();

                DB::table('lesson_progress')->insert([
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'watched_seconds' => 0,
                    'is_completed' => true,
                    'completed_at' => $completedAt,
                    'created_at' => $completedAt,
                    'updated_at' => $completedAt,
                ]);
                
                $this->stats['lesson_progress']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ Lesson progress migrated: {$this->stats['lesson_progress']}");
    }

    protected function showMigrationSummary(): void
    {
        $this->newLine();
        $this->info('✅ Migration Complete!');
        $this->newLine();
        
        $this->table(
            ['Entity', 'Migrated'],
            [
                ['Users', $this->stats['users']],
                ['Affiliates', $this->stats['affiliates_table']],
                ['Courses', $this->stats['courses']],
                ['Topics', $this->stats['topics']],
                ['Lessons', $this->stats['lessons']],
                ['Coupons', $this->stats['coupons']],
                ['Orders', $this->stats['orders']],
                ['Order Items', $this->stats['order_items']],
                ['Affiliate Commissions', $this->stats['affiliate_commissions']],
                ['Enrollments', $this->stats['enrollments']],
                ['Lesson Progress', $this->stats['lesson_progress']],
            ]
        );

        if (!$this->option('dry-run')) {
            $this->newLine();
            $this->warn('⚠️  Important: Users need to reset their passwords!');
            $this->info('   Consider sending password reset emails to migrated users.');
        }
    }
}
