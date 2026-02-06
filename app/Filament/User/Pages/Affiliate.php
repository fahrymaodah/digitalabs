<?php

namespace App\Filament\User\Pages;

use App\Models\Affiliate as AffiliateModel;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Services\EmailService;
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

    // Payout form
    public ?float $payout_amount = null;

    // Edit bank mode
    public bool $isEditingBank = false;

    // Pagination
    public int $commissionsPerPage = 5;
    public int $payoutsPerPage = 5;
    public int $commissionsPage = 1;
    public int $payoutsPage = 1;

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

    /**
     * Toggle bank info edit mode
     */
    public function toggleEditBank(): void
    {
        $this->isEditingBank = !$this->isEditingBank;
        
        // Reset to current values if cancelling
        if (!$this->isEditingBank && $this->affiliate) {
            $this->bank_name = $this->affiliate->bank_name;
            $this->bank_account_number = $this->affiliate->bank_account_number;
            $this->bank_account_name = $this->affiliate->bank_account_name;
        }
    }

    /**
     * Update bank information
     */
    public function updateBankInfo(): void
    {
        if (!$this->affiliate) {
            return;
        }

        $validated = $this->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:30',
            'bank_account_name' => 'required|string|max:100',
        ]);

        $this->affiliate->update([
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name' => $validated['bank_account_name'],
        ]);

        $this->isEditingBank = false;

        Notification::make()
            ->title('Bank Information Updated')
            ->body('Your bank account details have been updated successfully.')
            ->success()
            ->send();
    }

    /**
     * Request payout with custom amount
     */
    public function requestPayout(): void
    {
        if (!$this->affiliate) {
            Notification::make()
                ->title('Error')
                ->body('Affiliate account not found.')
                ->danger()
                ->send();
            return;
        }

        $pendingEarnings = (float) $this->affiliate->pending_earnings;

        // Validate amount
        $validated = $this->validate([
            'payout_amount' => [
                'required',
                'numeric',
                'min:100000',
            ],
        ], [
            'payout_amount.required' => 'Please enter the amount you want to withdraw.',
            'payout_amount.min' => 'Minimum payout amount is Rp 100,000.',
        ]);

        $amount = (float) $validated['payout_amount'];

        // Custom validation: amount tidak boleh lebih dari pending earnings
        if ($amount > $pendingEarnings) {
            $this->addError('payout_amount', 'Maximum amount is Rp ' . number_format($pendingEarnings, 0, ',', '.') . ' (your pending earnings).');
            return;
        }

        // Check if there's a pending payout
        $hasPendingPayout = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPendingPayout) {
            Notification::make()
                ->title('Cannot Request Payout')
                ->body('You already have a pending payout request. Please wait for it to be processed.')
                ->warning()
                ->send();
            return;
        }

        // Create payout request
        AffiliatePayout::create([
            'affiliate_id' => $this->affiliate->id,
            'amount' => $amount,
            'status' => 'pending',
            'bank_name' => $this->affiliate->bank_name,
            'bank_account_number' => $this->affiliate->bank_account_number,
            'bank_account_name' => $this->affiliate->bank_account_name,
        ]);

        // Note: pending_earnings will be reduced when admin approves the payout
        // This ensures accurate accounting and allows for cancellation without issues

        // Refresh affiliate
        $this->affiliate->refresh();

        // Reset form
        $this->payout_amount = null;

        Notification::make()
            ->title('Payout Requested')
            ->body('Your payout request of Rp ' . number_format($amount, 0, ',', '.') . ' has been submitted. We will process it within 1-3 business days.')
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        $data = [
            'affiliate' => $this->affiliate,
            'status' => $this->status,
            'isEditingBank' => $this->isEditingBank,
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

            // Commission history with pagination
            $commissionsQuery = AffiliateCommission::where('affiliate_id', $this->affiliate->id)
                ->with('order')
                ->orderBy('created_at', 'desc');
            
            // Get total count before pagination
            $commissionsTotal = $commissionsQuery->count();
            
            $data['commissionsPaginated'] = $commissionsQuery->paginate($this->commissionsPerPage, ['*'], 'commissionsPage', $this->commissionsPage);
            $data['commissions'] = $data['commissionsPaginated']->items();
            $data['commissionsTotal'] = $commissionsTotal;

            // Payout history with pagination
            $payoutsQuery = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
                ->orderBy('created_at', 'desc');
            
            // Get total count before pagination
            $payoutsTotal = $payoutsQuery->count();
            
            $data['payoutsPaginated'] = $payoutsQuery->paginate($this->payoutsPerPage, ['*'], 'payoutsPage', $this->payoutsPage);
            $data['payouts'] = $data['payoutsPaginated']->items();
            $data['payoutsTotal'] = $payoutsTotal;

            // Check if there's a pending payout
            $data['hasPendingPayout'] = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
                ->whereIn('status', ['pending', 'processing'])
                ->exists();

            // Current pending payout details (if any)
            $data['pendingPayout'] = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
                ->whereIn('status', ['pending', 'processing'])
                ->first();
        }

        return $data;
    }
}
