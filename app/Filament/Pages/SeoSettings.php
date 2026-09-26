<?php

namespace App\Filament\Pages;

use App\Models\SeoMeta;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

/** Site-wide SEO defaults used whenever a page or record has no value of its own. */
class SeoSettings extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static UnitEnum|string|null $navigationGroup = 'SEO';
    protected static ?string $navigationLabel = 'Global SEO Settings';
    protected static ?string $title = 'Global SEO Settings';
    protected static ?string $slug = 'seo-settings';
    protected static ?int $navigationSort = 1;

    private const FIELDS = [
        'seo_default_title',
        'seo_title_separator',
        'seo_default_description',
        'seo_default_keywords',
        'seo_default_og_image',
        'seo_twitter_site',
        'seo_twitter_card',
        'seo_facebook_app_id',
        'seo_robots_default',
        'seo_discourage_indexing',
        'seo_google_verification',
        'seo_bing_verification',
        'seo_robots_txt',
    ];

    public const DEFAULT_ROBOTS_TXT = <<<'TXT'
        User-agent: *
        Allow: /

        # Private / non-indexable areas
        Disallow: /admin
        Disallow: /admin/
        Disallow: /cart
        Disallow: /checkout
        Disallow: /order/
        Disallow: /wishlist
        Disallow: /profile
        Disallow: /dashboard
        Disallow: /chatbot/

        # Auth pages (no SEO value)
        Disallow: /login
        Disallow: /register
        Disallow: /forgot-password
        Disallow: /reset-password
        Disallow: /verify-email
        TXT;

    public ?array $data = [];

    public function mount(): void
    {
        $state = collect(self::FIELDS)->mapWithKeys(fn ($key) => [$key => SiteSetting::get($key)])->all();
        $state['seo_discourage_indexing'] = (bool) $state['seo_discourage_indexing'];
        $state['seo_robots_txt'] ??= self::DEFAULT_ROBOTS_TXT;

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Default meta tags')
                    ->description('Used on any page that doesn\'t define its own title, description or keywords.')
                    ->columns(4)
                    ->schema([
                        TextInput::make('seo_default_title')->label('Default meta title')->maxLength(255)
                            ->helperText('Homepage & fallback title. The site name ("' . config('app.name') . '") is appended automatically.')
                            ->columnSpan(3),
                        TextInput::make('seo_title_separator')->label('Title separator')->maxLength(5)->default('|'),
                        Textarea::make('seo_default_description')->label('Default meta description')->rows(3)->maxLength(500)
                            ->columnSpanFull(),
                        TagsInput::make('seo_default_keywords')->label('Default meta keywords')->separator(',')
                            ->columnSpanFull(),
                    ]),

                Section::make('Social sharing')
                    ->description('Open Graph (Facebook, LinkedIn, WhatsApp) and Twitter/X card defaults.')
                    ->columns(3)
                    ->schema([
                        FileUpload::make('seo_default_og_image')->label('Default share image')->image()
                            ->disk('public')->directory('seo')->imagePreviewHeight('120')
                            ->helperText('1200×630px recommended. Used when a page has no image.')
                            ->columnSpanFull(),
                        TextInput::make('seo_twitter_site')->label('Twitter/X handle')->prefix('@')
                            ->formatStateUsing(fn (?string $state) => ltrim((string) $state, '@'))
                            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? '@' . ltrim($state, '@') : ''),
                        Select::make('seo_twitter_card')->label('Default Twitter card')->options(SeoMeta::TWITTER_CARDS)
                            ->default('summary_large_image')->selectablePlaceholder(false),
                        TextInput::make('seo_facebook_app_id')->label('Facebook App ID')->maxLength(40),
                    ]),

                Section::make('Indexing')
                    ->columns(2)
                    ->schema([
                        Select::make('seo_robots_default')->label('Default robots meta')
                            ->options(SeoMeta::ROBOTS_OPTIONS)->default('index, follow')->selectablePlaceholder(false),
                        Toggle::make('seo_discourage_indexing')->label('Discourage search engines from indexing this site')
                            ->helperText('Forces "noindex, nofollow" everywhere and blocks all crawlers in robots.txt. Use for staging only.')
                            ->inline(false),
                        TextInput::make('seo_google_verification')->label('Google Search Console verification code')
                            ->helperText('Only the content="…" value of the meta tag.'),
                        TextInput::make('seo_bing_verification')->label('Bing Webmaster verification code'),
                        Textarea::make('seo_robots_txt')->label('robots.txt')->rows(12)
                            ->helperText('Served at /robots.txt. The sitemap line is added automatically.')
                            ->extraInputAttributes(['style' => 'font-family: monospace'])
                            ->columnSpanFull(),
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

        foreach (self::FIELDS as $key) {
            $value = $state[$key] ?? '';
            SiteSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : ($value ?? ''), 'seo');
        }

        Notification::make()->title('SEO settings saved')->success()->send();
    }
}
