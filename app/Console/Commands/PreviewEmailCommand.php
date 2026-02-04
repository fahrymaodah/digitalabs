<?php

namespace App\Console\Commands;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PreviewEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:preview {type : The email type to preview (welcome, order-created, payment-success, payment-failed, affiliate-approved, new-commission, payout-completed)}';

    /**
     * The console command description.
     */
    protected $description = 'Preview email templates by generating HTML output';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        
        $html = match ($type) {
            'welcome' => $this->previewWelcome(),
            'order-created' => $this->previewOrderCreated(),
            'payment-success' => $this->previewPaymentSuccess(),
            'payment-failed' => $this->previewPaymentFailed(),
            'affiliate-approved' => $this->previewAffiliateApproved(),
            'new-commission' => $this->previewNewCommission(),
            'payout-completed' => $this->previewPayoutCompleted(),
            default => null,
        };

        if (!$html) {
            $this->error("Unknown email type: {$type}");
            $this->line('Available types: welcome, order-created, payment-success, payment-failed, affiliate-approved, new-commission, payout-completed');
            return Command::FAILURE;
        }

        $filename = "email-preview-{$type}.html";
        $path = storage_path("app/{$filename}");
        File::put($path, $html);
        
        $this->info("Email preview saved to: {$path}");
        $this->line("Open in browser: file://{$path}");
        
        return Command::SUCCESS;
    }

    private function previewWelcome(): string
    {
        $user = User::first() ?? new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        return view('emails.user.welcome', ['user' => $user])->render();
    }

    private function previewOrderCreated(): string
    {
        // Always use mock data for preview since Order doesn't have direct course relation
        $order = $this->createMockOrder();
        return view('emails.order.created', ['order' => $order])->render();
    }

    private function previewPaymentSuccess(): string
    {
        // Always use mock data for preview
        $order = $this->createMockOrder();
        return view('emails.order.payment-success', ['order' => $order])->render();
    }

    private function previewPaymentFailed(): string
    {
        // Always use mock data for preview
        $order = $this->createMockOrder();
        return view('emails.order.payment-failed', [
            'order' => $order,
            'reason' => 'Saldo tidak mencukupi'
        ])->render();
    }

    private function previewAffiliateApproved(): string
    {
        $affiliate = Affiliate::with('user')->where('status', 'approved')->first();
        
        if (!$affiliate) {
            $affiliate = $this->createMockAffiliate();
        }

        return view('emails.affiliate.approved', ['affiliate' => $affiliate])->render();
    }

    private function previewNewCommission(): string
    {
        $commission = AffiliateCommission::with(['affiliate.user', 'order.course'])->first();
        
        if (!$commission) {
            $commission = $this->createMockCommission();
        }

        return view('emails.affiliate.new-commission', [
            'commission' => $commission,
            'totalCommissions' => 15,
            'pendingBalance' => 750000,
            'totalEarnings' => 2500000,
        ])->render();
    }

    private function previewPayoutCompleted(): string
    {
        $payout = AffiliatePayout::with('affiliate.user')->first();
        
        if (!$payout) {
            $payout = $this->createMockPayout();
        }

        return view('emails.affiliate.payout-completed', ['payout' => $payout])->render();
    }

    private function createMockOrder()
    {
        $user = User::first() ?? new User([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $course = new \stdClass();
        $course->title = 'Complete Web Development Masterclass';
        $course->slug = 'complete-web-development-masterclass';
        $course->instructor = 'DigitaLabs Team';
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
        $order->payment_url = 'https://payment.example.com/checkout';
        $order->coupon = null;

        return $order;
    }

    private function createMockAffiliate()
    {
        $user = User::first() ?? new User([
            'name' => 'Sarah Marketing',
            'email' => 'sarah@example.com',
        ]);

        $affiliate = new \stdClass();
        $affiliate->id = 1;
        $affiliate->user = $user;
        $affiliate->referral_code = 'SARAH2024';
        $affiliate->commission_rate = 20;
        $affiliate->status = 'approved';

        return $affiliate;
    }

    private function createMockCommission()
    {
        $affiliate = $this->createMockAffiliate();
        $order = $this->createMockOrder();

        $commission = new \stdClass();
        $commission->id = 1;
        $commission->affiliate = $affiliate;
        $commission->order = $order;
        $commission->amount = 99800;
        $commission->created_at = now();

        return $commission;
    }

    private function createMockPayout()
    {
        $affiliate = $this->createMockAffiliate();
        $affiliate->bank_name = 'BCA';
        $affiliate->bank_account_number = '1234567890';
        $affiliate->bank_account_name = 'Sarah Marketing';

        $payout = new \stdClass();
        $payout->id = 1;
        $payout->affiliate = $affiliate;
        $payout->affiliate_id = 1;
        $payout->amount = 500000;
        $payout->processed_at = now();

        return $payout;
    }
}
