<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\GadgetVariant;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\Slider;
use App\Models\SpecSheet;
use App\Models\TechGuide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PageContentSeeder::class);

        // ── Categories ────────────────────────────────────────────────────
        $categoryDefs = [
            ['name' => 'Mobile',     'slug' => 'mobile'],
            ['name' => 'Laptop',     'slug' => 'laptop'],
            ['name' => 'Earbuds',    'slug' => 'earbuds'],
            ['name' => 'Smartwatch', 'slug' => 'smartwatch'],
            ['name' => 'Accessory',  'slug' => 'accessory'],
        ];
        foreach ($categoryDefs as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        // ── Brands ────────────────────────────────────────────────────────
        $brandNames = [
            'Samsung', 'Apple', 'Xiaomi', 'OnePlus', 'OPPO',
            'Realme',  'Sony',  'HP',     'Dell',    'JBL',
            'boAt',    'Noise',
        ];
        $brands = [];
        foreach ($brandNames as $name) {
            $brands[$name] = Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        // Brand → Category associations
        $mobile     = Category::where('slug', 'mobile')->first();
        $laptop     = Category::where('slug', 'laptop')->first();
        $earbudsC   = Category::where('slug', 'earbuds')->first();
        $smartwatch = Category::where('slug', 'smartwatch')->first();
        $accessory  = Category::where('slug', 'accessory')->first();

        $brandCategories = [
            'Samsung' => [$mobile, $laptop, $smartwatch],
            'Apple'   => [$mobile, $laptop, $earbudsC, $smartwatch],
            'Xiaomi'  => [$mobile, $earbudsC, $smartwatch, $accessory],
            'OnePlus' => [$mobile, $earbudsC],
            'OPPO'    => [$mobile, $earbudsC],
            'Realme'  => [$mobile, $earbudsC, $smartwatch],
            'Sony'    => [$mobile, $earbudsC, $laptop],
            'HP'      => [$laptop, $accessory],
            'Dell'    => [$laptop, $accessory],
            'JBL'     => [$earbudsC, $accessory],
            'boAt'    => [$earbudsC, $smartwatch, $accessory],
            'Noise'   => [$earbudsC, $smartwatch],
        ];
        foreach ($brandCategories as $brandName => $cats) {
            $brand = $brands[$brandName];
            $catIds = collect($cats)->pluck('id')->toArray();
            $brand->categories()->syncWithoutDetaching($catIds);
        }

        // ── AccessoryTypes ────────────────────────────────────────────────
        $accessoryTypes = ['Earbuds', 'Headphones', 'Charger', 'Phone Case', 'USB Cable'];
        foreach ($accessoryTypes as $name) {
            DB::table('accessory_types')->insertOrIgnore([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Sliders ───────────────────────────────────────────────────────
        if (Slider::count() === 0) {
            $sliders = [
                [
                    'title'       => 'Samsung Galaxy S24 Ultra',
                    'subtitle'    => 'Experience the Future of Mobile Photography',
                    'description' => 'The most powerful Galaxy ever. With the built-in S Pen and 200MP camera, capture every detail in stunning clarity.',
                    'badge'       => 'New Arrival',
                    'btn1_text'   => 'Shop Now',
                    'btn1_url'    => '/gadgets',
                    'btn1_style'  => 'primary',
                    'btn2_text'   => 'Learn More',
                    'btn2_url'    => '/gadgets',
                    'btn2_style'  => 'dark',
                    'is_active'   => true,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'MacBook Air M3',
                    'subtitle'    => 'Supercharged by Apple Silicon',
                    'description' => "The world's thinnest laptop. Up to 18 hours of battery life and blazing-fast M3 chip performance.",
                    'badge'       => 'Best Seller',
                    'btn1_text'   => 'Explore Laptops',
                    'btn1_url'    => '/gadgets?category=laptop',
                    'btn1_style'  => 'primary',
                    'btn2_text'   => 'Compare',
                    'btn2_url'    => '/compare',
                    'btn2_style'  => 'dark',
                    'is_active'   => true,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Price Tracker — Never Overpay Again',
                    'subtitle'    => "Real-Time Price Monitoring for Nepal's Tech Market",
                    'description' => 'Track price drops across all major gadgets. Set alerts and buy at the perfect moment.',
                    'badge'       => 'Free Feature',
                    'btn1_text'   => 'Track Prices',
                    'btn1_url'    => '/gadgets',
                    'btn1_style'  => 'primary',
                    'btn2_text'   => 'Browse All',
                    'btn2_url'    => '/gadgets',
                    'btn2_style'  => 'dark',
                    'is_active'   => true,
                    'sort_order'  => 3,
                ],
                [
                    'title'       => 'AI-Powered Gadget Comparison',
                    'subtitle'    => 'Compare. Analyze. Decide with Confidence.',
                    'description' => 'Our AI compares up to 4 devices side by side and gives you a personalized buying recommendation.',
                    'badge'       => 'AI Feature',
                    'btn1_text'   => 'Try Compare',
                    'btn1_url'    => '/compare',
                    'btn1_style'  => 'primary',
                    'btn2_text'   => 'Browse Gadgets',
                    'btn2_url'    => '/gadgets',
                    'btn2_style'  => 'dark',
                    'is_active'   => true,
                    'sort_order'  => 4,
                ],
            ];
            foreach ($sliders as $s) {
                Slider::create($s);
            }
        }

        // ── Regular Users ─────────────────────────────────────────────────
        $regularUsers = [
            ['name' => 'Ramesh Thapa',  'email' => 'ramesh@example.com'],
            ['name' => 'Sita Sharma',   'email' => 'sita@example.com'],
            ['name' => 'Bikash Karki',  'email' => 'bikash@example.com'],
        ];
        foreach ($regularUsers as $u) {
            User::firstOrCreate(['email' => $u['email']], [
                'name'               => $u['name'],
                'password'           => Hash::make('password'),
                'is_admin'           => false,
                'email_verified_at'  => now(),
            ]);
        }

        // Ensure admin exists
        $admin = User::firstOrCreate(['email' => 'admin@gitinfosys.com'], [
            'name'              => 'Admin',
            'password'          => Hash::make('Admin@1234'),
            'is_admin'          => true,
            'email_verified_at' => now(),
        ]);

        // ── Gadgets ───────────────────────────────────────────────────────
        if (Gadget::count() === 0) {
            $gadgetData = [
                // ── Mobiles ──────────────────────────────────────
                [
                    'brand' => 'Samsung', 'category' => $mobile,
                    'name'  => 'Samsung Galaxy S24',
                    'price' => 89999, 'old_price' => 99999,
                    'is_trending' => true,  'is_featured' => true,
                    'release_date' => '2024-01-17',
                    'description'  => 'The Samsung Galaxy S24 packs a Snapdragon 8 Gen 3 processor and a 50MP triple camera system into a sleek 6.2-inch display. Ideal for power users in Nepal.',
                    'specs' => [
                        'display'      => '6.2-inch Dynamic AMOLED 2X, 2340×1080, 120Hz',
                        'processor'    => 'Snapdragon 8 Gen 3',
                        'ram'          => '8GB',
                        'storage'      => '256GB',
                        'battery'      => '4000mAh, 25W Fast Charging',
                        'camera'       => '50MP + 10MP + 12MP | 12MP Selfie',
                        'os'           => 'Android 14, One UI 6.1',
                        'connectivity' => '5G, Wi-Fi 6E, Bluetooth 5.3, NFC',
                        'weight'       => '167g',
                        'dimensions'   => '147 × 70.6 × 7.6 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Onyx Black',   'stock' => 15],
                        ['variant_type' => 'color',   'value' => 'Marble Gray',  'stock' => 10],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 89999,  'stock' => 20],
                        ['variant_type' => 'storage', 'value' => '512GB', 'price' => 104999, 'stock' => 8],
                    ],
                ],
                [
                    'brand' => 'Apple', 'category' => $mobile,
                    'name'  => 'iPhone 15 Pro',
                    'price' => 149999, 'old_price' => 164999,
                    'is_trending' => true, 'is_featured' => true,
                    'release_date' => '2023-09-22',
                    'description'  => 'The iPhone 15 Pro features the powerful A17 Pro chip, titanium design, and a 48MP main camera with Action button. A premium experience for serious users.',
                    'specs' => [
                        'display'      => '6.1-inch Super Retina XDR OLED, 2556×1179, 120Hz ProMotion',
                        'processor'    => 'Apple A17 Pro',
                        'ram'          => '8GB',
                        'storage'      => '256GB',
                        'battery'      => '3274mAh, 27W Fast Charging, MagSafe',
                        'camera'       => '48MP + 12MP + 12MP | 12MP TrueDepth Selfie',
                        'os'           => 'iOS 17',
                        'connectivity' => '5G, Wi-Fi 6E, Bluetooth 5.3, NFC, USB-C',
                        'weight'       => '187g',
                        'dimensions'   => '146.6 × 70.6 × 8.25 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Natural Titanium', 'stock' => 10],
                        ['variant_type' => 'color',   'value' => 'Black Titanium',   'stock' => 8],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 149999, 'stock' => 12],
                        ['variant_type' => 'storage', 'value' => '512GB', 'price' => 174999, 'stock' => 5],
                    ],
                ],
                [
                    'brand' => 'Xiaomi', 'category' => $mobile,
                    'name'  => 'Xiaomi 14',
                    'price' => 74999, 'old_price' => 82999,
                    'is_trending' => true, 'is_featured' => false,
                    'release_date' => '2024-02-25',
                    'description'  => 'Xiaomi 14 brings Leica co-engineered cameras and Snapdragon 8 Gen 3 performance to the mid-premium segment at an aggressive price point.',
                    'specs' => [
                        'display'      => '6.36-inch LTPO AMOLED, 2670×1200, 120Hz',
                        'processor'    => 'Snapdragon 8 Gen 3',
                        'ram'          => '12GB',
                        'storage'      => '256GB',
                        'battery'      => '4610mAh, 90W HyperCharge',
                        'camera'       => '50MP Leica + 50MP + 50MP | 32MP Selfie',
                        'os'           => 'Android 14, HyperOS',
                        'connectivity' => '5G, Wi-Fi 7, Bluetooth 5.4, NFC, USB-C',
                        'weight'       => '193g',
                        'dimensions'   => '152.8 × 71.5 × 8.2 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Black', 'stock' => 20],
                        ['variant_type' => 'color',   'value' => 'White', 'stock' => 15],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 74999, 'stock' => 25],
                        ['variant_type' => 'storage', 'value' => '512GB', 'price' => 89999, 'stock' => 10],
                    ],
                ],
                [
                    'brand' => 'OnePlus', 'category' => $mobile,
                    'name'  => 'OnePlus 12',
                    'price' => 79999, 'old_price' => 87999,
                    'is_trending' => false, 'is_featured' => true,
                    'release_date' => '2024-01-23',
                    'description'  => 'The OnePlus 12 features a Hasselblad-tuned 50MP periscope zoom camera, 100W SUPERVOOC charging, and a 6.82-inch 2K ProXDR display.',
                    'specs' => [
                        'display'      => '6.82-inch LTPO AMOLED, 3168×1440, 1–120Hz',
                        'processor'    => 'Snapdragon 8 Gen 3',
                        'ram'          => '12GB',
                        'storage'      => '256GB',
                        'battery'      => '5400mAh, 100W SUPERVOOC, 50W AirVOOC',
                        'camera'       => '50MP Hasselblad + 48MP + 64MP | 32MP Selfie',
                        'os'           => 'Android 14, OxygenOS 14',
                        'connectivity' => '5G, Wi-Fi 7, Bluetooth 5.4, NFC, USB-C',
                        'weight'       => '220g',
                        'dimensions'   => '164.3 × 75.8 × 9.15 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Silky Black',    'stock' => 18],
                        ['variant_type' => 'color',   'value' => 'Flowy Emerald',  'stock' => 12],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 79999, 'stock' => 20],
                        ['variant_type' => 'storage', 'value' => '512GB', 'price' => 94999, 'stock' => 6],
                    ],
                ],
                [
                    'brand' => 'OPPO', 'category' => $mobile,
                    'name'  => 'OPPO Reno 11 Pro',
                    'price' => 62999, 'old_price' => 69999,
                    'is_trending' => false, 'is_featured' => false,
                    'release_date' => '2024-01-10',
                    'description'  => 'OPPO Reno 11 Pro features a 50MP triple camera system, 80W SUPERVOOC fast charging, and a premium curved-glass design with MediaTek Dimensity 8200.',
                    'specs' => [
                        'display'      => '6.74-inch LTPO AMOLED, 2772×1240, 120Hz',
                        'processor'    => 'MediaTek Dimensity 8200',
                        'ram'          => '12GB',
                        'storage'      => '256GB',
                        'battery'      => '4600mAh, 80W SUPERVOOC',
                        'camera'       => '50MP Sony IMX890 + 32MP Telephoto + 8MP | 32MP Selfie',
                        'os'           => 'Android 14, ColorOS 14',
                        'connectivity' => '5G, Wi-Fi 6, Bluetooth 5.3, NFC',
                        'weight'       => '185g',
                        'dimensions'   => '161.6 × 74.2 × 8.1 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Rock Gray',        'stock' => 15],
                        ['variant_type' => 'color',   'value' => 'Misty Lavender',   'stock' => 12],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 62999, 'stock' => 18],
                    ],
                ],
                [
                    'brand' => 'Realme', 'category' => $mobile,
                    'name'  => 'Realme 12 Pro+',
                    'price' => 44999, 'old_price' => 49999,
                    'is_trending' => true, 'is_featured' => false,
                    'release_date' => '2024-02-01',
                    'description'  => 'Realme 12 Pro+ packs a 50MP Sony IMX890 OIS camera with 3x optical zoom, 67W SUPERVOOC charging, and a curved AMOLED display at an impressive mid-range price.',
                    'specs' => [
                        'display'      => '6.7-inch Curved AMOLED, 2412×1080, 120Hz',
                        'processor'    => 'Snapdragon 7s Gen 2',
                        'ram'          => '8GB',
                        'storage'      => '256GB',
                        'battery'      => '5000mAh, 67W SUPERVOOC',
                        'camera'       => '50MP Sony IMX890 OIS + 64MP 3× Periscope + 8MP | 32MP Selfie',
                        'os'           => 'Android 14, Realme UI 5.0',
                        'connectivity' => '5G, Wi-Fi 6, Bluetooth 5.3, NFC',
                        'weight'       => '190g',
                        'dimensions'   => '161.5 × 74 × 8.8 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Submarine Blue', 'stock' => 20],
                        ['variant_type' => 'color',   'value' => 'Pearl White',    'stock' => 15],
                        ['variant_type' => 'storage', 'value' => '256GB', 'price' => 44999, 'stock' => 22],
                    ],
                ],
                // ── Laptops ──────────────────────────────────────
                [
                    'brand' => 'Samsung', 'category' => $laptop,
                    'name'  => 'Samsung Galaxy Book4 Pro',
                    'price' => 149999, 'old_price' => 159999,
                    'is_trending' => false, 'is_featured' => true,
                    'release_date' => '2024-03-01',
                    'description'  => 'The Galaxy Book4 Pro is an ultra-slim laptop powered by Intel Core Ultra, featuring a stunning 3K AMOLED display and seamless Galaxy ecosystem integration.',
                    'specs' => [
                        'display'      => '16-inch 3K AMOLED, 2880×1800, 120Hz',
                        'processor'    => 'Intel Core Ultra 7 155H',
                        'ram'          => '16GB LPDDR5x',
                        'storage'      => '512GB NVMe SSD',
                        'battery'      => '76Wh, 65W PD Charging',
                        'camera'       => '3MP IR Webcam',
                        'os'           => 'Windows 11 Home',
                        'connectivity' => 'Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4, USB-C, HDMI',
                        'weight'       => '1.55kg',
                        'dimensions'   => '355.4 × 250.4 × 12.5 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color', 'value' => 'Moonstone Gray', 'stock' => 8],
                        ['variant_type' => 'ram',   'value' => '16GB', 'price' => 149999, 'stock' => 10],
                        ['variant_type' => 'ram',   'value' => '32GB', 'price' => 169999, 'stock' => 4],
                    ],
                ],
                [
                    'brand' => 'HP', 'category' => $laptop,
                    'name'  => 'HP Pavilion 15',
                    'price' => 74999, 'old_price' => 82999,
                    'is_trending' => false, 'is_featured' => false,
                    'release_date' => '2023-10-15',
                    'description'  => 'The HP Pavilion 15 is a reliable everyday laptop with AMD Ryzen 5 processing power, 15.6-inch FHD display, and long battery life. Ideal for students and professionals.',
                    'specs' => [
                        'display'      => '15.6-inch FHD IPS, 1920×1080, Anti-glare',
                        'processor'    => 'AMD Ryzen 5 7530U',
                        'ram'          => '8GB DDR4',
                        'storage'      => '512GB NVMe SSD',
                        'battery'      => '41Wh, up to 8.5 hours',
                        'camera'       => 'HD 720p Webcam',
                        'os'           => 'Windows 11 Home',
                        'connectivity' => 'Wi-Fi 5, Bluetooth 4.2, USB-A ×2, USB-C, HDMI',
                        'weight'       => '1.75kg',
                        'dimensions'   => '357.9 × 234.5 × 17.9 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Natural Silver',   'stock' => 12],
                        ['variant_type' => 'storage', 'value' => '512GB SSD', 'price' => 74999, 'stock' => 15],
                        ['variant_type' => 'storage', 'value' => '1TB SSD',   'price' => 84999, 'stock' => 6],
                    ],
                ],
                [
                    'brand' => 'Dell', 'category' => $laptop,
                    'name'  => 'Dell Inspiron 15 3520',
                    'price' => 69999, 'old_price' => 77999,
                    'is_trending' => false, 'is_featured' => false,
                    'release_date' => '2023-08-20',
                    'description'  => 'Dell Inspiron 15 3520 offers solid performance with Intel Core i5 12th Gen, 15.6-inch FHD display, and a compact lightweight build for daily use.',
                    'specs' => [
                        'display'      => '15.6-inch FHD, 1920×1080, 120Hz',
                        'processor'    => 'Intel Core i5-1235U',
                        'ram'          => '8GB DDR4',
                        'storage'      => '512GB SSD',
                        'battery'      => '54Wh, 65W Adapter',
                        'camera'       => '720p HD Webcam',
                        'os'           => 'Windows 11 Home',
                        'connectivity' => 'Wi-Fi 5, Bluetooth 5.1, USB-A ×2, USB-C, HDMI, SD Card',
                        'weight'       => '1.65kg',
                        'dimensions'   => '357.3 × 235.56 × 16.84 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color',   'value' => 'Carbon Black', 'stock' => 10],
                        ['variant_type' => 'storage', 'value' => '512GB', 'price' => 69999, 'stock' => 12],
                        ['variant_type' => 'storage', 'value' => '1TB',   'price' => 79999, 'stock' => 5],
                    ],
                ],
                // ── Earbuds ──────────────────────────────────────
                [
                    'brand' => 'Sony', 'category' => $earbudsC,
                    'name'  => 'Sony WF-1000XM5',
                    'price' => 29999, 'old_price' => 34999,
                    'is_trending' => true, 'is_featured' => true,
                    'release_date' => '2023-07-01',
                    'description'  => "Sony WF-1000XM5 are the world's best noise-cancelling earbuds with 8 hours of battery, LDAC Hi-Res Audio support, and premium call quality.",
                    'specs' => [
                        'display'      => 'N/A',
                        'processor'    => 'Integrated Processor V2',
                        'ram'          => 'N/A',
                        'storage'      => 'N/A',
                        'battery'      => '8hr (earbuds) + 24hr with case, USB-C Quick Charge',
                        'camera'       => 'N/A',
                        'os'           => 'N/A',
                        'connectivity' => 'Bluetooth 5.3, Multipoint, NFC',
                        'weight'       => '5.9g per bud',
                        'dimensions'   => 'Case: 49.8 × 50.3 × 27.2 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color', 'value' => 'Black',  'stock' => 20],
                        ['variant_type' => 'color', 'value' => 'Silver', 'stock' => 15],
                    ],
                ],
                [
                    'brand' => 'boAt', 'category' => $earbudsC,
                    'name'  => 'boAt Airdopes 141',
                    'price' => 1699, 'old_price' => 2499,
                    'is_trending' => true, 'is_featured' => false,
                    'release_date' => '2023-05-15',
                    'description'  => 'boAt Airdopes 141 delivers up to 42 hours of total playback, ENx mic technology for crystal-clear calls, and a compact design at an unbeatable price.',
                    'specs' => [
                        'display'      => 'N/A',
                        'processor'    => 'N/A',
                        'ram'          => 'N/A',
                        'storage'      => 'N/A',
                        'battery'      => '6hr (earbuds) + 36hr with case',
                        'camera'       => 'N/A',
                        'os'           => 'N/A',
                        'connectivity' => 'Bluetooth 5.3',
                        'weight'       => '4.3g per bud',
                        'dimensions'   => 'N/A',
                    ],
                    'variants' => [
                        ['variant_type' => 'color', 'value' => 'Active Black', 'stock' => 50],
                        ['variant_type' => 'color', 'value' => 'Mint Green',   'stock' => 30],
                        ['variant_type' => 'color', 'value' => 'Berry Blue',   'stock' => 25],
                    ],
                ],
                // ── Smartwatches ──────────────────────────────────
                [
                    'brand' => 'Samsung', 'category' => $smartwatch,
                    'name'  => 'Samsung Galaxy Watch 6',
                    'price' => 34999, 'old_price' => 39999,
                    'is_trending' => false, 'is_featured' => true,
                    'release_date' => '2023-08-11',
                    'description'  => 'Samsung Galaxy Watch 6 features advanced health monitoring, Wear OS 4, and a sleek design with 40mm/44mm options for fitness and smartwatch enthusiasts.',
                    'specs' => [
                        'display'      => '1.3-inch Super AMOLED, 432×432, Sapphire crystal glass',
                        'processor'    => 'Exynos W930, Dual-core 1.4GHz',
                        'ram'          => '2GB',
                        'storage'      => '16GB',
                        'battery'      => '300mAh (40mm), Wireless Charging',
                        'camera'       => 'N/A',
                        'os'           => 'Wear OS 4.0, One UI Watch 5.0',
                        'connectivity' => 'Bluetooth 5.3, Wi-Fi 2.4/5GHz, GPS, NFC',
                        'weight'       => '28.7g (without strap)',
                        'dimensions'   => '38.8 × 40.4 × 9.0 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color', 'value' => 'Graphite', 'stock' => 12],
                        ['variant_type' => 'color', 'value' => 'Gold',     'stock' => 8],
                        ['variant_type' => 'other', 'value' => '40mm', 'price' => 34999, 'stock' => 10],
                        ['variant_type' => 'other', 'value' => '44mm', 'price' => 37999, 'stock' => 8],
                    ],
                ],
                [
                    'brand' => 'Noise', 'category' => $smartwatch,
                    'name'  => 'Noise ColorFit Ultra 3',
                    'price' => 3999, 'old_price' => 5999,
                    'is_trending' => true, 'is_featured' => false,
                    'release_date' => '2023-09-01',
                    'description'  => 'Noise ColorFit Ultra 3 packs a 1.96-inch AMOLED display, 100+ sports modes, 8-day battery life, and Bluetooth calling at a very affordable price.',
                    'specs' => [
                        'display'      => '1.96-inch AMOLED, 410×502',
                        'processor'    => 'N/A',
                        'ram'          => 'N/A',
                        'storage'      => 'N/A',
                        'battery'      => '7–8 days typical use, Magnetic charging',
                        'camera'       => 'N/A',
                        'os'           => 'Noise OS',
                        'connectivity' => 'Bluetooth 5.3 calling, GPS',
                        'weight'       => '36g with strap',
                        'dimensions'   => '47 × 38 × 10.5 mm',
                    ],
                    'variants' => [
                        ['variant_type' => 'color', 'value' => 'Midnight Black', 'stock' => 40],
                        ['variant_type' => 'color', 'value' => 'Rose Gold',      'stock' => 30],
                        ['variant_type' => 'color', 'value' => 'Steel Blue',     'stock' => 25],
                    ],
                ],
            ];

            foreach ($gadgetData as $gd) {
                $gadget = Gadget::create([
                    'brand_id'     => $brands[$gd['brand']]->id,
                    'category_id'  => $gd['category']->id,
                    'name'         => $gd['name'],
                    'price'        => $gd['price'],
                    'old_price'    => $gd['old_price'],
                    'is_trending'  => $gd['is_trending'],
                    'is_featured'  => $gd['is_featured'],
                    'release_date' => $gd['release_date'],
                    'description'  => $gd['description'],
                    'views_count'  => rand(100, 8000),
                    'price_tracker_description' => 'Track price changes for ' . $gd['name'] . ' in Nepal. Get notified when the price drops.',
                ]);

                SpecSheet::create(array_merge(['gadget_id' => $gadget->id], $gd['specs']));

                foreach ($gd['variants'] as $v) {
                    GadgetVariant::create([
                        'gadget_id'    => $gadget->id,
                        'variant_type' => $v['variant_type'],
                        'value'        => $v['value'],
                        'price'        => $v['price'] ?? null,
                        'stock'        => $v['stock'],
                        'is_available' => true,
                    ]);
                }
            }
        }

        // ── News Articles ─────────────────────────────────────────────────
        if (NewsArticle::count() === 0) {
            $articles = [
                [
                    'user_id'          => $admin->id,
                    'title'            => 'Snapdragon 8 Gen 4 Expected to Debut at Qualcomm Summit 2024',
                    'slug'             => 'snapdragon-8-gen-4-debut-qualcomm-summit-2024',
                    'content'          => '<p>Qualcomm is expected to unveil the Snapdragon 8 Gen 4 mobile processor at its annual Snapdragon Summit in October 2024. Codenamed SM8750, the chip is rumored to feature a brand-new Oryon CPU architecture — the same used in Snapdragon X Elite laptops — promising a massive generational performance leap.</p><p>Early benchmarks show single-core scores up to 3,000 in Geekbench, nearly double that of the Snapdragon 8 Gen 3.</p><h2>What to Expect</h2><ul><li>Oryon CPU with 3nm TSMC fabrication</li><li>Adreno 830 GPU with massive performance uplift</li><li>Hexagon NPU at 80 TOPS for on-device AI</li><li>Wi-Fi 7, Bluetooth 5.4, and X80 5G modem</li></ul><p>The Samsung Galaxy S25 series and OnePlus 13 are expected to be among the first devices launching with Snapdragon 8 Gen 4 in early 2025.</p>',
                    'category'         => 'tech',
                    'is_published'     => true,
                    'views_count'      => rand(200, 3000),
                    'meta_description' => 'Qualcomm Snapdragon 8 Gen 4 is expected to launch at Snapdragon Summit 2024 with Oryon CPU and 80 TOPS AI performance.',
                ],
                [
                    'user_id'          => $admin->id,
                    'title'            => 'Apple Intelligence Coming to Nepal: What iPhone Users Need to Know',
                    'slug'             => 'apple-intelligence-nepal-iphone-users-guide',
                    'content'          => '<p>Apple Intelligence, the AI feature suite introduced with iOS 18.1, is gradually rolling out to more regions. Nepali iPhone users could soon access Writing Tools, Image Playground, and the revamped Siri with ChatGPT integration.</p><p>Apple Intelligence requires an iPhone 15 Pro or any iPhone 16 model, or an iPad/Mac with M1 chip or later.</p><h2>Key Features</h2><ul><li><strong>Writing Tools</strong>: Rewrite, proofread, and summarize text in any app</li><li><strong>Smart Reply</strong>: AI-suggested email and message replies</li><li><strong>Image Playground</strong>: Generate images from text prompts on-device</li><li><strong>Priority Notifications</strong>: AI ranks your most important alerts</li><li><strong>Siri with ChatGPT</strong>: Deep conversational AI with web knowledge</li></ul>',
                    'category'         => 'mobile',
                    'is_published'     => true,
                    'views_count'      => rand(300, 5000),
                    'meta_description' => 'Apple Intelligence is rolling out globally. Everything Nepali iPhone users need to know about AI features, compatibility, and how to enable them.',
                ],
                [
                    'user_id'          => $admin->id,
                    'title'            => 'Best Gaming Laptops in Nepal Under NPR 1.5 Lakh in 2024',
                    'slug'             => 'best-gaming-laptops-nepal-under-150000-2024',
                    'content'          => '<p>Gaming laptops have become increasingly accessible in Nepal, with powerful options now available under NPR 1.5 lakh.</p><h2>Top Picks</h2><h3>1. ASUS TUF Gaming A15 (2024) — NPR 1,19,999</h3><p>Powered by AMD Ryzen 7 7435HS and RTX 4060, excellent 1080p gaming performance with a 144Hz display.</p><h3>2. Lenovo IdeaPad Gaming 3 Gen 8 — NPR 99,999</h3><p>AMD Ryzen 5 7535HS with RTX 4050 — the best budget gaming laptop under 1 lakh in Nepal.</p><h3>3. HP Victus 16 (RTX 4060) — NPR 1,34,999</h3><p>Intel Core i7-13700H and RTX 4060 — a performance beast with a stunning 144Hz FHD display.</p>',
                    'category'         => 'laptop',
                    'is_published'     => true,
                    'views_count'      => rand(400, 8000),
                    'meta_description' => 'Discover the best gaming laptops available in Nepal under NPR 1.5 lakh in 2024, including top picks from ASUS, Lenovo, and HP.',
                ],
                [
                    'user_id'          => $admin->id,
                    'title'            => 'NTC and Ncell 5G Rollout in Nepal: Full 2024 Update',
                    'slug'             => 'ntc-ncell-5g-rollout-nepal-2024',
                    'content'          => "<p>Nepal's telecommunications sector is on the brink of a major leap forward as both NTC and Ncell have announced plans to accelerate 5G rollout across major cities by end 2024.</p><h2>Current Status</h2><ul><li><strong>NTC 5G</strong>: Trial phase in Kathmandu, Pokhara, and Chitwan; commercial launch expected Q1 2025</li><li><strong>Ncell 5G</strong>: Infrastructure deployment underway in Kathmandu</li><li><strong>Spectrum</strong>: NTA is finalizing 5G spectrum allocation policy</li></ul><h2>Which Phones Support 5G in Nepal?</h2><p>Popular 5G-capable options in Nepal: Samsung Galaxy S24 series, iPhone 15 Pro, OnePlus 12, and Xiaomi 14 — all support Sub-6GHz 5G bands compatible with Nepal's planned spectrum.</p>",
                    'category'         => 'telecom',
                    'is_published'     => true,
                    'views_count'      => rand(500, 9000),
                    'meta_description' => "NTC and Ncell are advancing 5G deployment in Nepal. Full update on timelines, spectrum plans, and compatible phones.",
                ],
            ];
            foreach ($articles as $article) {
                NewsArticle::create($article);
            }
        }

        // ── Tech Guides ───────────────────────────────────────────────────
        if (TechGuide::count() === 0) {
            $guides = [
                [
                    'user_id'      => $admin->id,
                    'title'        => 'How to Choose the Right Smartphone in Nepal: Complete Buying Guide 2024',
                    'slug'         => 'how-to-choose-smartphone-nepal-2024',
                    'content'      => '<h2>Step 1: Set Your Budget</h2><p>In Nepal, smartphones range from NPR 8,000 for entry-level devices to over NPR 2,00,000 for flagships. Budget: under NPR 20,000. Mid-range: NPR 20,000–60,000. Upper-mid: NPR 60,000–1,00,000. Flagship: above 1,00,000.</p><h2>Step 2: Pick Your Priority</h2><ul><li><strong>Camera</strong>: Samsung Galaxy S series, iPhone, Xiaomi</li><li><strong>Battery life</strong>: Realme, Xiaomi Note series, OPPO A series</li><li><strong>Performance</strong>: Snapdragon 8 Gen series or Apple A-series</li><li><strong>Display</strong>: AMOLED with 120Hz refresh rate is the sweet spot</li></ul><h2>Step 3: Consider 5G Readiness</h2><p>With NTC and Ncell rolling out 5G in Nepal, choosing a 5G-capable device makes sense if you plan to keep the phone 3–4 years.</p><h2>Step 4: Check After-Sales Service</h2><p>Always buy from authorized dealers in Nepal. Samsung, Apple, Xiaomi, and OnePlus all have authorized service centers in Kathmandu.</p>',
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => 'Laptop vs Tablet: Which Should You Buy for Work and Study in Nepal?',
                    'slug'         => 'laptop-vs-tablet-work-study-nepal',
                    'content'      => '<h2>The Key Differences</h2><p>Tablets excel at media consumption and light browsing. Laptops dominate for productivity, coding, and content creation. For most students and professionals in Nepal, a laptop is the better overall choice.</p><h2>When to Choose a Tablet</h2><ul><li>You primarily consume content (YouTube, Netflix, reading)</li><li>You want to take handwritten notes</li><li>Your work is fully cloud-based</li></ul><h2>When to Choose a Laptop</h2><ul><li>You code, design, or edit video/photo</li><li>You need Microsoft Office with full functionality</li><li>Your institution requires specific software</li></ul><h2>Best of Both Worlds</h2><p>Consider the iPad Pro with Magic Keyboard or Samsung Galaxy Tab S9 with Book Cover Keyboard — these hybrid solutions are available from NPR 80,000–1,50,000 in Nepal.</p>',
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => 'True Wireless Earbuds Buying Guide for Nepal 2024',
                    'slug'         => 'true-wireless-earbuds-buying-guide-nepal-2024',
                    'content'      => '<h2>Key Specifications Explained</h2><h3>Active Noise Cancellation (ANC)</h3><p>ANC uses microphones to analyze and cancel external noise. Premium ANC: Sony WF-1000XM5 and Apple AirPods Pro 2. Budget ANC: boAt Airdopes Pro series.</p><h3>Battery Life</h3><p>Look for at least 6 hours on the buds and 24 total hours including the case.</p><h3>Codec Support</h3><ul><li><strong>AAC</strong>: Standard for iOS</li><li><strong>aptX/aptX Adaptive</strong>: Best for Android</li><li><strong>LDAC</strong>: Highest quality (Sony)</li></ul><h2>Nepal Price Tiers</h2><ul><li><strong>Budget (under NPR 3,000)</strong>: boAt Airdopes, Noise Air Buds</li><li><strong>Mid-range (NPR 3,000–10,000)</strong>: OnePlus Buds Pro, Realme Buds Air</li><li><strong>Premium (NPR 10,000–35,000)</strong>: Sony WF-1000XM5, Samsung Galaxy Buds2 Pro</li></ul>',
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => "How to Read a Gadget Spec Sheet: A Beginner's Guide",
                    'slug'         => 'how-to-read-gadget-spec-sheet-beginners-guide',
                    'content'      => "<h2>Understanding Display Specs</h2><p>When you see '6.1-inch Super Retina XDR OLED, 2556x1179, 120Hz': 6.1-inch is the diagonal screen size. 2556x1179 is the resolution. 120Hz is the refresh rate — higher means smoother scrolling.</p><h2>Processor</h2><p>Snapdragon 8 Gen 3 or Apple A17 Pro are chip names. Newer generations are faster.</p><h2>RAM vs Storage</h2><p>RAM (8GB, 12GB) = working memory for multitasking. Storage (128GB, 256GB) = space for your photos, apps, and files.</p><h2>Battery</h2><p>mAh = capacity. Charging watts = speed. 65W charges most phones from 0–100% in under an hour.</p>",
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => 'Top 5 Reasons to Buy a 5G Phone in Nepal Right Now',
                    'slug'         => 'top-reasons-buy-5g-phone-nepal',
                    'content'      => '<h2>1. Future-Proof Your Investment</h2><p>Phones last 3–5 years. Buy 5G now to be ready when coverage reaches your area without upgrading.</p><h2>2. Better Processors</h2><p>Most 5G phones come with Snapdragon 7 Gen series or above — better performance even without 5G.</p><h2>3. Improved Wi-Fi</h2><p>5G chipsets usually support Wi-Fi 6 and Bluetooth 5.3+.</p><h2>4. Narrowing Price Gap</h2><p>5G phones in Nepal are now available from NPR 20,000–25,000. The premium over 4G is just NPR 2,000–5,000.</p><h2>5. Better Resale Value</h2><p>5G phones hold resale value better as 4G devices depreciate faster once 5G becomes mainstream.</p>',
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => 'How to Use the Price Tracker on Git Infosys',
                    'slug'         => 'how-to-use-price-tracker-git-infosys',
                    'content'      => '<h2>What is the Price Tracker?</h2><p>The Git Infosys Price Tracker monitors gadget prices over time, showing historical price trends so you can identify the best time to buy.</p><h2>How to Access Price History</h2><ol><li>Browse to any gadget page</li><li>Scroll down to the "Price History" section</li><li>View the chart showing price changes over past months</li></ol><h2>Tips for Smart Buying</h2><ul><li>Check if the current price is near the historical low</li><li>Look for Dashain/Tihar seasonal price cuts</li><li>Use the Compare tool to find the best value at your budget</li><li>Get AI-powered buying recommendations for personalized advice</li></ul>',
                    'is_published' => true,
                ],
                [
                    'user_id'      => $admin->id,
                    'title'        => 'Smartwatch Buying Guide for Nepal 2024',
                    'slug'         => 'smartwatch-buying-guide-nepal-2024',
                    'content'      => '<h2>Key Features to Look For</h2><h3>Health Monitoring</h3><ul><li>Heart rate sensor (standard on all watches)</li><li>SpO2 blood oxygen sensor</li><li>Sleep tracking</li><li>ECG — available on Samsung Galaxy Watch, Apple Watch</li></ul><h3>Battery Life</h3><p>Premium watches (Apple Watch, Galaxy Watch) last 1–2 days. Budget options like Noise, Fire-Boltt, Amazfit last 7–10 days.</p><h2>Nepal Price Ranges</h2><ul><li><strong>Budget (NPR 2,000–5,000)</strong>: Noise, Amazfit — basic fitness + Bluetooth calling</li><li><strong>Mid-range (NPR 5,000–20,000)</strong>: Amazfit GTR 4, Garmin Forerunner</li><li><strong>Premium (NPR 25,000+)</strong>: Samsung Galaxy Watch, Apple Watch</li></ul>',
                    'is_published' => true,
                ],
            ];
            foreach ($guides as $guide) {
                TechGuide::create($guide);
            }
        }

        // ── Reviews ───────────────────────────────────────────────────────
        if (Review::count() === 0) {
            $reviewData = [
                [
                    'gadget_slug' => 'samsung-galaxy-s24',
                    'user_id'     => $admin->id,
                    'title'       => 'Samsung Galaxy S24 Review: Snapdragon 8 Gen 3 Flagship Value',
                    'content'     => '<p>The Samsung Galaxy S24 represents excellent flagship value for Nepali consumers. With Snapdragon 8 Gen 3 globally, Samsung has finally delivered a consistently fast Galaxy S experience. The 50MP camera system produces stunning photos, especially in night mode.</p><p>Galaxy AI features — Circle to Search, Live Translate, and Generative Edit — work remarkably well. The 4000mAh battery is the weakest point, typically giving 1–1.5 days of usage, but the 25W charging is fast for the capacity.</p>',
                    'rating'      => 8.5,
                    'pros'        => "Snapdragon 8 Gen 3 performance\nExcellent 50MP camera system\nGalaxy AI features work genuinely well\nCompact and premium design\nBright 2600-nit display",
                    'cons'        => "Only 4000mAh battery\n25W charging (slower than competitors)\nNo charger in box\nGlass back prone to fingerprints",
                    'verdict'     => 'The Galaxy S24 is one of the best compact flagships in Nepal. Snapdragon performance, excellent cameras, and Galaxy AI in a pocket-friendly size.',
                    'is_published' => true,
                ],
                [
                    'gadget_slug' => 'iphone-15-pro',
                    'user_id'     => $admin->id,
                    'title'       => 'iPhone 15 Pro Review: Titanium, Action Button, and USB-C Finally',
                    'content'     => '<p>The iPhone 15 Pro is Apple\'s most refined iPhone yet. The titanium build makes it lighter, and the Action Button adds customizable utility. USB-C adoption means a universal cable — at full USB 3 speeds for the first time.</p><p>The A17 Pro chip is untouchable in performance. The 48MP camera with Tetraprism periscope zoom (5x) takes exceptional portrait and zoom photography. ProRes video is impressive for videographers.</p>',
                    'rating'      => 9.0,
                    'pros'        => "A17 Pro chip — fastest mobile processor\nTitanium build — lighter and premium\nExcellent 48MP camera with 5x periscope zoom\nAction Button adds customization\nUSB-C with USB 3 speeds",
                    'cons'        => "Very expensive at NPR 149,999\nSmall 3274mAh battery\n27W charging is slow in 2024\nThick camera bump",
                    'verdict'     => 'The iPhone 15 Pro is the best iPhone Apple has made. If you want the best camera, performance, and long-term software support in Nepal, this is it.',
                    'is_published' => true,
                ],
                [
                    'gadget_slug' => 'sony-wf-1000xm5',
                    'user_id'     => $admin->id,
                    'title'       => 'Sony WF-1000XM5 Review: Still the Best Noise-Cancelling Earbuds',
                    'content'     => "<p>Sony continues to dominate the premium TWS earbud category with the WF-1000XM5. The ANC is class-leading — it genuinely blocks traffic, chatter, and ambient noise in a way no other earbud under NPR 35,000 can match.</p><p>Sound quality via LDAC is spectacular when paired with a compatible Android phone, offering near-over-ear headphone quality in a tiny package.</p>",
                    'rating'      => 9.2,
                    'pros'        => "Best-in-class ANC performance\nLDAC Hi-Res Audio support\nExcellent call quality with AI noise rejection\nPremium build quality and IPX4 rating\nSmall and lightweight",
                    'cons'        => "Expensive at NPR 29,999\nNo wireless charging for the case\nRequires correct eartip fit for best ANC",
                    'verdict'     => 'The Sony WF-1000XM5 are the best true wireless earbuds you can buy in Nepal. ANC and sound quality remain unmatched. Highly recommended.',
                    'is_published' => true,
                ],
                [
                    'gadget_slug' => 'xiaomi-14',
                    'user_id'     => $admin->id,
                    'title'       => 'Xiaomi 14 Review: Leica Cameras at a Smarter Price',
                    'content'     => '<p>The Xiaomi 14 is a genuinely exciting smartphone for Nepal. Leica-tuned cameras, Snapdragon 8 Gen 3, and 90W fast charging in a compact body make it a compelling alternative to the Galaxy S24 at a lower price.</p><p>The main 50MP Leica camera with variable aperture is exceptional in outdoor photography. Low-light performance is class-leading in the under-NPR 80,000 segment.</p>',
                    'rating'      => 8.7,
                    'pros'        => "Snapdragon 8 Gen 3 performance\nExceptional Leica camera system\n90W HyperCharge — very fast\nCompact premium design\nWi-Fi 7 support",
                    'cons'        => "No official global ROM for Nepal\nNo IP68 rating (only IP68 for global markets)\nHyperOS may feel unfamiliar to some users",
                    'verdict'     => 'Xiaomi 14 offers flagship performance and Leica camera quality at a meaningfully lower price than Samsung and Apple equivalents. Excellent value for Nepal.',
                    'is_published' => true,
                ],
            ];

            foreach ($reviewData as $rd) {
                $gadget = Gadget::where('slug', $rd['gadget_slug'])->first();
                if ($gadget) {
                    Review::create([
                        'gadget_id'    => $gadget->id,
                        'user_id'      => $rd['user_id'],
                        'title'        => $rd['title'],
                        'content'      => $rd['content'],
                        'rating'       => $rd['rating'],
                        'pros'         => $rd['pros'],
                        'cons'         => $rd['cons'],
                        'verdict'      => $rd['verdict'],
                        'is_published' => $rd['is_published'],
                    ]);
                }
            }
        }
    }
}
