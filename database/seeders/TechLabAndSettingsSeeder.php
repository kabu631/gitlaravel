<?php

namespace Database\Seeders;

use App\Models\AuthorizedServiceCenter;
use App\Models\BankPartner;
use App\Models\CameraShootout;
use App\Models\CarrierFrequencyBand;
use App\Models\Gadget;
use App\Models\PageContent;
use App\Models\RivalMatchup;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class TechLabAndSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Camera Shootouts
        if (CameraShootout::count() === 0) {
            CameraShootout::create([
                'title'               => 'Kathmandu Night Street Test',
                'description'         => 'Patan Durbar Square ambient low-light, shadow noise reduction, and streetlamp highlight glare control.',
                'icon'                => 'Moon',
                'device_a_name'       => 'Apple iPhone 15 Pro Max',
                'device_a_specs'      => '48MP (1/1.28", 24mm, f/1.78, Sensor-shift OIS, 1.22µm)',
                'device_a_exif'       => 'f/1.78 · 1/25s · ISO 1250',
                'device_a_image'      => '/images/shootout/round1_sample_a.jpg',
                'device_a_device_image'=> '/storage/gadgets/iphone-15-pro.jpg',
                'phone_a_votes'       => 1420,
                'device_b_name'       => 'Samsung Galaxy S24 Ultra',
                'device_b_specs'      => '200MP (1/1.3", 24mm, f/1.7, Multi-directional PDAF, OIS)',
                'device_b_exif'       => 'f/1.70 · 1/20s · ISO 800',
                'device_b_image'      => '/images/shootout/round1_sample_b.jpg',
                'device_b_device_image'=> '/storage/gadgets/samsung-galaxy-s24.jpg',
                'phone_b_votes'       => 985,
                'winner_summary'      => 'Device A (iPhone 15 Pro Max) won with 59% of votes!',
                'editorial_deep_dive' => 'Device A exhibited cleaner lens glare suppression with its nanocoating, while Device B produced sharper brick texture at the expense of slight sharpening halos around temple carvings.',
                'is_active'           => true,
                'sort_order'          => 1,
            ]);

            CameraShootout::create([
                'title'               => 'Daylight Portrait & Skin Tones',
                'description'         => 'Natural outdoor sun exposure testing authentic skin complexion without oversaturating or synthetic smoothing.',
                'icon'                => 'Sun',
                'device_a_name'       => 'Apple iPhone 15 Pro Max',
                'device_a_specs'      => '48MP Photonic Engine with Smart HDR 5',
                'device_a_exif'       => 'f/1.78 · 1/640s · ISO 64',
                'device_a_image'      => '/images/shootout/round2_sample_a.jpg',
                'device_a_device_image'=> '/storage/gadgets/iphone-15-pro.jpg',
                'phone_a_votes'       => 1102,
                'device_b_name'       => 'Samsung Galaxy S24 Ultra',
                'device_b_specs'      => '200MP ProVisual Engine with AI ISP',
                'device_b_exif'       => 'f/1.70 · 1/750s · ISO 50',
                'device_b_image'      => '/images/shootout/round2_sample_b.jpg',
                'device_b_device_image'=> '/storage/gadgets/samsung-galaxy-s24.jpg',
                'phone_b_votes'       => 1240,
                'winner_summary'      => 'Device B (Galaxy S24 Ultra) won with 53% of votes!',
                'editorial_deep_dive' => 'Device B provided slightly warmer color temperature favored by Nepali readers, with hair edge separation rendered seamlessly through neural depth maps.',
                'is_active'           => true,
                'sort_order'          => 2,
            ]);

            CameraShootout::create([
                'title'               => '10x Telephoto Text Legibility',
                'description'         => 'Zoom clarity reading distant commercial shop signs across New Road from a 120-meter distance.',
                'icon'                => 'Eye',
                'device_a_name'       => 'Apple iPhone 15 Pro Max',
                'device_a_specs'      => '12MP 5x Telephoto (120mm, f/2.8, 3D Sensor-shift OIS)',
                'device_a_exif'       => 'f/2.80 · 1/120s · ISO 200',
                'device_a_image'      => '/images/shootout/round3_sample_a.jpg',
                'device_a_device_image'=> '/storage/gadgets/iphone-15-pro.jpg',
                'phone_a_votes'       => 680,
                'device_b_name'       => 'Samsung Galaxy S24 Ultra',
                'device_b_specs'      => '50MP 5x Periscope (111mm, f/3.4, Dual Pixel PDAF, OIS)',
                'device_b_exif'       => 'f/3.40 · 1/160s · ISO 125',
                'device_b_image'      => '/images/shootout/round3_sample_b.jpg',
                'device_b_device_image'=> '/storage/gadgets/samsung-galaxy-s24.jpg',
                'phone_b_votes'       => 1664,
                'winner_summary'      => 'Device B (Galaxy S24 Ultra 5x/10x Periscope) dominated with 71% of votes!',
                'editorial_deep_dive' => 'The 50MP 5x optical periscope lens on Device B utilizes pixel binning to create superior 10x hybrid lossless imagery compared to 12MP 5x quad-prism sensor.',
                'is_active'           => true,
                'sort_order'          => 3,
            ]);
        }

        // 2. Seed Partner Commercial Banks
        $banks = [
            ['name' => 'Nabil Bank', 'slug' => 'nabil', 'supported_tenures' => [3, 6, 9, 12, 18], 'min_downpayment_percent' => 0.00, 'sort_order' => 1],
            ['name' => 'NIC Asia Bank', 'slug' => 'nic', 'supported_tenures' => [3, 6, 9, 12, 18], 'min_downpayment_percent' => 10.00, 'sort_order' => 2],
            ['name' => 'Global IME Bank', 'slug' => 'global', 'supported_tenures' => [6, 12, 18], 'min_downpayment_percent' => 0.00, 'sort_order' => 3],
            ['name' => 'Himalayan Bank', 'slug' => 'hbl', 'supported_tenures' => [3, 6, 12], 'min_downpayment_percent' => 0.00, 'sort_order' => 4],
            ['name' => 'NMB Bank', 'slug' => 'nmb', 'supported_tenures' => [6, 12, 18], 'min_downpayment_percent' => 10.00, 'sort_order' => 5],
            ['name' => 'Standard Chartered', 'slug' => 'scb', 'supported_tenures' => [3, 6, 12], 'min_downpayment_percent' => 15.00, 'sort_order' => 6],
        ];

        foreach ($banks as $b) {
            BankPartner::updateOrCreate(['slug' => $b['slug']], array_merge($b, [
                'processing_fee_percent' => 0.00,
                'terms_note'             => 'Requires an active credit card. Approval terms subject to bank guidelines.',
                'is_active'              => true,
            ]));
        }

        // 3. Seed Carrier Frequency Bands
        $bands = [
            // NTC
            [
                'carrier'      => 'ntc',
                'code'         => 'Band n78 (3500MHz)',
                'technology'   => '5G Sub-6',
                'frequency'    => '3500 MHz (C-Band)',
                'role'         => 'High-density urban speed layer. Official NTC trial towers in Sundhara, Babarmaal & Chhauni.',
                'status_badge' => 'Trial Live',
                'sort_order'   => 1,
            ],
            [
                'carrier'      => 'ntc',
                'code'         => 'Band 3 (1800MHz)',
                'technology'   => '4G LTE FDD',
                'frequency'    => '1800 MHz (Band 3)',
                'role'         => 'Primary nationwide 4G LTE backbone across all 77 districts of Nepal.',
                'status_badge' => 'Commercial Primary',
                'sort_order'   => 2,
            ],
            [
                'carrier'      => 'ntc',
                'code'         => 'Band 8 (900MHz)',
                'technology'   => '4G LTE FDD',
                'frequency'    => '900 MHz (Band 8)',
                'role'         => 'Sub-1GHz rural coverage, deep indoor wall penetration, and highway connectivity.',
                'status_badge' => 'Commercial',
                'sort_order'   => 3,
            ],
            [
                'carrier'      => 'ntc',
                'code'         => 'Band 20 (800MHz)',
                'technology'   => '4G LTE FDD',
                'frequency'    => '800 MHz (Band 20)',
                'role'         => 'Ultra-rural mountain valley cellular propagation in remote geographic zones.',
                'status_badge' => 'Commercial',
                'sort_order'   => 4,
            ],
            // Ncell
            [
                'carrier'      => 'ncell',
                'code'         => 'Band 3 (1800MHz)',
                'technology'   => '4G LTE FDD',
                'frequency'    => '1800 MHz (Band 3)',
                'role'         => 'Primary Ncell 4G high-capacity metro coverage across major commercial cities.',
                'status_badge' => 'Commercial Primary',
                'sort_order'   => 5,
            ],
            [
                'carrier'      => 'ncell',
                'code'         => 'Band 8 (900MHz)',
                'technology'   => '4G LTE FDD',
                'frequency'    => '900 MHz (Band 8)',
                'role'         => 'Sub-1GHz coverage for reliable indoor building penetration and suburban reach.',
                'status_badge' => 'Commercial',
                'sort_order'   => 6,
            ],
            [
                'carrier'      => 'ncell',
                'code'         => 'Band 1 (2100MHz)',
                'technology'   => '4G / 3G',
                'frequency'    => '2100 MHz (Band 1)',
                'role'         => 'Supplementary high-bandwidth data layer refarmed from legacy 3G allocations.',
                'status_badge' => 'Commercial',
                'sort_order'   => 7,
            ],
        ];

        foreach ($bands as $band) {
            CarrierFrequencyBand::updateOrCreate(
                ['carrier' => $band['carrier'], 'code' => $band['code']],
                $band
            );
        }

        // 4. Seed Authorized Service Centers
        $serviceCenters = [
            [
                'brand'           => 'Apple',
                'name'            => 'Generation Next (Genxt) Apple Care',
                'address'         => 'Sherpa Mall, Durbar Marg, Kathmandu',
                'city'            => 'Kathmandu',
                'phone'           => '+977 1-4228945',
                'email'           => 'applecare@genxt.com.np',
                'avg_screen_cost' => 'Rs. 38,000 - 58,000 (OLED)',
                'is_authorized'   => true,
                'sort_order'      => 1,
            ],
            [
                'brand'           => 'Apple',
                'name'            => 'EvoStore Authorized Service',
                'address'         => 'Woodland Complex, Durbar Marg & Labim Mall, Lalitpur',
                'city'            => 'Kathmandu / Lalitpur',
                'phone'           => '+977 1-5536555',
                'email'           => 'support@evostore.com.np',
                'avg_screen_cost' => 'Rs. 35,000 - 55,000 (OLED)',
                'is_authorized'   => true,
                'sort_order'      => 2,
            ],
            [
                'brand'           => 'Samsung',
                'name'            => 'Samsung Plaza Central Service',
                'address'         => 'Opp. CTC Mall, Sundhara, Kathmandu',
                'city'            => 'Kathmandu',
                'phone'           => '+977 1660-01-72678',
                'email'           => 'service@samsungplaza.com.np',
                'avg_screen_cost' => 'Rs. 22,000 - 38,000 (Dynamic AMOLED)',
                'is_authorized'   => true,
                'sort_order'      => 3,
            ],
            [
                'brand'           => 'Xiaomi',
                'name'            => 'Xiaomi Official Service Center',
                'address'         => 'Tamrakar Complex, 5th Floor, New Road, Kathmandu',
                'city'            => 'Kathmandu',
                'phone'           => '+977 1-5325124',
                'email'           => 'service.np@xiaomi.com',
                'avg_screen_cost' => 'Rs. 6,500 - 18,000 (AMOLED)',
                'is_authorized'   => true,
                'sort_order'      => 4,
            ],
            [
                'brand'           => 'OnePlus',
                'name'            => 'OnePlus Nepal Service Center (Smart Talk)',
                'address'         => 'Pako, New Road, Kathmandu',
                'city'            => 'Kathmandu',
                'phone'           => '+977 1-4248888',
                'email'           => 'service@smarttalk.com.np',
                'avg_screen_cost' => 'Rs. 18,000 - 32,000 (Fluid AMOLED)',
                'is_authorized'   => true,
                'sort_order'      => 5,
            ],
        ];

        foreach ($serviceCenters as $sc) {
            AuthorizedServiceCenter::updateOrCreate(
                ['brand' => $sc['brand'], 'name' => $sc['name']],
                $sc
            );
        }

        // 5. Seed Rival Matchups (Linking to real gadgets if found)
        if (RivalMatchup::count() === 0) {
            $mobileA = Gadget::whereHas('category', fn($q) => $q->where('slug', 'mobile'))->orderBy('price', 'desc')->first();
            $mobileB = Gadget::whereHas('category', fn($q) => $q->where('slug', 'mobile'))->where('id', '!=', $mobileA?->id)->orderBy('price', 'desc')->first();

            if ($mobileA && $mobileB) {
                RivalMatchup::create([
                    'title'         => 'Ultra Flagship Smartphone Showdown',
                    'subtitle'      => 'Latest hot flagships compared head-to-head on Nepal pricing & benchmarks',
                    'category_slug' => 'mobile',
                    'device_a_id'   => $mobileA->id,
                    'device_b_id'   => $mobileB->id,
                    'metrics'       => [
                        ['label' => 'Performance & NPU', 'left_val' => '9.8 / 10', 'right_val' => '9.7 / 10', 'left_pct' => 52, 'right_pct' => 48],
                        ['label' => 'Camera & Optics', 'left_val' => '50MP Triple Array', 'right_val' => '48MP ProRAW', 'left_pct' => 50, 'right_pct' => 50],
                        ['label' => 'Battery & Fast Charge', 'left_val' => '5000mAh · 45W', 'right_val' => '4422mAh · 27W', 'left_pct' => 54, 'right_pct' => 46],
                        ['label' => 'Display & LTPO', 'left_val' => '120Hz Dynamic AMOLED', 'right_val' => '120Hz ProMotion', 'left_pct' => 50, 'right_pct' => 50],
                        ['label' => 'Nepal Value Score', 'left_val' => '9.4 / 10', 'right_val' => '9.1 / 10', 'left_pct' => 51, 'right_pct' => 49],
                    ],
                    'is_active'     => true,
                    'sort_order'    => 1,
                ]);
            }

            $laptopA = Gadget::whereHas('category', fn($q) => $q->where('slug', 'laptop'))->orderBy('price', 'desc')->first();
            $laptopB = Gadget::whereHas('category', fn($q) => $q->where('slug', 'laptop'))->where('id', '!=', $laptopA?->id)->orderBy('price', 'desc')->first();

            if ($laptopA && $laptopB) {
                RivalMatchup::create([
                    'title'         => 'Hot Ultrabook Showdown',
                    'subtitle'      => 'Top portable computing powerhouses evaluated for Nepali professionals & students',
                    'category_slug' => 'laptop',
                    'device_a_id'   => $laptopA->id,
                    'device_b_id'   => $laptopB->id,
                    'metrics'       => [
                        ['label' => 'CPU Architecture', 'left_val' => 'Intel Core Ultra', 'right_val' => 'Apple M3 Pro', 'left_pct' => 48, 'right_pct' => 52],
                        ['label' => 'Display Quality', 'left_val' => '3K Dynamic AMOLED', 'right_val' => 'Liquid Retina XDR', 'left_pct' => 50, 'right_pct' => 50],
                        ['label' => 'Battery Endurance', 'left_val' => '16 Hrs Productivity', 'right_val' => '22 Hrs All-Day', 'left_pct' => 45, 'right_pct' => 55],
                        ['label' => 'Build & Portability', 'left_val' => 'Ultra-thin 1.23kg', 'right_val' => 'Unibody 1.62kg', 'left_pct' => 55, 'right_pct' => 45],
                        ['label' => 'Nepal Value Score', 'left_val' => '9.2 / 10', 'right_val' => '9.0 / 10', 'left_pct' => 51, 'right_pct' => 49],
                    ],
                    'is_active'     => true,
                    'sort_order'    => 2,
                ]);
            }
        }

        // 6. Seed Global Site Settings
        $settings = [
            'announcement_enabled'       => 'true',
            'announcement_text'          => 'Nepal IT & Regulatory Radar: Airport MDMS customs calculator and 5G band radar are now live in Tech Lab!',
            'announcement_url'           => '/tech-lab',
            'announcement_badge'         => 'TECH LAB',
            'footer_phone'               => '+977-985-7039307',
            'footer_email'               => 'info@gitinfosys.com.np',
            'footer_address'             => 'Pako, New Road, Kathmandu - Nepal',
            'footer_hours'               => 'Sun – Fri: 10 AM – 8 PM',
            'footer_copyright'           => '© ' . date('Y') . ' Git Infosys. All rights reserved. Nepal\'s Premier Independent Tech Platform.',
            'social_facebook'            => 'https://facebook.com/gitinfosys',
            'social_instagram'           => 'https://instagram.com/gitinfosys',
            'social_twitter'             => 'https://twitter.com/gitinfosys',
            'social_youtube'             => 'https://youtube.com/@gitinfosys',
            'social_tiktok'              => 'https://tiktok.com/@gitinfosys',
            'cookie_consent_enabled'     => 'true',
            'cookie_consent_title'       => 'Cookies & Session Protection',
            'cookie_consent_message'     => 'We use cookies and active sessions to authenticate accounts, store device comparisons, manage your cart, and secure your session. Active sessions automatically end when your browser closes or system restarts.',
            'mdms_customs_duty_percent'  => '5',
            'mdms_vat_percent'           => '13',
            'mdms_nta_fee_standard'      => '3000',
            'mdms_nta_fee_luxury'        => '10000',
            'mdms_shramik_allowance_note'=> 'Labor Permit holders (Shramik) who have worked abroad for 6+ months with valid approval are eligible for 100% tax exemption on 1 phone.',
        ];

        foreach ($settings as $key => $val) {
            $group = match (true) {
                str_starts_with($key, 'announcement_') => 'announcement',
                str_starts_with($key, 'footer_')       => 'contact',
                str_starts_with($key, 'social_')       => 'social',
                str_starts_with($key, 'cookie_')       => 'cookie',
                str_starts_with($key, 'mdms_')         => 'mdms',
                default                                => 'general',
            };
            SiteSetting::set($key, $val, $group);
        }

        // 7. Seed PageContent for Services and About
        $servicesContent = PageContent::firstOrNew(['page' => 'services']);
        $servicesContent->heading = 'Our Platform Services';
        $servicesContent->subheading = "Git Infosys is Nepal's ultimate tech ecosystem. We offer an integrated suite of tools, reviews, and shopping experiences to make your tech life smarter.";
        $servicesContent->meta_description = "Explore what Git Infosys offers — gadget reviews, price comparison, buying guides, sponsored content, and more for Nepal's tech community.";
        $servicesContent->extra = [
            'services' => [
                [
                    'icon'     => '⭐',
                    'title'    => 'In-Depth Tech Reviews',
                    'desc'     => 'Honest, hands-on tests of the latest smartphones, laptops, and wearables. Our expert verdicts help you make confident buying decisions.',
                    'gradient' => 'linear-gradient(135deg, #6c5ce7, #4834d4)',
                ],
                [
                    'icon'     => '↔️',
                    'title'    => 'Gadget Comparisons',
                    'desc'     => 'Use our powerful comparison engine to put devices head-to-head. Analyze specs, benchmarks, and camera differences instantly.',
                    'gradient' => 'linear-gradient(135deg, #1877F2, #0A66C2)',
                ],
                [
                    'icon'     => '📈',
                    'title'    => 'Price & Specs Tracking',
                    'desc'     => 'Official Nepal MRP and market rates tracked regularly with historical data to know if you are getting a genuine deal.',
                    'gradient' => 'linear-gradient(135deg, #F56040, #C13584)',
                ],
                [
                    'icon'     => '📰',
                    'title'    => 'Daily Tech News',
                    'desc'     => 'Stay updated with blazing-fast coverage on the latest tech launches, leaks, and industry insights.',
                    'gradient' => 'linear-gradient(135deg, #00b894, #00cec9)',
                ],
                [
                    'icon'     => '🤝',
                    'title'    => 'Authorized Retail Guidance',
                    'desc'     => 'To preserve 100% editorial impartiality, we guide buyers to authorized distributors and certified retailers across Nepal for authentic hardware and official warranties.',
                    'gradient' => 'linear-gradient(135deg, #ff9f43, #ff6b6b)',
                ],
            ],
            'cta_heading' => 'Ready to upgrade your tech?',
            'cta_text'    => 'Join thousands of smart shoppers who trust Git Infosys for real-time reviews, competitive prices, and premium gadgets.',
        ];
        $servicesContent->save();

        $aboutContent = PageContent::firstOrNew(['page' => 'about']);
        $aboutContent->heading = "Nepal's Trusted Tech Platform";
        $aboutContent->subheading = 'We help Nepali consumers make smarter, more confident tech purchasing decisions.';
        $aboutContent->meta_description = 'Learn about Git Infosys — the team, mission, and values behind Nepal\'s leading tech review, gadget comparison, and price tracking platform.';
        $aboutContent->extra = array_merge($aboutContent->extra ?? [], [
            'mission' => "To be Nepal's most trusted source for gadget reviews, price comparisons, and tech news — empowering every buyer with unbiased, data-driven insights.",
            'vision'  => 'A Nepal where every consumer has access to transparent, up-to-date technology information and can shop with complete confidence.',
            'story'   => 'Git Infosys started as a passion project by a group of tech enthusiasts who were frustrated by the lack of reliable, localized tech information in Nepal.',
            'stats'   => [
                ['num' => '500+', 'label' => 'Products Reviewed'],
                ['num' => '50K+', 'label' => 'Monthly Visitors'],
                ['num' => '100+', 'label' => 'Expert Articles'],
                ['num' => '10K+', 'label' => 'Happy Shoppers'],
            ],
            'team'    => [
                ['name' => 'Kabindra Koirala', 'role' => 'Founder & Editor-in-Chief', 'bio' => 'Tech enthusiast passionate about making gadget buying decisions easier for Nepali consumers.'],
                ['name' => 'Tech Team', 'role' => 'Review Specialists', 'bio' => 'Our team of specialists rigorously test every product before publishing honest, unbiased reviews.'],
            ],
        ]);
        $aboutContent->save();
    }
}
