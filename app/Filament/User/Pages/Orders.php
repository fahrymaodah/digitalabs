<?php

namespace App\Filament\User\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Orders extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Order History';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Order History';
    protected string $view = 'filament.user.pages.orders';

    public function getViewData(): array
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with(['items.course', 'coupon'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'orders' => $orders,
        ];
    }
}
