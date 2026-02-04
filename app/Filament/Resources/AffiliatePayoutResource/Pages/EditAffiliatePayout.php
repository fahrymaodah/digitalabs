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

            // Update affiliate paid earnings
            $affiliate->paid_earnings += $this->record->amount;
            $affiliate->pending_earnings -= $this->record->amount;
            $affiliate->save();

            // Mark related commissions as paid
            $this->record->commissions()->update(['status' => 'paid']);

            Notification::make()
                ->title('Payout completed')
                ->body("Affiliate earnings have been updated.")
                ->success()
                ->send();
        }
    }
}
