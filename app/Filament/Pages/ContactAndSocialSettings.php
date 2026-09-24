<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * Single place to manage contact details and social links. Values are stored as
 * SiteSetting rows and shared to every page via the Inertia `settings` prop, so
 * the footer, header, contact page and static-page sidebar all update together.
 */
class ContactAndSocialSettings extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-phone';
    protected static UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Contact & Social Links';
    protected static ?string $title = 'Contact & Social Links';
    protected static ?int $navigationSort = 0;

    /** setting key => group */
    private const FIELDS = [
        'footer_email'     => 'contact',
        'footer_phone'     => 'contact',
        'footer_address'   => 'contact',
        'footer_hours'     => 'contact',
        'footer_copyright' => 'contact',
        'social_facebook'  => 'social',
        'social_instagram' => 'social',
        'social_twitter'   => 'social',
        'social_youtube'   => 'social',
        'social_linkedin'  => 'social',
        'social_tiktok'    => 'social',
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            collect(self::FIELDS)->map(fn ($group, $key) => SiteSetting::get($key))->all()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact details')
                    ->description('Shown in the footer, contact page and the sidebar of static pages.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('footer_email')->label('Email')->email()->maxLength(150),
                        TextInput::make('footer_phone')->label('Phone')->tel()->maxLength(40),
                        TextInput::make('footer_address')->label('Address')->maxLength(255),
                        TextInput::make('footer_hours')->label('Business hours')->maxLength(120),
                        TextInput::make('footer_copyright')->label('Footer copyright text')->maxLength(255)->columnSpanFull(),
                    ]),
                Section::make('Social media')
                    ->description('Leave a field empty to hide that icon everywhere.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('social_facebook')->label('Facebook')->url()->maxLength(255),
                        TextInput::make('social_instagram')->label('Instagram')->url()->maxLength(255),
                        TextInput::make('social_twitter')->label('X / Twitter')->url()->maxLength(255),
                        TextInput::make('social_youtube')->label('YouTube')->url()->maxLength(255),
                        TextInput::make('social_linkedin')->label('LinkedIn')->url()->maxLength(255),
                        TextInput::make('social_tiktok')->label('TikTok')->url()->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Save changes')->submit('save')->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (self::FIELDS as $key => $group) {
            SiteSetting::set($key, $state[$key] ?? '', $group);
        }

        Notification::make()->title('Contact & social links saved')->success()->send();
    }
}
