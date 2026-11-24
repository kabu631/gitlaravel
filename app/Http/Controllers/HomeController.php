<?php

namespace App\Http\Controllers;

use App\Support\PriceDrops;
use App\Support\Seo;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\Slider;
use App\Models\UpcomingLaunch;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $trending = Gadget::forCard()->where('is_trending', true)->latest()->take(8)->get();

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


        $featured = Gadget::forCard()->where('is_featured', true)->latest()->take(12)->get();
        if ($featured->isEmpty()) {
            $featured = Gadget::forCard()->latest()->take(12)->get();
        }

        $appName = config('app.name');
        $appUrl  = config('app.url');

        return Inertia::render('Home', [
            'sliders'          => Slider::active()->get(),
            'featured'         => $featured,
            'trending'         => $trending,
            'categories'       => Category::withCount('gadgets')->whereHas('gadgets')->get(),
            'news'             => NewsArticle::where('is_published', true)->latest()->take(8)->get(['id', 'title', 'slug', 'category', 'created_at']),
            'blogPosts'        => BlogPost::published()->with('author:id,name')->orderByDesc('is_featured')->orderByDesc('published_at')->take(3)
                ->get()->map(fn ($p) => [
                    'id' => $p->id, 'title' => $p->title, 'slug' => $p->slug,
                    'excerpt' => $p->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $p->content), 110),
                    'cover_image' => $p->cover_image, 'reading_time' => $p->reading_time,
                    'category_name' => \App\Filament\Resources\BlogPostResource::CATEGORIES[$p->category] ?? \Illuminate\Support\Str::headline($p->category),
                ]),
            'reviews'          => Review::with(['gadget:id,name,slug,image,brand_id', 'gadget.brand:id,name'])->where('is_published', true)->latest()->take(6)
                ->get(['id', 'gadget_id', 'title', 'slug', 'rating', 'verdict', 'created_at']),
            'brands'           => Brand::withCount('gadgets')->orderByDesc('gadgets_count')->take(15)->get(['id', 'name', 'slug', 'gadgets_count']),
            'priceTracker'     => PriceDrops::top(6),
            'upcomingLaunches' => $upcomingLaunches,
            // Optional: left out of the first response, requested by <WhenVisible> on scroll.
            'matchmakerPool'   => Inertia::optional(fn () => $this->matchmakerPool()),

            'seo' => Seo::make([
                'title'       => "Gadget Prices in Nepal, Reviews and Comparisons",
                'description' => 'Current prices in Nepal, full specifications and reviews for phones, laptops and gadgets. Compare devices, track price changes and read buying guides.',
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
                            'description'     => 'Gadget prices, specifications, reviews and comparisons for Nepal.',
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

    /**
     * Device pool for the client-side matchmaker: only devices someone can buy today (priced,
     * not discontinued) in the four categories the finder offers, and only the spec fields
     * the scoring reads. Cached, and rebuilt whenever a device is updated.
     */
    private function matchmakerPool(): array
    {
        $stamp = Gadget::max('updated_at');
        $key   = 'home:matchmaker:v1:' . md5((string) $stamp);

        return Cache::store('file')->remember($key, now()->addHours(6), function () {
            $clip = fn ($v, $n = 140) => $v === null || $v === '' ? null : Str::limit(strip_tags((string) $v), $n, '');

            return Gadget::query()
                ->with(['brand:id,name', 'category:id,slug', 'specs:id,gadget_id,battery,camera,display,processor,ram,extra_specs'])
                ->whereHas('category', fn ($q) => $q->whereIn('slug', ['mobile', 'laptop', 'tablet', 'earbuds']))
                ->whereIn('release_status', [Gadget::STATUS_AVAILABLE, Gadget::STATUS_ANNOUNCED])
                ->where('price', '>', 0)
                ->get(['id', 'name', 'slug', 'price', 'old_price', 'brand_id', 'category_id', 'image', 'is_featured', 'is_trending', 'release_date', 'created_at'])
                ->map(function (Gadget $g) use ($clip) {
                    $s     = $g->specs;
                    $extra = is_array($s?->extra_specs) ? $s->extra_specs : [];

                    return array_filter([
                        'id'           => $g->id,
                        'name'         => $g->name,
                        'slug'         => $g->slug,
                        'image'        => $g->image,
                        'price'        => (float) $g->price,
                        'old_price'    => $g->old_price ? (float) $g->old_price : null,
                        'brand'        => ['name' => $g->brand?->name],
                        'category'     => ['slug' => $g->category?->slug],
                        'is_featured'  => $g->is_featured ?: null,
                        'is_trending'  => $g->is_trending ?: null,
                        'release_date' => $g->release_date?->toDateString(),
                        'created_at'   => $g->created_at?->toDateString(),
                        'specs'        => array_filter([
                            'battery'     => $clip($s?->battery),
                            'camera'      => $clip($s?->camera),
                            'display'     => $clip($s?->display),
                            'processor'   => $clip($s?->processor),
                            'ram'         => $clip($s?->ram, 60),
                            'extra_specs' => array_filter([
                                'battery' => $clip($extra['battery'] ?? null),
                                'camera'  => $clip($extra['camera'] ?? null),
                            ]) ?: null,
                        ]),
                    ], fn ($v) => $v !== null);
                })
                ->values()
                ->all();
        });
    }
}
