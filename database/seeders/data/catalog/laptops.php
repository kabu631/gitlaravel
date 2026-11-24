<?php

/*
 * Laptops on sale in Nepal.
 */

$src = fn (string $label, string $url, string $date = '2026-11-22') => ['label' => $label, 'url' => $url, 'accessed' => $date];
$gbMac    = ['accessed' => '2026-09-17'];
$gbBest   = ['accessed' => '2026-11-19'];
$gbGaming = ['accessed' => '2026-10-08'];

return [

    [
        'name' => 'Apple MacBook Neo', 'slug' => 'macbook-neo', 'brand' => 'Apple', 'category' => 'laptop',
        'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 134999],
            ['ram' => '8GB', 'storage' => '512GB', 'price' => 154000],
        ],
        'history' => [['2026-09-17', 134999]],
        'specs' => [
            'display' => '13-inch Liquid Retina, 2408×1506, 500 nits',
            'processor' => 'Apple A18 Pro (6-core CPU, 5-core GPU)',
            'ram' => '8GB unified memory (not upgradeable)',
            'storage' => '256GB / 512GB SSD',
            'os' => 'macOS',
            'extra' => ['Note' => 'The 256GB model has no Touch ID and no keyboard backlight'],
        ],
        'description' => <<<'HTML'
<p>The MacBook Neo is Apple's answer to students who wanted a Mac but couldn't justify Air prices. It runs on the A18 Pro — the chip from the iPhone 16 Pro — and costs <strong>Rs 1,34,999</strong> in Nepal, about Rs 1 lakh less than the <a href="/products/macbook-air-m5-13">MacBook Air M5</a>.</p>
<p>That price comes with clear limits. Memory is fixed at 8GB, and the 256GB model drops Touch ID and the keyboard backlight, which you'll miss when typing in a dim room. For notes, assignments, browsing and light photo editing it's more than enough; for coding or video work, save for the Air. On the Windows side, the <a href="/products/dell-xps-13-2026">Dell XPS 13 (2026)</a> costs the same.</p>
HTML,
        'faqs' => [
            ['What is the MacBook Neo price in Nepal?', 'Rs 1,34,999 for 8GB/256GB and Rs 1,54,000 for 8GB/512GB.'],
            ['Does the MacBook Neo have a backlit keyboard?', 'Only the 512GB model. The 256GB model has neither a backlit keyboard nor Touch ID.'],
            ['Which chip does the MacBook Neo use?', 'The Apple A18 Pro with a 6-core CPU and 5-core GPU.'],
        ],
        'sources' => [$gbMac, $gbBest],
        'seo' => [
            'title' => 'MacBook Neo Price in Nepal: Apple\'s Cheapest MacBook Explained',
            'description' => 'MacBook Neo costs Rs 1,34,999 in Nepal. A18 Pro chip, 8GB memory, 13-inch display — what you give up versus the MacBook Air M5.',
            'keywords' => 'macbook neo price in nepal, cheapest macbook nepal, macbook for students nepal',
        ],
        'image_alt' => 'Apple MacBook Neo',
    ],

    [
        'name' => 'Apple MacBook Air 13 (M5)', 'slug' => 'macbook-air-m5-13', 'brand' => 'Apple', 'category' => 'laptop',
        'release_date' => '2026-04-10', 'featured' => true,
        'variants' => [
            ['ram' => '16GB', 'storage' => '512GB', 'price' => 234900],
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 289900],
        ],
        'history' => [['2026-09-17', 234900]],
        'specs' => [
            'display' => '13.6-inch Liquid Retina, 2560×1664, 500 nits, P3 wide colour',
            'processor' => 'Apple M5 (10-core CPU, 8/10-core GPU)',
            'ram' => '16GB unified memory (24GB option)',
            'storage' => '512GB / 1TB SSD',
            'os' => 'macOS',
        ],
        'description' => <<<'HTML'
<p>The 13-inch MacBook Air with M5 is the laptop we'd recommend to most people who can afford it: fanless and silent, light enough for a college bag, and fast enough for years of work. It starts at <strong>Rs 2,34,900</strong> in Nepal with 16GB of memory and 512GB of storage — a sensible base configuration that Apple used to charge extra for.</p>
<p>Battery life is the real headline for Nepali buyers; a full day of work away from a socket is normal. If you want a bigger screen, the <a href="/products/macbook-air-m5-15">15-inch model</a> costs Rs 35,000 more. If you edit video for clients, step up to the <a href="/products/macbook-pro-14-m5">MacBook Pro 14 (M5)</a> for its fan and brighter screen.</p>
HTML,
        'faqs' => [
            ['What is the MacBook Air M5 price in Nepal?', 'The 13-inch MacBook Air M5 costs Rs 2,34,900 for 16GB/512GB and Rs 2,89,900 for 16GB/1TB.'],
            ['When did the MacBook Air M5 launch in Nepal?', 'It went on sale in Nepal on 10 April 2026.'],
        ],
        'sources' => [$gbMac, $gbBest],
        'seo' => [
            'title' => 'MacBook Air M5 13-inch Price in Nepal: Specs & Who Should Buy',
            'description' => 'MacBook Air M5 (13-inch) costs Rs 2,34,900 in Nepal for 16GB/512GB. M5 chip, fanless design, all-day battery — full specs.',
            'keywords' => 'macbook air m5 price in nepal, macbook air 13 m5 nepal',
        ],
        'image_alt' => 'Apple MacBook Air with M5',
    ],

    [
        'name' => 'Apple MacBook Air 15 (M5)', 'slug' => 'macbook-air-m5-15', 'brand' => 'Apple', 'category' => 'laptop',
        'release_date' => '2026-04-10',
        'variants' => [
            ['ram' => '16GB', 'storage' => '512GB', 'price' => 269900],
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 329900],
        ],
        'history' => [['2026-09-17', 269900]],
        'specs' => [
            'display' => '15.3-inch Liquid Retina, 2880×1864, 500 nits, P3 wide colour',
            'processor' => 'Apple M5 (10-core CPU, 10-core GPU)',
            'ram' => '16GB unified memory (24GB option)',
            'storage' => '512GB / 1TB SSD',
            'os' => 'macOS',
        ],
        'description' => <<<'HTML'
<p>Same M5 chip, same silent fanless design, more screen. The 15-inch MacBook Air costs <strong>Rs 2,69,900</strong> in Nepal and is the better choice if you keep two windows side by side all day — spreadsheets and a browser, or code and documentation.</p>
<p>It's still thin and light enough to carry daily, but it won't fit every small laptop sleeve. Performance is identical to the <a href="/products/macbook-air-m5-13">13-inch model</a>, so don't pay the extra Rs 35,000 expecting more speed.</p>
HTML,
        'faqs' => [
            ['What is the MacBook Air 15 M5 price in Nepal?', 'Rs 2,69,900 for 16GB/512GB and Rs 3,29,900 for 16GB/1TB.'],
        ],
        'sources' => [$gbMac],
        'seo' => [
            'title' => 'MacBook Air 15-inch M5 Price in Nepal & Specifications',
            'description' => 'MacBook Air 15 (M5) costs Rs 2,69,900 in Nepal. 15.3-inch display, M5 chip, fanless — and whether it is worth Rs 35,000 more than the 13-inch.',
            'keywords' => 'macbook air 15 m5 price in nepal, macbook air 15 inch nepal',
        ],
        'image_alt' => 'Apple MacBook Air 15-inch with M5',
    ],

    [
        'name' => 'Apple MacBook Pro 14 (M5)', 'slug' => 'macbook-pro-14-m5', 'brand' => 'Apple', 'category' => 'laptop',
        'variants' => [
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 376500],
            ['ram' => '24GB', 'storage' => '1TB', 'price' => 415000],
            ['ram' => '32GB', 'storage' => '1TB', 'price' => 456000],
        ],
        'history' => [['2026-07-01', 376500]],
        'specs' => [
            'display' => '14.2-inch Liquid Retina XDR, 3024×1964, 120Hz ProMotion, 1,000 nits SDR (1,600 nits HDR peak)',
            'processor' => 'Apple M5',
            'ram' => '16GB / 24GB / 32GB unified memory',
            'storage' => '1TB SSD',
            'os' => 'macOS',
        ],
        'description' => <<<'HTML'
<p>The base 14-inch MacBook Pro now costs <strong>Rs 3,76,500</strong> in Nepal for 16GB/1TB, after a price increase on 1 July 2026 during the global memory shortage. Over the <a href="/products/macbook-air-m5-13">MacBook Air</a> you get a fan (so long exports don't throttle), a 120Hz mini-LED XDR display that is far better for HDR video and photo work, and more ports.</p>
<p>If your work is mainly code, documents and the web, the Air is the smarter buy. Photographers and video editors should look at the 24GB model. Heavier workloads point to the <a href="/products/macbook-pro-14-m5-pro">M5 Pro version</a>.</p>
HTML,
        'faqs' => [
            ['What is the MacBook Pro 14 M5 price in Nepal?', 'Rs 3,76,500 for 16GB/1TB, Rs 4,15,000 for 24GB/1TB and Rs 4,56,000 for 32GB/1TB.'],
            ['Did the MacBook Pro M5 get more expensive in Nepal?', 'Yes. The price went up on 1 July 2026 as memory costs rose.'],
        ],
        'sources' => [$gbMac, ['accessed' => '2026-07-01']],
        'seo' => [
            'title' => 'MacBook Pro 14 M5 Price in Nepal (After July 2026 Hike)',
            'description' => 'MacBook Pro 14-inch M5 costs Rs 3,76,500 in Nepal for 16GB/1TB after a July 2026 price hike. Configurations, display and specs.',
            'keywords' => 'macbook pro m5 price in nepal, macbook pro 14 m5 nepal',
        ],
        'image_alt' => 'Apple MacBook Pro 14-inch with M5',
    ],

    [
        'name' => 'Apple MacBook Pro 14 (M5 Pro)', 'slug' => 'macbook-pro-14-m5-pro', 'brand' => 'Apple', 'category' => 'laptop',
        'variants' => [
            ['edition' => 'M5 Pro 15-core CPU, 16-core GPU', 'ram' => '24GB', 'storage' => '1TB', 'price' => 474500],
            ['edition' => 'M5 Pro 15-core CPU, 16-core GPU', 'ram' => '24GB', 'storage' => '2TB', 'price' => 569000],
            ['edition' => 'M5 Pro 18-core CPU, 20-core GPU', 'ram' => '24GB', 'storage' => '2TB', 'price' => 606500],
        ],
        'history' => [['2026-09-17', 474500]],
        'specs' => [
            'display' => '14.2-inch Liquid Retina XDR, 3024×1964, 120Hz ProMotion',
            'processor' => 'Apple M5 Pro (up to 18-core CPU, 20-core GPU)',
            'ram' => '24GB unified memory',
            'storage' => '1TB / 2TB SSD',
            'os' => 'macOS',
        ],
        'description' => <<<'HTML'
<p>The M5 Pro MacBook Pro is for people who bill by the hour: video editors, 3D artists and developers running heavy builds. It starts at <strong>Rs 4,74,500</strong> in Nepal with a 15-core CPU, 16-core GPU, 24GB of memory and 1TB of storage. The top configuration we track — 18-core CPU, 20-core GPU and 2TB — costs Rs 6,06,500.</p>
<p>Pick the configuration above to see each price. If you don't regularly max out a laptop, the <a href="/products/macbook-pro-14-m5">standard M5 model</a> saves you about a lakh.</p>
HTML,
        'faqs' => [
            ['What is the MacBook Pro 14 M5 Pro price in Nepal?', 'It starts at Rs 4,74,500 (15-core CPU, 16-core GPU, 24GB, 1TB). The 2TB version is Rs 5,69,000, and the 18-core CPU, 20-core GPU model with 2TB is Rs 6,06,500.'],
        ],
        'sources' => [$gbMac],
        'seo' => [
            'title' => 'MacBook Pro 14 M5 Pro Price in Nepal: Every Configuration',
            'description' => 'MacBook Pro 14 with M5 Pro starts at Rs 4,74,500 in Nepal. Compare 15-core and 18-core configurations, memory and storage prices.',
            'keywords' => 'macbook pro m5 pro price in nepal, macbook pro 14 m5 pro nepal',
        ],
        'image_alt' => 'Apple MacBook Pro 14-inch with M5 Pro',
    ],

    [
        'name' => 'Dell XPS 13 (2026)', 'slug' => 'dell-xps-13-2026', 'brand' => 'Dell', 'category' => 'laptop',
        'variants' => [['ram' => '8GB', 'storage' => '512GB', 'price' => 134999]],
        'history' => [['2026-11-19', 134999]],
        'specs' => [
            'display' => '13.4-inch 2.5K (2408×1506) IPS touch, 500 nits',
            'processor' => 'Intel Core 5 320 (6 cores, up to 4.6GHz)',
            'ram' => '8GB LPDDR5x-7467 (soldered)',
            'storage' => '512GB SSD',
            'os' => 'Windows 11',
            'extra' => ['Model' => 'DX13260'],
        ],
        'description' => <<<'HTML'
<p>Dell's 2026 XPS 13 lands in Nepal at <strong>Rs 1,34,999</strong> — the same price as the <a href="/products/macbook-neo">MacBook Neo</a>, which makes this the most interesting Windows-versus-Mac decision of the year. The XPS keeps its premium build and a sharp 2.5K touchscreen, and runs a new Intel Core 5 320.</p>
<p>The catch is memory: 8GB is soldered and can't be upgraded, which is tight for Windows in 2026. It suits writers and frequent travellers who mostly work in a browser.</p>
HTML,
        'faqs' => [
            ['What is the Dell XPS 13 (2026) price in Nepal?', 'Rs 1,34,999 for the Core 5 320 model with 8GB RAM and 512GB SSD.'],
            ['Can you upgrade the RAM on the XPS 13 (2026)?', 'No, the 8GB of LPDDR5x memory is soldered to the board.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'Dell XPS 13 (2026) Price in Nepal: Specs vs MacBook Neo',
            'description' => 'Dell XPS 13 (2026) costs Rs 1,34,999 in Nepal. Core 5 320, 2.5K touchscreen, 8GB soldered RAM — and how it compares with the MacBook Neo.',
            'keywords' => 'dell xps 13 2026 price in nepal, dell xps 13 nepal',
        ],
        'image_alt' => 'Dell XPS 13 (2026)',
    ],

    [
        'name' => 'Lenovo Yoga Slim 7i Aura Edition', 'slug' => 'lenovo-yoga-slim-7i-aura-edition', 'brand' => 'Lenovo', 'category' => 'laptop',
        'variants' => [['ram' => '16GB', 'storage' => '512GB', 'price' => 164999]],
        'history' => [['2026-11-19', 164999]],
        'specs' => [
            'display' => '14-inch WUXGA OLED, 600 nits, 100% DCI-P3',
            'processor' => 'Intel Core Ultra 5 226V (Intel Arc 130V graphics)',
            'ram' => '16GB LPDDR5x-8533',
            'storage' => '512GB SSD',
            'os' => 'Windows 11',
            'extra' => ['Model' => '14ILL10'],
        ],
        'description' => <<<'HTML'
<p>For <strong>Rs 1,64,999</strong>, the Yoga Slim 7i Aura Edition gets you a 14-inch OLED screen with full DCI-P3 colour, fast 16GB LPDDR5x memory and Intel's efficient Lunar Lake-generation Core Ultra 5 226V. It is one of the better-value thin-and-light Windows laptops in Nepal for students and office workers who want good battery life.</p>
<p>It isn't a gaming or editing machine — the integrated Arc graphics handle light creative work, not heavy 3D. Spend more on the <a href="/products/asus-zenbook-14-oled-um3406ga">Zenbook 14 OLED</a> if you need 32GB of RAM.</p>
HTML,
        'faqs' => [
            ['What is the Lenovo Yoga Slim 7i Aura Edition price in Nepal?', 'Rs 1,64,999 for the Core Ultra 5 226V model with 16GB RAM and 512GB SSD.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'Lenovo Yoga Slim 7i Aura Edition Price in Nepal & Specs',
            'description' => 'Yoga Slim 7i Aura Edition costs Rs 1,64,999 in Nepal. 14-inch OLED, Core Ultra 5 226V, 16GB LPDDR5x — a strong thin-and-light pick.',
            'keywords' => 'lenovo yoga slim 7i aura edition price in nepal, yoga slim 7i nepal',
        ],
        'image_alt' => 'Lenovo Yoga Slim 7i Aura Edition',
    ],

    [
        'name' => 'ASUS Zenbook 14 OLED (UM3406GA)', 'slug' => 'asus-zenbook-14-oled-um3406ga', 'brand' => 'ASUS', 'category' => 'laptop',
        'variants' => [['ram' => '32GB', 'storage' => '1TB', 'price' => 254999]],
        'history' => [['2026-11-19', 254999]],
        'specs' => [
            'display' => '14-inch WUXGA OLED touch, 400 nits, 100% DCI-P3',
            'processor' => 'AMD Ryzen AI 7 445 (Radeon 840M graphics)',
            'ram' => '32GB LPDDR5x-8533',
            'storage' => '1TB SSD',
            'os' => 'Windows 11',
        ],
        'description' => <<<'HTML'
<p>The Zenbook 14 OLED is the thin laptop for people who refuse to compromise on memory: 32GB of fast LPDDR5x and a 1TB SSD alongside AMD's Ryzen AI 7 445. In Nepal it costs <strong>Rs 2,54,999</strong>, which is MacBook Air M5 territory.</p>
<p>The 14-inch OLED touchscreen covers the full DCI-P3 colour space, making it a good choice for photographers who prefer Windows. Battery life is solid rather than Mac-level.</p>
HTML,
        'faqs' => [
            ['What is the ASUS Zenbook 14 OLED UM3406GA price in Nepal?', 'Rs 2,54,999 for the Ryzen AI 7 445 model with 32GB RAM and 1TB SSD.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'ASUS Zenbook 14 OLED (UM3406GA) Price in Nepal & Specs',
            'description' => 'Zenbook 14 OLED UM3406GA costs Rs 2,54,999 in Nepal. Ryzen AI 7 445, 32GB RAM, 1TB SSD, 14-inch OLED touchscreen.',
            'keywords' => 'asus zenbook 14 oled price in nepal, um3406ga nepal',
        ],
        'image_alt' => 'ASUS Zenbook 14 OLED',
    ],

    [
        'name' => 'HP Victus 15', 'slug' => 'hp-victus-15', 'brand' => 'HP', 'category' => 'laptop',
        'variants' => [
            ['edition' => 'Ryzen 5 8645HS · RTX 3050 6GB', 'ram' => '16GB', 'storage' => '512GB', 'price' => 149000],
            ['edition' => 'Core i5-13420H · RTX 4050 6GB', 'ram' => '16GB', 'storage' => '512GB', 'price' => 160999],
        ],
        'history' => [['2026-11-19', 149000]],
        'specs' => [
            'display' => '15.6-inch FHD IPS, 144Hz',
            'processor' => 'AMD Ryzen 5 8645HS or Intel Core i5-13420H',
            'ram' => '16GB DDR5-5600',
            'storage' => '512GB PCIe Gen4 SSD',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 3050 6GB or RTX 4050 6GB'],
        ],
        'description' => <<<'HTML'
<p>The HP Victus 15 is the most affordable way into proper GPU gaming in Nepal right now. The <strong>Rs 1,49,000</strong> configuration pairs a Ryzen 5 8645HS with an RTX 3050; for about Rs 12,000 more you get an RTX 4050 with a Core i5-13420H, which is the version we'd pick for current games at 1080p. Select a configuration above to switch prices.</p>
<p>Both have 16GB of DDR5, a 144Hz screen and 512GB of storage you'll want to expand. If your budget stretches to Rs 1.95 lakh, the <a href="/products/lenovo-loq-15-2025">Lenovo LOQ 15</a> moves you up to an RTX 5050.</p>
HTML,
        'faqs' => [
            ['What is the HP Victus 15 price in Nepal?', 'Rs 1,49,000 with Ryzen 5 8645HS and RTX 3050, or Rs 1,60,999 with Core i5-13420H and RTX 4050. Both have 16GB RAM and 512GB SSD.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'HP Victus 15 Price in Nepal: RTX 3050 & RTX 4050 Variants',
            'description' => 'HP Victus 15 starts at Rs 1,49,000 in Nepal (RTX 3050). Compare the RTX 4050 model at Rs 1,60,999 — specs for budget gamers.',
            'keywords' => 'hp victus 15 price in nepal, budget gaming laptop nepal, rtx 4050 laptop nepal',
        ],
        'image_alt' => 'HP Victus 15 gaming laptop',
    ],

    [
        'name' => 'Lenovo LOQ 15 (2025)', 'slug' => 'lenovo-loq-15-2025', 'brand' => 'Lenovo', 'category' => 'laptop',
        'trending' => true,
        'variants' => [['edition' => 'Ryzen 5 220 · RTX 5050', 'ram' => '16GB', 'storage' => '512GB', 'price' => 194999]],
        'history' => [['2026-11-19', 194999]],
        'specs' => [
            'display' => '15.6-inch FHD, 144Hz, 300 nits, 100% sRGB',
            'processor' => 'AMD Ryzen 5 220 (6 cores, up to 4.9GHz)',
            'ram' => '16GB DDR5',
            'storage' => '512GB PCIe Gen4 SSD',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 5050 (100W TGP)', 'Model' => '15AHP10'],
        ],
        'description' => <<<'HTML'
<p>The LOQ 15 is Lenovo's budget gaming line, and the 2025 model with an RTX 5050 costs <strong>Rs 1,94,999</strong> in Nepal. The GPU runs at a full 100W, which matters more than the name on the box — many cheaper laptops starve the same chip of power.</p>
<p>The 144Hz screen covers 100% of sRGB, so it's also decent for photo editing, and Lenovo's cooling is quieter than most at this price. Upgrade the 512GB SSD early; modern games fill it fast.</p>
HTML,
        'faqs' => [
            ['What is the Lenovo LOQ 15 (2025) price in Nepal?', 'Rs 1,94,999 for the Ryzen 5 220, RTX 5050, 16GB RAM and 512GB SSD configuration.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'Lenovo LOQ 15 (2025) Price in Nepal: RTX 5050 Gaming Laptop',
            'description' => 'Lenovo LOQ 15 (15AHP10) costs Rs 1,94,999 in Nepal. Ryzen 5 220, RTX 5050 at 100W, 144Hz sRGB screen — specs and verdict.',
            'keywords' => 'lenovo loq 15 price in nepal, rtx 5050 laptop nepal',
        ],
        'image_alt' => 'Lenovo LOQ 15 gaming laptop',
    ],

    [
        'name' => 'Acer Nitro V 16 AI', 'slug' => 'acer-nitro-v-16-ai', 'brand' => 'Acer', 'category' => 'laptop',
        'variants' => [['edition' => 'Ryzen 7 350 · RTX 5060', 'ram' => '16GB', 'storage' => '1TB', 'price' => 230999]],
        'history' => [['2026-11-19', 230999]],
        'specs' => [
            'display' => '16-inch WUXGA IPS, 180Hz, 300 nits, 100% sRGB',
            'processor' => 'AMD Ryzen 7 350',
            'ram' => '16GB DDR5-5600',
            'storage' => '1TB PCIe Gen4 SSD',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 5060 8GB GDDR7 (95W TGP)', 'Model' => 'ANV16-42'],
        ],
        'description' => <<<'HTML'
<p>Around Rs 2.3 lakh, the Acer Nitro V 16 AI is the cheapest RTX 5060 laptop in our list at <strong>Rs 2,30,999</strong>, and it comes with a 1TB SSD and a taller 16-inch 180Hz screen — useful for both games and spreadsheets. The Ryzen 7 350 is a modern, efficient chip.</p>
<p>Acer runs the GPU at 95W; the <a href="/products/lenovo-legion-5-15ahp10">Legion 5</a> pushes the same RTX 5060 to 115W for better frame rates, for about Rs 29,000 more.</p>
HTML,
        'faqs' => [
            ['What is the Acer Nitro V 16 AI price in Nepal?', 'Rs 2,30,999 for the Ryzen 7 350, RTX 5060, 16GB RAM and 1TB SSD model.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'Acer Nitro V 16 AI Price in Nepal: RTX 5060, 180Hz Screen',
            'description' => 'Acer Nitro V 16 AI costs Rs 2,30,999 in Nepal. Ryzen 7 350, RTX 5060, 1TB SSD and a 16-inch 180Hz display.',
            'keywords' => 'acer nitro v 16 ai price in nepal, rtx 5060 laptop nepal',
        ],
        'image_alt' => 'Acer Nitro V 16 AI',
    ],

    [
        'name' => 'Lenovo Legion 5 (2025)', 'slug' => 'lenovo-legion-5-15ahp10', 'brand' => 'Lenovo', 'category' => 'laptop',
        'variants' => [['edition' => 'Ryzen 7 260 · RTX 5060', 'ram' => '16GB', 'storage' => '512GB', 'price' => 259999]],
        'history' => [['2026-11-19', 259999]],
        'specs' => [
            'display' => '15.3-inch WUXGA IPS, 165Hz, 300 nits, 100% sRGB',
            'processor' => 'AMD Ryzen 7 260 (8 cores, up to 5.1GHz)',
            'ram' => '16GB DDR5-5600',
            'storage' => '512GB PCIe Gen4 SSD',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 5060 8GB GDDR7 (115W TGP)', 'Model' => '15AHP10'],
        ],
        'description' => <<<'HTML'
<p>The Legion 5 is the mid-range gaming laptop we'd shortlist first. At <strong>Rs 2,59,999</strong> it runs the RTX 5060 at 115W, the highest power level in this price band, with a 165Hz 15.3-inch screen and Lenovo's well-regarded cooling. Some sellers quote Rs 2,49,999, so ask before you pay.</p>
<p>The trade-off is the 512GB SSD, small for a gaming machine. For a cheaper RTX 5060 option, see the <a href="/products/acer-nitro-v-16-ai">Acer Nitro V 16 AI</a>.</p>
HTML,
        'faqs' => [
            ['What is the Lenovo Legion 5 (2025) price in Nepal?', 'Rs 2,59,999 for the Ryzen 7 260, RTX 5060, 16GB/512GB model. Some sellers quote Rs 2,49,999.'],
        ],
        'sources' => [$gbBest],
        'seo' => [
            'title' => 'Lenovo Legion 5 (2025) Price in Nepal: RTX 5060 at 115W',
            'description' => 'Lenovo Legion 5 (15AHP10) costs about Rs 2,59,999 in Nepal. Ryzen 7 260, full-power RTX 5060, 165Hz display — specs and value.',
            'keywords' => 'lenovo legion 5 price in nepal, legion 5 2025 nepal',
        ],
        'image_alt' => 'Lenovo Legion 5 gaming laptop',
    ],

    [
        'name' => 'Lenovo Legion Pro 7i (2025)', 'slug' => 'lenovo-legion-pro-7i-2025', 'brand' => 'Lenovo', 'category' => 'laptop',
        'variants' => [
            ['edition' => 'RTX 5080', 'ram' => '32GB', 'storage' => '1TB', 'price' => 773900],
            ['edition' => 'RTX 5090', 'ram' => '32GB', 'storage' => '1TB', 'price' => 993900],
        ],
        'history' => [['2026-10-08', 773900]],
        'specs' => [
            'display' => '16-inch OLED WQXGA (2560×1600), 240Hz, up to 500 nits, 100% DCI-P3',
            'processor' => 'Intel Core Ultra 9 275HX (24 cores, up to 5.4GHz)',
            'ram' => '32GB DDR5-6400 (upgradeable to 64GB)',
            'storage' => '1TB PCIe Gen4 SSD (two slots)',
            'battery' => '80Wh',
            'weight' => '2.57kg',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 5080 16GB or RTX 5090 (175W TGP)'],
        ],
        'description' => <<<'HTML'
<p>If money is no object, the Legion Pro 7i is the gaming laptop to beat in Nepal. The RTX 5080 version costs <strong>Rs 7,73,900</strong> and the RTX 5090 version just under Rs 10 lakh at Rs 9,93,900; both use a Core Ultra 9 275HX, 32GB of DDR5 and a 240Hz OLED panel.</p>
<p>Lenovo lets the GPU draw a full 175W, and a second SSD slot makes storage upgrades easy. At 2.57kg it is a desktop replacement, not something to carry daily. For a slightly cheaper RTX 5080 with a Mini-LED screen, compare the <a href="/products/asus-rog-strix-scar-16-2025">ROG Strix SCAR 16</a>.</p>
HTML,
        'faqs' => [
            ['What is the Lenovo Legion Pro 7i price in Nepal?', 'Rs 7,73,900 for the RTX 5080 model and Rs 9,93,900 for the RTX 5090 model, both with Core Ultra 9 275HX, 32GB RAM and 1TB SSD.'],
        ],
        'sources' => [$gbGaming],
        'seo' => [
            'title' => 'Lenovo Legion Pro 7i (2025) Price in Nepal: RTX 5080 & 5090',
            'description' => 'Legion Pro 7i costs Rs 7,73,900 (RTX 5080) or Rs 9,93,900 (RTX 5090) in Nepal. 240Hz OLED, Core Ultra 9 275HX, 175W GPU.',
            'keywords' => 'legion pro 7i price in nepal, rtx 5090 laptop nepal, rtx 5080 laptop nepal',
        ],
        'image_alt' => 'Lenovo Legion Pro 7i',
    ],

    [
        'name' => 'ASUS ROG Strix SCAR 16 (2025)', 'slug' => 'asus-rog-strix-scar-16-2025', 'brand' => 'ASUS', 'category' => 'laptop',
        'variants' => [['edition' => 'RTX 5080', 'ram' => '32GB', 'storage' => '1TB', 'price' => 715900]],
        'history' => [['2026-10-08', 715900]],
        'specs' => [
            'display' => '16-inch Mini LED, 2560×1600, 240Hz, 100% DCI-P3',
            'processor' => 'Intel Core Ultra 9 275HX',
            'ram' => '32GB DDR5-6400',
            'storage' => '1TB PCIe Gen4 SSD',
            'battery' => '90Wh',
            'weight' => '2.8kg',
            'os' => 'Windows 11',
            'extra' => ['Graphics' => 'NVIDIA RTX 5080 16GB GDDR7 (175W TGP)'],
        ],
        'description' => <<<'HTML'
<p>The ROG Strix SCAR 16 costs <strong>Rs 7,15,900</strong> in Nepal for an RTX 5080 model — about Rs 58,000 less than the equivalent <a href="/products/lenovo-legion-pro-7i-2025">Legion Pro 7i</a>. The main difference is the screen: a bright Mini LED panel instead of OLED, which is better for HDR games and avoids any OLED burn-in worries on static HUDs.</p>
<p>It is heavy at 2.8kg and loud under load, as you'd expect from a 175W GPU, but few laptops in Nepal are faster.</p>
HTML,
        'faqs' => [
            ['What is the ROG Strix SCAR 16 (2025) price in Nepal?', 'Rs 7,15,900 for the Core Ultra 9 275HX and RTX 5080 model with 32GB RAM.'],
        ],
        'sources' => [$gbGaming],
        'seo' => [
            'title' => 'ASUS ROG Strix SCAR 16 (2025) Price in Nepal: RTX 5080',
            'description' => 'ROG Strix SCAR 16 (2025) costs Rs 7,15,900 in Nepal. Core Ultra 9 275HX, RTX 5080 at 175W, 240Hz Mini LED display.',
            'keywords' => 'rog strix scar 16 price in nepal, asus rog laptop nepal',
        ],
        'image_alt' => 'ASUS ROG Strix SCAR 16',
    ],
];
