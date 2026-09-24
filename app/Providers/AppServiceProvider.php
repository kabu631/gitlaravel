<?php

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        $this->configureRateLimiting();
        $this->configureAdminTables();

        // Auto-clean stale public/hot file if Vite dev server is offline to prevent white screen
        if (file_exists(public_path('hot'))) {
            $hotUrl = trim(@file_get_contents(public_path('hot')));
            if ($hotUrl) {
                $port = parse_url($hotUrl, PHP_URL_PORT) ?: 5173;
                $conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
                if (!is_resource($conn)) {
                    @unlink(public_path('hot'));
                } else {
                    fclose($conn);
                }
            }
        }
    }

    /**
     * One pagination, filter and sorting setup for every admin list page. Pagination: "Rows per page" selector,
     * "Showing x to y of z" summary, and Previous / page numbers / Next.
     */
    protected function configureAdminTables(): void
    {
        Table::configureUsing(fn (Table $table) => $table
            ->paginationMode(PaginationMode::Default)
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->extremePaginationLinks(false)
            // Filters: one two-column panel behind a labelled "Filters" button, remembered per user.
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->filtersFormMaxHeight('28rem')
            ->persistFiltersInSession()
            // Row actions: one trailing "Actions" column holding a ⋯ menu.
            ->recordActionsColumnLabel('Actions')
            ->recordActionsAlignment('end')
            ->recordActionsPosition(RecordActionsPosition::AfterColumns)
            // Column manager: two-column checklist behind a labelled "Columns" button.
            ->columnManagerColumns(2)
            ->columnManagerWidth(Width::Medium)
            ->persistColumnsInSession()
            ->columnManagerTriggerAction(fn (Action $action) => $action->button()->label('Columns')->color('gray'))
            ->filtersTriggerAction(fn (Action $action) => $action->button()->label('Filters')->color('gray')));

        // Row action menus (Edit, View, Delete, …) all share the same ⋯ trigger.
        ActionGroup::configureUsing(fn (ActionGroup $group) => $group
            ->icon(Heroicon::EllipsisHorizontal)
            ->label('Actions')
            ->tooltip('Actions')
            // Open the menu right-aligned under the ⋯ button so it sits inside the card.
            ->dropdownPlacement('bottom-end')
            ->color('gray'));

        // Filter dropdowns are searchable everywhere.
        SelectFilter::configureUsing(fn (SelectFilter $filter) => $filter->searchable()->preload());

        // Every text / icon column can be sorted by clicking its header. Columns that
        // aren't a real database column opt out with ->sortable(false) in their table.
        TextColumn::configureUsing(fn (TextColumn $column) => $column->sortable());
        IconColumn::configureUsing(fn (IconColumn $column) => $column->sortable());
    }

    /**
     * Named limiters for endpoints that are expensive (paid AI calls), spammable
     * (contact form), or vote-stuffable (reactions and shootout polls).
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('ai', fn (Request $request) => Limit::perMinute(10)
            ->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(3)
            ->by($request->ip()));

        RateLimiter::for('interactions', fn (Request $request) => Limit::perMinute(30)
            ->by($request->user()?->id ?: $request->ip()));
    }
}
