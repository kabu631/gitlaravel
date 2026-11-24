<?php

/*
 * Smartphones on sale in Nepal.
 */

$src = fn (string $label, string $url, string $date = '2026-11-22') => ['label' => $label, 'url' => $url, 'accessed' => $date];
$gbSamsung = ['accessed' => '2026-11-22'];
$ntApple   = ['accessed' => '2026-11-22'];
$gbXiaomi  = ['accessed' => '2026-11-22'];
$gbOnePlus = ['accessed' => '2026-11-22'];
$gbNothing = ['accessed' => '2026-11-22'];
$gbVivo    = ['accessed' => '2026-11-22'];
$gbRealme  = ['accessed' => '2026-11-22'];
$gbHonor   = ['accessed' => '2026-11-22'];

return [

    /* ─────────────────────────────── Apple ─────────────────────────────── */

    [
        'name' => 'iPhone 18 Pro', 'slug' => 'iphone-18-pro', 'brand' => 'Apple', 'category' => 'mobile',
        'release_date' => '2026-09-29', 'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 259900],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 301900],
            ['ram' => '12GB', 'storage' => '1TB', 'price' => 386900],
            ['ram' => '12GB', 'storage' => '2TB', 'price' => 513900],
        ],
        'history' => [['2026-09-29', 259900], ['2026-11-19', 259900]],
        'specs' => [
            'display' => '6.3-inch Super Retina XDR OLED, LTPO 1–120Hz ProMotion, 3,000 nits peak, anti-reflective coating',
            'processor' => 'Apple A20 Pro (2nm)',
            'ram' => '12GB',
            'storage' => '256GB / 512GB / 1TB / 2TB',
            'battery' => 'Up to 36 hours video playback; 50W wired (about 50% in 15 minutes), 25W MagSafe, 4.5W reverse wired',
            'camera' => '48MP Fusion main (variable aperture f/1.48–f/4.0) + 48MP ultra wide + 48MP 4x telephoto (100mm) | 18MP Center Stage front',
            'os' => 'iOS 27',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth 6.0, USB-C (USB 3), dual eSIM',
            'extra' => ['Water resistance' => 'IP68', 'Colours' => 'Black, Silver, Glacier, Burgundy'],
        ],
        'description' => <<<'HTML'
<p>The iPhone 18 Pro arrived in Nepal on 29 September 2026 at <strong>Rs 2,59,900</strong> for the 256GB model, and it is the first Pro iPhone where the main camera has a genuinely variable aperture. In practice that means the phone can stop down from f/1.48 to f/4.0, so group shots and close-ups keep more of the frame in focus instead of melting the background the way every phone camera has for years. The A20 Pro is Apple's first 2nm chip, and the 12GB of RAM is there mainly for on-device Apple Intelligence.</p>
<p>Charging finally catches up with the rest of the market: Apple quotes roughly half a battery in 15 minutes over a 50W charger. Storage tops out at a 2TB option priced at Rs 5,13,900 — only worth it if you shoot a lot of ProRes video. If you want the bigger screen and the 5x periscope lens instead of 4x, look at the <a href="/products/iphone-18-pro-max">iPhone 18 Pro Max</a>; if you're weighing Android, the obvious rival is the <a href="/compare?g1=iphone-18-pro&g2=samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a>.</p>
HTML,
        'faqs' => [
            ['What is the iPhone 18 Pro price in Nepal?', 'The official price starts at Rs 2,59,900 for 12GB/256GB. The 512GB model costs Rs 3,01,900, 1TB costs Rs 3,86,900 and the 2TB model costs Rs 5,13,900.'],
            ['When did the iPhone 18 Pro launch in Nepal?', 'Apple-authorised sellers started selling it in Nepal at the end of September 2026, alongside the iPhone 18 Pro Max and the foldable iPhone Duo.'],
            ['What is the difference between the iPhone 18 Pro and 18 Pro Max?', 'The Pro Max has a 6.9-inch screen instead of 6.3 inches, a 5x periscope telephoto instead of 4x, a 24MP front camera and a bigger battery. It costs Rs 20,000 more at every storage level.'],
        ],
        'sources' => [
            ['accessed' => '2026-11-19'],
            $ntApple,
        ],
        'seo' => [
            'title' => 'iPhone 18 Pro Price in Nepal (2026): Specs, Variants & Review',
            'description' => 'iPhone 18 Pro price in Nepal starts at Rs 2,59,900 (256GB). See every storage variant, A20 Pro specs, camera details and how it compares.',
            'keywords' => 'iphone 18 pro price in nepal, iphone 18 pro nepal, iphone 18 pro specs, iphone 18 pro 2tb price',
        ],
        'image_alt' => 'Apple iPhone 18 Pro rear cameras',
    ],

    [
        'name' => 'iPhone 18 Pro Max', 'slug' => 'iphone-18-pro-max', 'brand' => 'Apple', 'category' => 'mobile',
        'release_date' => '2026-09-29', 'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 279900],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 322900],
            ['ram' => '12GB', 'storage' => '1TB', 'price' => 407900],
            ['ram' => '12GB', 'storage' => '2TB', 'price' => 534900],
        ],
        'history' => [['2026-09-29', 279900], ['2026-11-01', 279900]],
        'specs' => [
            'display' => '6.9-inch Super Retina XDR OLED, 1320×2868, LTPO 120Hz, 3,000 nits peak, anti-reflective coating',
            'processor' => 'Apple A20 Pro (2nm)',
            'ram' => '12GB',
            'storage' => '256GB / 512GB / 1TB / 2TB',
            'battery' => '50W wired, 25W MagSafe wireless, 4.5W reverse wired',
            'camera' => '48MP main (variable aperture) + 48MP ultra wide + 48MP 5x periscope telephoto | 24MP front',
            'os' => 'iOS 27',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth 6.0, USB-C',
            'weight' => '249g',
            'dimensions' => '163.4 × 78.0 × 8.75 mm',
            'extra' => ['Water resistance' => 'IP68', 'Colours' => 'Burgundy, Glacier Blue, Silver, Black'],
        ],
        'description' => <<<'HTML'
<p>At <strong>Rs 2,79,900</strong> the iPhone 18 Pro Max is Rs 20,000 more than the regular Pro, and for that money you get three things that matter: a 6.9-inch screen, a 5x periscope camera instead of 4x, and a sharper 24MP selfie camera. Everything else — the A20 Pro chip, 12GB of RAM, the variable-aperture main camera and 50W charging — is shared with the smaller phone.</p>
<p>It is a heavy phone at 249g, and you will notice it after a long call or a commute on a crowded micro bus. Buy it for the screen and the longer zoom; if those don't matter to you, the <a href="/products/iphone-18-pro">iPhone 18 Pro</a> is the better-balanced phone. Android buyers in the same budget should also look at the <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a>, which has a 200MP main camera and an S Pen.</p>
HTML,
        'faqs' => [
            ['What is the iPhone 18 Pro Max price in Nepal?', 'It starts at Rs 2,79,900 for 256GB. The 512GB version is Rs 3,22,900, 1TB is Rs 4,07,900 and 2TB is Rs 5,34,900.'],
            ['Does the iPhone 18 Pro Max have a periscope camera?', 'Yes. It uses a 48MP periscope telephoto with 5x optical zoom, while the smaller 18 Pro uses a 4x telephoto.'],
            ['How much does the iPhone 18 Pro Max weigh?', 'It weighs 249g, which is on the heavy side for a phone.'],
        ],
        'sources' => [$ntApple],
        'seo' => [
            'title' => 'iPhone 18 Pro Max Price in Nepal: All Variants & Full Specs',
            'description' => 'iPhone 18 Pro Max costs Rs 2,79,900 in Nepal for 256GB, up to Rs 5,34,900 for 2TB. Specs, 5x periscope camera, weight and our take.',
            'keywords' => 'iphone 18 pro max price in nepal, iphone 18 pro max nepal, iphone 18 pro max specs',
        ],
        'image_alt' => 'Apple iPhone 18 Pro Max',
    ],

    [
        'name' => 'iPhone Duo', 'slug' => 'iphone-duo', 'brand' => 'Apple', 'category' => 'mobile',
        'release_date' => '2026-10-30', 'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 430900],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 472900],
            ['ram' => '12GB', 'storage' => '1TB', 'price' => 557900],
            ['ram' => '12GB', 'storage' => '2TB', 'price' => 684900],
        ],
        'history' => [['2026-10-30', 430900]],
        'specs' => [
            'display' => '7.6-inch inner LTPO OLED (1878×2670, 120Hz, nano-texture) + 5.4-inch outer LTPO OLED (1398×2034, 120Hz); 3,000 nits peak',
            'processor' => 'Apple A20 Pro (2nm) with vapour chamber',
            'ram' => '12GB',
            'storage' => '256GB / 512GB / 1TB / 2TB',
            'battery' => 'Dual-cell; up to 31 hours video (inner screen) or 44 hours (outer screen); 25W MagSafe/Qi2',
            'camera' => '48MP main + 48MP 2x telephoto | 12MP under-display inner camera, 12MP cover camera',
            'os' => 'iOS 27',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth 6.0, NFC, UWB (Gen 2), USB-C, dual nano-SIM',
            'weight' => '254g',
            'dimensions' => 'Folded 117.8 × 84.1 × 11.3 mm; unfolded 117.8 × 164.6 × 5.2 mm',
            'extra' => ['Build' => 'Titanium frame and hinge, Ceramic Shield front and back', 'Unlock' => 'Side-mounted Touch ID (no Face ID)', 'Water resistance' => 'IP68', 'Colours' => 'Night Sky, Star White', 'Stylus' => 'Apple Pencil support'],
        ],
        'description' => <<<'HTML'
<p>Apple's first foldable is a book-style phone with a 5.4-inch cover screen and a 7.6-inch inner display that opens to a squarish 1.4:1 canvas — closer to a small iPad than a stretched phone. In Nepal it starts at <strong>Rs 4,30,900</strong>, which makes it the most expensive phone most shops here have ever stocked.</p>
<p>A few choices stand out. There is no Face ID; Apple moved to a side-mounted Touch ID button. The camera system is a pair of 48MP sensors (main and 2x) rather than the triple setup on the Pro models, and the selfie camera under the inner screen is 12MP. The upside is a titanium hinge, IP68 water resistance — rare on foldables — and Apple Pencil support. If you want a foldable but not this price, Samsung's <a href="/products/samsung-galaxy-z-fold8">Galaxy Z Fold 8</a> costs about Rs 1.5 lakh less.</p>
HTML,
        'faqs' => [
            ['What is the iPhone Duo price in Nepal?', 'The official price is Rs 4,30,900 for 256GB, Rs 4,72,900 for 512GB, Rs 5,57,900 for 1TB and Rs 6,84,900 for 2TB.'],
            ['Is the iPhone Duo waterproof?', 'It is rated IP68 for dust and water resistance, which is unusual for a folding phone.'],
            ['Does the iPhone Duo have Face ID?', 'No. It uses a Touch ID fingerprint sensor built into the side button.'],
        ],
        'sources' => [['accessed' => '2026-11-01']],
        'seo' => [
            'title' => 'iPhone Duo Price in Nepal: Apple\'s First Foldable iPhone',
            'description' => 'iPhone Duo, Apple\'s first foldable, costs Rs 4,30,900 in Nepal (256GB). Inner and cover screens, cameras, battery life and every variant.',
            'keywords' => 'iphone duo price in nepal, iphone fold nepal, apple foldable iphone price nepal',
        ],
        'image_alt' => 'Apple iPhone Duo foldable',
    ],

    [
        'name' => 'iPhone 17', 'slug' => 'iphone-17', 'brand' => 'Apple', 'category' => 'mobile',
        'release_date' => '2025-09-19', 'featured' => true,
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 164999],
            ['ram' => '8GB', 'storage' => '512GB', 'price' => 215699],
        ],
        'history' => [['2026-11-01', 164999]],
        'specs' => [
            'display' => '6.3-inch Super Retina XDR OLED, 1206×2622, LTPO 120Hz ProMotion, 3,000 nits peak',
            'processor' => 'Apple A19 (3nm)',
            'ram' => '8GB',
            'storage' => '256GB / 512GB',
            'battery' => '25W MagSafe, 15W Qi2; about 50% in 20 minutes with a fast charger',
            'camera' => '48MP main + 48MP ultra wide | 18MP Center Stage front',
            'os' => 'iOS 26 (at launch)',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth 6.0, USB-C',
            'weight' => '177g',
            'dimensions' => '149.6 × 71.5 × 8.0 mm',
            'extra' => ['Colours' => 'Black, White, Mist Blue, Sage, Lavender'],
        ],
        'description' => <<<'HTML'
<p>The iPhone 17 is the year the standard iPhone stopped feeling like a cut-down Pro. It has the same 120Hz ProMotion screen, starts at 256GB of storage, and both rear cameras are 48MP. At <strong>Rs 1,64,999</strong> it is still a lot of money in Nepal, but it is the iPhone most people should buy if they don't need a telephoto lens.</p>
<p>What you give up versus the Pro models is the zoom camera and some battery headroom. Apple's next standard model, the <a href="/products/iphone-18">iPhone 18</a>, is expected around March 2027 — worth waiting for only if you're not in a hurry. For a cheaper way into iOS, the <a href="/products/iphone-17e">iPhone 17e</a> costs about Rs 32,000 less.</p>
HTML,
        'faqs' => [
            ['What is the iPhone 17 price in Nepal?', 'The iPhone 17 costs Rs 1,64,999 for 256GB and Rs 2,15,699 for 512GB.'],
            ['Does the iPhone 17 have a 120Hz display?', 'Yes. It is the first standard iPhone with a 120Hz ProMotion display and an always-on mode.'],
        ],
        'sources' => [$ntApple],
        'seo' => [
            'title' => 'iPhone 17 Price in Nepal: 256GB & 512GB, Specs and Verdict',
            'description' => 'iPhone 17 price in Nepal is Rs 1,64,999 (256GB). 120Hz ProMotion, dual 48MP cameras and A19 chip — specs and whether to wait for iPhone 18.',
            'keywords' => 'iphone 17 price in nepal, iphone 17 nepal, iphone 17 specs',
        ],
        'image_alt' => 'Apple iPhone 17 in Sage and Mist Blue',
    ],

    [
        'name' => 'iPhone Air', 'slug' => 'iphone-air', 'brand' => 'Apple', 'category' => 'mobile',
        'release_date' => '2025-09-19',
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 190699],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 232899],
            ['ram' => '12GB', 'storage' => '1TB', 'price' => 275099],
        ],
        'history' => [['2026-11-01', 190699]],
        'specs' => [
            'display' => '6.5-inch Super Retina XDR OLED, 1260×2736, LTPO 120Hz, 3,000 nits peak',
            'processor' => 'Apple A19 Pro (3nm)',
            'ram' => '12GB',
            'storage' => '256GB / 512GB / 1TB',
            'battery' => 'MagSafe wireless and wired charging; 4.5W reverse wired',
            'camera' => '48MP Fusion main | 18MP front',
            'os' => 'iOS 26 (at launch)',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth 6.0, USB-C',
            'weight' => '165g',
            'dimensions' => '156.2 × 74.7 × 5.6 mm',
            'extra' => ['Colours' => 'Space Black, Cloud White, Light Gold, Sky Blue'],
        ],
        'description' => <<<'HTML'
<p>At 5.6mm thick and 165g, the iPhone Air is the kind of phone you forget is in your shirt pocket. Apple got there by making compromises you should know about before paying <strong>Rs 1,90,699</strong>: there's a single 48MP rear camera, so no ultra wide and no telephoto, and the slim body leaves less room for battery than the iPhone 17 or the Pro models.</p>
<p>Inside it runs the same A19 Pro chip as last year's Pro phones, with 12GB of RAM and a 6.5-inch 120Hz screen. It makes sense for people who mostly message, browse and take the occasional photo, and who value how a phone feels in the hand over how long it lasts. Everyone else will be happier with the <a href="/products/iphone-17">iPhone 17</a>, which is cheaper and has two cameras.</p>
HTML,
        'faqs' => [
            ['What is the iPhone Air price in Nepal?', 'The iPhone Air costs Rs 1,90,699 for 256GB, Rs 2,32,899 for 512GB and Rs 2,75,099 for 1TB.'],
            ['How many cameras does the iPhone Air have?', 'One rear camera — a 48MP main sensor — and an 18MP front camera. There is no ultra wide or telephoto lens.'],
        ],
        'sources' => [$ntApple],
        'seo' => [
            'title' => 'iPhone Air Price in Nepal: 5.6mm Thin iPhone Specs & Trade-offs',
            'description' => 'iPhone Air price in Nepal starts at Rs 1,90,699. The 5.6mm, 165g iPhone has an A19 Pro chip and one 48MP camera — here are the trade-offs.',
            'keywords' => 'iphone air price in nepal, iphone air nepal, thinnest iphone',
        ],
        'image_alt' => 'Apple iPhone Air side profile',
    ],

    [
        'name' => 'iPhone 17e', 'slug' => 'iphone-17e', 'brand' => 'Apple', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 132499],
            ['ram' => '8GB', 'storage' => '512GB', 'price' => 175099],
        ],
        'history' => [['2026-11-01', 132499]],
        'specs' => [
            'display' => '6.1-inch Super Retina XDR OLED, 1,200 nits',
            'processor' => 'Apple A19 (3nm)',
            'ram' => '8GB',
            'storage' => '256GB / 512GB',
            'battery' => '4,005mAh; 15W MagSafe/Qi2 wireless',
            'camera' => '48MP Fusion main | 12MP front',
            'os' => 'iOS 26',
            'connectivity' => '5G, Wi-Fi, Bluetooth, USB-C',
            'weight' => '169g',
            'dimensions' => '146.7 × 71.5 × 7.8 mm',
            'extra' => ['Colours' => 'Black, White, Sky Blue, Cobalt Violet'],
        ],
        'description' => <<<'HTML'
<p>The iPhone 17e is Apple's cheapest current iPhone in Nepal at <strong>Rs 1,32,499</strong>, and it is built for people who want iOS, a recent chip and long software support without paying for extras. You get the same A19 chip as the <a href="/products/iphone-17">iPhone 17</a>, 256GB of storage as standard, a single 48MP camera and MagSafe charging.</p>
<p>The cuts are the ones you'd expect: a smaller, less bright 6.1-inch screen (1,200 nits against 3,000 on the iPhone 17), one rear camera and a 12MP selfie camera. For a parent, a first iPhone or a work phone that needs to last five years, those trade-offs are easy to live with.</p>
HTML,
        'faqs' => [
            ['What is the iPhone 17e price in Nepal?', 'The iPhone 17e costs Rs 1,32,499 for 256GB and Rs 1,75,099 for 512GB.'],
            ['Is the iPhone 17e worth it over the iPhone 17?', 'If you want the brighter 120Hz ProMotion screen and a second (ultra wide) camera, spend the extra Rs 32,500 on the iPhone 17. Otherwise the 17e has the same chip and storage for less.'],
        ],
        'sources' => [$ntApple],
        'seo' => [
            'title' => 'iPhone 17e Price in Nepal: Cheapest New iPhone, Specs & Review',
            'description' => 'iPhone 17e costs Rs 1,32,499 in Nepal with 256GB, an A19 chip and MagSafe. See what it cuts compared with the iPhone 17.',
            'keywords' => 'iphone 17e price in nepal, cheapest iphone nepal, iphone 17e specs',
        ],
        'image_alt' => 'Apple iPhone 17e',
    ],

    /* ─────────────────────────────── Samsung ─────────────────────────────── */

    [
        'name' => 'Samsung Galaxy S26 Ultra', 'slug' => 'samsung-galaxy-s26-ultra', 'brand' => 'Samsung', 'category' => 'mobile',
        'release_date' => '2026-02-25', 'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 224999],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 259999],
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 293999],
        ],
        'history' => [['2026-11-02', 224999]],
        'specs' => [
            'display' => '6.9-inch QHD+ Dynamic AMOLED 2X, 3120×1440, 1–120Hz, 2,600 nits peak, Privacy Display',
            'processor' => 'Snapdragon 8 Elite Gen 5 for Galaxy (3nm)',
            'ram' => '12GB / 16GB',
            'storage' => '256GB / 512GB / 1TB (UFS 4.0)',
            'battery' => '5,000mAh; 60W wired, 25W wireless, 4.5W reverse wireless',
            'camera' => '200MP f/1.4 main (OIS) + 50MP ultra wide + 10MP 3x telephoto + 50MP 5x periscope | 12MP front',
            'os' => 'Android 16, One UI 8.5 (7 years of updates)',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth, UWB, USB-C',
            'weight' => '214g',
            'dimensions' => '163.6 × 78.1 × 7.9 mm',
            'extra' => ['Water resistance' => 'IP68', 'Stylus' => 'Built-in S Pen'],
        ],
        'description' => <<<'HTML'
<p>Samsung's 2026 Ultra costs <strong>Rs 2,24,999</strong> in Nepal for 12GB/256GB, up from Rs 2,12,999 earlier in the year as memory prices pushed most phones up. It still has the most complete camera kit you can buy here — a 200MP main sensor plus both a 3x and a 5x zoom lens — and the built-in S Pen that no other flagship offers.</p>
<p>Two upgrades over the S25 Ultra are easy to feel day to day: charging jumps to 60W, and the new Privacy Display narrows the viewing angle when you want it, which is handy on public transport or in a bank queue. The battery stays at 5,000mAh, smaller than Chinese rivals like the <a href="/products/oneplus-15">OnePlus 15</a>, but Samsung's seven years of updates is the best support you'll get on Android. Comparing with Apple? See the <a href="/compare?g1=samsung-galaxy-s26-ultra&g2=iphone-18-pro-max">S26 Ultra vs iPhone 18 Pro Max</a>.</p>
HTML,
        'faqs' => [
            ['What is the Samsung Galaxy S26 Ultra price in Nepal?', 'Samsung Nepal lists it at Rs 2,24,999 for 12GB/256GB, Rs 2,59,999 for 12GB/512GB and Rs 2,93,999 for 16GB/1TB.'],
            ['Did the Galaxy S26 Ultra get more expensive in Nepal?', 'Yes. The 256GB model was Rs 2,12,999 and the 512GB model Rs 2,42,999, before 2026\'s memory-driven price revisions.'],
            ['How fast does the Galaxy S26 Ultra charge?', 'It supports 60W wired charging and 25W wireless charging.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy S26 Ultra Price in Nepal: Specs, Variants, Review',
            'description' => 'Galaxy S26 Ultra price in Nepal is Rs 2,24,999 (12/256GB). 200MP camera, S Pen, 60W charging and 7 years of updates — full specs.',
            'keywords' => 'samsung s26 ultra price in nepal, galaxy s26 ultra nepal, s26 ultra specs',
        ],
        'image_alt' => 'Samsung Galaxy S26 Ultra with S Pen',
    ],

    [
        'name' => 'Samsung Galaxy S26', 'slug' => 'samsung-galaxy-s26', 'brand' => 'Samsung', 'category' => 'mobile',
        'release_date' => '2026-02-25', 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 139999],
        ],
        'history' => [['2026-06-08', 133999], ['2026-11-02', 139999]],
        'specs' => [
            'display' => '6.3-inch FHD+ Dynamic AMOLED 2X, LTPO 1–120Hz, 2,600 nits peak',
            'processor' => 'Exynos 2600 (2nm) in most markets; Snapdragon 8 Elite Gen 5 for Galaxy in US/China',
            'ram' => '12GB',
            'storage' => '256GB / 512GB (UFS 4.0)',
            'battery' => '4,300mAh; 25W wired, 15W wireless, 4.5W reverse wireless',
            'camera' => '50MP main (OIS) + 12MP ultra wide + 10MP 3x telephoto (OIS) | 12MP front',
            'os' => 'Android 16, One UI 8.5 (7 years of updates)',
            'connectivity' => '5G, Wi-Fi 7, Bluetooth, USB-C',
            'weight' => '167g',
            'dimensions' => '149.6 × 71.7 × 7.2 mm',
            'extra' => ['Water resistance' => 'IP68'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy S26 is one of the few genuinely compact flagships left: 6.3 inches, 167g and 7.2mm thin. In Nepal it sells for <strong>Rs 1,39,999</strong> with 12GB of RAM and 256GB of storage. Samsung briefly cut it to Rs 1,33,999 (and an offer price of Rs 1,23,999) in June, so it is worth watching for another promotion.</p>
<p>It has a proper 3x telephoto lens, which most phones at this price drop, and the same seven-year update promise as the <a href="/products/samsung-galaxy-s26-ultra">S26 Ultra</a>. The trade-off is charging: 25W wired is slow next to the 80–120W on Chinese phones, and the 4,300mAh battery will get most people to the evening, not much further.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy S26 price in Nepal?', 'The official price is Rs 1,39,999 for 12GB RAM and 256GB storage.'],
            ['Which chip does the Galaxy S26 use in Nepal?', 'Outside the US and China, the S26 uses Samsung\'s 2nm Exynos 2600.'],
        ],
        'sources' => [
            $gbSamsung,
            ['accessed' => '2026-06-08'],
        ],
        'seo' => [
            'title' => 'Samsung Galaxy S26 Price in Nepal: Compact Flagship Specs',
            'description' => 'Galaxy S26 costs Rs 1,39,999 in Nepal (12/256GB). Compact 6.3-inch flagship with 3x zoom, Exynos 2600 and seven years of updates.',
            'keywords' => 'samsung galaxy s26 price in nepal, s26 nepal, galaxy s26 specs',
        ],
        'image_alt' => 'Samsung Galaxy S26',
    ],

    [
        'name' => 'Samsung Galaxy Z Fold 8', 'slug' => 'samsung-galaxy-z-fold8', 'brand' => 'Samsung', 'category' => 'mobile',
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 274999],
        ],
        'history' => [['2026-11-02', 274999]],
        'specs' => [
            'display' => '7.6-inch main (2448×1848, 120Hz, 3,000 nits) + 5.5-inch cover (1972×1248, 120Hz)',
            'processor' => 'Snapdragon 8 Elite Gen 5 for Galaxy',
            'ram' => '12GB / 16GB',
            'storage' => '256GB / 512GB / 1TB',
            'battery' => '4,800mAh; 45W wired, 20W wireless, 4.5W reverse wireless',
            'camera' => '50MP main (OIS) + 50MP ultra wide | 10MP on each screen',
            'os' => 'Android 17, One UI 9',
            'weight' => '201g',
            'dimensions' => '9.7mm folded, 4.5mm unfolded',
            'extra' => ['Water resistance' => 'IP48'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy Z Fold 8 costs <strong>Rs 2,74,999</strong> in Nepal and is the most practical way to get a tablet-sized screen in your pocket without paying iPhone Duo money. At 201g it is lighter than the iPhone 18 Pro Max, and the 5.5-inch cover screen is wide enough to type on comfortably when the phone is shut.</p>
<p>Samsung now uses two 50MP rear cameras, a 4,800mAh battery and 45W charging. The IP48 rating protects against water but not fine dust, so treat the hinge with some care during Kathmandu's dusty months. Samsung also sells a Z Fold 8 Ultra from Rs 2,99,999 for buyers who want the extra camera hardware.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Z Fold 8 price in Nepal?', 'The Galaxy Z Fold 8 costs Rs 2,74,999 for 12GB/256GB. The Z Fold 8 Ultra starts at Rs 2,99,999.'],
            ['Is the Galaxy Z Fold 8 dustproof?', 'No. It is rated IP48, which covers water immersion but only limited protection against small particles.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy Z Fold 8 Price in Nepal & Specifications',
            'description' => 'Galaxy Z Fold 8 costs Rs 2,74,999 in Nepal. 7.6-inch main screen, 201g weight, 45W charging and how it compares with the iPhone Duo.',
            'keywords' => 'galaxy z fold 8 price in nepal, samsung fold 8 nepal',
        ],
        'image_alt' => 'Samsung Galaxy Z Fold 8 unfolded',
    ],

    [
        'name' => 'Samsung Galaxy Z Flip 8', 'slug' => 'samsung-galaxy-z-flip8', 'brand' => 'Samsung', 'category' => 'mobile',
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 194999],
        ],
        'history' => [['2026-11-02', 194999]],
        'specs' => [
            'display' => '6.9-inch LTPO Dynamic AMOLED 2X (2520×1080, 120Hz) + 4.1-inch cover Super AMOLED (120Hz)',
            'processor' => 'Exynos 2600 (2nm)',
            'ram' => '12GB',
            'storage' => '256GB',
            'battery' => '4,300mAh; 25W wired, 15W wireless',
            'camera' => '50MP main (OIS) + 12MP ultra wide | 10MP front',
            'os' => 'Android 17, One UI 9',
            'weight' => 'About 180g',
            'dimensions' => '6.1mm unfolded, 13.1mm folded',
            'extra' => ['Colours' => 'Graphite, Cream, Pink, Mint'],
        ],
        'description' => <<<'HTML'
<p>The Z Flip 8 is the foldable for people who want a smaller phone, not a bigger one. Closed, it is a palm-sized square with a 4.1-inch cover screen big enough for maps, replies and framing selfies with the main camera. Open, it is a full 6.9-inch phone. It costs <strong>Rs 1,94,999</strong> in Nepal.</p>
<p>Samsung moved the Flip to its own 2nm Exynos 2600 and kept the 4,300mAh battery and 25W charging — so it's a fashion-forward phone first and an endurance phone second. If battery life tops your list, a slab phone like the <a href="/products/samsung-galaxy-s26">Galaxy S26</a> costs less and lasts about as long.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy Z Flip 8 price in Nepal?', 'It costs Rs 1,94,999 for 12GB RAM and 256GB storage.'],
            ['What colours does the Galaxy Z Flip 8 come in?', 'Graphite, Cream, Pink and Mint, depending on stock at Samsung stores in Nepal.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy Z Flip 8 Price in Nepal: Specs & Colours',
            'description' => 'Galaxy Z Flip 8 price in Nepal is Rs 1,94,999. Exynos 2600, 4.1-inch cover screen, 50MP camera and battery details.',
            'keywords' => 'galaxy z flip 8 price in nepal, samsung flip 8 nepal',
        ],
        'image_alt' => 'Samsung Galaxy Z Flip 8 folded',
    ],

    [
        'name' => 'Samsung Galaxy A57 5G', 'slug' => 'samsung-galaxy-a57-5g', 'brand' => 'Samsung', 'category' => 'mobile',
        'featured' => true,
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 96999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 107999],
        ],
        'history' => [['2026-11-02', 96999]],
        'specs' => [
            'display' => '6.7-inch FHD+ Super AMOLED, 120Hz, Gorilla Glass Victus+',
            'processor' => 'Exynos 1680 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '128GB / 256GB / 512GB',
            'battery' => '5,000mAh; 45W wired',
            'camera' => '50MP main + 12MP ultra wide + 5MP macro | 12MP front',
            'os' => 'Android 16, One UI 8.5 (6 years of updates)',
            'weight' => 'About 179g',
            'dimensions' => '6.9mm thick',
            'extra' => ['Colours' => 'Navy, Icy Blue, Lilac'],
        ],
        'description' => <<<'HTML'
<p>For years the Galaxy A5x was the default "safe" mid-range phone in Nepal. The A57 5G is still a good phone, but at <strong>Rs 96,999</strong> (8GB/256GB) it is no longer cheap. It was Rs 84,999 before Samsung's 2026 price revisions. The 12GB model jumped from Rs 89,999 to Rs 1,07,999.</p>
<p>What you get is a thin 6.9mm body, a bright 120Hz AMOLED screen, faster 45W charging and six years of Android updates, which is longer than any Chinese mid-ranger promises. The camera setup (50MP main, 12MP ultra wide, 5MP macro) is solid rather than exciting. At this price, compare it with the <a href="/products/redmi-note-17-pro-max-5g">Redmi Note 17 Pro Max</a> and <a href="/products/oneplus-nord-6">OnePlus Nord 6</a>, both of which offer much bigger batteries.</p>
HTML,
        'faqs' => [
            ['What is the Samsung Galaxy A57 5G price in Nepal?', 'The A57 5G costs Rs 96,999 for 8GB/256GB and Rs 1,07,999 for 12GB/256GB.'],
            ['How many years of updates does the Galaxy A57 get?', 'Samsung promises six years of Android OS and security updates.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy A57 5G Price in Nepal (Updated): Specs & Value',
            'description' => 'Galaxy A57 5G now costs Rs 96,999 in Nepal (8/256GB), up from Rs 84,999. Specs, 45W charging, six years of updates and rivals.',
            'keywords' => 'samsung a57 price in nepal, galaxy a57 5g nepal, a57 specs',
        ],
        'image_alt' => 'Samsung Galaxy A57 5G in Icy Blue',
    ],

    [
        'name' => 'Samsung Galaxy A37 5G', 'slug' => 'samsung-galaxy-a37-5g', 'brand' => 'Samsung', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '128GB', 'price' => 74999],
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 86999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 96999],
        ],
        'history' => [['2026-11-02', 74999]],
        'specs' => [
            'display' => '6.7-inch Super AMOLED, 120Hz, Gorilla Glass Victus 2',
            'processor' => 'Exynos 1480 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '128GB / 256GB',
            'battery' => '5,000mAh; 45W wired',
            'camera' => '50MP main (OIS) + 8MP ultra wide + 5MP macro | 12MP front',
            'os' => 'Android 16, One UI 8.5 (6 years of updates)',
            'extra' => ['Water resistance' => 'IP68'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy A37 5G starts at <strong>Rs 74,999</strong> in Nepal, up from Rs 64,999 before this year's price revisions. Its best features are the boring, important ones: an IP68 rating (upgraded from IP67), optical image stabilisation on the 50MP main camera, 45W charging and six years of updates.</p>
<p>The Exynos 1480 is a year-old chip, fine for social apps and casual games but not built for long gaming sessions. If you can stretch, the <a href="/products/samsung-galaxy-a57-5g">Galaxy A57</a> is faster and thinner; if you can't, the <a href="/products/samsung-galaxy-a17-5g">Galaxy A17 5G</a> keeps the Samsung software for much less.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy A37 5G price in Nepal?', 'Rs 74,999 for 8GB/128GB, Rs 86,999 for 8GB/256GB and Rs 96,999 for 12GB/256GB.'],
            ['Is the Galaxy A37 waterproof?', 'It is rated IP68 for dust and water resistance.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy A37 5G Price in Nepal: Specs, IP68 & Variants',
            'description' => 'Galaxy A37 5G price in Nepal starts at Rs 74,999. IP68, OIS camera, 45W charging, six years of updates — all variants and specs.',
            'keywords' => 'galaxy a37 price in nepal, samsung a37 5g nepal',
        ],
        'image_alt' => 'Samsung Galaxy A37 5G',
    ],

    [
        'name' => 'Samsung Galaxy A17 5G', 'slug' => 'samsung-galaxy-a17-5g', 'brand' => 'Samsung', 'category' => 'mobile',
        'trending' => true,
        'variants' => [
            ['ram' => '6GB', 'storage' => '128GB', 'price' => 44999],
            ['ram' => '8GB', 'storage' => '128GB', 'price' => 48999],
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 56999],
        ],
        'history' => [['2026-11-02', 44999]],
        'specs' => [
            'display' => '6.7-inch FHD+ Super AMOLED, 90Hz, 1,100 nits, Gorilla Glass Victus',
            'processor' => 'Exynos 1330',
            'ram' => '6GB / 8GB',
            'storage' => '128GB / 256GB, microSD',
            'battery' => '5,000mAh; 25W wired',
            'camera' => '50MP main (OIS) + 5MP ultra wide + 2MP macro | 13MP front',
            'os' => 'Android 15, One UI 7',
            'extra' => ['Water resistance' => 'IP54', 'Colours' => 'Black, Blue, Gray'],
        ],
        'description' => <<<'HTML'
<p>The Galaxy A17 5G is Samsung's best-selling style of phone in Nepal: an AMOLED screen, 5G and a 50MP camera with OIS for under Rs 50,000. It now starts at <strong>Rs 44,999</strong> (6GB/128GB). It was Rs 36,999 before 2026's memory-cost hikes, which is a big jump for a budget phone.</p>
<p>It keeps a microSD slot, which many rivals have dropped, and Samsung's software is clean and long-supported. Just don't expect fast charging — 25W is slow by 2026 standards. If charging speed and battery size matter more than the brand, the <a href="/products/redmi-17-5g">Redmi 17 5G</a> offers a 7,500mAh battery for less.</p>
HTML,
        'faqs' => [
            ['What is the Galaxy A17 5G price in Nepal now?', 'Rs 44,999 for 6GB/128GB, Rs 48,999 for 8GB/128GB and Rs 56,999 for 8GB/256GB.'],
            ['Does the Galaxy A17 5G support memory cards?', 'Yes, it has a microSD slot for expanding storage.'],
        ],
        'sources' => [$gbSamsung],
        'seo' => [
            'title' => 'Samsung Galaxy A17 5G Price in Nepal (New Price) & Specs',
            'description' => 'Galaxy A17 5G now starts at Rs 44,999 in Nepal after a price hike. AMOLED, OIS camera, microSD and 25W charging explained.',
            'keywords' => 'samsung a17 price in nepal, galaxy a17 5g nepal new price',
        ],
        'image_alt' => 'Samsung Galaxy A17 5G',
    ],

    /* ─────────────────────────────── Xiaomi / Redmi ─────────────────────────────── */

    [
        'name' => 'Xiaomi 17 Ultra', 'slug' => 'xiaomi-17-ultra', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'featured' => true,
        'variants' => [['ram' => '16GB', 'storage' => '512GB', 'price' => 199999]],
        'history' => [['2026-11-22', 199999]],
        'specs' => [
            'display' => '6.9-inch LTPO OLED, 2608×1200, 1–120Hz, 12-bit, 3,500 nits peak, Dolby Vision',
            'processor' => 'Snapdragon 8 Elite Gen 5 (3nm)',
            'ram' => '16GB LPDDR5X',
            'storage' => '512GB UFS 4.1',
            'battery' => '6,000mAh; 90W wired, 50W wireless, 22.5W reverse',
            'camera' => '50MP 1-inch Light Fusion 1050L main (f/1.67, OIS, LOFIC) + 200MP periscope (3.2x–4.3x optical) + 50MP ultra wide | 50MP front',
            'os' => 'Android 16, HyperOS 3',
            'extra' => ['Water resistance' => 'IP66, IP68, IP69'],
        ],
        'description' => <<<'HTML'
<p>The Xiaomi 17 Ultra is the phone for people who buy a phone as a camera. Its main sensor is a full 1-inch type with LOFIC technology for cleaner highlights, and the 200MP periscope lens zooms optically between roughly 3.2x and 4.3x — useful for street portraits in Asan or a temple roof across a courtyard. In Nepal it costs <strong>Rs 1,99,999</strong> for 16GB/512GB, the only configuration on sale.</p>
<p>Unlike many camera-first phones, it doesn't skimp elsewhere: a 6,000mAh battery, 90W wired and 50W wireless charging, IP68/IP69 protection and a 50MP selfie camera. HyperOS has improved but still ships with more pre-installed apps than Samsung or Google. For a camera-focused alternative with a longer zoom, see the <a href="/products/vivo-x300-pro">vivo X300 Pro</a>.</p>
HTML,
        'faqs' => [
            ['What is the Xiaomi 17 Ultra price in Nepal?', 'The Xiaomi 17 Ultra costs Rs 1,99,999 for 16GB RAM and 512GB storage.'],
            ['Does the Xiaomi 17 Ultra have a 1-inch camera sensor?', 'Yes. The 50MP main camera uses a 1-inch type Light Fusion 1050L sensor with optical stabilisation.'],
        ],
        'sources' => [$gbXiaomi],
        'seo' => [
            'title' => 'Xiaomi 17 Ultra Price in Nepal: 1-inch Camera Flagship Specs',
            'description' => 'Xiaomi 17 Ultra costs Rs 1,99,999 in Nepal (16/512GB). 1-inch main camera, 200MP periscope, 6,000mAh battery and 90W charging.',
            'keywords' => 'xiaomi 17 ultra price in nepal, xiaomi 17 ultra nepal, xiaomi 17 ultra camera',
        ],
        'image_alt' => 'Xiaomi 17 Ultra camera module',
    ],

    [
        'name' => 'Xiaomi 17T', 'slug' => 'xiaomi-17t', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 92999],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 99999],
        ],
        'history' => [['2026-11-22', 92999]],
        'specs' => [
            'display' => '6.59-inch 1.5K AMOLED, 120Hz, 12-bit, Dolby Vision, HDR10+',
            'processor' => 'MediaTek Dimensity 8500 Ultra (4nm)',
            'ram' => '12GB',
            'storage' => '256GB / 512GB',
            'battery' => '6,500mAh; 67W wired',
            'camera' => '50MP main (OIS) + 50MP 5x telephoto + 12MP ultra wide, Leica tuning | 32MP front',
            'os' => 'Android 16, HyperOS 3',
            'extra' => ['Water resistance' => 'IP68'],
        ],
        'description' => <<<'HTML'
<p>The Xiaomi 17T has just launched in Nepal from <strong>Rs 92,999</strong>, and its headline feature is unusual at this price: a 50MP 5x telephoto camera with Leica tuning. Most phones under Rs 1 lakh either skip zoom entirely or use a low-resolution 2x lens.</p>
<p>The Dimensity 8500 Ultra is a strong upper-mid-range chip, the 6.59-inch screen keeps the phone easy to hold, and the 6,500mAh battery with 67W charging is a sensible balance. Wireless charging is missing. Shoppers in this range should also compare the <a href="/products/oneplus-15r">OnePlus 15R</a>, which is faster but has a weaker camera.</p>
HTML,
        'faqs' => [
            ['What is the Xiaomi 17T price in Nepal?', 'Rs 92,999 for 12GB/256GB and Rs 99,999 for 12GB/512GB.'],
            ['Does the Xiaomi 17T have a telephoto camera?', 'Yes, a 50MP 5x telephoto alongside the 50MP main and 12MP ultra wide cameras.'],
        ],
        'sources' => [$gbXiaomi],
        'seo' => [
            'title' => 'Xiaomi 17T Price in Nepal: 5x Telephoto Phone Under Rs 1 Lakh',
            'description' => 'Xiaomi 17T launched in Nepal from Rs 92,999. Dimensity 8500 Ultra, 50MP 5x Leica telephoto, 6,500mAh battery — specs and verdict.',
            'keywords' => 'xiaomi 17t price in nepal, xiaomi 17t nepal launch',
        ],
        'image_alt' => 'Xiaomi 17T',
    ],

    [
        'name' => 'Redmi Note 17 Pro Max 5G', 'slug' => 'redmi-note-17-pro-max-5g', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'featured' => true, 'trending' => true,
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 69999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 79999],
        ],
        'history' => [['2026-11-22', 69999]],
        'specs' => [
            'display' => '6.83-inch 1.5K CrystalRes AMOLED, 120Hz, 3,500 nits peak, 12-bit, Gorilla Glass Victus 2',
            'processor' => 'Snapdragon 6 Gen 5 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '256GB',
            'battery' => '10,000mAh silicon-carbon; 100W wired, 27W reverse wired',
            'camera' => '50MP main (OIS) + 8MP ultra wide | 32MP front',
            'os' => 'Android 16, HyperOS 3',
            'extra' => ['Water resistance' => 'IP68, IP69K'],
        ],
        'description' => <<<'HTML'
<p>Ten thousand milliamp-hours. That is the number that makes the Redmi Note 17 Pro Max interesting — a battery that big used to mean a power bank, and here it sits in a phone with a 6.83-inch 1.5K AMOLED screen and 100W charging. It costs <strong>Rs 69,999</strong> for 8GB/256GB in Nepal.</p>
<p>Load-shedding is rare now, but long days away from a socket — trekking, field work, ride-sharing — are not, and this is the phone for them. The Snapdragon 6 Gen 5 handles everyday apps and casual gaming comfortably, though it is not a flagship chip, and the camera setup is just a main and an ultra wide. For an even cheaper big-battery option, see the <a href="/products/redmi-note-17-pro-5g">Redmi Note 17 Pro</a>.</p>
HTML,
        'faqs' => [
            ['What is the Redmi Note 17 Pro Max price in Nepal?', 'Rs 69,999 for 8GB/256GB and Rs 79,999 for 12GB/256GB.'],
            ['How big is the Redmi Note 17 Pro Max battery?', 'It has a 10,000mAh silicon-carbon battery with 100W wired charging.'],
            ['Is the Redmi Note 17 Pro Max waterproof?', 'It carries IP68 and IP69K ratings for dust and water resistance.'],
        ],
        'sources' => [$gbXiaomi],
        'seo' => [
            'title' => 'Redmi Note 17 Pro Max Price in Nepal: 10,000mAh Battery Phone',
            'description' => 'Redmi Note 17 Pro Max 5G costs Rs 69,999 in Nepal. 10,000mAh battery, 100W charging, 1.5K AMOLED and IP69K — full specs.',
            'keywords' => 'redmi note 17 pro max price in nepal, redmi note 17 pro max 5g nepal',
        ],
        'image_alt' => 'Redmi Note 17 Pro Max 5G',
    ],

    [
        'name' => 'Redmi Note 17 Pro 5G', 'slug' => 'redmi-note-17-pro-5g', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 57999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 64999],
        ],
        'history' => [['2026-11-22', 57999]],
        'specs' => [
            'display' => '6.83-inch 1.5K AMOLED (2772×1280), 120Hz, 3,500 nits peak, 3840Hz PWM dimming',
            'processor' => 'Snapdragon 6s Gen 4 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '256GB',
            'battery' => '8,340mAh; 67W wired, 22.5W reverse wired',
            'camera' => '50MP main (OIS) + 8MP ultra wide | 16MP front',
            'os' => 'Android 16, HyperOS 3',
            'extra' => ['Water resistance' => 'IP68, IP69K', 'Colours' => 'Sky Blue, Black, Satin Orange, Purple'],
        ],
        'description' => <<<'HTML'
<p>The Redmi Note 17 Pro is the sensible sibling of the Pro Max: the same big 6.83-inch 1.5K AMOLED screen and IP69K toughness, a still-huge 8,340mAh battery, and a <strong>Rs 57,999</strong> starting price. Charging drops to 67W and the chip to a Snapdragon 6s Gen 4, which is noticeably slower in games but fine for everything else.</p>
<p>Its 3840Hz PWM dimming is worth a mention for anyone who gets eye strain from OLED screens at night. If you game a lot, spend the extra Rs 12,000 on the <a href="/products/redmi-note-17-pro-max-5g">Pro Max</a>.</p>
HTML,
        'faqs' => [
            ['What is the Redmi Note 17 Pro price in Nepal?', 'Rs 57,999 for 8GB/256GB and Rs 64,999 for 12GB/256GB.'],
            ['Redmi Note 17 Pro or Pro Max?', 'The Pro Max has a faster Snapdragon 6 Gen 5, a 10,000mAh battery and 100W charging for Rs 12,000 more. The Pro is the better value if you don\'t play demanding games.'],
        ],
        'sources' => [$gbXiaomi],
        'seo' => [
            'title' => 'Redmi Note 17 Pro 5G Price in Nepal: Specs & Pro Max Comparison',
            'description' => 'Redmi Note 17 Pro 5G price in Nepal starts at Rs 57,999. 8,340mAh battery, 1.5K AMOLED, IP69K — and how it differs from the Pro Max.',
            'keywords' => 'redmi note 17 pro price in nepal, redmi note 17 pro 5g nepal',
        ],
        'image_alt' => 'Redmi Note 17 Pro 5G',
    ],

    [
        'name' => 'Redmi 17 5G', 'slug' => 'redmi-17-5g', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'variants' => [
            ['ram' => '4GB', 'storage' => '128GB', 'price' => 33999],
            ['ram' => '4GB', 'storage' => '256GB', 'price' => 37999],
            ['ram' => '6GB', 'storage' => '256GB', 'price' => 45999],
        ],
        'history' => [['2026-11-22', 33999]],
        'specs' => [
            'display' => '6.9-inch HD+ LCD, 120Hz, 825 nits peak, Gorilla Glass 7i',
            'processor' => 'Snapdragon 4 Gen 5 (4nm)',
            'ram' => '4GB / 6GB',
            'storage' => '128GB / 256GB, microSD',
            'battery' => '7,500mAh; 45W wired, 22.5W reverse wired',
            'camera' => '50MP main + auxiliary lens | 8MP front',
            'os' => 'Android 16, HyperOS 3',
            'extra' => ['Water resistance' => 'IP64'],
        ],
        'description' => <<<'HTML'
<p>The Redmi 17 5G is a big, simple phone with one standout strength: a 7,500mAh battery that will comfortably last two days of normal use. It starts at <strong>Rs 33,999</strong> in Nepal with 4GB of RAM and 128GB of storage, and you can add a microSD card.</p>
<p>The 6.9-inch screen is an HD+ LCD, so text is softer than on AMOLED phones like the <a href="/products/samsung-galaxy-a17-5g">Galaxy A17 5G</a>, but it refreshes at 120Hz and is protected by Gorilla Glass 7i. Pick the 6GB model if the phone has to last three years or more.</p>
HTML,
        'faqs' => [
            ['What is the Redmi 17 5G price in Nepal?', 'Rs 33,999 for 4GB/128GB, Rs 37,999 for 4GB/256GB and Rs 45,999 for 6GB/256GB.'],
            ['Does the Redmi 17 5G have an AMOLED screen?', 'No. It uses a 6.9-inch HD+ LCD with a 120Hz refresh rate.'],
        ],
        'sources' => [$gbXiaomi],
        'seo' => [
            'title' => 'Redmi 17 5G Price in Nepal: 7,500mAh Budget 5G Phone',
            'description' => 'Redmi 17 5G starts at Rs 33,999 in Nepal. 7,500mAh battery, 6.9-inch 120Hz screen, Snapdragon 4 Gen 5 and microSD support.',
            'keywords' => 'redmi 17 5g price in nepal, redmi 17 nepal',
        ],
        'image_alt' => 'Redmi 17 5G',
    ],

    /* ─────────────────────────────── OnePlus ─────────────────────────────── */

    [
        'name' => 'OnePlus 15', 'slug' => 'oneplus-15', 'brand' => 'OnePlus', 'category' => 'mobile',
        'featured' => true, 'trending' => true,
        'variants' => [['ram' => '16GB', 'storage' => '512GB', 'price' => 189999]],
        'history' => [['2026-11-20', 189999]],
        'specs' => [
            'display' => '6.78-inch AMOLED, LTPO 1–120Hz (up to 165Hz in games), Dolby Vision',
            'processor' => 'Snapdragon 8 Elite Gen 5 (3nm)',
            'ram' => '12GB / 16GB LPDDR5X',
            'storage' => '256GB / 512GB UFS 4.1',
            'battery' => '7,300mAh; up to 120W wired, 50W wireless',
            'camera' => '50MP Sony LYT-700 main (OIS) + 50MP ultra wide + 50MP 3.5x telephoto | 32MP front',
            'os' => 'Android 16, OxygenOS 16',
            'weight' => '211–215g',
            'dimensions' => '161.4 × 76.7 × 8.1 mm',
            'extra' => ['Water resistance' => 'IP68, IP69K'],
        ],
        'description' => <<<'HTML'
<p>The OnePlus 15 is the flagship for people who care about speed and stamina more than camera bragging rights. It pairs the Snapdragon 8 Elite Gen 5 with a 7,300mAh battery — 2,300mAh more than the <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a> — and its screen can run at 165Hz in supported games. In Nepal it is sold as a single 16GB/512GB model for <strong>Rs 1,89,999</strong>.</p>
<p>All three rear cameras are 50MP, including a 3.5x telephoto, and OnePlus has moved away from Hasselblad branding to its own processing. Published specs disagree about the charger: OnePlus advertises up to 120W, but check what is in the box at the store before you buy.</p>
HTML,
        'faqs' => [
            ['What is the OnePlus 15 price in Nepal?', 'The OnePlus 15 costs Rs 1,89,999 for 16GB RAM and 512GB storage.'],
            ['How big is the OnePlus 15 battery?', 'It has a 7,300mAh battery, among the largest on a flagship phone.'],
            ['Does the OnePlus 15 come with a charger in Nepal?', 'Charger bundling has varied by market, so confirm with the seller. The phone supports up to 120W wired and 50W wireless charging.'],
        ],
        'sources' => [$gbOnePlus],
        'seo' => [
            'title' => 'OnePlus 15 Price in Nepal: Snapdragon 8 Elite Gen 5, 7,300mAh',
            'description' => 'OnePlus 15 costs Rs 1,89,999 in Nepal (16/512GB). 7,300mAh battery, 165Hz gaming display, triple 50MP cameras — specs and verdict.',
            'keywords' => 'oneplus 15 price in nepal, oneplus 15 nepal, oneplus 15 specs',
        ],
        'image_alt' => 'OnePlus 15',
    ],

    [
        'name' => 'OnePlus 15R', 'slug' => 'oneplus-15r', 'brand' => 'OnePlus', 'category' => 'mobile',
        'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 98499],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 106999],
        ],
        'history' => [['2026-11-20', 98499]],
        'specs' => [
            'display' => '6.83-inch AMOLED, 165Hz, Dolby Vision, HDR10+',
            'processor' => 'Snapdragon 8 Gen 5 (3nm)',
            'ram' => '12GB LPDDR5X',
            'storage' => '256GB / 512GB UFS 4.1',
            'battery' => '7,400mAh; 80W wired',
            'camera' => '50MP main (OIS) + 8MP ultra wide | 32MP front',
            'os' => 'Android 16, OxygenOS 16',
            'weight' => '213–219g',
            'dimensions' => '163.4 × 77.0 × 8.1 mm',
            'extra' => ['Water resistance' => 'IP66, IP68, IP69, IP69K'],
        ],
        'description' => <<<'HTML'
<p>At <strong>Rs 98,499</strong> the OnePlus 15R gets you a current-generation Snapdragon 8-class chip and a 7,400mAh battery for roughly half the price of the <a href="/products/oneplus-15">OnePlus 15</a>. For gamers on a budget, few phones in Nepal make a stronger case: 165Hz screen, flagship-level performance and 80W charging.</p>
<p>The savings come from the camera (a main and a basic 8MP ultra wide, no telephoto) and from missing wireless charging. If photography matters, the <a href="/products/xiaomi-17t">Xiaomi 17T</a> is the better all-rounder at a similar price.</p>
HTML,
        'faqs' => [
            ['What is the OnePlus 15R price in Nepal?', 'Rs 98,499 for 12GB/256GB and Rs 1,06,999 for 12GB/512GB.'],
            ['Is the OnePlus 15R good for gaming?', 'Yes. Its Snapdragon 8 Gen 5 and 165Hz display make it one of the fastest phones under Rs 1 lakh in Nepal.'],
        ],
        'sources' => [$gbOnePlus],
        'seo' => [
            'title' => 'OnePlus 15R Price in Nepal: Best Gaming Phone Under Rs 1 Lakh?',
            'description' => 'OnePlus 15R costs Rs 98,499 in Nepal. Snapdragon 8 Gen 5, 165Hz AMOLED, 7,400mAh battery and 80W charging — specs and trade-offs.',
            'keywords' => 'oneplus 15r price in nepal, oneplus 15r nepal',
        ],
        'image_alt' => 'OnePlus 15R',
    ],

    [
        'name' => 'OnePlus Nord 6', 'slug' => 'oneplus-nord-6', 'brand' => 'OnePlus', 'category' => 'mobile',
        'variants' => [['ram' => '8GB', 'storage' => '256GB', 'price' => 89999]],
        'history' => [['2026-11-20', 89999]],
        'specs' => [
            'display' => '6.78-inch 1.5K AMOLED, 165Hz, 1,800 nits HBM, PWM + DC dimming',
            'processor' => 'Snapdragon 8s Gen 4 (4nm)',
            'ram' => '8GB / 12GB LPDDR5X',
            'storage' => '256GB / 512GB UFS 4.1',
            'battery' => '7,500mAh; 80W wired, 27W reverse wired',
            'camera' => '50MP main (OIS) + 8MP ultra wide | 32MP front',
            'os' => 'Android 16',
            'weight' => '217g',
            'dimensions' => '162.5 × 77.5 × 8.5 mm',
            'extra' => ['Water resistance' => 'IP66, IP68, IP69, IP69K'],
        ],
        'description' => <<<'HTML'
<p>The Nord 6 uses the Snapdragon 8s Gen 4 — the same chip as the <a href="/products/nothing-phone-3">Nothing Phone (3)</a> — and wraps it in a 7,500mAh battery with 80W charging. At <strong>Rs 89,999</strong> for 8GB/256GB in Nepal, it sits awkwardly close to the <a href="/products/oneplus-15r">OnePlus 15R</a>, which is faster for Rs 8,500 more.</p>
<p>If you can find it discounted, it is an easy recommendation: a sharp 165Hz screen, strong dust and water protection, and battery life that should see most people through two days.</p>
HTML,
        'faqs' => [
            ['What is the OnePlus Nord 6 price in Nepal?', 'The Nord 6 costs Rs 89,999 for 8GB RAM and 256GB storage.'],
        ],
        'sources' => [$gbOnePlus],
        'seo' => [
            'title' => 'OnePlus Nord 6 Price in Nepal: Specs, Battery & Rivals',
            'description' => 'OnePlus Nord 6 costs Rs 89,999 in Nepal. Snapdragon 8s Gen 4, 7,500mAh battery, 165Hz screen — and why the 15R may be the smarter buy.',
            'keywords' => 'oneplus nord 6 price in nepal, nord 6 nepal',
        ],
        'image_alt' => 'OnePlus Nord 6',
    ],

    /* ─────────────────────────────── Nothing ─────────────────────────────── */

    [
        'name' => 'Nothing Phone (3)', 'slug' => 'nothing-phone-3', 'brand' => 'Nothing', 'category' => 'mobile',
        'variants' => [['ram' => '16GB', 'storage' => '512GB', 'price' => 109999]],
        'history' => [['2026-11-22', 109999]],
        'specs' => [
            'display' => '6.67-inch FHD+ AMOLED, 120Hz, Gorilla Glass 7i',
            'processor' => 'Snapdragon 8s Gen 4 (4nm)',
            'ram' => '12GB / 16GB',
            'storage' => '256GB / 512GB',
            'battery' => '5,150mAh; 65W wired, 15W wireless',
            'camera' => '50MP main + 50MP ultra wide + 50MP 3x periscope | 50MP front',
            'os' => 'Android, Nothing OS (5 years of updates)',
            'weight' => '218g',
            'dimensions' => '160.6 × 75.6 × 9.0 mm',
            'extra' => ['Water resistance' => 'IP68', 'Other' => 'Glyph Matrix rear display, eSIM, Wi-Fi 7'],
        ],
        'description' => <<<'HTML'
<p>The Nothing Phone (3) is a flagship with a personality. The Glyph Matrix on the back can show the time, a battery meter or simple notifications without waking the screen, and Nothing OS is one of the cleanest Android skins available. In Nepal it costs <strong>Rs 1,09,999</strong> for 16GB/512GB.</p>
<p>The hardware is upper-mid-range rather than top-tier — a Snapdragon 8s Gen 4 and a 5,150mAh battery — but you get a 3x periscope camera and wireless charging, which the <a href="/products/oneplus-15r">OnePlus 15R</a> doesn't have. Buy it if you value design and software as much as raw speed.</p>
HTML,
        'faqs' => [
            ['What is the Nothing Phone (3) price in Nepal?', 'It costs Rs 1,09,999 for 16GB RAM and 512GB storage.'],
            ['Does the Nothing Phone (3) have wireless charging?', 'Yes, 15W wireless charging alongside 65W wired.'],
        ],
        'sources' => [$gbNothing],
        'seo' => [
            'title' => 'Nothing Phone (3) Price in Nepal: Specs, Glyph Matrix & Verdict',
            'description' => 'Nothing Phone (3) costs Rs 1,09,999 in Nepal (16/512GB). Snapdragon 8s Gen 4, 3x periscope camera and the Glyph Matrix explained.',
            'keywords' => 'nothing phone 3 price in nepal, nothing phone (3) nepal',
        ],
        'image_alt' => 'Nothing Phone (3) rear with Glyph Matrix',
    ],

    [
        'name' => 'Nothing Phone (4a)', 'slug' => 'nothing-phone-4a', 'brand' => 'Nothing', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '128GB', 'price' => 69999],
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 79999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 83999],
        ],
        'history' => [['2026-11-22', 69999]],
        'specs' => [
            'display' => '6.78-inch AMOLED, 120Hz, Gorilla Glass 7i',
            'processor' => 'Snapdragon 7s Gen 4 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '128GB / 256GB',
            'battery' => '5,080mAh; 50W wired',
            'camera' => '50MP main + 8MP ultra wide + 50MP 3.5x periscope | 32MP front',
            'os' => 'Android 16, Nothing OS 4.1',
            'weight' => '205g',
            'dimensions' => '163.9 × 77.5 × 8.5 mm',
            'extra' => ['Water resistance' => 'IP64'],
        ],
        'description' => <<<'HTML'
<p>A 3.5x periscope camera on a phone that starts at <strong>Rs 69,999</strong> is the Nothing Phone (4a)'s party trick, and it is a genuinely useful one for portraits and zoomed photos. The rest is classic Nothing: a transparent-look design, clean software and a smooth 120Hz AMOLED screen.</p>
<p>The Snapdragon 7s Gen 4 is a mid-range chip and the 5,080mAh battery is average for 2026, so heavy gamers should look at the <a href="/products/redmi-note-17-pro-max-5g">Redmi Note 17 Pro Max</a> instead. There is also a Phone (4a) Pro at Rs 1,09,999 for those who want more.</p>
HTML,
        'faqs' => [
            ['What is the Nothing Phone (4a) price in Nepal?', 'Rs 69,999 for 8GB/128GB, Rs 79,999 for 8GB/256GB and Rs 83,999 for 12GB/256GB.'],
            ['Does the Nothing Phone (4a) have a telephoto camera?', 'Yes, a 50MP periscope telephoto with 3.5x optical zoom.'],
        ],
        'sources' => [$gbNothing],
        'seo' => [
            'title' => 'Nothing Phone (4a) Price in Nepal: Periscope Camera Mid-ranger',
            'description' => 'Nothing Phone (4a) price in Nepal starts at Rs 69,999. 3.5x periscope camera, Snapdragon 7s Gen 4 and Nothing OS 4.1 — full specs.',
            'keywords' => 'nothing phone 4a price in nepal, nothing phone (4a) nepal',
        ],
        'image_alt' => 'Nothing Phone (4a)',
    ],

    /* ─────────────────────────────── vivo / Realme / Honor ─────────────────────────────── */

    [
        'name' => 'vivo X300 Pro', 'slug' => 'vivo-x300-pro', 'brand' => 'vivo', 'category' => 'mobile',
        'variants' => [['ram' => '16GB', 'storage' => '512GB', 'price' => 199999]],
        'history' => [['2026-09-01', 199999], ['2026-11-01', 199999]],
        'specs' => [
            'display' => '6.78-inch BOE Q10+ AMOLED, 2800×1260, 120Hz, 4,500 nits peak',
            'processor' => 'MediaTek Dimensity 9500 (3nm)',
            'ram' => '16GB LPDDR5X',
            'storage' => '512GB UFS 4.1',
            'battery' => '6,510mAh; 90W wired, 40W wireless',
            'camera' => '50MP main (OIS) + 50MP ultra wide + 200MP periscope 3.7x (OIS) | 50MP front',
            'os' => 'Android 16, OriginOS 6',
        ],
        'description' => <<<'HTML'
<p>vivo's X-series has quietly become one of the best camera phones you can buy in Nepal, and the X300 Pro continues that with a 200MP periscope lens at 3.7x zoom backed by optical stabilisation. It costs <strong>Rs 1,99,999</strong> for 16GB/512GB after a price hike on 1 September 2026 (it was Rs 1,79,999 before).</p>
<p>The Dimensity 9500 is a true flagship chip, the 6,510mAh battery is big, and charging covers 90W wired and 40W wireless. It is a direct rival to the <a href="/products/xiaomi-17-ultra">Xiaomi 17 Ultra</a> at the same price: pick the vivo for the longer zoom, the Xiaomi for the 1-inch main sensor.</p>
HTML,
        'faqs' => [
            ['What is the vivo X300 Pro price in Nepal?', 'Rs 1,99,999 for 16GB RAM and 512GB storage, following a price increase on 1 September 2026.'],
            ['vivo X300 Pro or Xiaomi 17 Ultra?', 'Both cost Rs 1,99,999. The vivo has the longer 3.7x periscope zoom; the Xiaomi has the larger 1-inch main camera sensor.'],
        ],
        'sources' => [$gbVivo, ['accessed' => '2026-09-01']],
        'seo' => [
            'title' => 'vivo X300 Pro Price in Nepal (After Hike): Camera Specs',
            'description' => 'vivo X300 Pro now costs Rs 1,99,999 in Nepal. 200MP 3.7x periscope, Dimensity 9500, 6,510mAh battery — specs and how it compares.',
            'keywords' => 'vivo x300 pro price in nepal, vivo x300 pro nepal price hike',
        ],
        'image_alt' => 'vivo X300 Pro camera',
    ],

    [
        'name' => 'vivo V80', 'slug' => 'vivo-v80', 'brand' => 'vivo', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 108999],
            ['ram' => '8GB', 'storage' => '512GB', 'price' => 133999],
        ],
        'history' => [['2026-11-01', 108999]],
        'specs' => [
            'display' => '6.59-inch 1.5K AMOLED (2750×1260), up to 144Hz, 5,000 nits local peak',
            'processor' => 'Snapdragon 7 Gen 4 (4nm)',
            'ram' => '8GB',
            'storage' => '256GB / 512GB',
            'battery' => '7,200mAh; 90W wired',
            'camera' => '50MP Sony LYTIA 700V main (OIS) + 50MP 3x telephoto + 8MP ultra wide | 50MP front',
            'os' => 'Android 17, OriginOS 7 (6 years of security updates)',
            'weight' => '203g',
            'dimensions' => '7.69mm thick',
            'extra' => ['Water resistance' => 'IP68, IP69', 'Colours' => 'Sunrise Anthem, Horizon Blue, Stellar Black'],
        ],
        'description' => <<<'HTML'
<p>vivo's V-series is built around portraits, and the V80 makes that case with a 50MP 3x telephoto and a 50MP selfie camera — a pairing aimed squarely at people who photograph faces more than landscapes. It costs <strong>Rs 1,08,999</strong> in Nepal for 8GB/256GB.</p>
<p>Despite a 7,200mAh battery, the body is under 7.7mm thick and weighs 203g, and vivo promises six years of security updates. The Snapdragon 7 Gen 4 is the weak spot for the price; at Rs 1 lakh, the <a href="/products/oneplus-15r">OnePlus 15R</a> is much faster if performance matters more than portraits.</p>
HTML,
        'faqs' => [
            ['What is the vivo V80 price in Nepal?', 'Rs 1,08,999 for 8GB/256GB and Rs 1,33,999 for 8GB/512GB.'],
        ],
        'sources' => [$gbVivo],
        'seo' => [
            'title' => 'vivo V80 Price in Nepal: Portrait Camera Phone Specs',
            'description' => 'vivo V80 costs Rs 1,08,999 in Nepal. 50MP 3x telephoto, 50MP selfie camera, 7,200mAh battery and IP69 — full specifications.',
            'keywords' => 'vivo v80 price in nepal, vivo v80 nepal',
        ],
        'image_alt' => 'vivo V80',
    ],

    [
        'name' => 'Realme GT 8 Pro', 'slug' => 'realme-gt-8-pro', 'brand' => 'Realme', 'category' => 'mobile',
        'variants' => [
            ['edition' => 'Standard (Wind Moves White)', 'ram' => '16GB', 'storage' => '512GB', 'price' => 149999],
            ['edition' => 'Aston Martin Edition', 'ram' => '16GB', 'storage' => '512GB', 'price' => 159999],
        ],
        'history' => [['2026-10-28', 149999]],
        'specs' => [
            'display' => '6.79-inch QHD+ AMOLED (1440×3136), 144Hz, 7,000 nits peak',
            'processor' => 'Snapdragon 8 Elite Gen 5 (3nm)',
            'ram' => '16GB LPDDR5X',
            'storage' => '512GB UFS 4.1',
            'battery' => '7,000mAh; 120W wired, 50W wireless',
            'camera' => '50MP main (OIS) + 200MP 3x telephoto + 50MP ultra wide | 32MP front',
            'os' => 'Android 16, Realme UI 7',
            'dimensions' => '8.2mm thick',
            'extra' => ['Water resistance' => 'IP68, IP69'],
        ],
        'description' => <<<'HTML'
<p>The Realme GT 8 Pro is the cheapest way into a Snapdragon 8 Elite Gen 5 phone in Nepal at <strong>Rs 1,49,999</strong> — Rs 40,000 less than the <a href="/products/oneplus-15">OnePlus 15</a> with the same chip. It doesn't feel like a budget flagship, either: a QHD+ 144Hz screen, a 7,000mAh battery with 120W wired and 50W wireless charging, and a 200MP 3x telephoto.</p>
<p>The Aston Martin Edition costs Rs 10,000 more for a green finish and branding, with the same internals. Realme UI is busier than OxygenOS, which is the main reason to consider spending more on the OnePlus.</p>
HTML,
        'faqs' => [
            ['What is the Realme GT 8 Pro price in Nepal?', 'Rs 1,49,999 for 16GB/512GB. The Aston Martin Edition costs Rs 1,59,999.'],
            ['Does the Realme GT 8 Pro support wireless charging?', 'Yes, 50W wireless as well as 120W wired charging.'],
        ],
        'sources' => [$gbRealme],
        'seo' => [
            'title' => 'Realme GT 8 Pro Price in Nepal: Cheapest Snapdragon 8 Elite Gen 5',
            'description' => 'Realme GT 8 Pro costs Rs 1,49,999 in Nepal. QHD+ 144Hz, 200MP 3x telephoto, 7,000mAh with 120W charging. Aston Martin Edition price too.',
            'keywords' => 'realme gt 8 pro price in nepal, realme gt8 pro nepal, aston martin edition',
        ],
        'image_alt' => 'Realme GT 8 Pro',
    ],

    [
        'name' => 'Realme P4 Power', 'slug' => 'realme-p4-power', 'brand' => 'Realme', 'category' => 'mobile',
        'variants' => [['ram' => '12GB', 'storage' => '256GB', 'price' => 76999]],
        'history' => [['2026-10-28', 76999]],
        'specs' => [
            'display' => '6.8-inch quad-curved 1.5K AMOLED, 144Hz, 6,500 nits peak',
            'processor' => 'MediaTek Dimensity 7400 Ultra (4nm)',
            'ram' => '12GB',
            'storage' => '256GB UFS 3.1',
            'battery' => '10,001mAh silicon-carbon; 80W wired, 27W reverse',
            'camera' => '50MP Sony IMX882 (OIS) + 8MP ultra wide | 16MP front',
            'os' => 'Android 16, Realme UI 7',
            'extra' => ['Water resistance' => 'IP66, IP68, IP69'],
        ],
        'description' => <<<'HTML'
<p>Realme's P4 Power puts a 10,001mAh battery in a phone with a curved 144Hz screen, which is not a combination you used to see. At <strong>Rs 76,999</strong> for 12GB/256GB it competes directly with the <a href="/products/redmi-note-17-pro-max-5g">Redmi Note 17 Pro Max</a>, which has an equally big battery and faster 100W charging for Rs 7,000 less.</p>
<p>The P4 Power's advantages are the curved display and 12GB of RAM as standard. Its camera is a straightforward 50MP main plus an ultra wide.</p>
HTML,
        'faqs' => [
            ['What is the Realme P4 Power price in Nepal?', 'Rs 76,999 for 12GB RAM and 256GB storage.'],
        ],
        'sources' => [$gbRealme],
        'seo' => [
            'title' => 'Realme P4 Power Price in Nepal: 10,001mAh Battery Phone',
            'description' => 'Realme P4 Power costs Rs 76,999 in Nepal. 10,001mAh battery, 144Hz curved AMOLED and Dimensity 7400 Ultra — specs and rivals.',
            'keywords' => 'realme p4 power price in nepal, realme p4 power nepal',
        ],
        'image_alt' => 'Realme P4 Power',
    ],

    [
        'name' => 'Honor Magic 8 Pro', 'slug' => 'honor-magic-8-pro', 'brand' => 'Honor', 'category' => 'mobile',
        'variants' => [['ram' => '12GB', 'storage' => '512GB', 'price' => 199999]],
        'history' => [['2026-10-26', 199999]],
        'specs' => [
            'display' => '6.71-inch LTPO OLED, 120Hz, 6,000 nits peak',
            'processor' => 'Snapdragon 8 Elite Gen 5 (3nm)',
            'ram' => '12GB / 16GB',
            'storage' => '256GB / 512GB / 1TB UFS 4.1',
            'battery' => '7,100mAh; 100W wired, 80W wireless',
            'camera' => '50MP main + 200MP telephoto + 50MP ultra wide | 50MP front',
            'os' => 'Android 16, MagicOS 10',
            'weight' => '219g',
            'dimensions' => '161.2 × 75.0 × 8.3 mm',
            'extra' => ['Water resistance' => 'IP68, IP69K', 'Unlock' => 'Ultrasonic fingerprint + 3D face unlock', 'Colours' => 'Sunrise Gold, Sky Cyan, Black, White'],
        ],
        'description' => <<<'HTML'
<p>Honor's Magic 8 Pro is a complete flagship: Snapdragon 8 Elite Gen 5, a 200MP telephoto, a 7,100mAh battery and the fastest wireless charging on this list at 80W. It is also one of the few Android phones in Nepal with secure 3D face unlock, not just a camera-based one. It costs <strong>Rs 1,99,999</strong> for 12GB/512GB; some listings show offer prices around Rs 1,79,999, so ask the shop.</p>
<p>MagicOS 10 is polished, though Honor's update promise is shorter than Samsung's. For the same money the <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a> offers longer support and an S Pen; the Honor offers a bigger battery and much faster charging.</p>
HTML,
        'faqs' => [
            ['What is the Honor Magic 8 Pro price in Nepal?', 'Its listed price is Rs 1,99,999 for 12GB/512GB, with occasional offer prices lower.'],
            ['Does the Honor Magic 8 Pro have face unlock?', 'Yes, a 3D face unlock system as well as an under-display fingerprint sensor.'],
        ],
        'sources' => [$gbHonor],
        'seo' => [
            'title' => 'Honor Magic 8 Pro Price in Nepal: Specs, Camera & Charging',
            'description' => 'Honor Magic 8 Pro price in Nepal is Rs 1,99,999. 200MP telephoto, 7,100mAh battery, 100W wired and 80W wireless charging.',
            'keywords' => 'honor magic 8 pro price in nepal, honor magic8 pro nepal',
        ],
        'image_alt' => 'Honor Magic 8 Pro',
    ],

    [
        'name' => 'Honor X9d', 'slug' => 'honor-x9d', 'brand' => 'Honor', 'category' => 'mobile',
        'variants' => [
            ['ram' => '8GB', 'storage' => '256GB', 'price' => 54999],
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 59999],
        ],
        'history' => [['2026-10-26', 54999]],
        'specs' => [
            'display' => '6.79-inch AMOLED (2640×1200), 120Hz, 6,000 nits peak',
            'processor' => 'Snapdragon 6 Gen 4 (4nm)',
            'ram' => '8GB / 12GB',
            'storage' => '256GB / 512GB',
            'battery' => '8,300mAh silicon-carbon; 66W wired, reverse charging',
            'camera' => '108MP main (OIS, f/1.75) + 5MP ultra wide | 16MP front',
            'os' => 'Android 15, MagicOS 9',
            'weight' => '193g',
            'dimensions' => '161.9 × 76.1 × 7.76 mm',
            'extra' => ['Durability' => 'IP69K / IP68 / IP66, rated for 2.5m drops'],
        ],
        'description' => <<<'HTML'
<p>The Honor X9d is built to survive the kind of life phones actually lead in Nepal: Honor rates it for 2.5m drops and IP69K water jets, and it packs an 8,300mAh battery into a body that weighs only 193g. It costs <strong>Rs 54,999</strong> for 8GB/256GB.</p>
<p>The 108MP main camera has optical stabilisation, and the AMOLED screen gets very bright outdoors. It ships with the older Android 15, which is worth knowing if you like the newest features.</p>
HTML,
        'faqs' => [
            ['What is the Honor X9d price in Nepal?', 'Rs 54,999 for 8GB/256GB and Rs 59,999 for 12GB/256GB.'],
        ],
        'sources' => [$gbHonor],
        'seo' => [
            'title' => 'Honor X9d Price in Nepal: Drop-Resistant 8,300mAh Phone',
            'description' => 'Honor X9d costs Rs 54,999 in Nepal. 8,300mAh battery, 2.5m drop resistance, IP69K and a 108MP OIS camera in a 193g body.',
            'keywords' => 'honor x9d price in nepal, honor x9d nepal',
        ],
        'image_alt' => 'Honor X9d',
    ],
];
