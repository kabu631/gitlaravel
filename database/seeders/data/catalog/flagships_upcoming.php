<?php

/*
 * Flagships launched abroad but not yet in Nepal ("announced"), and flagships not launched yet
 * ("upcoming"), researched 2026-11-23. Every Nepal price here is our estimate (price_is_estimate),
 * and the description says what it is based on. Replace it with the official price when the brand's
 * Nepal distributor announces one, and switch status to "available".
 *
 * Checked and deliberately left out:
 *  - Realme GT 9 / GT 9 Pro: reported cancelled for 2026 (Realme paused new launches in China).
 *  - Xiaomi 18 Ultra: leaks say it was dropped; not announced.
 *  - Nothing Phone (4): Nothing has said it isn't coming in 2026; no 2027 date.
 *  - Google Pixel: not sold officially in Nepal.
 */

$src = fn (string $label, string $url, string $date = '2026-11-23') => ['label' => $label, 'url' => $url, 'accessed' => $date];

return [

    /* ─────────────── Launched globally, Nepal price pending ─────────────── */

    [
        'name' => 'Xiaomi 18 Pro Max', 'slug' => 'xiaomi-18-pro-max', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched globally 17 Nov 2026; Nepal date not announced',
        'release_date' => '2026-11-17', 'trending' => true,
        'variants' => [
            ['ram' => '12GB', 'storage' => '256GB', 'price' => 219999],
            ['ram' => '12GB', 'storage' => '512GB', 'price' => 239999],
            ['ram' => '16GB', 'storage' => '1TB', 'price' => 269999],
        ],
        'history' => [],
        'specs' => [
            'display' => 'About 6.9-inch AMOLED main screen + 2.9-inch 120Hz AMOLED rear display',
            'processor' => 'Snapdragon 8 Elite Extreme Gen 6 (2nm, up to 5.0GHz)',
            'ram' => '12GB or 16GB LPDDR5X',
            'storage' => '256GB, 512GB or 1TB',
            'battery' => '7,050mAh (global model); 8,500mAh in China',
            'camera' => '200MP main + 200MP periscope telephoto + 50MP ultra wide, Leica',
            'os' => 'Xiaomi HyperOS 4, Android 17',
            'extra' => ['Europe price' => '€1,599 (12GB/256GB)', 'China launch' => 'September 2026', 'Colours' => 'White, Cosmic Blue, Velvet Red'],
        ],
        'description' => <<<'HTML'
<p>The Xiaomi 18 Pro Max is Xiaomi's top phone this year. It launched in China in September 2026 and globally at an event in Paris on 17 November, starting at <strong>€1,599</strong> in Europe for 12GB/256GB. Xiaomi Nepal hasn't announced it yet.</p>
<p>It is the first phone with a chip that runs at 5GHz, the Snapdragon 8 Elite Extreme Gen 6. It has two 200MP Leica cameras (main and periscope telephoto) plus a 50MP ultra wide, and a 2.9-inch screen on the back for selfies with the main camera and for notifications.</p>
<h3>Smaller battery outside China</h3>
<p>The global model has a <strong>7,050mAh</strong> battery, against 8,500mAh in China. That's still bigger than most flagships, but if you're thinking of importing one, know which version you're getting.</p>
<h3>Expected price in Nepal</h3>
<p>Our estimate is about <strong>Rs 2,19,999</strong> for 12GB/256GB. The <a href="/products/xiaomi-17-ultra">Xiaomi 17 Ultra</a> sells here for Rs 1,99,999, and the 18 Pro Max costs more in Europe. Choose a storage option above to see the estimate for each one. We'll update this page when Xiaomi Nepal announces official prices.</p>
HTML,
        'faqs' => [
            ['Is the Xiaomi 18 Pro Max available in Nepal?', 'Not officially yet. It launched globally on 17 November 2026 and Xiaomi Nepal has not announced a date.'],
            ['What will the Xiaomi 18 Pro Max cost in Nepal?', 'Our estimate is about Rs 2,19,999 for 12GB/256GB, based on the €1,599 European price and the Xiaomi 17 Ultra\'s Nepal price. It is not an official figure.'],
            ['How big is the Xiaomi 18 Pro Max battery?', 'The global model has 7,050mAh. The Chinese model has 8,500mAh.'],
        ],
        'sources' => [
            $src('Xiaomi UK – Xiaomi 18 Pro Max specs', 'https://www.mi.com/uk/product/xiaomi-18-pro-max/specs/'),
            $src('Android Headlines – Xiaomi 18 Pro and Pro Max global prices', 'https://www.androidheadlines.com/2026/11/xiaomi-18-pro-and-18-pro-max-are-finally-going-global-and-the-prices-are-steep.html'),
            $src('Gizchina – European 18 Pro costs more, packs less battery', 'https://www.gizchina.com/xiaomi-phones/xiaomis-european-18-pro-costs-more-and-packs-less-battery'),
            $src('91mobiles – Xiaomi 18 Pro series launched in China', 'https://www.91mobiles.com/hub/xiaomi-18-pro-series-launched-price-specifications-global-debut-confirmed/'),
        ],
        'launch_highlight' => 'Two 200MP Leica cameras and a rear display',
        'seo' => [
            'title' => 'Xiaomi 18 Pro Max Price in Nepal (Expected), Specs & Launch',
            'description' => 'Xiaomi 18 Pro Max launched globally at €1,599. Expected Nepal price about Rs 2,19,999, dual 200MP Leica cameras, 7,050mAh global battery.',
            'keywords' => 'xiaomi 18 pro max price in nepal, xiaomi 18 pro max nepal, xiaomi 18 pro max specs',
        ],
        'image_alt' => 'Xiaomi 18 Pro Max',
    ],

    [
        'name' => 'Xiaomi 18 Pro', 'slug' => 'xiaomi-18-pro', 'brand' => 'Xiaomi', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched globally 17 Nov 2026; Nepal date not announced',
        'release_date' => '2026-11-17',
        'price' => 189999,
        'history' => [],
        'specs' => [
            'display' => 'AMOLED main screen + 2.7-inch rear display',
            'processor' => 'Snapdragon 8 Elite Gen 6 (2nm)',
            'ram' => '12GB',
            'battery' => '6,000mAh (global model); 7,000mAh in China',
            'camera' => '200MP main + 200MP telephoto + 50MP ultra wide, Leica',
            'os' => 'Xiaomi HyperOS 4, Android 17',
            'extra' => ['Europe price' => '€1,399', 'Colours' => 'White, Cosmic Blue, Velvet Red'],
        ],
        'description' => <<<'HTML'
<p>The Xiaomi 18 Pro is the smaller of Xiaomi's two new flagships. It launched globally in Paris on 17 November 2026 at <strong>€1,399</strong>, alongside the <a href="/products/xiaomi-18-pro-max">18 Pro Max</a>. It isn't on sale in Nepal yet.</p>
<p>It uses the regular Snapdragon 8 Elite Gen 6 (the Pro Max gets the faster "Extreme" version), keeps the two 200MP Leica cameras, and has a 2.7-inch rear display. The global battery is 6,000mAh, down from 7,000mAh in China.</p>
<p>Our Nepal estimate is about <strong>Rs 1,89,999</strong>, based on the European price and current Xiaomi flagship prices here. It isn't an official figure.</p>
HTML,
        'faqs' => [
            ['What is the difference between the Xiaomi 18 Pro and 18 Pro Max?', 'The Pro Max has a bigger screen and rear display, the faster Snapdragon 8 Elite Extreme Gen 6 chip and a bigger battery (7,050mAh vs 6,000mAh globally). Both have two 200MP Leica cameras.'],
            ['What will the Xiaomi 18 Pro cost in Nepal?', 'Our estimate is about Rs 1,89,999. Xiaomi Nepal has not announced a price.'],
        ],
        'sources' => [
            $src('Gizchina – Xiaomi 18 Pro launches in Europe at €1,399', 'https://www.gizchina.com/xiaomi-phones/xiaomis-european-18-pro-costs-more-and-packs-less-battery'),
            $src('PhoneArena – Xiaomi 18 Pro and 18 Pro Max official', 'https://www.phonearena.com/news/xiaomi-18-pro-18-pro-max-official-specs-features-prices_id183565'),
        ],
        'launch_highlight' => 'Dual 200MP Leica cameras in a smaller body',
        'seo' => [
            'title' => 'Xiaomi 18 Pro Price in Nepal (Expected) & Specs',
            'description' => 'Xiaomi 18 Pro launched globally at €1,399. Expected Nepal price about Rs 1,89,999, Snapdragon 8 Elite Gen 6, dual 200MP Leica cameras.',
            'keywords' => 'xiaomi 18 pro price in nepal, xiaomi 18 pro specs, xiaomi 18 pro nepal launch',
        ],
        'image_alt' => 'Xiaomi 18 Pro',
    ],

    [
        'name' => 'vivo X500 Pro Max', 'slug' => 'vivo-x500-pro-max', 'brand' => 'vivo', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched globally 27 Oct 2026; South Asia launch expected around end of November',
        'release_date' => '2026-10-27',
        'price' => 239999,
        'history' => [],
        'specs' => [
            'display' => '6.85-inch AMOLED',
            'processor' => 'MediaTek Dimensity 9600 Pro (2nm)',
            'ram' => '12GB',
            'battery' => 'Up to 7,800mAh (largest in the X500 series); 90W wired, 40W wireless',
            'camera' => 'Triple rear camera led by a 200MP Zeiss periscope telephoto; optional Zeiss Telephoto Extender Gen 2 Ultra (400mm equivalent)',
            'extra' => ['Europe price' => 'From about €1,599 (reported)', 'China launch' => '21 September 2026'],
        ],
        'description' => <<<'HTML'
<p>vivo's camera flagship for 2026 launched in China in September and globally on 27 October. It isn't sold in Nepal yet, but reports say vivo plans to bring the X500 series to India by the end of November, and Nepal usually follows soon after.</p>
<p>The headline is a 200MP Zeiss periscope telephoto, plus an optional clip-on telephoto extender for very long zoom. It has a 6.85-inch screen, MediaTek's 2nm Dimensity 9600 Pro and a battery of up to 7,800mAh with 90W charging.</p>
<p>Our Nepal estimate is about <strong>Rs 2,39,999</strong>. The <a href="/products/vivo-x300-pro">vivo X300 Pro</a> sells for Rs 1,99,999, and the Pro Max is the bigger, pricier model. Treat the European price reports as unconfirmed: we found €1,299 to €1,599 depending on the source and the model.</p>
HTML,
        'faqs' => [
            ['When will the vivo X500 Pro Max launch in Nepal?', 'vivo Nepal has not announced a date. Reports say the series reaches India by the end of November 2026.'],
            ['What will the vivo X500 Pro Max cost in Nepal?', 'Our estimate is about Rs 2,39,999. It is not an official figure.'],
        ],
        'sources' => [
            $src('91mobiles – vivo X500 series goes global', 'https://www.91mobiles.com/hub/vivo-x500-pro-pro-max-launched-globally-price-specifications/'),
            $src('Fonearena – vivo X500, X500 Pro and X500 Pro Max go global', 'https://www.fonearena.com/blog/495857/vivo-x500-x500-pro-x500-pro-max-price-specifications-global.html'),
            $src('Notebookcheck – vivo X500 Pro series launches', 'https://www.notebookcheck.net/Vivo-X500-Pro-series-launches-with-new-flagship-SoC-tweaked-Zeiss-camera-setup-and-up-to-8-000mAh-battery.1404721.0.html'),
        ],
        'launch_highlight' => '200MP Zeiss periscope and up to 7,800mAh battery',
        'seo' => [
            'title' => 'vivo X500 Pro Max Price in Nepal (Expected), Specs & Launch',
            'description' => 'vivo X500 Pro Max launched globally. Expected Nepal price about Rs 2,39,999, 200MP Zeiss periscope, Dimensity 9600 Pro, up to 7,800mAh.',
            'keywords' => 'vivo x500 pro max price in nepal, vivo x500 pro max nepal, vivo x500 series',
        ],
        'image_alt' => 'vivo X500 Pro Max',
    ],

    [
        'name' => 'vivo X500 Pro', 'slug' => 'vivo-x500-pro', 'brand' => 'vivo', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched globally 27 Oct 2026; South Asia launch expected around end of November',
        'release_date' => '2026-10-27',
        'price' => 209999,
        'history' => [],
        'specs' => [
            'display' => '6.36-inch AMOLED, 1216 × 2660, 144Hz',
            'processor' => 'MediaTek Dimensity 9600 Pro (2nm)',
            'ram' => '12GB',
            'battery' => '6,510mAh; 90W wired, 40W wireless',
            'camera' => '50MP main + 50MP ultra wide + 64MP 85mm Zeiss APO telephoto (Sony LYTIA 610)',
            'os' => 'OriginOS 7, Android 17 (Chinese model)',
            'extra' => ['Europe price' => 'From about €1,299 (reported)', 'China price' => 'From CNY 6,499'],
        ],
        'description' => <<<'HTML'
<p>The vivo X500 Pro is the compact model of vivo's new camera flagships, with a 6.36-inch screen and a 6,510mAh battery, which is a lot for a phone this size. It launched in China on 21 September 2026 and globally on 27 October. It isn't sold in Nepal yet.</p>
<p>The cameras are a 50MP main, a 50MP ultra wide and a 64MP Zeiss APO telephoto at 85mm, which suits portraits. Charging is 90W wired and 40W wireless.</p>
<p>Our Nepal estimate is about <strong>Rs 2,09,999</strong>, slightly above the <a href="/products/vivo-x300-pro">vivo X300 Pro</a>'s Rs 1,99,999. Reported European prices start at about €1,299. If you want the bigger 200MP telephoto, look at the <a href="/products/vivo-x500-pro-max">X500 Pro Max</a>.</p>
HTML,
        'faqs' => [
            ['Is the vivo X500 Pro a compact phone?', 'Yes. It has a 6.36-inch screen, which is small for a 2026 flagship, and still has a 6,510mAh battery.'],
            ['What will the vivo X500 Pro cost in Nepal?', 'Our estimate is about Rs 2,09,999. vivo Nepal has not announced a price.'],
        ],
        'sources' => [
            $src('9to5Google – vivo X500 series launches globally', 'https://9to5google.com/2026/10/27/vivos-x500-series-launches-globally/'),
            $src('Gizchina – vivo X500 series goes global from €1,299', 'https://www.gizchina.com/vivo-phones/vivo-x500-series-goes-global-with-european-prices-starting-at-eur1299'),
            $src('Gizmochina – vivo X500 series roundup', 'https://www.gizmochina.com/2026/09/09/vivo-x500-series-roundup-specs-leaks-and-launch-details/'),
        ],
        'launch_highlight' => 'Compact 6.36-inch flagship with a 6,510mAh battery',
        'seo' => [
            'title' => 'vivo X500 Pro Price in Nepal (Expected), Specs & Launch',
            'description' => 'vivo X500 Pro: 6.36-inch compact flagship, Dimensity 9600 Pro, 64MP Zeiss APO telephoto, 6,510mAh. Expected Nepal price about Rs 2,09,999.',
            'keywords' => 'vivo x500 pro price in nepal, vivo x500 pro specs, vivo x500 pro nepal',
        ],
        'image_alt' => 'vivo X500 Pro',
    ],

    [
        'name' => 'OPPO Find X10 Pro Max', 'slug' => 'oppo-find-x10-pro-max', 'brand' => 'OPPO', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched globally 21 Oct 2026; Nepal availability not announced',
        'release_date' => '2026-10-21',
        'price' => 239999,
        'history' => [],
        'specs' => [
            'display' => '6.78-inch LTPO OLED, 1.5K, 144Hz',
            'processor' => 'MediaTek Dimensity 9600 Pro (2nm)',
            'storage' => '256GB, 512GB or 1TB',
            'battery' => '8,000mAh; 80W wired',
            'camera' => 'Three 200MP rear cameras; main uses a 1/1.28-inch Samsung ISOCELL HPC sensor, Hasselblad',
            'extra' => ['Europe price' => 'About €1,799 for 12GB/512GB (reported)', 'US import price' => '$1,599 (12GB/256GB, Giztop)', 'DXOMARK' => 'Listed, not yet tested (Nov 2026)'],
        ],
        'description' => <<<'HTML'
<p>The OPPO Find X10 Pro Max is the first phone with three 200MP rear cameras. OPPO announced it in China in September 2026 and launched it globally on 21 October. There's no "Find X10 Pro": this year OPPO sells the Find X10E, Find X10 and Find X10 Pro Max.</p>
<p>It has MediaTek's 2nm Dimensity 9600 Pro, a 6.78-inch 144Hz screen and an 8,000mAh battery with 80W charging.</p>
<h3>Will it come to Nepal?</h3>
<p>OPPO Nepal hasn't announced it, and OPPO's recent Find flagships have mostly reached Nepal through importers rather than official channels. If it does arrive officially, we expect about <strong>Rs 2,39,999</strong>, based on the reported €1,799 European price and $1,599 import price. OPPO's official line-up in Nepal is the Reno series. The <a href="/products/oppo-reno-16-5g">OPPO Reno 16 5G</a> costs Rs 1,09,999.</p>
HTML,
        'faqs' => [
            ['Does the OPPO Find X10 Pro Max have three 200MP cameras?', 'Yes. The main, telephoto and ultra wide cameras are all 200MP.'],
            ['Is there an OPPO Find X10 Pro?', 'No. The 2026 line-up is the Find X10E, Find X10 and Find X10 Pro Max.'],
            ['What will the Find X10 Pro Max cost in Nepal?', 'If it launches officially, we expect about Rs 2,39,999. OPPO Nepal has not announced it.'],
        ],
        'sources' => [
            $src('TechRepublic – OPPO Find X10 Pro Max cheat sheet', 'https://www.techrepublic.com/article/news-oppo-find-x10-pro-max-cheat-sheet/'),
            $src('My Mobile India – Find X10 Pro Max launched globally', 'https://www.mymobileindia.com/oppo-find-x10-pro-max-launched-globally-with-worlds-first-triple-200mp-camera-system-price-specs-and-features/'),
            $src('Gizmochina – Find X10 Pro Max global version pre-sale', 'https://www.gizmochina.com/2026/10/21/oppo-find-x10-pro-max-global-version-giztop/'),
            $src('DXOMARK – OPPO Find X10 Pro Max (testing pending)', 'https://www.dxomark.com/smartphones/Oppo/Find-X10-Pro-Max'),
        ],
        'launch_highlight' => 'World\'s first triple 200MP camera',
        'seo' => [
            'title' => 'OPPO Find X10 Pro Max Price in Nepal (Expected) & Specs',
            'description' => 'OPPO Find X10 Pro Max: three 200MP cameras, Dimensity 9600 Pro, 8,000mAh. Launched globally; expected Nepal price about Rs 2,39,999 if it arrives.',
            'keywords' => 'oppo find x10 pro max price in nepal, find x10 pro max nepal, oppo find x10',
        ],
        'image_alt' => 'OPPO Find X10 Pro Max',
    ],

    [
        'name' => 'Honor Magic 9 Pro Max', 'slug' => 'honor-magic-9-pro-max', 'brand' => 'Honor', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched in China Sep 2026; Europe expected January 2027',
        'release_date' => '2026-09-28',
        'price' => 219999,
        'history' => [],
        'specs' => [
            'display' => '6.8-inch LTPO OLED',
            'processor' => 'Snapdragon 8 Elite Extreme Gen 6 (2nm)',
            'ram' => 'Up to 16GB',
            'storage' => 'Up to 1TB',
            'battery' => '8,800mAh in China (global version reported at about 8,000mAh); 100W wired, 80W wireless',
            'camera' => '200MP main + 200MP telephoto + 50MP ultra wide | 55MP front',
            'extra' => ['China price' => 'From CNY 6,499', 'Europe pre-order' => '€1,499.90 (reported)'],
        ],
        'description' => <<<'HTML'
<p>Honor launched the Magic 9 series in Beijing on 28 September 2026. This year there's no plain "Magic 9 Pro": the models are the Magic 9, the Magic 9 Pro Max and a Super Edition. The Pro Max is the one most likely to come to Nepal, as the successor to the <a href="/products/honor-magic-8-pro">Honor Magic 8 Pro</a> (Rs 1,99,999).</p>
<p>It has a 6.8-inch LTPO OLED, the Snapdragon 8 Elite Extreme Gen 6, two 200MP cameras (main and telephoto), a 50MP ultra wide and a 55MP selfie camera. The Chinese model has an 8,800mAh battery. Hong Kong listings for the global version show about 8,000mAh.</p>
<p>Reports point to a European launch in January 2027 at around €1,499. Our Nepal estimate is about <strong>Rs 2,19,999</strong>. It isn't official yet.</p>
HTML,
        'faqs' => [
            ['Is there an Honor Magic 9 Pro?', 'Not as a separate model. Honor launched the Magic 9, Magic 9 Pro Max and Magic 9 Super Edition.'],
            ['When will the Honor Magic 9 Pro Max launch in Nepal?', 'Honor has not announced a date. Reports point to Europe in January 2027, and Nepal launches usually follow the international launch.'],
            ['What will it cost in Nepal?', 'Our estimate is about Rs 2,19,999, based on the Magic 8 Pro\'s Nepal price and the reported €1,499 European price.'],
        ],
        'sources' => [
            $src('PhoneArena – Honor Magic 9 Pro Max made official', 'https://www.phonearena.com/news/honor-magic-9-pro-max-is-made-official_id183646'),
            $src('PhoneDroid – Honor Magic 9 Pro Max price and release date', 'https://www.phonedroid.net/blog/honor-magic-9-pro-max-specs/'),
            $src('TechNetBooks – Magic 9 Pro Max global edition', 'https://www.technetbooks.com/2026/11/honor-magic-9-pro-max-global-edition.html'),
        ],
        'launch_highlight' => 'Two 200MP cameras and a huge battery',
        'seo' => [
            'title' => 'Honor Magic 9 Pro Max Price in Nepal (Expected), Specs & Launch',
            'description' => 'Honor Magic 9 Pro Max: Snapdragon 8 Elite Extreme Gen 6, dual 200MP cameras, up to 8,800mAh. Expected Nepal price about Rs 2,19,999.',
            'keywords' => 'honor magic 9 pro max price in nepal, honor magic 9 pro nepal, honor magic 9 series',
        ],
        'image_alt' => 'Honor Magic 9 Pro Max',
    ],

    [
        'name' => 'OnePlus 16', 'slug' => 'oneplus-16', 'brand' => 'OnePlus', 'category' => 'mobile',
        'status' => 'announced', 'estimate' => true, 'expected_launch' => 'Launched in China 12 Oct 2026; global/India launch expected late 2026 to January 2027',
        'release_date' => '2026-10-12', 'trending' => true,
        'price' => 199999,
        'history' => [],
        'specs' => [
            'display' => '185Hz AMOLED',
            'processor' => 'Snapdragon 8 Elite Extreme Gen 6 (2nm)',
            'ram' => '12GB and up',
            'storage' => '256GB and up',
            'battery' => '9,000mAh (Chinese model); 100W wired, 50W wireless',
            'camera' => '200MP main + 50MP 3x telephoto + ultra wide',
            'os' => 'ColorOS 17 (China) / OxygenOS 17 expected globally, Android 17',
            'extra' => ['China price' => 'CNY 5,999 (12GB/256GB)'],
        ],
        'description' => <<<'HTML'
<p>OnePlus launched the OnePlus 16 in China on 12 October 2026. It has the Snapdragon 8 Elite Extreme Gen 6, a 185Hz screen (the highest refresh rate on any OnePlus), a 200MP main camera with a 50MP 3x telephoto, and a 9,000mAh battery with 100W charging.</p>
<p>OnePlus hasn't confirmed the global or India launch. Reports range from the end of 2026 to January 2027. Global models sometimes get a smaller battery than the Chinese version, so check the final spec sheet.</p>
<p>Our Nepal estimate is about <strong>Rs 1,99,999</strong>, a little above the <a href="/products/oneplus-15">OnePlus 15</a>'s Rs 1,89,999. In China it starts at CNY 5,999.</p>
HTML,
        'faqs' => [
            ['When will the OnePlus 16 launch in Nepal?', 'OnePlus has not announced a global or Nepal date. It launched in China on 12 October 2026.'],
            ['What will the OnePlus 16 cost in Nepal?', 'Our estimate is about Rs 1,99,999. It is not official.'],
        ],
        'sources' => [
            $src('Android Authority – OnePlus 16 launch date', 'https://www.androidauthority.com/oneplus-16-launch-date-3715892/'),
            $src('tech-ish – OnePlus 16 confirmed specs and price', 'https://tech-ish.com/2026/09/24/oneplus-16-confirmed-with-snapdragon-8-elite-extreme-gen-6-specs-colours-price-and-launch-details/'),
            $src('TelecomTalk – OnePlus 16 India launch', 'https://telecomtalk.info/oneplus16-indialaunch-top3reasons-why-you-should-wait/1013173/'),
        ],
        'launch_highlight' => '185Hz screen and 9,000mAh battery',
        'seo' => [
            'title' => 'OnePlus 16 Price in Nepal (Expected), Specs & Launch Date',
            'description' => 'OnePlus 16 launched in China with Snapdragon 8 Elite Extreme Gen 6, 185Hz screen, 200MP camera and 9,000mAh battery. Expected Nepal price about Rs 1,99,999.',
            'keywords' => 'oneplus 16 price in nepal, oneplus 16 launch date nepal, oneplus 16 specs',
        ],
        'image_alt' => 'OnePlus 16',
    ],

    /* ─────────────────────────────── Upcoming ─────────────────────────────── */

    [
        'name' => 'Samsung Galaxy Z Fold 9', 'slug' => 'samsung-galaxy-z-fold9', 'brand' => 'Samsung', 'category' => 'mobile',
        'status' => 'upcoming', 'estimate' => true, 'expected_launch' => 'July 2027 (Samsung\'s usual Unpacked timing)',
        'price' => 279999,
        'history' => [],
        'specs' => [
            'extra' => ['Line-up (rumoured)' => 'Galaxy Z Fold 9 and Z Fold 9 Ultra', 'Design (rumoured)' => 'Wider, passport-style 4:3 shape kept', 'S Pen' => 'Not expected'],
        ],
        'description' => <<<'HTML'
<p>Samsung hasn't announced the Galaxy Z Fold 9. Samsung has launched its foldables every July, so July 2027 is the likely date. Reports so far say:</p>
<ul>
<li>There will be a <strong>Fold 9 and a Fold 9 Ultra</strong>, like this year's <a href="/products/samsung-galaxy-z-fold8">Z Fold 8</a> and <a href="/products/samsung-galaxy-z-fold-8-ultra">Z Fold 8 Ultra</a>.</li>
<li>The wider, passport-style 4:3 shape stays.</li>
<li>No S Pen support is expected.</li>
</ul>
<p>It's early, so treat all of this as rumour. Our placeholder Nepal estimate is <strong>Rs 2,79,999</strong>, a little above the Z Fold 8's Rs 2,74,999.</p>
HTML,
        'faqs' => [
            ['When will the Galaxy Z Fold 9 launch?', 'Samsung has not confirmed it. Based on past launches, July 2027 is most likely.'],
            ['Should I wait for the Z Fold 9?', 'If you need a phone now, the Z Fold 8 is on sale in Nepal at Rs 2,74,999. The Fold 9 is more than six months away.'],
        ],
        'sources' => [
            $src('PhoneArena – Galaxy Z Fold 9 release date expectations', 'https://www.phonearena.com/galaxy-z-fold-9-release-date-price-features-news'),
            $src('Gulf News – Galaxy Z Fold 9 leaks', 'https://gulfnews.com/technology/samsung-galaxy-z-fold-9-leaks-hidden-camera-s-pen-ultra-model-and-everything-we-know-1.500707251'),
        ],
        'launch_highlight' => 'Expected July 2027 alongside a Fold 9 Ultra',
        'seo' => [
            'title' => 'Galaxy Z Fold 9 Price in Nepal (Expected), Launch Date & Leaks',
            'description' => 'Galaxy Z Fold 9 is expected in July 2027. Rumoured Fold 9 Ultra, passport-style design; placeholder Nepal estimate Rs 2,79,999.',
            'keywords' => 'galaxy z fold 9 price in nepal, z fold 9 launch date, samsung fold 9 nepal',
        ],
        'image_alt' => 'Samsung Galaxy Z Fold 9',
    ],
];
