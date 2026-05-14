<?php

namespace App\Http\Controllers;

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

    public function priceTracker()
    {
        // Get gadgets that have a price_tracker_description OR have price history
        $gadgets = Gadget::with(['brand', 'priceHistory'])
            ->whereNotNull('price_tracker_description')
            ->orWhereHas('priceHistory')
            ->latest()
            ->paginate(15);

        // Process trend for each gadget
        $gadgets->getCollection()->transform(function ($gadget) {
            $history = $gadget->priceHistory;
            $gadget->trend = 'Stable';
            if ($history->count() >= 2) {
                $latest = $history->last()->price;
                $previous = $history->first()->price;
                if ($latest < $previous) $gadget->trend = 'Dropped';
                if ($latest > $previous) $gadget->trend = 'Increased';
            }
            return $gadget;
        });

        $trendingGadgets = Gadget::with('brand')->where('is_trending', true)->take(5)->get();

        return Inertia::render('Pages/PriceTracker', array_merge($this->sidebarData(), [
            'heading'    => 'Price Tracker',
            'subheading' => 'Track latest price drops, hikes, and our editorial market insights.',
            'gadgets'    => $gadgets,
            'trending'   => $trendingGadgets,
            'seo'        => [
                'title'       => 'Price Tracker — Monitor Gadget Prices in Nepal',
                'description' => 'Track the latest price drops and hikes for smartphones and laptops in Nepal with expert insights.',
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
}
