<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PriceHistory;
use App\Models\Review;
use App\Models\Slider;
use App\Models\UpcomingLaunch;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $trending = Gadget::with(['brand', 'category'])->where('is_trending', true)->latest()->take(8)->get();

        // Calculate 7-day price change for price tracker
        $trendingIds     = $trending->pluck('id');
        $priceHistoryMap = PriceHistory::whereIn('gadget_id', $trendingIds)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('gadget_id');

        $priceTracker = $trending->map(function ($g) use ($priceHistoryMap) {
            $history = $priceHistoryMap->get($g->id, collect())->sortByDesc('date');
            $cutoff  = now()->subDays(7);
            $latest  = $history->first();
            // Fallback to the oldest available history if no 7-day data exists
            $old     = $history->first(fn($h) => $h->date->lte($cutoff)) ?? $history->last();

            $priceChange     = ($latest && $old) ? round((float) $latest->price - (float) $old->price) : 0;
            $oldPrice        = (float) ($g->old_price ?? 0);
            $currentPrice    = (float) $g->price;
            $discountPercent = ($oldPrice > $currentPrice) ? round((($oldPrice - $currentPrice) / $oldPrice) * 100) : 0;

            return [
                'id'               => $g->id,
                'name'             => $g->name,
                'slug'             => $g->slug,
                'image'            => $g->image,
                'price'            => $currentPrice,
                'old_price'        => $oldPrice,
                'discount_percent' => $discountPercent,
                'brand'            => $g->brand?->name,
                'category'         => $g->category?->slug,
                'price_change'     => $priceChange,
                'is_hot_change'    => $discountPercent >= 15 || $priceChange <= -2000,
            ];
        })->values()->toArray();

        // Hottest Nepal price drop for hero flank showcase
        $hottestDrop = collect($priceTracker)->sortByDesc('discount_percent')->first();

        // Curated same-category rival matchups for Battleground Arena 2.0 (Mobile-Mobile, Laptop-Laptop, Earbuds-Earbuds, Watch-Watch)
        $matchups = [];

        // 1. Mobile vs Mobile
        $mobileRivals = Gadget::with(['brand', 'category'])
            ->whereNotNull('image')
            ->whereHas('category', fn($q) => $q->where('slug', 'mobile'))
            ->orderBy('is_trending', 'desc')
            ->latest()
            ->take(2)
            ->get();
        if ($mobileRivals->count() >= 2) {
            $matchups[] = [
                'id'       => 'mobile',
                'category' => 'mobile',
                'catName'  => 'Smartphones',
                'title'    => 'Ultra Flagship Smartphone Showdown',
                'subtitle' => 'Latest hot flagships compared head-to-head on Nepal pricing & benchmarks',
                'deviceA'  => $mobileRivals[0],
                'deviceB'  => $mobileRivals[1],
                'metrics'  => [
                    ['label' => 'Performance & NPU', 'leftVal' => '9.8 / 10', 'rightVal' => '9.7 / 10', 'leftPct' => 52, 'rightPct' => 48],
                    ['label' => 'Camera & Optics', 'leftVal' => '50MP Triple Array', 'rightVal' => '48MP ProRAW', 'leftPct' => 50, 'rightPct' => 50],
                    ['label' => 'Battery & Fast Charge', 'leftVal' => '4000mAh · 25W', 'rightVal' => '3274mAh · 27W', 'leftPct' => 53, 'rightPct' => 47],
                    ['label' => 'Display & LTPO', 'leftVal' => '120Hz Dynamic AMOLED', 'rightVal' => '120Hz ProMotion', 'leftPct' => 50, 'rightPct' => 50],
                    ['label' => 'Nepal Value Score', 'leftVal' => '9.4 / 10', 'rightVal' => '9.1 / 10', 'leftPct' => 51, 'rightPct' => 49],
                ]
            ];
        }

        // 2. Laptop vs Laptop
        $laptopRivals = Gadget::with(['brand', 'category'])
            ->whereNotNull('image')
            ->whereHas('category', fn($q) => $q->where('slug', 'laptop'))
            ->orderBy('is_trending', 'desc')
            ->latest()
            ->take(2)
            ->get();
        if ($laptopRivals->count() >= 2) {
            $matchups[] = [
                'id'       => 'laptop',
                'category' => 'laptop',
                'catName'  => 'Laptops',
                'title'    => 'Hot Ultrabook Showdown',
                'subtitle' => 'Top portable computing powerhouses evaluated for Nepali professionals & students',
                'deviceA'  => $laptopRivals[0],
                'deviceB'  => $laptopRivals[1],
                'metrics'  => [
                    ['label' => 'CPU Architecture', 'leftVal' => 'Intel Core Ultra', 'rightVal' => 'Intel Core i5 / Ryzen', 'leftPct' => 55, 'rightPct' => 45],
                    ['label' => 'Display Quality', 'leftVal' => '3K Dynamic AMOLED', 'rightVal' => 'FHD Anti-Glare IPS', 'leftPct' => 60, 'rightPct' => 40],
                    ['label' => 'Battery Endurance', 'leftVal' => '18 Hrs Productivity', 'rightVal' => '10 Hrs Standard', 'leftPct' => 58, 'rightPct' => 42],
                    ['label' => 'Build & Portability', 'leftVal' => 'Ultra-thin 1.23kg', 'rightVal' => 'Chassis 1.75kg', 'leftPct' => 56, 'rightPct' => 44],
                    ['label' => 'Nepal Value Score', 'leftVal' => '9.2 / 10', 'rightVal' => '9.0 / 10', 'leftPct' => 51, 'rightPct' => 49],
                ]
            ];
        }

        // 3. Earbuds vs Earbuds
        $earbudRivals = Gadget::with(['brand', 'category'])
            ->whereNotNull('image')
            ->whereHas('category', fn($q) => $q->where('slug', 'earbuds'))
            ->orderBy('is_trending', 'desc')
            ->latest()
            ->take(2)
            ->get();
        if ($earbudRivals->count() >= 2) {
            $matchups[] = [
                'id'       => 'earbuds',
                'category' => 'earbuds',
                'catName'  => 'Earbuds',
                'title'    => 'Audio & Noise Cancellation Showdown',
                'subtitle' => 'Flagship active noise cancellation vs budget everyday sound in Nepal',
                'deviceA'  => $earbudRivals[0],
                'deviceB'  => $earbudRivals[1],
                'metrics'  => [
                    ['label' => 'Active Noise Cancellation', 'leftVal' => 'Integrated Processor V2', 'rightVal' => 'Passive Noise Isolation', 'leftPct' => 70, 'rightPct' => 30],
                    ['label' => 'Codec & Hi-Res Sound', 'leftVal' => 'LDAC 24-bit Hi-Res', 'rightVal' => 'AAC / SBC Standard', 'leftPct' => 65, 'rightPct' => 35],
                    ['label' => 'Total Battery Playtime', 'leftVal' => '32 Hours with Case', 'rightVal' => '42 Hours with Case', 'leftPct' => 45, 'rightPct' => 55],
                    ['label' => 'Microphone & Calls', 'leftVal' => 'AI Bone Conduction', 'rightVal' => 'Dual Mic ENx', 'leftPct' => 60, 'rightPct' => 40],
                    ['label' => 'Nepal Value Score', 'leftVal' => '9.3 / 10', 'rightVal' => '9.5 / 10', 'leftPct' => 49, 'rightPct' => 51],
                ]
            ];
        }

        // 4. Smartwatch vs Smartwatch
        $watchRivals = Gadget::with(['brand', 'category'])
            ->whereNotNull('image')
            ->whereHas('category', fn($q) => $q->where('slug', 'smartwatch'))
            ->orderBy('is_trending', 'desc')
            ->latest()
            ->take(2)
            ->get();
        if ($watchRivals->count() >= 2) {
            $matchups[] = [
                'id'       => 'smartwatch',
                'category' => 'smartwatch',
                'catName'  => 'Smartwatches',
                'title'    => 'Wearables & Health Watch Duel',
                'subtitle' => 'Advanced medical biometric sensors vs accessible AMOLED fitness companion',
                'deviceA'  => $watchRivals[0],
                'deviceB'  => $watchRivals[1],
                'metrics'  => [
                    ['label' => 'Health & ECG Sensors', 'leftVal' => 'Optical Heart & SpO2', 'rightVal' => 'BioActive Sensor + ECG', 'leftPct' => 40, 'rightPct' => 60],
                    ['label' => 'Display & AMOLED Nits', 'leftVal' => '1.96-inch 500 Nits', 'rightVal' => 'Sapphire Crystal 2000 Nits', 'leftPct' => 42, 'rightPct' => 58],
                    ['label' => 'Battery Life (Days)', 'leftVal' => '7 Days Typical Use', 'rightVal' => '40 Hours WearOS', 'leftPct' => 65, 'rightPct' => 35],
                    ['label' => 'App Ecosystem & Calls', 'leftVal' => 'BT Calling & Alerts', 'rightVal' => 'Full WearOS / Google Play', 'leftPct' => 40, 'rightPct' => 60],
                    ['label' => 'Nepal Value Score', 'leftVal' => '9.4 / 10', 'rightVal' => '9.1 / 10', 'leftPct' => 52, 'rightPct' => 48],
                ]
            ];
        }

        // Upcoming Nepal Gadget Launch Roadmap — loaded from admin (UpcomingLaunches resource)
        $upcomingLaunches = UpcomingLaunch::active()
            ->orderBy('sort_order')
            ->get()
            ->map(fn($l) => [
                'name'          => $l->name,
                'brand'         => $l->brand,
                'category'      => $l->category,
                'expected_date' => $l->expected_date,
                'est_price'     => $l->est_price,
                'confidence'    => $l->confidence,
                'badge'         => $l->badge,
                'highlight'     => $l->highlight,
                'tag_color'     => $l->tag_color,
            ])
            ->toArray();


        // Lightweight device pool for client-side AI matchmaker
        $matchmakerPool = Gadget::with(['brand:id,name', 'category:id,slug,name'])
            ->select('id', 'name', 'slug', 'price', 'old_price', 'brand_id', 'category_id', 'image', 'is_featured', 'is_trending')
            ->get();

        $featured = Gadget::with(['brand', 'category'])->where('is_featured', true)->latest()->take(12)->get();
        if ($featured->isEmpty()) {
            $featured = Gadget::with(['brand', 'category'])->latest()->take(12)->get();
        }

        $appName = config('app.name');
        $appUrl  = config('app.url');

        return Inertia::render('Home', [
            'sliders'          => Slider::active()->get(),
            'featured'         => $featured,
            'trending'         => $trending,
            'categories'       => Category::withCount('gadgets')->get(),
            'news'             => NewsArticle::where('is_published', true)->latest()->take(8)->get(),
            'reviews'          => Review::with(['gadget.brand'])->where('is_published', true)->latest()->take(6)->get(),
            'brands'           => Brand::withCount('gadgets')->orderByDesc('gadgets_count')->take(15)->get(['id', 'name', 'slug', 'gadgets_count']),
            'priceTracker'     => $priceTracker,
            'hottestDrop'      => $hottestDrop,
            'rivalShowdown'    => $mobileRivals,
            'matchups'         => $matchups,
            'upcomingLaunches' => $upcomingLaunches,
            'matchmakerPool'   => $matchmakerPool,

            'seo' => [
                'title'       => "Nepal's #1 Tech Review, Gadget Prices & Comparison",
                'description' => 'Discover the latest smartphones, laptops, and accessories with honest reviews, live price tracking, and spec comparisons. Make smarter buying decisions with Git Infosys.',
                'canonical'   => $appUrl . '/',
                'type'        => 'website',
                'json_ld'     => [
                    '@context' => 'https://schema.org',
                    '@graph'   => [
                        [
                            '@type'           => 'WebSite',
                            '@id'             => $appUrl . '/#website',
                            'url'             => $appUrl,
                            'name'            => $appName,
                            'description'     => 'Nepal\'s trusted tech review & gadget price comparison platform.',
                            'potentialAction' => [
                                '@type'       => 'SearchAction',
                                'target'      => ['@type' => 'EntryPoint', 'urlTemplate' => $appUrl . '/search?q={search_term_string}'],
                                'query-input' => 'required name=search_term_string',
                            ],
                        ],
                        [
                            '@type' => 'Organization',
                            '@id'   => $appUrl . '/#organization',
                            'name'  => $appName,
                            'url'   => $appUrl,
                            'logo'  => [
                                '@type' => 'ImageObject',
                                'url'   => $appUrl . '/images/og-default.jpg',
                            ],
                            'sameAs' => [
                                'https://facebook.com/gitinfosys',
                                'https://twitter.com/gitinfosys',
                                'https://instagram.com/gitinfosys',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
