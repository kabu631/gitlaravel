<?php

namespace App\Filament\Widgets;

use App\Models\Gadget;
use App\Models\Order;
use App\Models\User;
use App\Models\NewsArticle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue    = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $ordersToday     = Order::whereDate('created_at', today())->count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $totalUsers      = User::where('is_admin', false)->count();
        $newUsersThisMonth = User::where('is_admin', false)
            ->whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Total Revenue', 'NPR ' . number_format($totalRevenue))
                ->description('All completed orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Orders Today', $ordersToday)
                ->description($pendingOrders . ' pending')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Products', Gadget::count())
                ->description(Gadget::where('is_featured', true)->count() . ' featured')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('info'),

            Stat::make('Registered Users', $totalUsers)
                ->description('+' . $newUsersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
