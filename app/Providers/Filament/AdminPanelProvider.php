<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\RecentOrdersTable;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(config('filament.path', 'secure-admin'))
            ->login()
            ->brandName('Git Infosys Admin')
            ->brandLogo(asset('images/logo_dark.png'))
            ->darkModeBrandLogo(asset('images/logo-white.png'))
            ->brandLogoHeight('2.25rem')
            ->maxContentWidth(Width::SevenExtraLarge)
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString(
                    '<link rel="stylesheet" href="' . asset('css/filament-compact.css') . '?v=' . (@filemtime(public_path('css/filament-compact.css')) ?: '1') . '">'
                )
            )
            ->colors([
                'primary' => Color::hex('#FF991B'),
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                StatsOverview::class,
                RecentOrdersTable::class,
            ])
            ->navigation(fn (\Filament\Navigation\NavigationBuilder $builder) => \App\Services\DynamicNavigationService::build($builder))
            ->navigationGroups(['Catalog', 'Content', 'Tech Lab & Tools', 'Settings', 'Sales', 'Users'])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Back to Website')
                    ->icon('heroicon-o-home')
                    ->url('/'),
                'logout' => MenuItem::make()->label('Logout'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\AuthorizeSystemModuleAccess::class,
            ]);
    }
}
