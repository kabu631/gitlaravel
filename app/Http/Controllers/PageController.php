<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PageContent;
use App\Mail\ContactMessageMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PageController extends Controller
{
    private function sidebarData(): array
    {
        return [
            'sidebarProducts' => Gadget::with('brand')
                ->where('is_featured', true)
                ->latest()
                ->take(4)
                ->get(['id', 'name', 'slug', 'image', 'price', 'old_price', 'brand_id']),
            'sidebarNews' => NewsArticle::where('is_published', true)
                ->latest()
                ->take(4)
                ->get(['id', 'title', 'slug', 'thumbnail', 'category', 'created_at']),
        ];
    }

    public function about()
    {
        $content = PageContent::forPage('about');
        $extra   = $content?->extra ?? [];

        $stats = [
            ['num' => '500+', 'label' => 'Products Reviewed'],
            ['num' => '50K+', 'label' => 'Monthly Visitors'],
            ['num' => '100+', 'label' => 'Expert Articles'],
            ['num' => '10K+', 'label' => 'Happy Shoppers'],
        ];

        $team = [
            ['name' => 'Kabindra Koirala', 'role' => 'Founder & Editor-in-Chief', 'bio' => 'Tech enthusiast passionate about making gadget buying decisions easier for Nepali consumers.'],
            ['name' => 'Tech Team', 'role' => 'Review Specialists', 'bio' => 'Our team of specialists rigorously test every product before publishing honest, unbiased reviews.'],
        ];

        return Inertia::render('Pages/About', array_merge($this->sidebarData(), [
            'heading'    => $content?->heading    ?? "Nepal's Trusted Tech Platform",
            'subheading' => $content?->subheading ?? 'We help Nepali consumers make smarter, more confident tech purchasing decisions.',
            'mission'    => $extra['mission'] ?? "To be Nepal's most trusted source for gadget reviews, price comparisons, and tech news — empowering every buyer with unbiased, data-driven insights.",
            'vision'     => $extra['vision']  ?? 'A Nepal where every consumer has access to transparent, up-to-date technology information and can shop with complete confidence.',
            'story'      => $extra['story']   ?? 'Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal.',
            'stats'      => $stats,
            'team'       => $team,
            'seo'        => [
                'title'       => 'About Git Infosys — Nepal\'s Trusted Tech Review Platform',
                'description' => $content?->meta_description ?? 'Learn about Git Infosys — the team, mission, and values behind Nepal\'s leading tech review, gadget comparison, and price tracking platform.',
                'canonical'   => route('pages.about'),
                'type'        => 'website',
            ],
        ]));
    }

    public function priceTracker(Request $request)
    {
        $selectedCategory = $request->query('category', 'all');
        $selectedFilter   = $request->query('filter', 'all'); // all, hot, dropped, increased, stable
        $selectedSort     = $request->query('sort', 'hot_drops'); // hot_drops, biggest_amount, latest, price_low, price_high
        $search           = trim($request->query('search', ''));

        // 1. Fetch categories with counts of tracked gadgets
        $categories = Category::withCount(['gadgets' => function ($q) {
            $q->whereNotNull('price_tracker_description')->orWhereHas('priceHistory');
        }])->having('gadgets_count', '>', 0)->get(['id', 'name', 'slug', 'gadgets_count']);

        // 2. Query all tracked gadgets
        $query = Gadget::with(['brand', 'category', 'priceHistory'])
            ->where(function ($q) {
                $q->whereNotNull('price_tracker_description')
                  ->orWhereHas('priceHistory');
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('brand', fn($b) => $b->where('name', 'like', "%{$search}%"));
            });
        }

        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->whereHas('category', fn($c) => $c->where('slug', $selectedCategory));
        }

        $allTrackedGadgets = $query->latest()->get();

        // 3. Compute price tracking metrics for each gadget
        $processed = $allTrackedGadgets->map(function ($gadget) {
            $history = $gadget->priceHistory->sortBy('date')->values();
            $currentPrice = (float) $gadget->price;

            if ($gadget->old_price && (float)$gadget->old_price > 0 && (float)$gadget->old_price != $currentPrice) {
                $previousPrice = (float) $gadget->old_price;
            } elseif ($history->count() >= 2) {
                $previousPrice = (float) $history[$history->count() - 2]->price;
            } elseif ($history->count() == 1) {
                $previousPrice = (float) $history[0]->price;
            } else {
                $previousPrice = $currentPrice;
            }

            $diff = $currentPrice - $previousPrice;
            $diffAmount = abs($diff);
            $percent = $previousPrice > 0 ? round(($diffAmount / $previousPrice) * 100, 1) : 0;

            $trend = 'Stable';
            if ($diff < -0.01) {
                $trend = 'Dropped';
            } elseif ($diff > 0.01) {
                $trend = 'Increased';
            }

            // Hot change: heavy price drop (>= 20% drop OR >= NPR 10,000 drop OR marked trending with a drop)
            $isHotChange = ($trend === 'Dropped') && ($percent >= 20 || $diffAmount >= 10000 || ($gadget->is_trending && $percent >= 5));

            $lastChangedDate = null;
            if ($history->count()) {
                $lastChangedDate = $history->last()->date ? $history->last()->date->format('M d, Y') : null;
            }

            $gadget->current_price      = $currentPrice;
            $gadget->previous_price     = $previousPrice;
            $gadget->price_diff         = $diff;
            $gadget->price_diff_amount  = $diffAmount;
            $gadget->price_diff_percent = $percent;
            $gadget->trend              = $trend;
            $gadget->is_hot_change      = $isHotChange;
            $gadget->last_changed_date  = $lastChangedDate ?? $gadget->updated_at?->format('M d, Y');
            $gadget->buy_url            = $gadget->referral_buy_url;
            $gadget->history_points     = $history->map(fn($h) => [
                'date'  => $h->date ? $h->date->format('M Y') : '',
                'price' => (float)$h->price,
            ])->toArray();

            return $gadget;
        });

        // 4. Calculate Aggregate Stats
        $totalTracked       = $processed->count();
        $hotDropsCount      = $processed->where('is_hot_change', true)->count();
        $totalDropsCount    = $processed->where('trend', 'Dropped')->count();
        $maxDiscountPercent = $processed->max('price_diff_percent') ?? 0;
        $totalSavings       = $processed->where('trend', 'Dropped')->sum('price_diff_amount');

        // Top 4 hot drops for featured spotlight banner
        $topHotDrops = $processed->filter(fn($g) => $g->is_hot_change)
            ->sortByDesc('price_diff_percent')
            ->take(4)
            ->values();

        // 5. Apply Status/Change Filter
        $filtered = $processed;
        if ($selectedFilter === 'hot') {
            $filtered = $filtered->filter(fn($g) => $g->is_hot_change);
        } elseif ($selectedFilter === 'dropped') {
            $filtered = $filtered->filter(fn($g) => $g->trend === 'Dropped');
        } elseif ($selectedFilter === 'increased') {
            $filtered = $filtered->filter(fn($g) => $g->trend === 'Increased');
        } elseif ($selectedFilter === 'stable') {
            $filtered = $filtered->filter(fn($g) => $g->trend === 'Stable');
        }

        // 6. Apply Sorting
        if ($selectedSort === 'hot_drops') {
            $filtered = $filtered->sort(function ($a, $b) {
                if ($a->is_hot_change !== $b->is_hot_change) {
                    return $b->is_hot_change <=> $a->is_hot_change;
                }
                return $b->price_diff_percent <=> $a->price_diff_percent;
            });
        } elseif ($selectedSort === 'biggest_amount') {
            $filtered = $filtered->sortByDesc('price_diff_amount');
        } elseif ($selectedSort === 'price_low') {
            $filtered = $filtered->sortBy('current_price');
        } elseif ($selectedSort === 'price_high') {
            $filtered = $filtered->sortByDesc('current_price');
        } elseif ($selectedSort === 'latest') {
            $filtered = $filtered->sortByDesc('updated_at');
        }

        $trendingGadgets = Gadget::with('brand')->where('is_trending', true)->take(5)->get();

        return Inertia::render('Pages/PriceTracker', array_merge($this->sidebarData(), [
            'heading'           => 'Nepal Gadget Price Tracker',
            'subheading'        => 'Track real-time market price revisions, heavy drop alerts, and official price cuts in Nepal.',
            'gadgets'           => $filtered->values(),
            'categories'        => $categories,
            'selectedCategory'  => $selectedCategory,
            'selectedFilter'    => $selectedFilter,
            'selectedSort'      => $selectedSort,
            'search'            => $search,
            'stats'             => [
                'total_tracked'        => $totalTracked,
                'hot_drops_count'      => $hotDropsCount,
                'total_drops_count'    => $totalDropsCount,
                'max_discount_percent' => $maxDiscountPercent,
                'total_savings'        => $totalSavings,
            ],
            'topHotDrops'       => $topHotDrops,
            'trending'          => $trendingGadgets,
            'seo'               => [
                'title'       => 'Price Tracker — Monitor Gadget Prices & Heavy Drops in Nepal',
                'description' => 'Track the latest smartphone, laptop, and gadget price drops in Nepal with live market analytics and official buying links.',
                'canonical'   => route('pages.price-tracker'),
            ],
        ]));
    }

    public function contact()
    {
        $content = PageContent::forPage('contact');
        $extra   = $content?->extra ?? [];

        $mapLat = $extra['map_lat'] ?? '27.7172';
        $mapLng = $extra['map_lng'] ?? '85.3240';

        $mapEmbed = $extra['map_embed']
            ?? "https://www.openstreetmap.org/export/embed.html?bbox=" . ($mapLng - 0.04) . "%2C" . ($mapLat - 0.03) . "%2C" . ($mapLng + 0.04) . "%2C" . ($mapLat + 0.03) . "&layer=mapnik&marker={$mapLat}%2C{$mapLng}";

        return Inertia::render('Pages/Contact', array_merge($this->sidebarData(), [
            'heading'    => $content?->heading    ?? 'Get In Touch',
            'subheading' => $content?->subheading ?? "Have a question, feedback, or partnership inquiry? We'd love to hear from you.",
            'address'    => $extra['address'] ?? 'Kathmandu, Nepal',
            'email'      => $extra['email']   ?? 'info@gitinfosys.com',
            'phone'      => $extra['phone']   ?? '+977 000 000 000',
            'hours'      => $extra['hours']   ?? 'Sun – Fri: 9 AM – 6 PM',
            'mapEmbed'   => $mapEmbed,
            'seo'        => [
                'title'       => 'Contact Git Infosys — Get in Touch',
                'description' => $content?->meta_description ?? "Have a question, review request, or partnership proposal? Contact the Git Infosys team and we'll get back to you shortly.",
                'canonical'   => route('pages.contact'),
                'noindex'     => false,
            ],
        ]));
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'required|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        $message = ContactMessage::create($validated);

        try {
            Mail::to(config('mail.from.address', 'owner@gitinfosys.com'))->send(new ContactMessageMail($message));
        } catch (\Exception $e) {
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return back()->with('success', 'Your message has been sent. We\'ll get back to you soon!');
    }

    public function services()
    {
        $content = PageContent::forPage('services');

        return Inertia::render('Pages/Services', array_merge($this->sidebarData(), [
            'heading'    => $content?->heading    ?? 'Our Platform Services',
            'subheading' => $content?->subheading ?? "Git Infosys is Nepal's ultimate tech ecosystem. We offer an integrated suite of tools, reviews, and shopping experiences to make your tech life smarter.",
            'body'       => $content?->body,
            'seo'        => [
                'title'       => 'Our Services — Git Infosys',
                'description' => $content?->meta_description ?? "Explore what Git Infosys offers — gadget reviews, price comparison, buying guides, sponsored content, and more for Nepal's tech community.",
                'canonical'   => route('pages.services'),
            ],
        ]));
    }

    public function terms()
    {
        $content = PageContent::forPage('terms');

        return Inertia::render('Pages/Terms', array_merge($this->sidebarData(), [
            'heading'    => $content?->heading    ?? 'Terms & Conditions',
            'subheading' => $content?->subheading ?? 'Please read these terms carefully before using our platform.',
            'body'       => $content?->body,
            'seo'        => [
                'title'       => 'Terms & Conditions — Git Infosys',
                'description' => $content?->meta_description ?? 'Read the terms and conditions governing your use of the Git Infosys website, products, and services.',
                'canonical'   => route('pages.terms'),
                'noindex'     => true,
            ],
        ]));
    }

    public function privacy()
    {
        $content = PageContent::forPage('privacy');

        return Inertia::render('Pages/Privacy', array_merge($this->sidebarData(), [
            'heading'    => $content?->heading    ?? 'Privacy Policy',
            'subheading' => $content?->subheading ?? 'Your privacy is important to us. This policy explains how we collect and use your data.',
            'body'       => $content?->body,
            'seo'        => [
                'title'       => 'Privacy Policy — Git Infosys',
                'description' => $content?->meta_description ?? 'Understand how Git Infosys collects, uses, and protects your personal information when you use our platform.',
                'canonical'   => route('pages.privacy'),
                'noindex'     => true,
            ],
        ]));
    }

    public function techLab()
    {
        return Inertia::render('Pages/TechLab', array_merge($this->sidebarData(), [
            'seo' => [
                'title'       => 'Interactive Tech Lab & Nepal Ownership Radar — Git Infosys',
                'description' => 'Airport MDMS customs duty tax calculator, NTC/Ncell 5G band compatibility, blind camera shootouts, and gaming thermal simulator for Nepal tech buyers.',
                'canonical'   => route('pages.tech-lab'),
            ],
        ]));
    }
}

