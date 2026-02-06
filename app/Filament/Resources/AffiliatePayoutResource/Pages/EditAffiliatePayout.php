<?php

namespace App\Filament\Resources\AffiliatePayoutResource\Pages;

use App\Filament\Resources\AffiliatePayoutResource;
use App\Models\AffiliateCommission;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAffiliatePayout extends EditRecord
{
    protected static string $resource = AffiliatePayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // If status changed to completed, update affiliate earnings
        if ($this->record->wasChanged('status') && $this->record->status === 'completed') {
            $affiliate = $this->record->affiliate;

            // Update affiliate earnings
            $affiliate->paid_earnings += $this->record->amount;
            $affiliate->pending_earnings -= $this->record->amount;
            $affiliate->save();

            // Mark commissions as paid proportionally based on payout amount
            // Get approved commissions ordered by oldest first (FIFO)
            $approvedCommissions = $affiliate->commissions()
                ->where('status', 'approved')
                ->whereNull('paid_at')
                ->orderBy('created_at', 'asc')
                ->get();

            $remainingAmount = $this->record->amount;

            foreach ($approvedCommissions as $commission) {
                if ($remainingAmount <= 0) {
                    break;
                }

                // Mark commission as paid if fully covered by remaining payout amount
                if ($commission->commission_amount <= $remainingAmount) {
                    $commission->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    $remainingAmount -= $commission->commission_amount;
                }
            }

            Notification::make()
                ->title('Payout completed')
                ->body("Affiliate earnings have been updated. Rp " . number_format($this->record->amount, 0, ',', '.') . " has been paid.")
                ->success()
                ->send();
        }
    }
}
