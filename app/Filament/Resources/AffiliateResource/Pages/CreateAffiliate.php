<?php

namespace App\Filament\Resources\AffiliateResource\Pages;

use App\Filament\Resources\AffiliateResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateAffiliate extends CreateRecord
{
    protected static string $resource = AffiliateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique referral code if not provided
        if (empty($data['referral_code'])) {
            $data['referral_code'] = strtoupper(Str::random(8));
        }

        // Set default earnings to 0
        $data['total_earnings'] = 0;
        $data['pending_earnings'] = 0;
        $data['paid_earnings'] = 0;

        return $data;
    }
}
