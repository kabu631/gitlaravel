<?php

/*
 * Gaming consoles, games and accessories.
 */

$src = fn (string $label, string $url, string $date = '2026-11-22') => ['label' => $label, 'url' => $url, 'accessed' => $date];
$gbPs5     = ['accessed' => '2026-11-20'];
$gbPs5Slim = ['accessed' => '2026-11-20'];
$gbPs5Pro  = ['accessed' => '2026-11-20'];
$gbXiaomiA = ['accessed' => '2026-02-11'];
$gbBoat    = ['accessed' => '2026-02-09'];

return [

    /* ─────────────────────────────── Gaming ─────────────────────────────── */

    [
        'name' => 'Sony PlayStation 5', 'slug' => 'sony-playstation-5', 'brand' => 'Sony', 'category' => 'gaming',
        'featured' => true, 'trending' => true,
        'variants' => [
            ['edition' => 'Digital Edition', 'ram' => '16GB GDDR6', 'storage' => '825GB SSD', 'price' => 95000],
            ['edition' => 'Slim · Disc Edition', 'ram' => '16GB GDDR6', 'storage' => '1TB SSD', 'price' => 139999],
            ['edition' => 'PS5 Pro', 'ram' => '16GB GDDR6 + 2GB DDR5', 'storage' => '2TB SSD', 'price' => 145000],
        ],
        'history' => [['2026-11-20', 95000]],
        'specs' => [
            'processor' => 'AMD Zen 2, 8 cores / 16 threads, up to 3.5GHz',
            'ram' => '16GB GDDR6, 256-bit, 448GB/s',
            'storage' => '825GB, 1TB or 2TB custom SSD + M.2 NVMe expansion slot',
            'connectivity' => 'Wi-Fi, Bluetooth, Ethernet, HDMI 2.1, USB-C',
            'extra' => [
                'GPU' => '10.3 TFLOPS (PS5 / Slim), 16.7 TFLOPS (PS5 Pro)',
                'Disc drive' => 'Built in on Disc Edition; optional detachable drive for Digital Edition and Pro, sold separately',
                'Output' => 'Up to 4K at 120Hz',
            ],
        ],
        'description' => <<<'HTML'
<p>There are three PlayStation 5 models to choose from in Nepal, and the price changes a lot between them. Pick one above to see its price:</p>
<ul>
<li><strong>Digital Edition (825GB):</strong> Rs 95,000. No disc drive, so every game is a download from the PlayStation Store.</li>
<li><strong>PS5 Slim Disc Edition (1TB):</strong> Rs 1,39,999. Plays physical Blu-ray games, which you can buy second-hand or swap with friends in Kathmandu.</li>
<li><strong>PS5 Pro (2TB):</strong> Rs 1,45,000. A 16.7-TFLOPS GPU (up from 10.3) gives sharper 4K and steadier frame rates in games with Pro enhancements.</li>
</ul>
<p>The RAM is the same on all three: 16GB of GDDR6. The Pro adds 2GB of DDR5 for system tasks, but the extra 6.4 TFLOPS of graphics power is what you're really paying for.</p>
<h3>Which PS5 should you buy?</h3>
<p>Most people should buy the Disc Edition if they play on a 1080p TV or want to save on games, because second-hand discs are the cheapest way to build a library in Nepal. The Pro only makes sense on a good 4K TV. If you're buying for <a href="/products/gta-6">GTA VI</a>, it runs on every model.</p>
<p>Remember that downloads are big. GTA VI alone is a very large install, and 825GB fills up quickly on a typical Nepali home connection, so think about adding an M.2 SSD later.</p>
<p>Prices were last checked on 20 November 2026. Shop prices vary with stock, so compare a couple of authorised sellers before buying.</p>
HTML,
        'faqs' => [
            ['What is the PS5 price in Nepal?', 'The Digital Edition (825GB) is Rs 95,000, the PS5 Slim Disc Edition (1TB) is Rs 1,39,999 and the PS5 Pro (2TB) is Rs 1,45,000, as of 20 November 2026.'],
            ['Does the PS5 Pro have more RAM than the normal PS5?', 'Both have 16GB of GDDR6. The Pro adds 2GB of DDR5 for system tasks, and its main upgrade is a faster 16.7-TFLOPS GPU.'],
            ['Can the PS5 Digital Edition play discs?', 'Not out of the box. You can add Sony\'s detachable disc drive, which is sold separately.'],
            ['Can I add more storage to a PS5?', 'Yes. Every PS5 model has an M.2 NVMe expansion slot for an extra SSD.'],
        ],
        'sources' => [$gbPs5, $gbPs5Slim, $gbPs5Pro],
        'seo' => [
            'title' => 'PS5 Price in Nepal 2026: Digital, Slim Disc & PS5 Pro Compared',
            'description' => 'PS5 price in Nepal: Digital Rs 95,000, Slim Disc 1TB Rs 1,39,999, PS5 Pro 2TB Rs 1,45,000. Storage, RAM and GPU differences explained.',
            'keywords' => 'ps5 price in nepal, ps5 pro price in nepal, ps5 slim price in nepal, playstation 5 nepal',
        ],
        'image_alt' => 'Sony PlayStation 5 console with DualSense controller',
    ],

    [
        'name' => 'Sony DualSense Wireless Controller', 'slug' => 'sony-dualsense-wireless-controller', 'brand' => 'Sony', 'category' => 'gaming',
        'price' => 9500,
        'history' => [['2026-11-20', 9500]],
        'specs' => [
            'battery' => 'Built-in rechargeable, USB-C charging',
            'connectivity' => 'Bluetooth, USB-C wired',
            'extra' => ['Features' => 'Haptic feedback, adaptive triggers, built-in microphone and speaker', 'Compatible with' => 'PS5, PC'],
        ],
        'description' => <<<'HTML'
<p>A second DualSense controller costs <strong>Rs 9,500</strong> in Nepal — worth it if you play football or racing games with friends. The adaptive triggers and haptic feedback are what make PS5 games feel different from older consoles. The DualSense Charging Station is listed at Rs 6,500 if you want to charge two controllers at once.</p>
<p>It also works on PC over Bluetooth or USB-C. See the full <a href="/products/sony-playstation-5">PS5 price list</a>.</p>
HTML,
        'faqs' => [['What is the DualSense controller price in Nepal?', 'The DualSense Wireless Controller costs Rs 9,500 in Nepal as of 20 November 2026.']],
        'sources' => [$gbPs5],
        'seo' => [
            'title' => 'PS5 DualSense Controller Price in Nepal',
            'description' => 'The PS5 DualSense Wireless Controller costs Rs 9,500 in Nepal. Haptic feedback, adaptive triggers and PC support.',
            'keywords' => 'dualsense price in nepal, ps5 controller price in nepal',
        ],
        'image_alt' => 'Sony DualSense wireless controller for PS5',
    ],

    [
        'name' => 'Grand Theft Auto VI', 'slug' => 'gta-6', 'brand' => 'Rockstar Games', 'category' => 'games',
        'featured' => true, 'trending' => true,
        'release_date' => '2026-11-19',
        'variants' => [
            ['edition' => 'Standard Edition (PS5)', 'price' => 13499],
            ['edition' => 'Ultimate Edition (PS5)', 'price' => 16999],
        ],
        'history' => [['2026-11-19', 13499]],
        'specs' => [
            'extra' => [
                'Platforms' => 'PlayStation 5, Xbox Series X|S (no PC version announced)',
                'Developer' => 'Rockstar Games',
                'Publisher' => 'Rockstar Games (Take-Two Interactive)',
                'Release date' => '19 November 2026',
                'Genre' => 'Open-world action-adventure',
                'Setting' => 'The state of Leonida, including Vice City',
                'US price' => '$79.99 Standard / $99.99 Ultimate (PlayStation Store)',
            ],
        ],
        'description' => <<<'HTML'
<p>GTA VI finally came out on <strong>19 November 2026</strong> for PS5 and Xbox Series X|S, after being delayed twice. It is set in the state of Leonida, a fictional take on Florida that includes Vice City, and follows Lucia and Jason.</p>
<h3>GTA VI price in Nepal</h3>
<p>There is no official Rockstar price for Nepal. Buyers either pay the PlayStation Store price in dollars ($79.99 Standard, $99.99 Ultimate) with a dollar card, or buy a code or account from a local game shop. Expect about <strong>Rs 13,499 for the Standard Edition</strong> and Rs 16,999 for the Ultimate Edition. You can switch editions above.</p>
<p>Some shops advertise much cheaper "secondary" accounts. Those are shared accounts, not your own copy, and can stop working if the original owner changes the password. Be careful with them.</p>
<h3>A record launch</h3>
<p>Variety reported that Take-Two counted $3.79 billion in sales in the first 24 hours. Sensor Tower's separate estimate was about 24.6 million copies on launch day, around 18.4 million of them on PS5. The two figures measure different things, but either way it was one of the biggest entertainment launches ever.</p>
<p>Don't have a console yet? Compare the <a href="/products/sony-playstation-5">PS5 Digital, Slim and Pro prices</a> in Nepal. GTA VI runs on all three, and the Pro gives you better image quality on a 4K TV.</p>
HTML,
        'faqs' => [
            ['When was GTA VI released?', 'GTA VI was released on 19 November 2026 for PlayStation 5 and Xbox Series X|S.'],
            ['What is the GTA 6 price in Nepal?', 'There is no official Nepal price. Local shops charge about Rs 13,499 for the Standard Edition and Rs 16,999 for the Ultimate Edition. On the PlayStation Store it costs $79.99 and $99.99.'],
            ['Is GTA VI available on PC?', 'Not yet. Rockstar launched it on PS5 and Xbox Series X|S and has not announced a PC version.'],
            ['Is GTA VI on PS4?', 'No. It is only on current-generation consoles: PS5 and Xbox Series X|S.'],
        ],
        'sources' => [
            ['accessed' => '2026-11-22'],
            $src('Variety – GTA 6 first-day sales', 'https://variety.com/2026/gaming/news/gta-6-sales-billions-first-day-1236954198/'),
            $src('HotHardware – Sensor Tower launch estimate', 'https://hothardware.com/news/gta-6-launch-haul-dwarfs-gta-5s-day-one-record'),
        ],
        'seo' => [
            'title' => 'GTA 6 Price in Nepal: Standard & Ultimate Edition, Release Date',
            'description' => 'GTA VI launched on 19 November 2026. Price in Nepal: Standard Rs 13,499, Ultimate Rs 16,999 at local shops. Platforms, editions and what to avoid.',
            'keywords' => 'gta 6 price in nepal, gta vi nepal, gta 6 release date, gta 6 ps5 nepal',
        ],
        'image_alt' => 'Grand Theft Auto VI official key art',
    ],

    [
        'name' => 'Nintendo Switch 2', 'slug' => 'nintendo-switch-2', 'brand' => 'Nintendo', 'category' => 'gaming',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'No official Nepal price yet',
        'price' => 69999,
        'history' => [],
        'specs' => [
            'display' => '7.9-inch LCD, 1080p, up to 120Hz, HDR',
            'storage' => '256GB',
            'extra' => ['Output' => 'Up to 4K when docked', 'Controllers' => 'Magnetic Joy-Con 2 with mouse mode', 'US price' => '$499 from 1 September 2026 (was $449.99)'],
        ],
        'description' => <<<'HTML'
<p>The Nintendo Switch 2 isn't officially sold in Nepal, so the consoles you see in Kathmandu shops are imported by the shops themselves. That's why we show an <strong>expected price of about Rs 70,000</strong>, our own estimate rather than an official figure.</p>
<p>Here's how we got that number. At the $449.99 launch price, imported units started at roughly Rs 62,000. Nintendo then raised the US price to $499 from 1 September 2026, and prices also went up in Japan and South Korea. Expect shop prices around Rs 70,000 or more, depending on the bundle.</p>
<p>Before buying, check whether the console is the Japanese-language model, which can be locked to Japanese. If you mainly want big-budget games like <a href="/products/gta-6">GTA VI</a>, which isn't on Switch 2, a <a href="/products/sony-playstation-5">PS5</a> is the better buy.</p>
HTML,
        'faqs' => [
            ['What is the Nintendo Switch 2 price in Nepal?', 'There is no official Nepal price. Based on the US price rising to $499 in September 2026, we expect imported units to cost around Rs 70,000 or more.'],
            ['Is GTA VI available on the Switch 2?', 'No. GTA VI is only on PS5 and Xbox Series X|S.'],
        ],
        'sources' => [
            ['accessed' => '2026-11-22'],
            $src('Digital Trends – Switch 2 US price increase', 'https://www.digitaltrends.com/gaming/nintendo-just-announced-a-price-hike-for-the-switch-2-in-the-us/'),
        ],
        'launch_highlight' => 'No official Nepal price; imports only',
        'seo' => [
            'title' => 'Nintendo Switch 2 Price in Nepal (Expected) & Specs',
            'description' => 'Nintendo Switch 2 has no official Nepal price. After the US price rose to $499, we expect around Rs 70,000 for imported units. Specs and buying tips.',
            'keywords' => 'nintendo switch 2 price in nepal, switch 2 nepal',
        ],
        'image_alt' => 'Nintendo Switch 2 console',
    ],

    /* ─────────────────────────────── Accessories ─────────────────────────────── */

    [
        'name' => 'Xiaomi Sound Pocket', 'slug' => 'xiaomi-sound-pocket', 'brand' => 'Xiaomi', 'category' => 'accessory', 'accessory_type' => 'speaker',
        'price' => 2499,
        'history' => [['2026-02-11', 2499]],
        'specs' => ['extra' => ['Output' => '5W']],
        'description' => <<<'HTML'
<p>The Xiaomi Sound Pocket is a small 5W Bluetooth speaker for <strong>Rs 2,499</strong> — loud enough for a bedroom or a picnic, small enough to throw in a bag. For a fuller sound at parties, step up to the <a href="/products/mi-portable-bluetooth-speaker-16w">Mi Portable Bluetooth Speaker (16W)</a>.</p>
HTML,
        'faqs' => [['What is the Xiaomi Sound Pocket price in Nepal?', 'The Xiaomi Sound Pocket costs Rs 2,499.']],
        'sources' => [$gbXiaomiA],
        'seo' => [
            'title' => 'Xiaomi Sound Pocket Price in Nepal: 5W Bluetooth Speaker',
            'description' => 'Xiaomi Sound Pocket costs Rs 2,499 in Nepal. A compact 5W Bluetooth speaker for travel and small rooms.',
            'keywords' => 'xiaomi sound pocket price in nepal, bluetooth speaker nepal',
        ],
        'image_alt' => 'Xiaomi Sound Pocket Bluetooth speaker',
    ],

    [
        'name' => 'Mi Portable Bluetooth Speaker (16W)', 'slug' => 'mi-portable-bluetooth-speaker-16w', 'brand' => 'Xiaomi', 'category' => 'accessory', 'accessory_type' => 'speaker',
        'price' => 6599,
        'history' => [['2026-02-11', 6599]],
        'specs' => ['extra' => ['Output' => '16W']],
        'description' => <<<'HTML'
<p>At <strong>Rs 6,599</strong>, the 16W Mi Portable Bluetooth Speaker has the power for a terrace gathering or a small party. If you only need something for your desk or a short trip, the <a href="/products/xiaomi-sound-pocket">Xiaomi Sound Pocket</a> costs much less.</p>
HTML,
        'faqs' => [['What is the Mi Portable Bluetooth Speaker 16W price in Nepal?', 'It costs Rs 6,599.']],
        'sources' => [$gbXiaomiA],
        'seo' => [
            'title' => 'Mi Portable Bluetooth Speaker 16W Price in Nepal',
            'description' => 'Mi Portable Bluetooth Speaker (16W) costs Rs 6,599 in Nepal — Xiaomi\'s louder portable speaker for parties and outdoors.',
            'keywords' => 'mi portable bluetooth speaker 16w price in nepal',
        ],
        'image_alt' => 'Mi Portable Bluetooth Speaker 16W',
    ],

    [
        'name' => 'boAt EnergyShroom PB-331', 'slug' => 'boat-energyshroom-pb-331', 'brand' => 'boAt', 'category' => 'accessory', 'accessory_type' => 'power-bank',
        'price' => 4299, 'old_price' => 4799,
        'history' => [['2026-02-09', 4299]],
        'specs' => ['battery' => '20,000mAh'],
        'description' => <<<'HTML'
<p>A 20,000mAh power bank is worth having in Nepal, whether for long bus rides, treks or power cuts. The boAt EnergyShroom PB-331 holds enough to charge most phones three to four times, and its offer price is <strong>Rs 4,299</strong> (MRP Rs 4,799).</p>
<p>A 20,000mAh pack is heavy, though. If you only need a top-up on the way home, boAt's smaller power banks start at about Rs 2,000.</p>
HTML,
        'faqs' => [['What is the boAt EnergyShroom PB-331 price in Nepal?', 'The offer price is Rs 4,299, against an MRP of Rs 4,799.']],
        'sources' => [$gbBoat],
        'seo' => [
            'title' => 'boAt EnergyShroom PB-331 Price in Nepal: 20,000mAh Power Bank',
            'description' => 'boAt EnergyShroom PB-331 20,000mAh power bank costs Rs 4,299 in Nepal (MRP Rs 4,799). Enough for three to four phone charges.',
            'keywords' => 'boat power bank price in nepal, 20000mah power bank nepal',
        ],
        'image_alt' => 'boAt EnergyShroom PB-331 power bank',
    ],

    [
        'name' => 'Mi Dual Mode Wireless Mouse', 'slug' => 'mi-dual-mode-wireless-mouse', 'brand' => 'Xiaomi', 'category' => 'accessory', 'accessory_type' => 'mouse',
        'price' => 1499,
        'history' => [['2026-02-11', 1499]],
        'specs' => ['connectivity' => 'Bluetooth and 2.4GHz USB receiver'],
        'description' => <<<'HTML'
<p>Xiaomi's Dual Mode mouse connects over Bluetooth or its USB receiver, so you can switch between a laptop and a desktop. At <strong>Rs 1,499</strong> it's an easy add-on for students buying a laptop such as the <a href="/products/macbook-air-m5-13">MacBook Air</a> or <a href="/products/hp-victus-15">HP Victus</a>.</p>
HTML,
        'faqs' => [['What is the Mi Dual Mode Wireless Mouse price in Nepal?', 'It costs about Rs 1,499 in Nepal.']],
        'sources' => [$gbXiaomiA],
        'seo' => [
            'title' => 'Mi Dual Mode Wireless Mouse Price in Nepal',
            'description' => 'Mi Dual Mode Wireless Mouse costs Rs 1,499 in Nepal. Bluetooth and USB receiver in one, for laptops and desktops.',
            'keywords' => 'mi wireless mouse price in nepal, xiaomi mouse nepal',
        ],
        'image_alt' => 'Mi Dual Mode Wireless Mouse',
    ],

    [
        'name' => 'Xiaomi Mi Router 4A', 'slug' => 'xiaomi-mi-router-4a', 'brand' => 'Xiaomi', 'category' => 'accessory', 'accessory_type' => 'router',
        'price' => 3999,
        'history' => [['2026-02-11', 3999]],
        'specs' => ['connectivity' => 'Dual-band Wi-Fi (2.4GHz + 5GHz)'],
        'description' => <<<'HTML'
<p>The ISP-supplied router is often the weak point of a Nepali home connection. The Mi Router 4A (<strong>Rs 3,999</strong>) is an inexpensive dual-band upgrade that spreads Wi-Fi better across a flat. If your plan is faster than about 100Mbps, look for a gigabit model instead, since a router can bottleneck a fast fibre line.</p>
HTML,
        'faqs' => [['What is the Mi Router 4A price in Nepal?', 'The Xiaomi Mi Router 4A costs Rs 3,999.']],
        'sources' => [$gbXiaomiA],
        'seo' => [
            'title' => 'Xiaomi Mi Router 4A Price in Nepal: Dual-Band Wi-Fi',
            'description' => 'Xiaomi Mi Router 4A costs Rs 3,999 in Nepal. A dual-band Wi-Fi router upgrade for home fibre connections.',
            'keywords' => 'mi router 4a price in nepal, xiaomi router nepal, wifi router price in nepal',
        ],
        'image_alt' => 'Xiaomi Mi Router 4A',
    ],
];
