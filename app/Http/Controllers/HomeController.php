<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PriceHistory;
use App\Models\Review;
use App\Models\Slider;
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
            $old     = $history->first(fn($h) => $h->date->lte($cutoff));
            return [
                'id'           => $g->id,
                'name'         => $g->name,
                'slug'         => $g->slug,
                'price'        => (float) $g->price,
                'brand'        => $g->brand?->name,
                'category'     => $g->category?->slug,
                'price_change' => ($latest && $old) ? round((float) $latest->price - (float) $old->price) : null,
            ];
        })->values()->toArray();

        $appName = config('app.name');
        $appUrl  = config('app.url');

        return Inertia::render('Home', [
            'sliders'      => Slider::active()->get(),
            'featured'     => Gadget::with(['brand', 'category'])->where('is_featured', true)->latest()->take(12)->get(),
            'trending'     => $trending,
            'categories'   => Category::withCount('gadgets')->get(),
            'news'         => NewsArticle::where('is_published', true)->latest()->take(6)->get(),
            'reviews'      => Review::with(['gadget.brand'])->where('is_published', true)->latest()->take(6)->get(),
            'brands'       => Brand::withCount('gadgets')->orderByDesc('gadgets_count')->take(15)->get(['id', 'name', 'slug', 'gadgets_count']),
            'priceTracker' => $priceTracker,

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
