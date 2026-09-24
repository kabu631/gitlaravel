<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Team Members';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Team Member')->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('role')->required()->maxLength(255),
                Textarea::make('bio')->rows(3)->maxLength(500)->columnSpanFull(),
                FileUpload::make('photo')->image()->avatar()->disk('public')->directory('team')->nullable(),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('photo')->disk('public')->circular(),
            TextColumn::make('name')->searchable()->weight('bold'),
            TextColumn::make('role'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                ActionGroup::make([
                    EditAction::make()->modalHeading('EDIT TEAM MEMBER')->modalWidth('lg'),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageTeamMembers::route('/')];
    }
}
