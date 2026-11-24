<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gadget extends Model
{
    use BacksUpImages, HasSeo;

    protected array $imageBackupFields = ['image'];
    protected $fillable = [
        'brand_id', 'category_id', 'name', 'slug', 'accessory_type',
        'image', 'model_3d', 'sketchfab_embed', 'price', 'old_price',
        'release_date', 'is_featured', 'is_trending', 'description', 'price_tracker_description', 'buy_url', 'out_of_stock_at', 'views_count',
        'release_status', 'expected_launch', 'price_is_estimate', 'launch_price_note', 'faqs', 'sources',
    ];

    /** Sold in Nepal at an official/retail price. */
    public const STATUS_AVAILABLE = 'available';
    /** Launched globally; official Nepal price/availability not confirmed yet. */
    public const STATUS_ANNOUNCED = 'announced';
    /** Not launched yet — specs and price are expectations. */
    public const STATUS_UPCOMING = 'upcoming';

    /** No longer sold new; kept for reference with its launch price. */
    public const STATUS_DISCONTINUED = 'discontinued';

    public const STATUSES = [
        self::STATUS_AVAILABLE => 'Available in Nepal',
        self::STATUS_ANNOUNCED => 'Announced (Nepal price pending)',
        self::STATUS_UPCOMING  => 'Upcoming',
        self::STATUS_DISCONTINUED => 'Discontinued (launch price shown)',
    ];

    protected $appends = ['referral_buy_url'];

    public function getReferralBuyUrlAttribute(): string
    {
        return !empty($this->buy_url) ? $this->buy_url : 'https://onin.com.np/';
    }

    protected $casts = [
        'is_featured'  => 'boolean',
        'is_trending'  => 'boolean',
        'price'        => 'decimal:2',
        'old_price'    => 'decimal:2',
        'release_date' => 'date',
        'out_of_stock_at' => 'date:Y-m-d',
        'price_is_estimate' => 'boolean',
        'faqs'         => 'array',
        'sources'      => 'array',
    ];

    public function scopeReleased($query)
    {
        return $query->where('release_status', '!=', self::STATUS_UPCOMING);
    }

    /**
     * Just what a product card renders (GadgetCard.vue), so listing pages don't ship
     * descriptions, FAQs, sources and the full spec sheet for every tile.
     */
    public function scopeForCard($query)
    {
        return $query
            ->select([
                'gadgets.id', 'gadgets.brand_id', 'gadgets.category_id', 'gadgets.name', 'gadgets.slug',
                'gadgets.image', 'gadgets.price', 'gadgets.old_price', 'gadgets.price_is_estimate',
                'gadgets.launch_price_note', 'gadgets.release_status', 'gadgets.release_date',
                'gadgets.is_featured', 'gadgets.is_trending', 'gadgets.buy_url', 'gadgets.out_of_stock_at', 'gadgets.created_at',
            ])
            ->with([
                'brand:id,name,slug',
                'category:id,name,slug',
                'specs:id,gadget_id,processor,display,ram,storage,battery',
            ]);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('release_status', self::STATUS_UPCOMING);
    }

    public function isUpcoming(): bool
    {
        return $this->release_status === self::STATUS_UPCOMING;
    }

    protected static function booted(): void
    {
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));

        // Create initial price history on creation
        static::created(function ($gadget) {
            $gadget->priceHistory()->create([
                'price' => $gadget->price,
                'date'  => now(),
            ]);
        });

        // Track price changes
        static::updated(function ($gadget) {
            if ($gadget->isDirty('price')) {
                $gadget->priceHistory()->create([
                    'price' => $gadget->price,
                    'date'  => now(),
                ]);
            }
        });
    }

    public function brand()          { return $this->belongsTo(Brand::class); }
    public function category()       { return $this->belongsTo(Category::class); }
    public function specs()          { return $this->hasOne(SpecSheet::class); }
    public function variants()       { return $this->hasMany(GadgetVariant::class)->orderBy('variant_type')->orderBy('value'); }
    public function productVariants(){ return $this->hasMany(ProductVariant::class)->orderBy('price'); }
    public function images()         { return $this->hasMany(GadgetImage::class)->orderBy('order'); }
    public function priceHistory()   { return $this->hasMany(PriceHistory::class)->orderBy('date'); }
    public function reviews()        { return $this->hasMany(Review::class); }
    public function comments()       { return $this->hasMany(UserComment::class); }
    public function wishlistedBy()   { return $this->belongsToMany(User::class, 'wishlists'); }

    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->comments()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function getEditorialReviewAttribute(): ?Review
    {
        return $this->reviews()->where('is_published', true)->first();
    }

    public function getAlgorithmicBadgesAttribute(): array
    {
        $badges = [];
        $specs = $this->specs;
        $battery = $specs?->battery ?? '';
        $camera = $specs?->camera ?? '';
        $proc = $specs?->processor ?? '';
        $disp = $specs?->display ?? '';

        if (preg_match('/(\d{4,5})\s*mAh/i', $battery, $m)) {
            $mah = (int)$m[1];
            if ($mah >= 6000) {
                $badges[] = "🔋 {$mah}mAh Monster";
            } elseif ($mah >= 5000) {
                $badges[] = "🔋 {$mah}mAh Marathon";
            }
        } elseif (preg_match('/(\d{2,3})\s*Wh/i', $battery, $m) && (int)$m[1] >= 70) {
            $badges[] = "🔋 {$m[1]}Wh Heavy Duty";
        }

        if (preg_match('/(\d{2,3})\s*MP/i', $camera, $m)) {
            $mp = (int)$m[1];
            if ($mp >= 50) {
                $lens = '';
                if (stripos($camera, 'Leica') !== false) $lens = ' Leica';
                elseif (stripos($camera, 'Hasselblad') !== false) $lens = ' Hasselblad';
                elseif (stripos($camera, 'Zeiss') !== false) $lens = ' Zeiss';
                elseif (stripos($camera, 'OIS') !== false) $lens = ' OIS';
                $badges[] = "📸 {$mp}MP{$lens}";
            }
        }

        if (preg_match('/(120Hz|144Hz|165Hz|240Hz|RTX\s*\d{4}|Snapdragon 8|Dimensity 9|Apple A1[78]|M[1234]\s*Pro|M[1234]\s*Max)/i', $proc . ' ' . $disp, $m)) {
            $badges[] = "⚡ " . trim($m[1]) . " Performance";
        }

        if ($this->old_price && $this->price && $this->old_price > $this->price) {
            $pct = round((($this->old_price - $this->price) / $this->old_price) * 100);
            if ($pct >= 10) {
                $badges[] = "💎 Save {$pct}% VFM";
            }
        }

        return $badges;
    }

    public function variantsByType(): array
    {
        $groups = [];
        $labels = [
            'ram'          => 'RAM',
            'color'        => 'Color',
            'storage'      => 'Storage',
            'screen_size'  => 'Screen Size',
            'connectivity' => 'Connectivity',
            'other'        => 'Other',
        ];
        foreach ($this->variants()->where('is_available', true)->get() as $v) {
            if (!isset($groups[$v->variant_type])) {
                $groups[$v->variant_type] = [
                    'label'   => $labels[$v->variant_type] ?? $v->variant_type,
                    'options' => [],
                ];
            }
            $groups[$v->variant_type]['options'][] = [
                'value'        => $v->value,
                'price'        => $v->price ?? $this->price,
                'is_available' => $v->is_available,
            ];
        }
        return $groups;
    }
}
