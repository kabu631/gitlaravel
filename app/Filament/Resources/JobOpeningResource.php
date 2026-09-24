<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobOpeningResource\Pages;
use App\Models\JobOpening;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JobOpeningResource extends Resource
{
    protected static ?string $model = JobOpening::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Job Openings';
    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Job Opening')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('department')->maxLength(100)->placeholder('e.g. Editorial, Engineering'),
                TextInput::make('location')->required()->maxLength(150)->default('Kathmandu, Nepal'),
                Select::make('type')->options([
                    'Full-time' => 'Full-time', 'Part-time' => 'Part-time', 'Contract' => 'Contract',
                    'Internship' => 'Internship', 'Freelance' => 'Freelance', 'Remote' => 'Remote',
                ])->default('Full-time')->required(),
                DatePicker::make('closes_at')->label('Apply before')->helperText('Empty = open until removed'),
                Textarea::make('summary')->rows(2)->maxLength(300)->columnSpanFull(),
                RichEditor::make('description')->columnSpanFull()
                    ->helperText('Responsibilities, requirements, benefits'),
                TextInput::make('apply_email')->email()->label('Apply email')
                    ->helperText('Defaults to the site contact email'),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->weight('bold'),
            TextColumn::make('department'),
            TextColumn::make('type')->badge(),
            TextColumn::make('location'),
            TextColumn::make('closes_at')->date('d M Y')->placeholder('Open'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                ActionGroup::make([
                    EditAction::make()->modalHeading('EDIT JOB OPENING')->modalWidth('2xl'),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageJobOpenings::route('/')];
    }
}
