<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use App\Models\BlogPost;
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
        $trending = Gadget::with(['brand', 'category', 'specs'])->where('is_trending', true)->latest()->take(8)->get();

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

        // Upcoming Nepal Gadget Launch Roadmap — loaded from admin (UpcomingLaunches resource)
        $upcomingLaunches = UpcomingLaunch::active()
            ->with('gadget:id,slug')
            ->orderBy('sort_order')
            ->get()
            ->map(fn($l) => [
                'slug'          => $l->gadget?->slug,
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


        // Device pool with hardware specs for client-side AI matchmaker and algorithmic leaderboards
        $matchmakerPool = Gadget::with(['brand:id,name', 'category:id,slug,name', 'specs'])
            ->select('id', 'name', 'slug', 'price', 'old_price', 'brand_id', 'category_id', 'image', 'is_featured', 'is_trending', 'release_date', 'created_at')
            ->get();

        $featured = Gadget::with(['brand', 'category', 'specs'])->where('is_featured', true)->latest()->take(12)->get();
        if ($featured->isEmpty()) {
            $featured = Gadget::with(['brand', 'category', 'specs'])->latest()->take(12)->get();
        }

        $appName = config('app.name');
        $appUrl  = config('app.url');

        return Inertia::render('Home', [
            'sliders'          => Slider::active()->get(),
            'featured'         => $featured,
            'trending'         => $trending,
            'categories'       => Category::withCount('gadgets')->get(),
            'news'             => NewsArticle::where('is_published', true)->latest()->take(8)->get(),
            'blogPosts'        => BlogPost::published()->with('author:id,name')->orderByDesc('is_featured')->orderByDesc('published_at')->take(3)
                ->get()->map(fn ($p) => [
                    'id' => $p->id, 'title' => $p->title, 'slug' => $p->slug,
                    'excerpt' => $p->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $p->content), 110),
                    'cover_image' => $p->cover_image, 'reading_time' => $p->reading_time,
                    'category_name' => \App\Filament\Resources\BlogPostResource::CATEGORIES[$p->category] ?? \Illuminate\Support\Str::headline($p->category),
                ]),
            'reviews'          => Review::with(['gadget.brand'])->where('is_published', true)->latest()->take(6)->get(),
            'brands'           => Brand::withCount('gadgets')->orderByDesc('gadgets_count')->take(15)->get(['id', 'name', 'slug', 'gadgets_count']),
            'priceTracker'     => $priceTracker,
            'upcomingLaunches' => $upcomingLaunches,
            'matchmakerPool'   => $matchmakerPool,

            'seo' => Seo::make([
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
            ]),
        ]);
    }
}
