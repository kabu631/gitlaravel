<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersTable extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Recent Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::with('user')->latest()->limit(10))
            ->columns([
                TextColumn::make('id')->label('Order #')->prefix('#'),
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'NPR ' . number_format($state)),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),
                TextColumn::make('payment_method')->label('Payment')->badge(),
                TextColumn::make('created_at')->label('Date')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn(Order $record): string => route('filament.admin.resources.orders.edit', ['record' => $record])),
            ]);
    }
}
