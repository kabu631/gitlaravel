<?php

/*
 * Announced-but-not-in-Nepal and upcoming devices. Every price here is an estimate and is
 * flagged as one on the site (price_is_estimate). The basis for each estimate is written into
 * the description so readers can judge it.
 */

$src = fn (string $label, string $url, string $date = '2026-11-23') => ['label' => $label, 'url' => $url, 'accessed' => $date];

return [

    /* ─────────────── Announced globally, Nepal price pending ─────────────── */

    [
        'name' => 'Apple MacBook Pro 14 (M6)', 'slug' => 'macbook-pro-14-m6', 'brand' => 'Apple', 'category' => 'laptop',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Nepal availability not announced',
        'featured' => true,
        'variants' => [
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 385999],
            ['ram' => '24GB', 'storage' => '1TB', 'price' => 423599],
            ['ram' => '32GB', 'storage' => '1TB', 'price' => 462999],
            ['ram' => '16GB', 'storage' => '2TB', 'price' => 481999],
            ['ram' => '24GB', 'storage' => '2TB', 'price' => 519599],
        ],
        'history' => [],
        'specs' => [
            'display' => '14.2-inch Liquid Retina XDR (3024×1964), ProMotion 120Hz, 1,000 nits sustained / 1,600 nits HDR peak; nano-texture optional',
            'processor' => 'Apple M6 (12-core CPU, 12-core GPU with Neural Accelerators)',
            'ram' => '16GB, 24GB or 32GB unified memory',
            'storage' => '1TB, 2TB or 4TB SSD',
            'battery' => '72.4Wh; up to 24 hours video streaming, 16 hours wireless web; 70W adapter included',
            'os' => 'macOS 27',
            'connectivity' => '3× Thunderbolt 4, HDMI, SDXC, MagSafe 3, headphone jack; Wi-Fi 7, Bluetooth 6 (Apple N1)',
            'weight' => 'About 1.55kg',
            'extra' => ['Camera' => '12MP Center Stage', 'Colours' => 'Space Black, Silver', 'US price' => 'From $1,999 (16GB / 1TB)'],
        ],
        'description' => <<<'HTML'
<p>Apple launched the M6 MacBook Pro 14 globally at the end of October 2026, starting at $1,999 with 16GB of memory and 1TB of storage. It isn't officially on sale in Nepal yet. We expect it to start at around <strong>Rs 3,85,999</strong> once it arrives — an estimate converted from the US price, not an official figure.</p>
<p>Choose a memory and storage option above to see the expected price for each configuration. Storage is the expensive upgrade: going from 1TB to 2TB adds about Rs 96,000, while 16GB to 24GB of memory adds about Rs 37,600.</p>
<h3>Should you wait, or buy the M5?</h3>
<p>The M6 keeps the same mini-LED screen and design as the <a href="/products/macbook-pro-14-m5">M5 MacBook Pro 14</a>, with a faster chip, Wi-Fi 7 and up to 24 hours of battery life. If you need a laptop now, the M5 is available in Nepal at a fixed price. If you want the new tandem-OLED touchscreen, that's a different, much pricier model: the <a href="/products/macbook-pro-proled-xdr">MacBook Pro with ProLED XDR display</a>.</p>
HTML,
        'faqs' => [
            ['What is the expected MacBook Pro M6 price in Nepal?', 'We expect about Rs 3,85,999 for the 16GB/1TB model, based on the $1,999 US price. Apple has not announced a Nepal price.'],
            ['Does the M6 MacBook Pro have an OLED screen?', 'No. The 14-inch M6 model keeps the mini-LED Liquid Retina XDR display. The OLED touchscreen is on the separate MacBook Pro with ProLED XDR display.'],
        ],
        'sources' => [['accessed' => '2026-10-28']],
        'launch_highlight' => 'M6 chip, Wi-Fi 7, up to 24-hour battery',
        'seo' => [
            'title' => 'MacBook Pro M6 Price in Nepal (Expected): All Configurations',
            'description' => 'MacBook Pro 14 M6 is expected from Rs 3,85,999 in Nepal (16GB/1TB). Expected prices for every configuration, specs, and M6 vs M5.',
            'keywords' => 'macbook pro m6 price in nepal, macbook pro 2026 nepal, m6 macbook nepal',
        ],
        'image_alt' => 'Apple MacBook Pro 14-inch with M6 chip',
    ],

    [
        'name' => 'Apple MacBook Pro with ProLED XDR Display', 'slug' => 'macbook-pro-proled-xdr', 'brand' => 'Apple', 'category' => 'laptop',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Nepal availability not announced',
        'variants' => [
            ['edition' => '14-inch · M5 Pro', 'ram' => '24GB', 'storage' => '1TB', 'price' => 535999],
            ['edition' => '16-inch · M5 Pro', 'ram' => '24GB', 'storage' => '1TB', 'price' => 629999],
            ['edition' => '14-inch · M5 Max', 'ram' => '36GB', 'storage' => '2TB', 'price' => 825999],
            ['edition' => '16-inch · M5 Max', 'ram' => '36GB', 'storage' => '2TB', 'price' => 879999],
        ],
        'history' => [],
        'specs' => [
            'display' => 'ProLED XDR tandem OLED touchscreen, ProMotion 10–120Hz, 2,000 nits peak, Dynamic Island; 14.2-inch (3024×1986) or 16.3-inch (3472×2266)',
            'processor' => 'Apple M5 Pro (up to 18-core CPU, 20-core GPU) or M5 Max (18-core CPU, up to 40-core GPU)',
            'ram' => '24GB to 128GB unified memory',
            'storage' => '1TB to 16TB SSD',
            'battery' => '72.6Wh (14-inch) / 100Wh (16-inch); up to 24 hours video on the 16-inch M5 Pro',
            'connectivity' => '3× Thunderbolt 5, HDMI, SDXC, MagSafe 3, headphone jack; Wi-Fi 7, Bluetooth 6',
            'weight' => '1.38kg (14-inch M5 Pro) to 1.84kg (16-inch M5 Max)',
            'extra' => ['Camera' => '12MP Center Stage', 'Colours' => 'Space Black, Silver', 'US price' => 'From $2,899 (14-inch M5 Pro)'],
        ],
        'description' => <<<'HTML'
<p>This is the biggest MacBook Pro redesign in years: a tandem-OLED "ProLED XDR" screen, touch support for the first time on a Mac, and a Dynamic Island like the iPhone. Apple announced it a day after the <a href="/products/macbook-pro-14-m6">M6 MacBook Pro 14</a>, with M5 Pro and M5 Max chips.</p>
<p>It isn't sold officially in Nepal yet. Converted from US prices, we expect about <strong>Rs 5,35,999</strong> for the 14-inch M5 Pro and up to Rs 8,79,999 for the 16-inch M5 Max. Pick a model above to compare. Expect the final shop prices to vary with import costs.</p>
<p>For most video editors and developers in Nepal, the regular <a href="/products/macbook-pro-14-m5-pro">MacBook Pro 14 M5 Pro</a> does the same work for far less. This model is for people who need the OLED screen for colour work or want the newest design.</p>
HTML,
        'faqs' => [
            ['What is the expected price of the OLED MacBook Pro in Nepal?', 'We expect about Rs 5,35,999 for the 14-inch M5 Pro, Rs 6,29,999 for the 16-inch M5 Pro, Rs 8,25,999 for the 14-inch M5 Max and Rs 8,79,999 for the 16-inch M5 Max. These are estimates from the US prices.'],
            ['Is the new MacBook Pro a touchscreen?', 'Yes. The MacBook Pro with ProLED XDR display is the first Mac with a touchscreen.'],
        ],
        'sources' => [['accessed' => '2026-10-29']],
        'launch_highlight' => 'Tandem OLED touchscreen and Dynamic Island',
        'seo' => [
            'title' => 'OLED MacBook Pro (ProLED XDR) Price in Nepal: Expected Prices',
            'description' => 'The MacBook Pro with ProLED XDR OLED touchscreen is expected from Rs 5,35,999 in Nepal. 14 and 16-inch, M5 Pro and M5 Max prices and specs.',
            'keywords' => 'oled macbook pro price in nepal, macbook pro touchscreen nepal, proled xdr',
        ],
        'image_alt' => 'Apple MacBook Pro with ProLED XDR display',
    ],

    /* ─────────────────────────────── Upcoming ─────────────────────────────── */

    [
        'name' => 'Samsung Galaxy S27 Ultra', 'slug' => 'samsung-galaxy-s27-ultra', 'brand' => 'Samsung', 'category' => 'mobile',
        'status' => 'upcoming', 'estimate' => true, 'expected_launch' => 'January–February 2027',
        'trending' => true,
        'price' => 240000,
        'history' => [],
        'specs' => [
            'processor' => 'Snapdragon 8 Elite Gen 6 for Galaxy (rumoured, 2nm)',
            'battery' => 'About 5,700mAh (rumoured, up from 5,000mAh)',
            'extra' => ['Camera (rumoured)' => 'Three cameras side by side in one horizontal module, smaller bump', 'S Pen' => 'Expected to stay', 'Line-up' => 'Galaxy S27, S27+, S27 Pro and S27 Ultra expected'],
        ],
        'description' => <<<'HTML'
<p>Samsung hasn't announced the Galaxy S27 Ultra. Leaks point to a Galaxy Unpacked event in January or February 2027, with shops getting stock a few weeks later. Everything below is rumour, so treat it that way.</p>
<h3>What the leaks say</h3>
<ul>
<li><strong>Chip:</strong> a 2nm Snapdragon 8 Elite Gen 6 (some reports say a "Pro" version).</li>
<li><strong>Battery:</strong> about 5,700mAh, the first increase from 5,000mAh in years.</li>
<li><strong>Design:</strong> leaked renders show three cameras side by side in one horizontal strip, with a much smaller bump.</li>
<li><strong>S Pen:</strong> still included, despite earlier rumours.</li>
</ul>
<h3>Expected price in Nepal</h3>
<p>Our estimate is about <strong>Rs 2,40,000</strong> for the base model. The <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a> launched at Rs 2,24,999, and US reports expect $1,399–1,499, up from $1,299. Indian outlets estimate about ₹1,49,990. We'll replace this estimate with the official price when Samsung Nepal announces it.</p>
HTML,
        'faqs' => [
            ['When will the Galaxy S27 Ultra launch?', 'Samsung has not confirmed a date. Leaks point to January or February 2027.'],
            ['What will the Galaxy S27 Ultra cost in Nepal?', 'Our estimate is about Rs 2,40,000, based on the S26 Ultra\'s Rs 2,24,999 launch price and reports of a higher US price. It is not an official figure.'],
        ],
        'sources' => [
            $src('Digit – Galaxy S27 Ultra leaks and India pricing', 'https://www.digit.in/news/mobile-phones/samsung-galaxy-s27-ultra-leaks-price-in-india-launch-timeline-design-camera-and-other-details-we-know.html'),
            $src('Tom\'s Guide – Galaxy S27 Ultra rumours', 'https://tomsguide.com/phones/samsung-phones/samsung-galaxy-s27-ultra-rumors-release-display-upgrades-and-everything-we-know-so-far'),
            $src('Android Headlines – S27 Ultra CAD renders', 'https://www.androidheadlines.com/exclusive-samsung-galaxy-s27-ultra-official-cad-renders-leak'),
        ],
        'launch_highlight' => 'Rumoured 5,700mAh battery and 2nm chip',
        'seo' => [
            'title' => 'Galaxy S27 Ultra Price in Nepal (Expected), Launch Date & Leaks',
            'description' => 'Galaxy S27 Ultra is expected in January–February 2027 at about Rs 2,40,000 in Nepal. Rumoured 2nm Snapdragon, 5,700mAh battery and new camera design.',
            'keywords' => 'galaxy s27 ultra price in nepal, s27 ultra launch date, samsung s27 nepal',
        ],
    ],

    [
        'name' => 'Samsung Galaxy S27', 'slug' => 'samsung-galaxy-s27', 'brand' => 'Samsung', 'category' => 'mobile',
        'status' => 'upcoming', 'estimate' => true, 'expected_launch' => 'January–February 2027',
        'price' => 145000,
        'history' => [],
        'specs' => [
            'extra' => ['Line-up' => 'Galaxy S27, S27+, S27 Pro and S27 Ultra expected', 'Status' => 'Not announced; specs unconfirmed'],
        ],
        'description' => <<<'HTML'
<p>The standard Galaxy S27 is expected alongside the <a href="/products/samsung-galaxy-s27-ultra">S27 Ultra</a> at Samsung's early-2027 Unpacked event. Reports suggest four models this time: S27, S27+, a new S27 Pro and the Ultra.</p>
<p>Very little is known about the base model's specs yet, so we haven't listed rumours we can't check. Our price estimate of <strong>about Rs 1,45,000</strong> is based on the <a href="/products/samsung-galaxy-s26">Galaxy S26</a>, which now sells for Rs 1,39,999 after a mid-year increase, plus the higher prices expected across the line-up.</p>
HTML,
        'faqs' => [
            ['When is the Galaxy S27 coming to Nepal?', 'Samsung usually brings its new S series to Nepal within a few weeks of the global launch, which is expected in January or February 2027.'],
            ['How much will the Galaxy S27 cost in Nepal?', 'We estimate about Rs 1,45,000, based on the Galaxy S26\'s current Rs 1,39,999 price. Samsung has not announced pricing.'],
        ],
        'sources' => [$src('PhoneArena – Galaxy S27 release date and price expectations', 'https://www.phonearena.com/galaxy-s27-release-date-price-features-news')],
        'launch_highlight' => 'Four-model line-up expected',
        'seo' => [
            'title' => 'Samsung Galaxy S27 Price in Nepal (Expected) & Launch Date',
            'description' => 'Galaxy S27 is expected in early 2027 at around Rs 1,45,000 in Nepal. What we know about the line-up, launch timing and price.',
            'keywords' => 'galaxy s27 price in nepal, samsung s27 launch date',
        ],
    ],

    [
        'name' => 'Apple iPhone 18', 'slug' => 'iphone-18', 'brand' => 'Apple', 'category' => 'mobile',
        'status' => 'upcoming', 'estimate' => true, 'expected_launch' => 'Spring 2027 (around March)',
        'trending' => true,
        'price' => 175000,
        'history' => [],
        'specs' => [
            'display' => '6.3-inch (rumoured)',
            'processor' => 'Apple A20, 2nm (reported)',
            'ram' => 'More than the iPhone 17\'s 8GB (reported)',
            'camera' => 'Dual 48MP rear, 24MP front (rumoured)',
        ],
        'description' => <<<'HTML'
<p>Apple split its 2026 line-up. The <a href="/products/iphone-18-pro">iPhone 18 Pro</a>, Pro Max and the foldable came first, and the standard iPhone 18 follows in spring 2027. Bloomberg's Mark Gurman has repeatedly reported the spring timing, and later supply-chain reports point to a March launch. Apple hasn't confirmed anything.</p>
<h3>Rumoured specs</h3>
<p>The phone is expected to have the 2nm A20 chip (one step below the Pro's A20 Pro), a 6.3-inch display, two 48MP rear cameras, a 24MP front camera and more RAM than the iPhone 17's 8GB.</p>
<h3>Expected price in Nepal</h3>
<p>Our estimate is <strong>about Rs 1,75,000</strong> for the base model. The <a href="/products/iphone-17">iPhone 17</a> sells for Rs 1,64,999 at a US price of $799, and reports expect the iPhone 18 at $849–899. Apple also raised Mac and iPad prices in June 2026, so a small increase looks likely.</p>
HTML,
        'faqs' => [
            ['When will the iPhone 18 launch?', 'Reports point to spring 2027, probably around March. Apple has not announced a date. The iPhone 18 Pro and Pro Max launched earlier, in autumn 2026.'],
            ['What will the iPhone 18 cost in Nepal?', 'Our estimate is about Rs 1,75,000, based on the iPhone 17\'s Rs 1,64,999 price and a rumoured US price of $849–899.'],
        ],
        'sources' => [
            $src('MacRumors – iPhone 18 roundup', 'https://www.macrumors.com/roundup/iphone-18/'),
            $src('Macworld – iPhone 18 and 18e release date, specs, price', 'https://www.macworld.com/article/3188152/iphone-18e-18-2027-release-date-specs-price-cameras.html'),
        ],
        'launch_highlight' => 'A20 chip, dual 48MP cameras (rumoured)',
        'seo' => [
            'title' => 'iPhone 18 Price in Nepal (Expected) & Spring 2027 Launch Date',
            'description' => 'The standard iPhone 18 is expected in spring 2027 at around Rs 1,75,000 in Nepal. Rumoured A20 chip, 6.3-inch display and dual 48MP cameras.',
            'keywords' => 'iphone 18 price in nepal, iphone 18 launch date, iphone 18 nepal',
        ],
    ],

    [
        'name' => 'Apple iPhone 18e', 'slug' => 'iphone-18e', 'brand' => 'Apple', 'category' => 'mobile',
        'status' => 'upcoming', 'estimate' => true, 'expected_launch' => 'Spring 2027',
        'price' => 135000,
        'history' => [],
        'specs' => [
            'processor' => 'Apple A20 (reported)',
            'camera' => 'Single 48MP rear (reported)',
            'extra' => ['Modem' => 'Apple C2 (reported)'],
        ],
        'description' => <<<'HTML'
<p>The iPhone 18e is expected with the standard <a href="/products/iphone-18">iPhone 18</a> in spring 2027. Reports suggest it will be close to the <a href="/products/iphone-17e">iPhone 17e</a> with a newer chip: the A20, a single 48MP camera and Apple's second-generation C2 modem. One leak claims Apple will keep a 60Hz screen again.</p>
<p>We estimate <strong>about Rs 1,35,000</strong> in Nepal. That assumes Apple holds the US price at $599, as some analysts expect, which would keep it close to the 17e's Rs 1,32,499. If Apple moves to the $649–699 that Macworld suggests, expect closer to Rs 1,50,000.</p>
HTML,
        'faqs' => [
            ['What will the iPhone 18e cost in Nepal?', 'We estimate around Rs 1,35,000 if Apple keeps the US price at $599, or closer to Rs 1,50,000 if it rises to $649–699. Apple has not announced pricing.'],
        ],
        'sources' => [
            $src('Macworld – iPhone 18 and 18e release date, specs, price', 'https://www.macworld.com/article/3188152/iphone-18e-18-2027-release-date-specs-price-cameras.html'),
            $src('9to5Mac – low-cost iPhone 18 details from leaker', 'https://9to5mac.com/2026/10/27/apples-low-cost-iphone-18-details-revealed-by-leaker/'),
        ],
        'launch_highlight' => 'A20 chip and C2 modem (reported)',
        'seo' => [
            'title' => 'iPhone 18e Price in Nepal (Expected) & Release Date',
            'description' => 'iPhone 18e is expected in spring 2027 at around Rs 1,35,000 in Nepal. Reported A20 chip, 48MP camera and C2 modem.',
            'keywords' => 'iphone 18e price in nepal, iphone 18e launch date',
        ],
    ],
];
