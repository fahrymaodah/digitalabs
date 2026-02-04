<?php

namespace App\Filament\User\Pages;

use App\Models\Affiliate as AffiliateModel;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Affiliate extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Affiliate';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Affiliate Program';
    protected string $view = 'filament.user.pages.affiliate';

    public ?AffiliateModel $affiliate = null;
    public string $status = 'none'; // none, pending, approved, rejected, suspended

    // Registration form
    public ?string $bank_name = null;
    public ?string $bank_account_number = null;
    public ?string $bank_account_name = null;
    public ?string $notes = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->affiliate = AffiliateModel::where('user_id', $user->id)->first();

        if ($this->affiliate) {
            $this->status = $this->affiliate->status;
            $this->bank_name = $this->affiliate->bank_name;
            $this->bank_account_number = $this->affiliate->bank_account_number;
            $this->bank_account_name = $this->affiliate->bank_account_name;
        }
    }

    public function register(): void
    {
        $user = Auth::user();

        // Validate
        $validated = $this->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:30',
            'bank_account_name' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Generate unique referral code
        $referralCode = strtoupper(Str::random(8));
        while (AffiliateModel::where('referral_code', $referralCode)->exists()) {
            $referralCode = strtoupper(Str::random(8));
        }

        $this->affiliate = AffiliateModel::create([
            'user_id' => $user->id,
            'referral_code' => $referralCode,
            'commission_rate' => 10.00, // Default 10%
            'status' => 'pending',
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name' => $validated['bank_account_name'],
            'notes' => $validated['notes'],
        ]);

        $this->status = 'pending';

        Notification::make()
            ->title('Application Submitted')
            ->body('Your affiliate application has been submitted. Please wait for admin approval.')
            ->success()
            ->send();
    }

    public function requestPayout(): void
    {
        if (!$this->affiliate || $this->affiliate->pending_earnings < 100000) {
            Notification::make()
                ->title('Cannot Request Payout')
                ->body('Minimum payout amount is Rp 100,000')
                ->danger()
                ->send();
            return;
        }

        $amount = $this->affiliate->pending_earnings;

        AffiliatePayout::create([
            'affiliate_id' => $this->affiliate->id,
            'amount' => $amount,
            'status' => 'pending',
            'bank_name' => $this->affiliate->bank_name,
            'bank_account_number' => $this->affiliate->bank_account_number,
            'bank_account_name' => $this->affiliate->bank_account_name,
        ]);

        // Reset pending earnings
        $this->affiliate->update(['pending_earnings' => 0]);

        Notification::make()
            ->title('Payout Requested')
            ->body('Your payout request of Rp ' . number_format($amount, 0, ',', '.') . ' has been submitted.')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $data = [
            'affiliate' => $this->affiliate,
            'status' => $this->status,
        ];

        if ($this->affiliate && $this->status === 'approved') {
            // Get stats
            $data['stats'] = [
                'total_earnings' => $this->affiliate->total_earnings ?? 0,
                'pending_earnings' => $this->affiliate->pending_earnings ?? 0,
                'paid_earnings' => $this->affiliate->paid_earnings ?? 0,
                'commission_rate' => $this->affiliate->commission_rate ?? 10,
            ];

            // Referral link
            $data['referral_link'] = url('/?ref=' . $this->affiliate->referral_code);

            // Commission history
            $data['commissions'] = AffiliateCommission::where('affiliate_id', $this->affiliate->id)
                ->with('order')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Payout history
            $data['payouts'] = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return $data;
    }
}
