<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    public const CACHE_KEY = 'header_menu';

    public const TYPES = [
        'link'       => 'Link',
        'heading'    => 'Heading (dropdown label)',
        'categories' => 'Auto: Shop-by-category grid',
        'divider'    => 'Divider',
    ];

    public const STYLES = [
        'default' => 'Default',
        'blue'    => 'Blue highlight',
        'emerald' => 'Green highlight',
        'rose'    => 'Red highlight',
    ];

    public const ICONS = [
        'Cpu' => 'Cpu', 'Scale' => 'Scale', 'Bot' => 'Bot', 'Flame' => 'Flame', 'Star' => 'Star',
        'Award' => 'Award', 'Newspaper' => 'Newspaper', 'Activity' => 'Activity', 'Lightbulb' => 'Lightbulb',
        'Gamepad2' => 'Gamepad', 'Smartphone' => 'Smartphone', 'BookOpen' => 'Book', 'ShieldCheck' => 'Shield',
        'ShoppingBag' => 'Shopping bag', 'Wrench' => 'Wrench', 'PenLine' => 'Pen', 'Sparkles' => 'Sparkles',
        'Laptop' => 'Laptop', 'Tablet' => 'Tablet', 'Headphones' => 'Headphones', 'Watch' => 'Watch',
    ];

    protected $fillable = [
        'parent_id', 'label', 'type', 'url', 'icon', 'subtitle', 'badge_text', 'style',
        'open_in_new_tab', 'show_on_mobile', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'show_on_mobile'  => 'boolean',
        'is_active'       => 'boolean',
        'sort_order'      => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeVisible(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    /** Nested tree of active items for the public header. */
    public static function tree(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $cols = ['id', 'label', 'type', 'url', 'icon', 'subtitle', 'badge_text', 'style', 'open_in_new_tab', 'show_on_mobile'];

            return self::visible()->whereNull('parent_id')->orderBy('sort_order')
                ->with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->get()
                ->map(fn ($m) => [
                    ...$m->only($cols),
                    'children' => $m->children->map(fn ($c) => $c->only($cols))->values()->all(),
                ])->values()->all();
        });
    }

    /** [label, url, icon, subtitle, badge, style, type] */
    public static function seedDefaults(): void
    {
        if (self::query()->exists()) {
            return;
        }

        $tree = [
            ['Products', null, 'Cpu', [
                ['All Products', '/products', 'Cpu', 'Full catalog & filters'],
                ['Shop by Category', null, null, null, null, 'default', 'heading'],
                ['Categories', null, null, null, null, 'default', 'categories'],
                ['Side-by-Side Compare', '/compare', 'Scale', 'Spec & price showdown'],
                ['PC Builder', '/pc-builder', 'Bot', 'Custom build estimator', 'AI', 'blue'],
                ['Price Tracker', '/price-tracker', 'Flame', 'Nepal price drops & cuts', 'HOT', 'rose'],
            ]],
            ['Reviews', null, null, [
                ['All Reviews', '/reviews', 'Star', 'Expert verdicts'],
                ["Editor's Choice", '/reviews?filter=editors-choice', 'Award', 'Top ranked gadgets'],
            ]],
            ['News', null, null, [
                ['All Tech News', '/news', 'Newspaper', 'Latest updates'],
                ['Rumors & Upcoming', '/news?category=rumors', 'Activity'],
                ['Technology', '/news?category=technology', 'Lightbulb'],
                ['AI & Innovations', '/news?category=ai-ml', 'Bot'],
                ['Gaming', '/news?category=gaming', 'Gamepad2'],
                ['Mobile Launches', '/news?category=mobile', 'Smartphone'],
            ]],
            ['Guides', null, null, [
                ['All Tech Guides', '/guides', 'BookOpen', 'Tips & tutorials'],
                ['Tech Lab', '/tech-lab', 'ShieldCheck', 'Interactive hardware tests', 'NEW', 'emerald'],
                ['Buying Guides', '/guides?type=buying-guide', 'ShoppingBag'],
                ['How-to Guides', '/guides?type=how-to', 'Wrench'],
            ]],
            ['Blog', '/blog', 'PenLine', []],
            ['Compare', '/compare', 'Scale', []],
            ['PC Builder', '/pc-builder', 'Bot', [], 'blue'],
        ];

        foreach ($tree as $i => $row) {
            $parent = self::create([
                'label' => $row[0], 'url' => $row[1], 'icon' => $row[2],
                'style' => $row[4] ?? 'default', 'sort_order' => $i + 1,
            ]);

            foreach ($row[3] as $j => $c) {
                $c = array_pad($c, 7, null);
                self::create([
                    'parent_id' => $parent->id, 'label' => $c[0], 'url' => $c[1], 'icon' => $c[2],
                    'subtitle' => $c[3], 'badge_text' => $c[4], 'style' => $c[5] ?? 'default',
                    'type' => $c[6] ?? 'link', 'sort_order' => $j + 1,
                ]);
            }
        }
    }
}
