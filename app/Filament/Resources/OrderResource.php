<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use BackedEnum;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static UnitEnum|string|null $navigationGroup = 'Sales';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Section::make('Customer Information')->columnSpanFull()
                ->icon('heroicon-o-user')
                ->schema([
                    TextInput::make('first_name')->disabled(),
                    TextInput::make('last_name')->disabled(),
                    TextInput::make('email')->disabled(),
                    TextInput::make('phone_number')->disabled()->label('Phone'),
                    TextInput::make('shipping_address')->disabled()->columnSpanFull(),
                ])->columns(2),

            Section::make('Order Details')->columnSpanFull()
                ->icon('heroicon-o-receipt-percent')
                ->schema([
                    TextInput::make('payment_method')->disabled()->label('Payment Method'),
                    TextInput::make('total_amount')->disabled()->prefix('NPR')->label('Total Amount'),
                    Select::make('is_paid')
                        ->label('Payment Status')
                        ->options([0 => 'Unpaid', 1 => 'Paid'])
                        ->native(false),
                    Select::make('status')
                        ->label('Order Status')
                        ->options([
                            'pending'    => 'Pending',
                            'processing' => 'Processing',
                            'shipped'    => 'Shipped',
                            'delivered'  => 'Delivered',
                            'cancelled'  => 'Cancelled',
                        ])
                        ->native(false)
                        ->required(),
                ])->columns(2),

            Section::make('Ordered Items')->columnSpanFull()
                ->icon('heroicon-o-shopping-cart')
                ->schema([
                    Placeholder::make('items_display')
                        ->label('')
                        ->content(function ($record) {
                            if (!$record || !$record->relationLoaded('items')) {
                                $record?->load('items.gadget.brand');
                            }

                            $items = $record?->items ?? collect();

                            if ($items->isEmpty()) {
                                return new HtmlString('<p class="text-sm text-gray-400 italic">No items found for this order.</p>');
                            }

                            $rows = '';
                            foreach ($items as $item) {
                                $gadget  = $item->gadget;
                                $imgUrl  = $gadget->image
                                    ? Storage::url($gadget->image)
                                    : asset('images/placeholder.png');

                                $variantHtml = '';
                                if (!empty($item->variant_info) && is_array($item->variant_info)) {
                                    $badges = collect($item->variant_info)
                                        ->map(fn($v, $k) => '<span style="display:inline-block;margin:0 4px 4px 0;padding:2px 8px;background:#fff8ec;color:#be5c06;border:1px solid #ffdca4;border-radius:9999px;font-size:11px;font-weight:600;">'
                                            . e(ucfirst($k)) . ': ' . e($v) . '</span>')
                                        ->implode('');
                                    $variantHtml = '<div style="margin-top:4px;">' . $badges . '</div>';
                                }

                                $subtotal = number_format($item->quantity * $item->price);

                                $rows .= '
                                <div style="display:flex;align-items:center;gap:14px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:10px;">
                                    <img src="' . $imgUrl . '" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid #e5e7eb;">
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:14px;font-weight:700;color:#111827;">' . e($gadget->name) . '</div>
                                        <div style="font-size:12px;color:#6b7280;">' . e($gadget->brand?->name ?? '') . '</div>
                                        ' . $variantHtml . '
                                    </div>
                                    <div style="text-align:right;flex-shrink:0;">
                                        <div style="font-size:12px;color:#6b7280;">Qty: <strong>' . $item->quantity . '</strong></div>
                                        <div style="font-size:12px;color:#6b7280;">@ NPR ' . number_format($item->price) . '</div>
                                        <div style="font-size:14px;font-weight:700;color:#ff991b;margin-top:4px;">NPR ' . $subtotal . '</div>
                                    </div>
                                </div>';
                            }

                            $grandTotal = number_format($items->sum(fn($i) => $i->quantity * $i->price));
                            $rows .= '<div style="text-align:right;padding:8px 4px 0;border-top:2px solid #e5e7eb;margin-top:4px;">
                                <span style="font-size:14px;font-weight:700;color:#111827;">Order Total: NPR ' . $grandTotal . '</span>
                            </div>';

                            return new HtmlString('<div>' . $rows . '</div>');
                        }),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->prefix('#')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn($state, $record) => $state . ' ' . $record->last_name)
                    ->description(fn($record) => $record->email)
                    ->searchable(),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('items.gadget.name')->sortable(false)
                    ->label('Products')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'NPR ' . number_format($state))
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('payment_method')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('is_paid')
                    ->label('Paid')
                    ->formatStateUsing(fn($state) => $state ? 'Paid' : 'Unpaid')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),

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

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending'    => 'Pending',
                    'processing' => 'Processing',
                    'shipped'    => 'Shipped',
                    'delivered'  => 'Delivered',
                    'cancelled'  => 'Cancelled',
                ]),
                SelectFilter::make('is_paid')
                    ->label('Payment')
                    ->options([1 => 'Paid', 0 => 'Unpaid']),
            ])
            ->actions([
\Filament\Actions\ActionGroup::make([EditAction::make()->label('Manage')]),
])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelationManagers(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
