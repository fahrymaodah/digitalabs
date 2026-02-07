<?php

namespace App\Filament\Widgets;

use App\Models\Affiliate;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingAffiliates extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected static ?string $pollingInterval = '60s';

    public function getHeading(): ?string
    {
        return '🤝 Pending Affiliate Requests';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Affiliate::query()
                    ->where('status', 'pending')
                    ->with('user')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant')
                    ->description(fn (Affiliate $record): string => $record->user?->email ?? '-')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('referral_code')
                    ->label('Kode')
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->copyMessage('Kode referral disalin!'),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->since()
                    ->description(fn (Affiliate $record): string => $record->created_at->format('d M Y')),
            ])
            ->recordActions([
                Action::make('approve')
                    ->button()
                    ->hiddenLabel()
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->size(Size::Large)
                    ->tooltip('Approve')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Affiliate')
                    ->modalDescription('Yakin mau approve affiliate ini? Status akan berubah menjadi approved.')
                    ->modalSubmitActionLabel('Ya, Approve')
                    ->action(function (Affiliate $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->title('Affiliate approved!')
                            ->success()
                            ->send();
                    }),
                    
                Action::make('reject')
                    ->button()
                    ->hiddenLabel()
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->size(Size::Large)
                    ->tooltip('Reject')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Affiliate')
                    ->modalDescription('Yakin mau reject affiliate ini? Permintaan akan ditolak.')
                    ->modalSubmitActionLabel('Ya, Reject')
                    ->action(function (Affiliate $record) {
                        $record->update(['status' => 'rejected']);
                        
                        Notification::make()
                            ->title('Affiliate rejected')
                            ->warning()
                            ->send();
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak ada pending request')
            ->emptyStateDescription('Semua affiliate request sudah diproses.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
