<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // If status changed to paid, handle payment completion
        if ($this->record->wasChanged('status') && $this->record->status === 'paid') {
            $this->record->markAsPaid();

            Notification::make()
                ->title('Order marked as paid')
                ->body('Course access has been granted to the customer.')
                ->success()
                ->send();
        }
    }
}
