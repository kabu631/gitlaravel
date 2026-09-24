<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Popup;
use Illuminate\Database\Seeder;

class BlogAndPopupSeeder extends Seeder
{
    public function run(): void
    {
        Popup::updateOrCreate(['title' => 'Welcome to Git Infosys!'], [
            'badge'         => '🎉 Welcome Offer',
            'body'          => "Nepal's trusted place for gadget reviews, live price tracking and spec comparisons. Find the best deal on your next phone or laptop today.",
            'image'         => 'sliders/slider-1.jpg',
            'btn_text'      => 'Browse Latest Gadgets',
            'btn_url'       => '/products',
            'delay_seconds' => 2,
            'frequency'     => 'once_per_session',
            'is_active'     => true,
        ]);

        $posts = [
            [
                'title' => '5 Things to Check Before Buying a Smartphone in Nepal', 'category' => 'tips', 'featured' => true,
                'image' => 'news/article-1.jpg', 'tags' => ['smartphone', 'buying-guide', 'nepal'],
                'excerpt' => 'Warranty, MDMS registration, network bands and more — a quick checklist so you never overpay or get stuck with a locked phone.',
                'content' => '<p>Buying a phone in Nepal is different from buying one abroad. Before you pay, run through this checklist.</p><h2>1. Check MDMS registration</h2><p>Every imported phone must be registered with the Mobile Device Management System. Ask the seller for proof, or verify the IMEI yourself.</p><h2>2. Confirm the official warranty</h2><p>Buy from an authorized distributor so the warranty is valid at service centers in Nepal.</p><h2>3. Look at network bands</h2><p>Make sure the phone supports the 4G/5G bands used by Ncell and Nepal Telecom.</p><h2>4. Compare prices</h2><p>Use our price tracker to see whether the current price is a real discount.</p><h2>5. Inspect the box</h2><p>Check that the seal, charger and accessories match the region and model.</p>',
            ],
            [
                'title' => 'How to Make Your Laptop Battery Last Longer', 'category' => 'how-to', 'featured' => false,
                'image' => 'news/article-2.jpg', 'tags' => ['laptop', 'battery', 'tips'],
                'excerpt' => 'Simple settings and habits that can add hours to your laptop battery and keep it healthy for years.',
                'content' => '<p>A few small changes can noticeably extend both daily battery life and overall battery lifespan.</p><h2>Lower screen brightness</h2><p>The display is usually the biggest power draw. Drop brightness to a comfortable minimum.</p><h2>Use battery saver mode</h2><p>Enable your OS power saver so background apps and CPU boost are limited.</p><h2>Avoid heat</h2><p>Heat degrades lithium batteries fastest. Keep vents clear and avoid using the laptop on soft surfaces.</p><h2>Do not keep it at 100% all day</h2><p>If your laptop has a charge limit option, set it to 80% when plugged in most of the time.</p>',
            ],
            [
                'title' => 'Earbuds vs Headphones: Which Should You Buy?', 'category' => 'opinion', 'featured' => false,
                'image' => 'news/article-3.jpg', 'tags' => ['audio', 'earbuds', 'headphones'],
                'excerpt' => 'Portability or sound quality? We break down who should pick earbuds and who is better off with over-ear headphones.',
                'content' => '<p>Both have their place — the right choice depends on how and where you listen.</p><h2>Choose earbuds if…</h2><p>You commute, exercise, or want something that fits in a pocket. Modern ANC earbuds are surprisingly good.</p><h2>Choose headphones if…</h2><p>You listen for hours at a desk and value comfort, bigger drivers and longer battery life.</p><h2>Our take</h2><p>For most people in Nepal, a mid-range pair of ANC earbuds covers 90% of daily use. Keep headphones for dedicated listening.</p>',
            ],
            [
                'title' => 'Dashain & Tihar Tech Deals: How to Spot a Real Discount', 'category' => 'deals', 'featured' => false,
                'image' => 'news/article-4.jpg', 'tags' => ['deals', 'festival', 'price-tracker'],
                'excerpt' => 'Festival season means big banners — but not every "50% off" is real. Here is how to check before you buy.',
                'content' => '<p>Every festival season, shops advertise huge discounts. Some are genuine, many are not.</p><h2>Check the price history</h2><p>Our price tracker shows how a gadget\'s price has moved, so you can see if the "old price" was ever real.</p><h2>Compare across sellers</h2><p>Look at at least three authorized sellers before deciding.</p><h2>Read the fine print</h2><p>Bundle offers and bank discounts often have conditions. Make sure the warranty stays valid.</p>',
            ],
        ];

        foreach ($posts as $i => $p) {
            BlogPost::updateOrCreate(['title' => $p['title']], [
                'excerpt'      => $p['excerpt'],
                'content'      => $p['content'],
                'cover_image'  => $p['image'],
                'category'     => $p['category'],
                'tags'         => $p['tags'],
                'is_published' => true,
                'is_featured'  => $p['featured'],
                'published_at' => now()->subDays(($i + 1) * 2),
            ]);
        }
    }
}
