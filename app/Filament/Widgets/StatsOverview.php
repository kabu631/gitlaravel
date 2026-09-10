<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Order;
use App\Models\Review;
use App\Models\TechGuide;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue      = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $ordersToday       = Order::whereDate('created_at', today())->count();
        $pendingOrders     = Order::where('status', 'pending')->count();
        $totalUsers        = User::where('is_admin', false)->count();
        $newUsersThisMonth = User::where('is_admin', false)
            ->whereMonth('created_at', now()->month)->count();
        $publishedReviews  = Review::where('is_published', true)->count();
        $editorsChoice     = Review::where('is_published', true)->where('rating', '>=', 8)->count();
        $publishedNews     = NewsArticle::where('is_published', true)->count();
        $pendingMessages   = ContactMessage::where('is_read', false)->count();
        $publishedGuides   = TechGuide::where('is_published', true)->count();

        return [
            Stat::make('Total Revenue', 'NPR ' . number_format($totalRevenue))
                ->description('All non-cancelled orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Orders Today', $ordersToday)
                ->description($pendingOrders . ' pending')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Products', Gadget::count())
                ->description(Gadget::where('is_featured', true)->count() . ' featured · ' . Gadget::where('is_trending', true)->count() . ' trending')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('info'),

            Stat::make('Registered Users', $totalUsers)
                ->description('+' . $newUsersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Reviews Published', $publishedReviews)
                ->description($editorsChoice . " Editor's Choice (≥ 8.0)")
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('News Articles', $publishedNews)
                ->description('Published & live')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('info'),

            Stat::make('Tech Guides', $publishedGuides)
                ->description('Published & live')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('success'),

            Stat::make('Contact Messages', $pendingMessages)
                ->description('Unread messages')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($pendingMessages > 0 ? 'danger' : 'gray'),
        ];
    }
}
