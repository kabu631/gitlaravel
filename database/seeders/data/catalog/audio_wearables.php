<?php

/*
 * Earbuds and smartwatches — prices from GadgetByte Nepal's "Best wireless earbuds" (updated
 * 13 Nov 2026), "Best smartwatch" (28 Sep 2026), Apple Watch (14 Oct 2026) and Xiaomi/boAt
 * accessory price lists. Apple Watch Series 12 and Ultra 4 carry GadgetByte's expected prices.
 */

$src = fn (string $label, string $url, string $date = '2026-11-22') => ['label' => $label, 'url' => $url, 'accessed' => $date];
$gbBuds    = $src('GadgetByte Nepal – Best wireless earbuds', 'https://gadgetbytenepal.com/best-wireless-earbuds-nepal/', '2026-11-13');
$gbWatch   = $src('GadgetByte Nepal – Best smartwatches in Nepal', 'https://www.gadgetbytenepal.com/best-smartwatch-nepal', '2026-09-28');
$gbApple   = $src('GadgetByte Nepal – Apple Watch price list', 'https://gadgetbytenepal.com/apple-watch-price-in-nepal', '2026-10-14');
$gbXiaomiA = $src('GadgetByte Nepal – Xiaomi accessories price list', 'https://www.gadgetbytenepal.com/xiaomi-accessories-price-nepal', '2026-02-11');
$gbBoat    = $src('GadgetByte Nepal – boAt accessories price list', 'https://www.gadgetbytenepal.com/boat-accessories-price-nepal', '2026-02-09');

return [

    /* ─────────────────────────────── Earbuds ─────────────────────────────── */

    [
        'name' => 'Apple AirPods Pro 3', 'slug' => 'airpods-pro-3', 'brand' => 'Apple', 'category' => 'earbuds',
        'featured' => true, 'trending' => true,
        'price' => 47999,
        'history' => [['2026-11-13', 47999]],
        'specs' => [
            'processor' => 'Apple H2 with custom high-excursion driver',
            'battery' => 'Up to 24 hours with ANC (earbuds + case)',
            'connectivity' => 'Bluetooth 5.3; AAC, SBC',
            'weight' => '5.55g per earbud',
            'extra' => ['Noise cancellation' => 'Active noise cancellation, Transparency mode', 'Water resistance' => 'IP57'],
        ],
        'description' => <<<'HTML'
<p>AirPods Pro 3 cost <strong>Rs 47,999</strong> in Nepal and remain the easiest recommendation for iPhone owners: noise cancellation that copes well with traffic and bus engines, instant pairing with Apple devices, and IP57 protection against sweat and rain.</p>
<p>They make much less sense on Android, where you lose automatic switching and most settings. Android users get better value from the <a href="/products/samsung-galaxy-buds4-pro">Galaxy Buds 4 Pro</a> or, for the best noise cancelling, the <a href="/products/sony-wf-1000xm6">Sony WF-1000XM6</a>.</p>
HTML,
        'faqs' => [
            ['What is the AirPods Pro 3 price in Nepal?', 'AirPods Pro 3 cost Rs 47,999 in Nepal.'],
            ['Are AirPods Pro 3 water resistant?', 'Yes, they are rated IP57 for dust and water resistance.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'AirPods Pro 3 Price in Nepal: ANC, Battery & Specs',
            'description' => 'AirPods Pro 3 cost Rs 47,999 in Nepal. Active noise cancellation, IP57, up to 24 hours with the case — and when Android users should skip them.',
            'keywords' => 'airpods pro 3 price in nepal, airpods pro nepal',
        ],
        'image_alt' => 'Apple AirPods Pro 3 earbuds',
    ],

    [
        'name' => 'Apple AirPods 5', 'slug' => 'airpods-5', 'brand' => 'Apple', 'category' => 'earbuds',
        'variants' => [
            ['edition' => 'Standard case', 'price' => 31000],
            ['edition' => 'Wireless charging case', 'price' => 36500],
        ],
        'history' => [['2026-11-13', 31000]],
        'specs' => [
            'battery' => 'Up to 22 hours total listening (earbuds + case)',
            'connectivity' => 'Bluetooth 5.3; AAC, SBC',
            'weight' => '4.3g per earbud',
            'extra' => ['Fit' => 'Open (semi in-ear)', 'Noise cancellation' => 'Active noise cancellation', 'Water resistance' => 'IP57'],
        ],
        'description' => <<<'HTML'
<p>AirPods 5 keep the open, no-silicone-tip fit that many people find more comfortable than the Pro, and now include active noise cancellation on every model. They cost <strong>Rs 31,000</strong> in Nepal, or Rs 36,500 with the wireless charging case — choose the edition above to compare.</p>
<p>Open-fit ANC can't block as much as sealed earbuds, so if you commute through noisy streets every day, the <a href="/products/airpods-pro-3">AirPods Pro 3</a> are worth the extra.</p>
HTML,
        'faqs' => [
            ['What is the AirPods 5 price in Nepal?', 'Rs 31,000 with the standard case, or Rs 36,500 with the wireless charging case.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'AirPods 5 Price in Nepal: Standard vs Wireless Charging Case',
            'description' => 'AirPods 5 cost Rs 31,000 in Nepal (Rs 36,500 with wireless charging). Open-fit design with ANC, IP57 and 22-hour battery.',
            'keywords' => 'airpods 5 price in nepal, airpods nepal',
        ],
        'image_alt' => 'Apple AirPods 5',
    ],

    [
        'name' => 'Samsung Galaxy Buds 4 Pro', 'slug' => 'samsung-galaxy-buds4-pro', 'brand' => 'Samsung', 'category' => 'earbuds',
        'price' => 35799,
        'history' => [['2026-11-13', 35799]],
        'specs' => [
            'battery' => 'Up to 26 hours with ANC / 30 hours without (earbuds + case)',
            'connectivity' => 'Bluetooth 6.1; AAC, SBC, SSC HiFi, SSC UHQ, LC3',
            'weight' => '5.1g per earbud',
            'extra' => ['Drivers' => '11mm woofer + 5.5mm tweeter', 'Noise cancellation' => 'Active noise cancellation', 'Water resistance' => 'IP57'],
        ],
        'description' => <<<'HTML'
<p>For Galaxy phone owners, the Buds 4 Pro at <strong>Rs 35,799</strong> are the natural pick. The two-way driver setup (11mm woofer plus 5.5mm tweeter) and Samsung's SSC UHQ codec give noticeably richer sound with a <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26</a>-series phone than plain AAC.</p>
<p>Battery life is good — up to 26 hours with noise cancelling on — and IP57 means sweat and rain are not a worry.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Buds 4 Pro price in Nepal?', 'Galaxy Buds 4 Pro cost Rs 35,799.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Samsung Galaxy Buds 4 Pro Price in Nepal & Specs',
            'description' => 'Galaxy Buds 4 Pro cost Rs 35,799 in Nepal. Two-way drivers, ANC, SSC UHQ codec, IP57 and up to 30 hours battery.',
            'keywords' => 'galaxy buds 4 pro price in nepal, samsung buds 4 pro nepal',
        ],
        'image_alt' => 'Samsung Galaxy Buds 4 Pro',
    ],

    [
        'name' => 'Samsung Galaxy Buds 4', 'slug' => 'samsung-galaxy-buds4', 'brand' => 'Samsung', 'category' => 'earbuds',
        'price' => 26099,
        'history' => [['2026-11-13', 26099]],
        'specs' => [
            'battery' => 'Up to 24 hours with ANC / 30 hours without (earbuds + case)',
            'connectivity' => 'Bluetooth 6.1; AAC, SBC, SSC HiFi, SSC UHQ, LC3',
            'weight' => '4.6g per earbud',
            'extra' => ['Fit' => 'Semi in-ear', 'Driver' => '11mm dynamic', 'Water resistance' => 'IP54'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy Buds 4 (<strong>Rs 26,099</strong>) are Samsung's open-fit alternative to the <a href="/products/samsung-galaxy-buds4-pro">Buds 4 Pro</a>: lighter, easier to wear for hours, with active noise cancellation that takes the edge off rather than blocking everything. Sound quality is good with a single 11mm driver.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Buds 4 price in Nepal?', 'Galaxy Buds 4 cost Rs 26,099.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Samsung Galaxy Buds 4 Price in Nepal: Open-fit ANC Earbuds',
            'description' => 'Galaxy Buds 4 cost Rs 26,099 in Nepal. Semi in-ear fit, ANC, 11mm driver and up to 30 hours battery with the case.',
            'keywords' => 'galaxy buds 4 price in nepal',
        ],
        'image_alt' => 'Samsung Galaxy Buds 4',
    ],

    [
        'name' => 'Samsung Galaxy Buds 3 FE', 'slug' => 'samsung-galaxy-buds3-fe', 'brand' => 'Samsung', 'category' => 'earbuds',
        'price' => 19999,
        'history' => [['2026-11-13', 19999]],
        'specs' => ['extra' => ['Noise cancellation' => 'Active noise cancellation']],
        'description' => <<<'HTML'
<p>At <strong>Rs 19,999</strong>, the Galaxy Buds 3 FE are the cheapest way to get Samsung's own earbuds and their tight integration with Galaxy phones — automatic switching, Galaxy AI live translation and easy pairing. They make the most sense if you already own a Samsung phone; otherwise the <a href="/products/oneplus-nord-buds-4">OnePlus Nord Buds 4</a> give you ANC for a quarter of the price.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Buds 3 FE price in Nepal?', 'Galaxy Buds 3 FE cost Rs 19,999.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Samsung Galaxy Buds 3 FE Price in Nepal',
            'description' => 'Galaxy Buds 3 FE cost Rs 19,999 in Nepal — the most affordable Samsung earbuds with ANC and Galaxy integration.',
            'keywords' => 'galaxy buds 3 fe price in nepal',
        ],
        'image_alt' => 'Samsung Galaxy Buds 3 FE',
    ],

    [
        'name' => 'Sony WF-1000XM6', 'slug' => 'sony-wf-1000xm6', 'brand' => 'Sony', 'category' => 'earbuds',
        'featured' => true,
        'price' => 64999,
        'history' => [['2026-11-13', 64999]],
        'specs' => [
            'battery' => 'Up to 24 hours (earbuds + case)',
            'connectivity' => 'Bluetooth 5.3 with multipoint; AAC, SBC, LDAC, LC3',
            'weight' => '6.2g per earbud',
            'extra' => ['Driver' => '8.4mm dynamic', 'Noise cancellation' => 'Active noise cancellation', 'Water resistance' => 'IPX4'],
        ],
        'description' => <<<'HTML'
<p>Sony's WF-1000XM6 are the most expensive earbuds in Nepal's mainstream market at <strong>Rs 64,999</strong>, and they earn it with class-leading noise cancellation and LDAC support for high-resolution audio on Android. Multipoint lets them stay connected to your phone and laptop at the same time.</p>
<p>They are bigger and heavier (6.2g per bud) than most rivals and only IPX4-rated, so they are better for flights and offices than the gym. For iPhone users, <a href="/products/airpods-pro-3">AirPods Pro 3</a> are the more convenient choice.</p>
HTML,
        'faqs' => [
            ['What is the Sony WF-1000XM6 price in Nepal?', 'The Sony WF-1000XM6 cost Rs 64,999 in Nepal.'],
            ['Do the WF-1000XM6 support LDAC?', 'Yes, along with AAC, SBC and LC3, and they support multipoint connections.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Sony WF-1000XM6 Price in Nepal: Best Noise Cancelling Earbuds?',
            'description' => 'Sony WF-1000XM6 cost Rs 64,999 in Nepal. Top-tier ANC, LDAC, multipoint and 24-hour battery — specs and who they suit.',
            'keywords' => 'sony wf-1000xm6 price in nepal, sony earbuds nepal',
        ],
        'image_alt' => 'Sony WF-1000XM6 earbuds',
    ],

    [
        'name' => 'Soundcore Liberty 5 Pro', 'slug' => 'soundcore-liberty-5-pro', 'brand' => 'Soundcore', 'category' => 'earbuds',
        'variants' => [
            ['edition' => 'Liberty 5 Pro', 'price' => 34999],
            ['edition' => 'Liberty 5 Pro Max (screen case)', 'price' => 39999],
        ],
        'history' => [['2026-11-13', 34999]],
        'specs' => [
            'battery' => 'Up to 6.5 hours (earbuds) / 28 hours with case, ANC on',
            'connectivity' => 'Bluetooth 6.1 with multipoint; AAC, SBC, LDAC',
            'weight' => '5.5g per earbud',
            'extra' => ['Driver' => '9.2mm dynamic', 'Water resistance' => 'IP55', 'Pro Max' => 'Charging case with AMOLED display'],
        ],
        'description' => <<<'HTML'
<p>Anker's Soundcore Liberty 5 Pro (<strong>Rs 34,999</strong>) offer flagship features — strong ANC, LDAC, multipoint and Bluetooth 6.1 — for roughly half the price of Sony's best. The Pro Max version adds a case with a small AMOLED screen for controls and notifications, for Rs 39,999; pick the edition above.</p>
HTML,
        'faqs' => [
            ['What is the Soundcore Liberty 5 Pro price in Nepal?', 'Rs 34,999 for the Liberty 5 Pro and Rs 39,999 for the Liberty 5 Pro Max.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Soundcore Liberty 5 Pro & Pro Max Price in Nepal',
            'description' => 'Soundcore Liberty 5 Pro cost Rs 34,999 in Nepal; the Pro Max with an AMOLED case costs Rs 39,999. ANC, LDAC and multipoint.',
            'keywords' => 'soundcore liberty 5 pro price in nepal, anker earbuds nepal',
        ],
        'image_alt' => 'Soundcore Liberty 5 Pro earbuds and case',
    ],

    [
        'name' => 'OnePlus Nord Buds 4', 'slug' => 'oneplus-nord-buds-4', 'brand' => 'OnePlus', 'category' => 'earbuds',
        'trending' => true,
        'price' => 4999,
        'history' => [['2026-11-13', 4999]],
        'specs' => [
            'battery' => 'Up to 54 hours with ANC (earbuds + case)',
            'connectivity' => 'Bluetooth 6.1 with multipoint; AAC, SBC',
            'weight' => '4.3g per earbud',
            'extra' => ['Driver' => '12mm dynamic', 'Noise cancellation' => 'Hybrid ANC, up to 52dB', 'Water resistance' => 'IP55'],
        ],
        'description' => <<<'HTML'
<p>The Nord Buds 4 are the bargain of this list. GadgetByte lists them at <strong>Rs 4,999</strong> once a store discount and wallet cashback are applied, and for that you get hybrid noise cancellation rated up to 52dB, multipoint Bluetooth 6.1 and up to 54 hours of battery with the case.</p>
<p>Codec support is basic (AAC and SBC), but for calls, commuting and YouTube that won't matter.</p>
HTML,
        'faqs' => [
            ['What is the OnePlus Nord Buds 4 price in Nepal?', 'GadgetByte lists Rs 4,999 after a Rs 1,500 store discount and Rs 500 wallet cashback; the regular price is higher.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'OnePlus Nord Buds 4 Price in Nepal: ANC Earbuds Under Rs 5,000',
            'description' => 'OnePlus Nord Buds 4 cost about Rs 4,999 in Nepal with offers. 52dB hybrid ANC, multipoint and up to 54 hours battery.',
            'keywords' => 'oneplus nord buds 4 price in nepal, best earbuds under 5000 nepal',
        ],
        'image_alt' => 'OnePlus Nord Buds 4',
    ],

    [
        'name' => 'Redmi Buds 5A', 'slug' => 'redmi-buds-5a', 'brand' => 'Xiaomi', 'category' => 'earbuds',
        'price' => 1999,
        'history' => [['2026-11-13', 1999]],
        'specs' => [
            'battery' => 'Up to 30 hours without ANC (earbuds + case)',
            'connectivity' => 'Bluetooth 5.4; SBC',
            'weight' => '3.6g per earbud',
            'extra' => ['Driver' => '12mm dynamic', 'Noise cancellation' => 'ANC up to 25dB', 'Water resistance' => 'IPX4'],
        ],
        'description' => <<<'HTML'
<p>Noise cancelling for under Rs 2,000 sounded impossible a few years ago. The Redmi Buds 5A cost <strong>Rs 1,999</strong> and include basic ANC (up to 25dB), a 12mm driver and up to 30 hours of playback. They are a good spare pair or a first pair of true wireless earbuds; don't expect the isolation of the <a href="/products/oneplus-nord-buds-4">Nord Buds 4</a>.</p>
HTML,
        'faqs' => [
            ['What is the Redmi Buds 5A price in Nepal?', 'Redmi Buds 5A cost Rs 1,999.'],
        ],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Redmi Buds 5A Price in Nepal: ANC Earbuds for Rs 1,999',
            'description' => 'Redmi Buds 5A cost Rs 1,999 in Nepal with basic 25dB ANC, 12mm drivers and up to 30 hours battery.',
            'keywords' => 'redmi buds 5a price in nepal, cheapest anc earbuds nepal',
        ],
        'image_alt' => 'Redmi Buds 5A',
    ],

    [
        'name' => 'JBL Wave Buds 2', 'slug' => 'jbl-wave-buds-2', 'brand' => 'JBL', 'category' => 'earbuds',
        'price' => 8900,
        'history' => [['2026-11-13', 8900]],
        'specs' => [],
        'description' => <<<'HTML'
<p>JBL's Wave Buds 2 (<strong>Rs 8,900</strong>) are for people who want JBL's bass-forward sound and a familiar brand at a mid price. They sit between the budget <a href="/products/oneplus-nord-buds-4">OnePlus Nord Buds 4</a> and premium options like the Galaxy Buds 3 FE.</p>
HTML,
        'faqs' => [['What is the JBL Wave Buds 2 price in Nepal?', 'JBL Wave Buds 2 cost Rs 8,900.']],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'JBL Wave Buds 2 Price in Nepal',
            'description' => 'JBL Wave Buds 2 cost Rs 8,900 in Nepal — JBL sound in a mid-priced true wireless pair.',
            'keywords' => 'jbl wave buds 2 price in nepal',
        ],
        'image_alt' => 'JBL Wave Buds 2',
    ],

    [
        'name' => 'Huawei FreeBuds 7i', 'slug' => 'huawei-freebuds-7i', 'brand' => 'Huawei', 'category' => 'earbuds',
        'price' => 17990,
        'history' => [['2026-11-13', 17990]],
        'specs' => [],
        'description' => <<<'HTML'
<p>Huawei doesn't sell its top FreeBuds in Nepal, so the FreeBuds 7i (<strong>Rs 17,990</strong>) are the best Huawei earbuds you can buy here officially. They are a good match for Huawei watch owners; on other phones, compare them with the <a href="/products/samsung-galaxy-buds3-fe">Galaxy Buds 3 FE</a>.</p>
HTML,
        'faqs' => [['What is the Huawei FreeBuds 7i price in Nepal?', 'Huawei FreeBuds 7i cost Rs 17,990.']],
        'sources' => [$gbBuds],
        'seo' => [
            'title' => 'Huawei FreeBuds 7i Price in Nepal',
            'description' => 'Huawei FreeBuds 7i cost Rs 17,990 in Nepal — Huawei\'s best officially sold earbuds here.',
            'keywords' => 'huawei freebuds 7i price in nepal',
        ],
        'image_alt' => 'Huawei FreeBuds 7i',
    ],

    [
        'name' => 'boAt Nirvana Ion ANC', 'slug' => 'boat-nirvana-ion-anc', 'brand' => 'boAt', 'category' => 'earbuds',
        'price' => 6599, 'old_price' => 6999,
        'history' => [['2026-02-09', 6599]],
        'specs' => [
            'battery' => 'Up to 120 hours with case (ANC off), 100 hours with ANC on at 60% volume',
            'connectivity' => 'Bluetooth 5.2, USB-C',
            'extra' => ['Noise cancellation' => 'ANC up to 32dB + ENx mics', 'Drivers' => '10mm dual', 'Water resistance' => 'IPX4'],
        ],
        'description' => <<<'HTML'
<p>boAt's Nirvana Ion ANC is built around battery life: boAt claims up to 120 hours of playback with the case, which means charging it a couple of times a month. The offer price is <strong>Rs 6,599</strong> (MRP Rs 6,999). Noise cancellation is rated at 32dB — useful on buses, not as strong as the <a href="/products/oneplus-nord-buds-4">Nord Buds 4</a>.</p>
HTML,
        'faqs' => [['What is the boAt Nirvana Ion ANC price in Nepal?', 'The offer price is Rs 6,599, against an MRP of Rs 6,999.']],
        'sources' => [$gbBoat],
        'seo' => [
            'title' => 'boAt Nirvana Ion ANC Price in Nepal: 120-Hour Battery Earbuds',
            'description' => 'boAt Nirvana Ion ANC cost Rs 6,599 in Nepal. 32dB ANC, dual 10mm drivers and up to 120 hours battery with the case.',
            'keywords' => 'boat nirvana ion anc price in nepal, boat earbuds nepal',
        ],
        'image_alt' => 'boAt Nirvana Ion ANC',
    ],

    /* ─────────────────────────────── Smartwatches ─────────────────────────────── */

    [
        'name' => 'Apple Watch Ultra 3', 'slug' => 'apple-watch-ultra-3', 'brand' => 'Apple', 'category' => 'smartwatch',
        'release_date' => '2025-09-19',
        'variants' => [
            ['edition' => '49mm · Ocean Band', 'price' => 155000],
            ['edition' => '49mm · Titanium Milanese Loop', 'price' => 175000],
        ],
        'history' => [['2026-06-10', 155000]],
        'specs' => [
            'display' => '49mm wide-angle LTPO3 OLED, always-on',
            'battery' => 'Up to 42 hours',
            'connectivity' => 'GPS + Cellular, satellite SOS and messaging',
            'extra' => ['Case' => 'Titanium', 'Water resistance' => 'WR100, dive-rated'],
        ],
        'description' => <<<'HTML'
<p>The Apple Watch Ultra 3 costs <strong>Rs 1,55,000</strong> in Nepal with the Ocean Band, or Rs 1,75,000 with the titanium Milanese Loop. Its standout feature for Nepal is two-way satellite communication: in the hills, where mobile signal disappears quickly, you can send texts and SOS messages over satellite where Apple supports it.</p>
<p>Battery life of up to 42 hours makes multi-day treks realistic with Low Power Mode. Apple has since announced the <a href="/products/apple-watch-ultra-4">Ultra 4</a>; wait for its official Nepal price if you're not in a hurry.</p>
HTML,
        'faqs' => [
            ['What is the Apple Watch Ultra 3 price in Nepal?', 'Rs 1,55,000 with the Ocean Band and Rs 1,75,000 with the Milanese Loop.'],
            ['Does the Apple Watch Ultra 3 have satellite messaging?', 'Yes. It supports satellite SOS and messaging in supported regions, which is useful when trekking out of mobile coverage.'],
        ],
        'sources' => [$src('GadgetByte Nepal – Apple Watch Ultra 3 in Nepal', 'https://gadgetbytenepal.com/apple-watch-ultra-3-price-nepal/', '2026-06-10')],
        'seo' => [
            'title' => 'Apple Watch Ultra 3 Price in Nepal: Satellite Messaging Watch',
            'description' => 'Apple Watch Ultra 3 costs Rs 1,55,000 in Nepal. Two-way satellite messaging, 42-hour battery and titanium build — ideal for trekking.',
            'keywords' => 'apple watch ultra 3 price in nepal, apple watch ultra nepal',
        ],
        'image_alt' => 'Apple Watch Ultra 3',
    ],

    [
        'name' => 'Apple Watch Series 12', 'slug' => 'apple-watch-series-12', 'brand' => 'Apple', 'category' => 'smartwatch',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Official Nepal price awaited',
        'variants' => [
            ['edition' => '42mm GPS (aluminium)', 'price' => 80000],
            ['edition' => '46mm GPS (aluminium)', 'price' => 87000],
        ],
        'history' => [],
        'specs' => [
            'display' => 'Always-on LTPO3 wide-angle OLED, up to 2,000 nits; 42mm or 46mm',
            'processor' => 'Apple S11',
            'storage' => '64GB',
            'battery' => 'Up to 24 hours (38 hours in Low Power Mode)',
            'connectivity' => 'Bluetooth 5.3, Wi-Fi 4, LTE/5G (cellular models)',
            'weight' => '32.2g (42mm aluminium GPS)',
            'dimensions' => '42 × 37 × 9.7 mm / 46 × 40 × 9.7 mm',
            'extra' => ['Health' => 'Electrical and optical heart sensors, blood oxygen, temperature, depth gauge to 6m', 'Water resistance' => 'WR50, IP6X'],
        ],
        'description' => <<<'HTML'
<p>Apple Watch Series 12 is the newest mainstream Apple Watch. GadgetByte expects it to cost around <strong>Rs 80,000</strong> for the 42mm GPS model and Rs 87,000 for 46mm once the official Nepal price is set — treat those as estimates for now.</p>
<p>It moves to the S11 chip, extends battery life to 24 hours (38 in Low Power Mode) and gains a brighter 2,000-nit screen. If you want something you can buy today at a fixed price, the <a href="/products/apple-watch-se-3">Apple Watch SE 3</a> starts at Rs 52,500.</p>
HTML,
        'faqs' => [
            ['What is the expected Apple Watch Series 12 price in Nepal?', 'GadgetByte expects about Rs 80,000 for the 42mm GPS model and Rs 87,000 for 46mm. The official Nepal price had not been announced when we checked.'],
        ],
        'sources' => [$gbApple, $gbWatch],
        'launch_highlight' => 'S11 chip, 24-hour battery, 2,000-nit display',
        'seo' => [
            'title' => 'Apple Watch Series 12 Price in Nepal (Expected) & Specs',
            'description' => 'Apple Watch Series 12 is expected at about Rs 80,000 (42mm) in Nepal. S11 chip, 24-hour battery and health sensors explained.',
            'keywords' => 'apple watch series 12 price in nepal, apple watch 12 nepal',
        ],
        'image_alt' => 'Apple Watch Series 12',
    ],

    [
        'name' => 'Apple Watch Ultra 4', 'slug' => 'apple-watch-ultra-4', 'brand' => 'Apple', 'category' => 'smartwatch',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Official Nepal price awaited',
        'variants' => [['edition' => '49mm titanium · Alpine Loop', 'price' => 163000]],
        'history' => [],
        'specs' => [
            'display' => '1.98-inch always-on LTPO3 wide-angle OLED (422×514), up to 3,000 nits, sapphire',
            'processor' => 'Apple S11',
            'storage' => '64GB',
            'battery' => 'Up to 50 hours (84 hours in Low Power Mode)',
            'connectivity' => 'Dual-frequency GPS (L1 + L5), satellite SOS and messaging',
            'weight' => '63g',
            'dimensions' => '49 × 44 × 12 mm',
            'extra' => ['Water resistance' => 'WR100, EN13319 (diving to 40m), IP6X, MIL-STD-810H'],
        ],
        'description' => <<<'HTML'
<p>The Apple Watch Ultra 4 stretches battery life to 50 hours (84 in Low Power Mode) — the number that matters most on a long trek — and keeps the dual-frequency GPS and satellite features that make the Ultra line useful in the mountains. GadgetByte expects a Nepal price around <strong>Rs 1,63,000</strong> with the Alpine Loop; the official price wasn't out when we checked.</p>
HTML,
        'faqs' => [
            ['What is the expected Apple Watch Ultra 4 price in Nepal?', 'Around Rs 1,63,000 with the Alpine Loop, according to GadgetByte\'s expected pricing. It is an estimate until the official price is announced.'],
        ],
        'sources' => [$gbApple, $gbWatch],
        'launch_highlight' => '50-hour battery, S11 chip, satellite features',
        'seo' => [
            'title' => 'Apple Watch Ultra 4 Price in Nepal (Expected): 50-Hour Battery',
            'description' => 'Apple Watch Ultra 4 is expected at about Rs 1,63,000 in Nepal. 50-hour battery, S11 chip, dual-band GPS and dive rating.',
            'keywords' => 'apple watch ultra 4 price in nepal',
        ],
        'image_alt' => 'Apple Watch Ultra 4',
    ],

    [
        'name' => 'Apple Watch SE 3', 'slug' => 'apple-watch-se-3', 'brand' => 'Apple', 'category' => 'smartwatch',
        'variants' => [
            ['edition' => '40mm GPS', 'price' => 52500],
            ['edition' => '44mm GPS', 'price' => 58500],
        ],
        'history' => [['2026-09-28', 52500]],
        'specs' => [
            'display' => 'Always-on Retina LTPO OLED, 1,000 nits; 40mm or 44mm',
            'processor' => 'Apple S10',
            'storage' => '64GB',
            'battery' => 'Up to 18 hours (32 hours Low Power Mode); 0–80% in about 45 minutes',
            'connectivity' => 'GPS, Bluetooth 5.3',
            'weight' => '26.3g (40mm) / 32.9g (44mm)',
            'extra' => ['Health' => 'Optical heart sensor, temperature, sleep apnea notifications (no ECG or blood oxygen)', 'Water resistance' => 'WR50'],
        ],
        'description' => <<<'HTML'
<p>The Apple Watch SE 3 is the sensible Apple Watch: <strong>Rs 52,500</strong> for the 40mm model and Rs 58,500 for 44mm, with an always-on screen for the first time on an SE, the S10 chip and fast charging. It covers notifications, workouts, sleep tracking and fall detection well.</p>
<p>It skips ECG and blood-oxygen readings, which are reserved for the <a href="/products/apple-watch-series-12">Series 12</a> and Ultra models.</p>
HTML,
        'faqs' => [
            ['What is the Apple Watch SE 3 price in Nepal?', 'Rs 52,500 for 40mm and Rs 58,500 for 44mm (GPS models).'],
            ['Does the Apple Watch SE 3 have ECG?', 'No. ECG and blood-oxygen measurement are only on the higher-end Apple Watch models.'],
        ],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Apple Watch SE 3 Price in Nepal: 40mm & 44mm, Specs',
            'description' => 'Apple Watch SE 3 costs Rs 52,500 (40mm) in Nepal. Always-on display, S10 chip, fast charging — and what it leaves out.',
            'keywords' => 'apple watch se 3 price in nepal, cheapest apple watch nepal',
        ],
        'image_alt' => 'Apple Watch SE 3',
    ],

    [
        'name' => 'Samsung Galaxy Watch 9', 'slug' => 'samsung-galaxy-watch9', 'brand' => 'Samsung', 'category' => 'smartwatch',
        'trending' => true,
        'variants' => [
            ['edition' => '40mm', 'price' => 66499],
            ['edition' => '44mm', 'price' => 71499],
        ],
        'history' => [['2026-09-28', 66499]],
        'specs' => [
            'display' => '1.34-inch (40mm) or 1.47-inch (44mm) AMOLED, 3,000 nits, sapphire crystal',
            'processor' => 'Snapdragon Wear Elite (3nm)',
            'ram' => '2GB',
            'storage' => '32GB',
            'battery' => '390mAh (40mm) / 445mAh (44mm), about 1.5 days',
            'os' => 'Wear OS 7, One UI Watch 9',
            'connectivity' => 'Bluetooth 6.0, dual-band GPS (L1 + L5)',
            'weight' => '31.5g (40mm) / 34g (44mm)',
            'extra' => ['Sensors' => 'BioActive sensor (optical, electrical heart, body composition), temperature', 'Water resistance' => '5ATM, IP68, MIL-STD-810H', 'Colours' => 'Graphite, Silver, Cream'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy Watch 9 is the Android equivalent of an Apple Watch and the obvious pick for Samsung phone owners. It costs <strong>Rs 66,499</strong> for 40mm and Rs 71,499 for 44mm in Nepal, and moves to Qualcomm's 3nm Snapdragon Wear Elite.</p>
<p>Samsung's BioActive sensor adds body-composition readings alongside heart-rate tracking. Battery life is about a day and a half — fine for most, short for trekking, where the <a href="/products/huawei-watch-gt-6">Huawei Watch GT 6</a> lasts up to three weeks.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Watch 9 price in Nepal?', 'Rs 66,499 for the 40mm model and Rs 71,499 for 44mm.'],
        ],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Samsung Galaxy Watch 9 Price in Nepal: 40mm & 44mm',
            'description' => 'Galaxy Watch 9 costs Rs 66,499 (40mm) in Nepal. Snapdragon Wear Elite, Wear OS 7, sapphire glass and BioActive health sensor.',
            'keywords' => 'galaxy watch 9 price in nepal, samsung watch 9 nepal',
        ],
        'image_alt' => 'Samsung Galaxy Watch 9',
    ],

    [
        'name' => 'Samsung Galaxy Watch 8 Classic', 'slug' => 'samsung-galaxy-watch8-classic', 'brand' => 'Samsung', 'category' => 'smartwatch',
        'price' => 82999,
        'history' => [['2026-09-28', 82999]],
        'specs' => [
            'display' => '1.34-inch AMOLED (438×438), 3,000 nits, sapphire; rotating bezel',
            'processor' => 'Exynos W1000 (3nm)',
            'storage' => '64GB',
            'battery' => '445mAh, about 3 days',
            'os' => 'Wear OS 6, One UI Watch 8',
            'weight' => '63.5g',
            'dimensions' => '46.4 × 46 × 10.6 mm',
            'extra' => ['Water resistance' => '5ATM, IP68, MIL-STD-810H', 'Colours' => 'Black, White'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy Watch 8 Classic brings back the physical rotating bezel that fans of earlier Classic models loved, in a 46mm stainless-steel case that looks at home with a formal shirt. It costs <strong>Rs 82,999</strong> and lasts around three days between charges — longer than the newer <a href="/products/samsung-galaxy-watch9">Galaxy Watch 9</a>.</p>
HTML,
        'faqs' => [['What is the Galaxy Watch 8 Classic price in Nepal?', 'The 46mm Galaxy Watch 8 Classic costs Rs 82,999.']],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Samsung Galaxy Watch 8 Classic Price in Nepal',
            'description' => 'Galaxy Watch 8 Classic costs Rs 82,999 in Nepal. Rotating bezel, 46mm steel case and about three days of battery life.',
            'keywords' => 'galaxy watch 8 classic price in nepal',
        ],
        'image_alt' => 'Samsung Galaxy Watch 8 Classic',
    ],

    [
        'name' => 'Samsung Galaxy Watch Ultra 2', 'slug' => 'samsung-galaxy-watch-ultra-2', 'brand' => 'Samsung', 'category' => 'smartwatch',
        'price' => 123499,
        'history' => [['2026-09-28', 123499]],
        'specs' => [
            'display' => '1.52-inch AMOLED (498×498), 5,000 nits, sapphire',
            'processor' => 'Snapdragon Wear Elite (3nm)',
            'storage' => '64GB',
            'battery' => '800mAh, about 5 days',
            'os' => 'Wear OS 7, One UI Watch 9',
            'weight' => '61.5g',
            'dimensions' => '47.1 × 47.4 × 10.7 mm',
            'extra' => ['Case' => 'Titanium', 'Water resistance' => '10ATM, IP69K, EN13319 (diving), MIL-STD-810H'],
        ],
        'description' => <<<'HTML'
<p>Samsung's answer to the Apple Watch Ultra costs <strong>Rs 1,23,499</strong> in Nepal and is the first Galaxy Watch Ultra sold officially here. The 800mAh battery gives about five days of use, the 5,000-nit screen stays readable in mountain sun, and the titanium case is rated for diving.</p>
HTML,
        'faqs' => [['What is the Galaxy Watch Ultra 2 price in Nepal?', 'The Galaxy Watch Ultra 2 costs Rs 1,23,499.']],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Samsung Galaxy Watch Ultra 2 Price in Nepal & Specs',
            'description' => 'Galaxy Watch Ultra 2 costs Rs 1,23,499 in Nepal. Titanium, 5,000-nit display, five-day battery and dive rating.',
            'keywords' => 'galaxy watch ultra 2 price in nepal',
        ],
        'image_alt' => 'Samsung Galaxy Watch Ultra 2',
    ],

    [
        'name' => 'Huawei Watch GT 6', 'slug' => 'huawei-watch-gt-6', 'brand' => 'Huawei', 'category' => 'smartwatch',
        'featured' => true,
        'variants' => [
            ['edition' => 'Watch GT 6 (41mm / 46mm)', 'price' => 34490],
            ['edition' => 'Watch GT 6 Pro (46mm titanium)', 'price' => 54490],
        ],
        'history' => [['2026-09-28', 34490]],
        'specs' => [
            'display' => '1.32-inch (41mm) or 1.47-inch (46mm) AMOLED (466×466), 3,000 nits',
            'battery' => 'Up to 14 days (41mm) / 21 days (46mm)',
            'os' => 'HarmonyOS 5',
            'connectivity' => 'Dual-band GPS (L1 + L5)',
            'weight' => '37.5g (41mm) / 51.3g (46mm) / 54.7g (Pro)',
            'extra' => ['Water resistance' => '5ATM', 'Pro extras' => 'Titanium case, sapphire glass, EN13319 dive rating'],
        ],
        'description' => <<<'HTML'
<p>Battery life is why people buy Huawei watches, and the Watch GT 6 delivers up to three weeks on the 46mm model. It costs <strong>Rs 34,490</strong> in Nepal; the titanium GT 6 Pro with sapphire glass costs Rs 54,490 — switch editions above.</p>
<p>The trade-off is apps: HarmonyOS covers fitness, sleep and notifications well, but there's no third-party app store like Wear OS or watchOS. For trekkers and runners who hate charging, that's an easy trade.</p>
HTML,
        'faqs' => [
            ['What is the Huawei Watch GT 6 price in Nepal?', 'Rs 34,490 for the Watch GT 6 and Rs 54,490 for the GT 6 Pro.'],
            ['How long does the Huawei Watch GT 6 battery last?', 'Up to 14 days on the 41mm model and up to 21 days on the 46mm model, according to Huawei.'],
        ],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Huawei Watch GT 6 & GT 6 Pro Price in Nepal: 21-Day Battery',
            'description' => 'Huawei Watch GT 6 costs Rs 34,490 in Nepal (GT 6 Pro Rs 54,490). Up to 21-day battery, dual-band GPS and AMOLED display.',
            'keywords' => 'huawei watch gt 6 price in nepal, huawei gt 6 pro nepal',
        ],
        'image_alt' => 'Huawei Watch GT 6',
    ],

    [
        'name' => 'Huawei Watch Fit 5', 'slug' => 'huawei-watch-fit-5', 'brand' => 'Huawei', 'category' => 'smartwatch',
        'variants' => [
            ['edition' => 'Watch Fit 5', 'price' => 27990],
            ['edition' => 'Watch Fit 5 Pro', 'price' => 43990],
        ],
        'history' => [['2026-09-28', 27990]],
        'specs' => [
            'display' => '1.82-inch AMOLED (Fit 5) / 1.92-inch AMOLED with sapphire (Fit 5 Pro)',
            'battery' => 'Up to 10 days',
            'os' => 'HarmonyOS',
            'extra' => ['Water resistance' => '5ATM', 'Pro extras' => 'ECG, dive mode, dual-band GPS'],
        ],
        'description' => <<<'HTML'
<p>The Watch Fit 5 is a slim, rectangular fitness watch for <strong>Rs 27,990</strong>, with a big AMOLED screen and up to 10 days of battery. The Fit 5 Pro (Rs 43,990) adds a sapphire-covered screen, ECG and a dive mode.</p>
HTML,
        'faqs' => [['What is the Huawei Watch Fit 5 price in Nepal?', 'Rs 27,990 for the Watch Fit 5 and Rs 43,990 for the Fit 5 Pro.']],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Huawei Watch Fit 5 & Fit 5 Pro Price in Nepal',
            'description' => 'Huawei Watch Fit 5 costs Rs 27,990 in Nepal; Fit 5 Pro costs Rs 43,990. Slim AMOLED fitness watch with up to 10-day battery.',
            'keywords' => 'huawei watch fit 5 price in nepal',
        ],
        'image_alt' => 'Huawei Watch Fit 5',
    ],

    [
        'name' => 'Xiaomi Watch S5', 'slug' => 'xiaomi-watch-s5', 'brand' => 'Xiaomi', 'category' => 'smartwatch',
        'price' => 39999,
        'history' => [['2026-09-28', 39999]],
        'specs' => [
            'display' => '1.48-inch AMOLED (480×480), 2,500 nits',
            'battery' => 'Up to 21 days (light use)',
            'os' => 'HyperOS 3',
            'connectivity' => 'Dual-band GPS (L1 + L5)',
            'weight' => '46g',
            'dimensions' => '46 × 46 × 10.99 mm',
            'extra' => ['Water resistance' => '5ATM', 'Colours' => 'Black, Silver, Ceramic Blue, Jungle Green'],
        ],
        'description' => <<<'HTML'
<p>The Xiaomi Watch S5 (<strong>Rs 39,999</strong>) is a round, stainless-steel watch that looks more expensive than it is, with a sharp AMOLED screen and up to three weeks of light use per charge. It pairs best with Xiaomi phones running HyperOS.</p>
HTML,
        'faqs' => [['What is the Xiaomi Watch S5 price in Nepal?', 'The Xiaomi Watch S5 costs Rs 39,999.']],
        'sources' => [$gbWatch],
        'seo' => [
            'title' => 'Xiaomi Watch S5 Price in Nepal & Specs',
            'description' => 'Xiaomi Watch S5 costs Rs 39,999 in Nepal. 1.48-inch AMOLED, dual-band GPS and up to 21 days battery.',
            'keywords' => 'xiaomi watch s5 price in nepal',
        ],
        'image_alt' => 'Xiaomi Watch S5',
    ],

    [
        'name' => 'Redmi Watch 5 Active', 'slug' => 'redmi-watch-5-active', 'brand' => 'Xiaomi', 'category' => 'smartwatch',
        'trending' => true,
        'price' => 4499,
        'history' => [['2026-02-11', 4499]],
        'specs' => [
            'display' => '2.0-inch LCD (320×385), 500 nits',
            'battery' => 'Up to 18 days normal use',
            'connectivity' => 'Bluetooth 5.3',
            'extra' => ['Health' => 'Heart rate, SpO2', 'Water resistance' => 'IPX8'],
        ],
        'description' => <<<'HTML'
<p>For <strong>Rs 4,499</strong>, the Redmi Watch 5 Active is a no-fuss first smartwatch: a big 2-inch screen, heart-rate and SpO2 tracking, and up to 18 days between charges. It's an LCD rather than AMOLED, so it isn't as vivid, but at this price that's fair.</p>
HTML,
        'faqs' => [['What is the Redmi Watch 5 Active price in Nepal?', 'The Redmi Watch 5 Active costs Rs 4,499.']],
        'sources' => [$gbXiaomiA],
        'seo' => [
            'title' => 'Redmi Watch 5 Active Price in Nepal: Budget Smartwatch',
            'description' => 'Redmi Watch 5 Active costs Rs 4,499 in Nepal. 2-inch display, SpO2 and up to 18-day battery.',
            'keywords' => 'redmi watch 5 active price in nepal, best smartwatch under 5000 nepal',
        ],
        'image_alt' => 'Redmi Watch 5 Active',
    ],
];
