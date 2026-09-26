<?php

namespace App\Filament\Schemas;

use App\Models\SeoMeta;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class SeoForm
{
    /** Collapsible "SEO" section bound to the record's `seo` morphOne relation. */
    public static function section(): Section
    {
        return Section::make('SEO')
            ->description('Search engine & social sharing metadata. Empty fields fall back to the content and global SEO settings.')
            ->icon('heroicon-o-magnifying-glass-circle')
            ->relationship('seo')
            ->collapsible()
            ->collapsed()
            ->columnSpanFull()
            ->schema(static::fields());
    }

    /** SEO-friendly slug input with uniqueness check and live URL preview. */
    public static function slug(?string $routeName = null, bool $required = false): TextInput
    {
        return TextInput::make('slug')
            ->label('URL slug')
            ->required($required)
            ->maxLength(255)
            ->unique(ignoreRecord: true)
            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
            ->validationMessages(['regex' => 'Use lowercase letters, numbers and single hyphens only (e.g. my-great-post).'])
            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Str::slug($state) : $state)
            ->live(onBlur: true)
            ->helperText(fn (?string $state) => filled($state) && $routeName
                ? route($routeName, $state)
                : 'Lowercase words separated by hyphens. Auto-generated from the name/title if left empty.');
    }

    public static function fields(): array
    {
        return [
            Tabs::make('seo_tabs')->contained(false)->tabs([
                Tab::make('Search')->icon('heroicon-o-magnifying-glass')->columns(2)->schema([
                    TextInput::make('meta_title')
                        ->label('Meta title')
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->hint(fn (?string $state) => static::counter($state, 60))
                        ->helperText('Aim for 50–60 characters. The site name is appended automatically.')
                        ->columnSpanFull(),
                    Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(3)
                        ->maxLength(500)
                        ->live(onBlur: true)
                        ->hint(fn (?string $state) => static::counter($state, 160))
                        ->helperText('Aim for 120–160 characters.')
                        ->columnSpanFull(),
                    TagsInput::make('meta_keywords')
                        ->label('Meta keywords')
                        ->separator(',')
                        ->placeholder('Add a keyword')
                        ->columnSpanFull(),
                    Html::make(fn (Get $get) => static::serpPreview($get('meta_title'), $get('meta_description')))
                        ->columnSpanFull(),
                ]),

                Tab::make('Open Graph')->icon('heroicon-o-share')->columns(2)->schema([
                    TextInput::make('og_title')->label('OG title')->maxLength(255)
                        ->helperText('Defaults to the meta title.'),
                    Select::make('og_type')->label('OG type')->options(SeoMeta::OG_TYPES)
                        ->placeholder('Page default'),
                    Textarea::make('og_description')->label('OG description')->rows(2)->maxLength(500)
                        ->helperText('Defaults to the meta description.')->columnSpanFull(),
                    FileUpload::make('og_image')->label('OG image')->image()->disk('public')->directory('seo')
                        ->imagePreviewHeight('120')
                        ->helperText('Recommended 1200×630px. Defaults to the content\'s main image.')
                        ->columnSpanFull(),
                    TextInput::make('image_alt')->label('Image ALT text')->maxLength(255)
                        ->helperText('Describes the main/share image for screen readers and image search. Defaults to the title.')
                        ->columnSpanFull(),
                ]),

                Tab::make('Twitter / X')->icon('heroicon-o-chat-bubble-left-right')->columns(2)->schema([
                    Select::make('twitter_card')->label('Card type')->options(SeoMeta::TWITTER_CARDS)
                        ->placeholder('Global default'),
                    TextInput::make('twitter_title')->label('Twitter title')->maxLength(255)
                        ->helperText('Defaults to the OG title.'),
                    Textarea::make('twitter_description')->label('Twitter description')->rows(2)->maxLength(500)
                        ->helperText('Defaults to the OG description.')->columnSpanFull(),
                    FileUpload::make('twitter_image')->label('Twitter image')->image()->disk('public')->directory('seo')
                        ->imagePreviewHeight('120')->helperText('Defaults to the OG image.')->columnSpanFull(),
                ]),

                Tab::make('Advanced')->icon('heroicon-o-cog-6-tooth')->columns(2)->schema([
                    TextInput::make('canonical_url')->label('Canonical URL')->url()->maxLength(255)
                        ->helperText('Only set when this content duplicates another URL. Defaults to the page\'s own URL.')
                        ->columnSpanFull(),
                    Select::make('robots')->label('Robots meta')->options(SeoMeta::ROBOTS_OPTIONS)
                        ->placeholder('Default (global setting)')
                        ->helperText('"noindex" also removes the page from sitemap.xml.'),
                    CheckboxList::make('robots_directives')->label('Extra robots directives')
                        ->options(SeoMeta::ROBOTS_DIRECTIVES)->columns(1),
                    Textarea::make('structured_data')->label('Custom structured data (JSON-LD)')
                        ->rows(6)
                        ->rule('nullable')
                        ->rule('json')
                        ->helperText('Optional. Replaces the auto-generated schema.org JSON-LD for this page.')
                        ->extraInputAttributes(['style' => 'font-family: monospace'])
                        ->columnSpanFull(),
                ]),
            ])->columnSpanFull(),
        ];
    }

    private static function counter(?string $state, int $limit): string
    {
        $len = mb_strlen((string) $state);

        return $len > $limit ? "{$len} / {$limit} — too long" : "{$len} / {$limit}";
    }

    private static function serpPreview(?string $title, ?string $description): HtmlString
    {
        $site  = e(config('app.name'));
        $title = e(Str::limit($title ?: 'Page title (uses content title when empty)', 60));
        $desc  = e(Str::limit($description ?: 'Meta description (uses the excerpt/content when empty).', 160));
        $host  = e(parse_url(config('app.url'), PHP_URL_HOST) ?: 'example.com');

        return new HtmlString(<<<HTML
            <div style="border:1px solid rgba(127,127,127,.25);border-radius:10px;padding:12px 14px;font-family:arial,sans-serif;max-width:600px">
                <div style="font-size:11px;opacity:.6;margin-bottom:4px">Google preview</div>
                <div style="font-size:12px;color:#188038">{$host}</div>
                <div style="font-size:18px;color:#1a0dab;line-height:1.3">{$title} | {$site}</div>
                <div style="font-size:13px;opacity:.8;line-height:1.45">{$desc}</div>
            </div>
        HTML);
    }
}
