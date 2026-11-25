<?php

/*
 * Flagship phones from 2018 to 2025, brand by brand, for the reference/second-hand part of the site.
 *
 * Same shape as archive.php (status "discontinued", price 0, launch price in launch_price_note), but
 * written compactly and expanded by $flagship() below, so every row gets the same description, FAQs
 * and SEO wording.
 *
 * Launch prices are the manufacturer's launch price in the market named (US where the phone was sold
 * there, otherwise Europe, India, UK or China); they are not converted.
 *
 * DXOMARK scores were read from each phone's dxomark.com page on 2026-11-23. Only phones whose page
 * shows a score carry one. Scores marked "v5" are from DXOMARK's version-5 camera protocol, which
 * DXOMARK says is not directly comparable with the current version 6; unmarked scores carry no
 * protocol label on the page (newer phones).
 */

$months = [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

/** Current models on the site to point second-hand shoppers at, per brand (slug => [name, Nepal price]). */
$equivalents = [
    'Apple'          => ['iphone-17' => ['iPhone 17', 'Rs 1,64,999'], 'iphone-18-pro' => ['iPhone 18 Pro', 'Rs 2,59,900']],
    'Samsung'        => ['samsung-galaxy-s26' => ['Samsung Galaxy S26', 'Rs 1,39,999'], 'samsung-galaxy-s26-ultra' => ['Samsung Galaxy S26 Ultra', 'Rs 2,24,999']],
    'Samsung Fold'   => ['samsung-galaxy-z-flip8' => ['Samsung Galaxy Z Flip 8', 'Rs 1,94,999'], 'samsung-galaxy-z-fold8' => ['Samsung Galaxy Z Fold 8', 'Rs 2,74,999']],
    'Xiaomi'         => ['xiaomi-17t' => ['Xiaomi 17T', 'Rs 92,999'], 'xiaomi-17-ultra' => ['Xiaomi 17 Ultra', 'Rs 1,99,999']],
    'Huawei'         => ['honor-magic-8-pro' => ['Honor Magic 8 Pro', 'Rs 1,99,999'], 'xiaomi-17-ultra' => ['Xiaomi 17 Ultra', 'Rs 1,99,999']],
    'Honor'          => ['honor-600-pro' => ['Honor 600 Pro', 'Rs 1,29,999'], 'honor-magic-8-pro' => ['Honor Magic 8 Pro', 'Rs 1,99,999']],
    'Honor Fold'     => ['honor-magic-v5' => ['Honor Magic V5', 'Rs 2,66,999'], 'samsung-galaxy-z-fold8' => ['Samsung Galaxy Z Fold 8', 'Rs 2,74,999']],
    'vivo'           => ['vivo-v80' => ['vivo V80', 'Rs 1,08,999'], 'vivo-x300-pro' => ['vivo X300 Pro', 'Rs 1,99,999']],
    'OPPO'           => ['oppo-reno-16-5g' => ['OPPO Reno 16 5G', 'Rs 1,09,999'], 'vivo-x300-pro' => ['vivo X300 Pro', 'Rs 1,99,999']],
    'OnePlus'        => ['oneplus-15r' => ['OnePlus 15R', 'Rs 98,499'], 'oneplus-15' => ['OnePlus 15', 'Rs 1,89,999']],
    'Realme'         => ['realme-p4-power' => ['Realme P4 Power', null], 'realme-gt-8-pro' => ['Realme GT 8 Pro', 'Rs 1,49,999']],
    'Nothing'        => ['nothing-phone-4a' => ['Nothing Phone (4a)', null], 'nothing-phone-3' => ['Nothing Phone (3)', 'Rs 1,09,999']],
    'Tecno'          => ['tecno-camon-slim' => ['Tecno Camon Slim', 'Rs 65,999'], 'tecno-pova-curve-2' => ['Tecno Pova Curve 2', 'Rs 52,999']],
    'Motorola'       => ['moto-g96' => ['Moto G96', null], 'motorola-edge-70-fusion' => ['Motorola Edge 70 Fusion', 'Rs 79,999']],
];

$flagship = function (array $p) use ($months, $equivalents): array {
    [$amount, $market, $config] = $p['launch'];
    $marketPhrase = ['US' => 'in the US', 'UK' => 'in the UK', 'Europe' => 'in Europe', 'India' => 'in India', 'China' => 'in China'][$market];
    $note = "{$amount} {$market} launch price ({$config})";
    $at = "{$amount} {$marketPhrase} ({$config})";

    [$y, $m, $d] = array_map('intval', explode('-', $p['date']));
    $when = "{$d} {$months[$m]} {$y}";

    $extra = [];
    if (! empty($p['notes'])) {
        $extra['Build & features'] = $p['notes'];
    }

    $sources = [];
    $links = [];
    foreach ($equivalents[$p['family'] ?? $p['brand']] as $slug => [$label, $price]) {
        $links[] = "the <a href=\"/products/{$slug}\">{$label}</a>" . ($price ? " ({$price})" : '');
    }

    $charging = ! empty($p['charge']) ? ', ' . lcfirst($p['charge']) . ' charging' : '';
    $article = preg_match('/^(8|11|18)\D/', $p['front'] ?? '') ? 'an' : 'a';
    $selfie = ! empty($p['front']) ? ", with {$article} {$p['front']} selfie camera" : '';
    $why = ! empty($p['why']) ? "<p>{$p['why']}</p>\n" : '';
    $notable = ! empty($p['notes']) ? " Notable: {$p['notes']}." : '';

    $description = <<<HTML
<p>The {$p['name']} launched on {$when} at {$at}. It has since been replaced by newer models, so we list its launch price rather than a current Nepal price. We keep its specs here for reference and for anyone weighing up a second-hand unit.</p>
{$why}<p>It has a {$p['display']} screen and the {$p['chip']}. Cameras: {$p['camera']}{$selfie}. Battery: {$p['battery']}{$charging}.{$notable}</p>
<p>Buying used? Check battery health, make sure the phone is registered on NTA's MDMS system (<a href="/guides/how-to-check-phone-is-official-in-nepal">how to check</a>), and compare the asking price with a new mid-range phone. If you would rather buy new, the current equivalents are {$links[0]} and {$links[1]}.</p>
HTML;

    $faqs = [
        ["When was the {$p['name']} released?", "It launched on {$when}."],
        ["What was the {$p['name']} launch price?", "It launched at {$at}. It has been replaced by newer models, so there is no current official Nepal price."],
        ["Which processor does the {$p['name']} use?", "It uses the {$p['chip']}."],
    ];
    return [
        'name' => $p['name'],
        'slug' => $p['slug'],
        'brand' => $p['brand'],
        'category' => 'mobile',
        'status' => 'discontinued',
        'release_date' => $p['date'],
        'listed_at' => $p['date'],
        'price' => 0,
        'launch_price_note' => $note,
        'history' => [],
        'specs' => array_filter([
            'display' => $p['display'],
            'processor' => $p['chip'],
            'ram' => $p['ram'] ?? null,
            'battery' => $p['battery'] . (! empty($p['charge']) ? "; {$p['charge']}" : ''),
            'camera' => $p['camera'] . (! empty($p['front']) ? " | {$p['front']} front" : ''),
            'os' => $p['os'],
            'weight' => $p['weight'] ?? null,
            'extra' => $extra ?: null,
        ]),
        'description' => $description,
        'faqs' => $faqs,
        'sources' => $sources,
        'seo' => [
            'title' => "{$p['name']}: Launch Price, Specs & Release Date",
            'description' => "{$p['name']} specs, release date and launch price ({$at})" . '. Is it still worth buying second-hand in Nepal?',
            'keywords' => strtolower("{$p['name']} price in nepal, {$p['name']} specs, {$p['name']} second hand price nepal"),
        ],
        'image_alt' => $p['name'],
    ];
};

$phones = [

    /* ─────────────────────────────── Apple ─────────────────────────────── */

    ['name' => 'Apple iPhone XS Max', 'slug' => 'apple-iphone-xs-max', 'brand' => 'Apple', 'date' => '2018-09-21', 'launch' => ['$1,099', 'US', '64GB'],
        'display' => '6.5-inch Super Retina OLED, 2688 x 1242', 'chip' => 'Apple A12 Bionic (7nm)', 'ram' => '4GB',
        'battery' => '3174mAh', 'charge' => '15W wired (5W charger in the box), Qi wireless',
        'camera' => '12MP wide + 12MP 2x telephoto', 'front' => '7MP TrueDepth', 'os' => 'iOS 12 at launch',
        'notes' => 'Stainless steel frame, IP68, Face ID', 'why' => 'It was the first "Max" iPhone: the XS with a 6.5-inch screen, the biggest Apple had made at the time.',
        'dxo' => ['path' => 'Apple/iPhone-XS-Max', 'selfie' => 105]],

    ['name' => 'Apple iPhone 11 Pro Max', 'slug' => 'apple-iphone-11-pro-max', 'brand' => 'Apple', 'date' => '2019-09-20', 'launch' => ['$1,099', 'US', '64GB'],
        'display' => '6.5-inch Super Retina XDR OLED, 2688 x 1242', 'chip' => 'Apple A13 Bionic (7nm)', 'ram' => '4GB',
        'battery' => '3969mAh', 'charge' => '18W fast charger in the box, Qi wireless',
        'camera' => '12MP wide + 12MP ultra wide + 12MP 2x telephoto', 'front' => '12MP TrueDepth', 'os' => 'iOS 13 at launch',
        'notes' => 'Matte glass back, stainless steel frame, IP68 (4m), Night mode',
        'dxo' => ['path' => 'Apple/iPhone-11-Pro-Max', 'camera' => 122, 'selfie' => 122, 'display' => 122, 'v5' => true]],

    ['name' => 'Apple iPhone 12 Pro', 'slug' => 'apple-iphone-12-pro', 'brand' => 'Apple', 'date' => '2020-10-23', 'launch' => ['$999', 'US', '128GB'],
        'display' => '6.1-inch Super Retina XDR OLED, 2532 x 1170', 'chip' => 'Apple A14 Bionic (5nm)', 'ram' => '6GB',
        'battery' => '2815mAh', 'charge' => '20W wired, 15W MagSafe',
        'camera' => '12MP wide + 12MP ultra wide + 12MP 2x telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 14 at launch',
        'notes' => '5G, Ceramic Shield, flat stainless steel sides, IP68, MagSafe',
        'dxo' => ['path' => 'Apple/iPhone-12-Pro', 'camera' => 127, 'selfie' => 132, 'display' => 124, 'v5' => true]],

    ['name' => 'Apple iPhone 12 Pro Max', 'slug' => 'apple-iphone-12-pro-max', 'brand' => 'Apple', 'date' => '2020-11-13', 'launch' => ['$1,099', 'US', '128GB'],
        'display' => '6.7-inch Super Retina XDR OLED, 2778 x 1284', 'chip' => 'Apple A14 Bionic (5nm)', 'ram' => '6GB',
        'battery' => '3687mAh', 'charge' => '20W wired, 15W MagSafe',
        'camera' => '12MP wide with sensor-shift stabilisation + 12MP ultra wide + 12MP 2.5x telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 14 at launch',
        'notes' => '5G, Ceramic Shield, IP68, MagSafe; first iPhone with sensor-shift stabilisation',
        'dxo' => ['path' => 'Apple/iPhone-12-Pro-Max', 'camera' => 131, 'selfie' => 132, 'display' => 127, 'battery' => 121, 'v5' => true]],

    ['name' => 'Apple iPhone 13 Pro', 'slug' => 'apple-iphone-13-pro', 'brand' => 'Apple', 'date' => '2021-09-24', 'launch' => ['$999', 'US', '128GB'],
        'display' => '6.1-inch Super Retina XDR OLED, ProMotion 120Hz', 'chip' => 'Apple A15 Bionic (5nm)', 'ram' => '6GB',
        'battery' => '3095mAh', 'charge' => 'About 23W wired, 15W MagSafe',
        'camera' => '12MP wide + 12MP ultra wide with macro + 12MP 3x telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 15 at launch',
        'notes' => 'First 120Hz iPhone, Cinematic mode, ProRes video, IP68',
        'dxo' => ['path' => 'Apple/iPhone-13-Pro', 'camera' => 141, 'selfie' => 134, 'display' => 141, 'battery' => 118, 'v5' => true]],

    ['name' => 'Apple iPhone 13 Pro Max', 'slug' => 'apple-iphone-13-pro-max', 'brand' => 'Apple', 'date' => '2021-09-24', 'launch' => ['$1,099', 'US', '128GB'],
        'display' => '6.7-inch Super Retina XDR OLED, ProMotion 120Hz', 'chip' => 'Apple A15 Bionic (5nm)', 'ram' => '6GB',
        'battery' => '4352mAh', 'charge' => 'About 27W wired, 15W MagSafe',
        'camera' => '12MP wide + 12MP ultra wide with macro + 12MP 3x telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 15 at launch',
        'notes' => '120Hz ProMotion, Cinematic mode, ProRes video, IP68; known for long battery life',
        'dxo' => ['path' => 'Apple/iPhone-13-Pro-Max', 'camera' => 141, 'selfie' => 134, 'display' => 142, 'battery' => 136, 'v5' => true]],

    ['name' => 'Apple iPhone 14 Pro', 'slug' => 'apple-iphone-14-pro', 'brand' => 'Apple', 'date' => '2022-09-16', 'launch' => ['$999', 'US', '128GB'],
        'display' => '6.1-inch LTPO Super Retina XDR OLED, 1–120Hz, Always-On, Dynamic Island', 'chip' => 'Apple A16 Bionic (4nm)', 'ram' => '6GB',
        'battery' => '3200mAh', 'charge' => 'About 27W wired, 15W MagSafe',
        'camera' => '48MP main + 12MP ultra wide + 12MP 3x telephoto + LiDAR', 'front' => '12MP TrueDepth with autofocus', 'os' => 'iOS 16 at launch',
        'notes' => 'Dynamic Island, first 48MP iPhone camera, Crash Detection, IP68',
        'dxo' => ['path' => 'Apple/iPhone-14-Pro', 'camera' => 146, 'selfie' => 145, 'display' => 146, 'battery' => 119, 'v5' => true]],

    ['name' => 'Apple iPhone 14 Pro Max', 'slug' => 'apple-iphone-14-pro-max', 'brand' => 'Apple', 'date' => '2022-09-16', 'launch' => ['$1,099', 'US', '128GB'],
        'display' => '6.7-inch LTPO Super Retina XDR OLED, 1–120Hz, Always-On, Dynamic Island', 'chip' => 'Apple A16 Bionic (4nm)', 'ram' => '6GB',
        'battery' => '4323mAh', 'charge' => 'About 27W wired, 15W MagSafe',
        'camera' => '48MP main + 12MP ultra wide + 12MP 3x telephoto + LiDAR', 'front' => '12MP TrueDepth with autofocus', 'os' => 'iOS 16 at launch',
        'notes' => 'Dynamic Island, Always-On display, Crash Detection, IP68',
        'dxo' => ['path' => 'Apple/iPhone-14-Pro-Max', 'camera' => 146, 'selfie' => 145, 'display' => 146, 'battery' => 133, 'v5' => true]],

    ['name' => 'Apple iPhone 15 Pro', 'slug' => 'apple-iphone-15-pro', 'brand' => 'Apple', 'date' => '2023-09-22', 'launch' => ['$999', 'US', '128GB'],
        'display' => '6.1-inch LTPO Super Retina XDR OLED, 120Hz, Always-On', 'chip' => 'Apple A17 Pro (3nm)', 'ram' => '8GB',
        'battery' => '3274mAh', 'charge' => 'USB-C (USB 3), about 27W wired, 15W MagSafe',
        'camera' => '48MP main + 12MP ultra wide + 12MP 3x telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 17 at launch',
        'notes' => 'Titanium frame, Action button, first USB-C iPhone, IP68',
        'dxo' => ['path' => 'Apple/iPhone-15-Pro', 'camera' => 154, 'selfie' => 149, 'display' => 151, 'battery' => 114, 'v5' => true]],

    ['name' => 'Apple iPhone 15 Pro Max', 'slug' => 'apple-iphone-15-pro-max', 'brand' => 'Apple', 'date' => '2023-09-22', 'launch' => ['$1,199', 'US', '256GB'],
        'display' => '6.7-inch LTPO Super Retina XDR OLED, 120Hz, Always-On', 'chip' => 'Apple A17 Pro (3nm)', 'ram' => '8GB',
        'battery' => '4441mAh', 'charge' => 'USB-C (USB 3), about 27W wired, 15W MagSafe',
        'camera' => '48MP main + 12MP ultra wide + 12MP 5x tetraprism telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 17 at launch',
        'notes' => 'Titanium frame, Action button, first 5x zoom on an iPhone, IP68',
        'dxo' => ['path' => 'Apple/iPhone-15-Pro-Max', 'camera' => 154, 'selfie' => 149, 'display' => 151, 'battery' => 134, 'v5' => true]],

    ['name' => 'Apple iPhone 16 Pro', 'slug' => 'apple-iphone-16-pro', 'brand' => 'Apple', 'date' => '2024-09-20', 'launch' => ['$999', 'US', '128GB'],
        'display' => '6.3-inch LTPO Super Retina XDR OLED, 120Hz, Always-On', 'chip' => 'Apple A18 Pro (3nm)', 'ram' => '8GB',
        'battery' => '3582mAh', 'charge' => 'About 30W wired, 25W MagSafe (with 30W adapter)',
        'camera' => '48MP Fusion main + 48MP ultra wide + 12MP 5x tetraprism telephoto + LiDAR', 'front' => '12MP TrueDepth', 'os' => 'iOS 18 at launch',
        'notes' => 'Camera Control button, titanium frame, 4K 120fps Dolby Vision video, Apple Intelligence, IP68',
        'dxo' => ['path' => 'Apple/iPhone-16-Pro', 'camera' => 157, 'display' => 150, 'v5' => true]],

    ['name' => 'Apple iPhone 17 Pro', 'slug' => 'apple-iphone-17-pro', 'brand' => 'Apple', 'date' => '2025-09-19', 'launch' => ['$1,099', 'US', '256GB'],
        'display' => '6.3-inch Super Retina XDR OLED, ProMotion up to 120Hz, Always-On, 3,000 nits peak', 'chip' => 'Apple A19 Pro (3nm)', 'ram' => '12GB',
        'battery' => 'Up to 31 hours video playback (Apple figure)', 'charge' => 'Up to 40W wired (50% in about 20 minutes), MagSafe',
        'camera' => '48MP Fusion main + 48MP ultra wide + 48MP 4x telephoto', 'front' => '18MP Center Stage', 'os' => 'iOS 26 at launch',
        'notes' => 'Aluminium unibody with vapour chamber cooling, Ceramic Shield 2 back, IP68',
        'why' => 'Apple dropped titanium for an aluminium unibody with a vapour chamber, so the 17 Pro holds its performance for longer under load.',
        'dxo' => ['path' => 'Apple/iPhone-17-Pro', 'camera' => 168, 'selfie' => 154]],

    ['name' => 'Apple iPhone 17 Pro Max', 'slug' => 'apple-iphone-17-pro-max', 'brand' => 'Apple', 'date' => '2025-09-19', 'launch' => ['$1,199', 'US', '256GB'],
        'display' => '6.9-inch Super Retina XDR OLED, ProMotion up to 120Hz, Always-On, 3,000 nits peak', 'chip' => 'Apple A19 Pro (3nm)', 'ram' => '12GB',
        'battery' => 'Up to 37 hours video playback (Apple figure)', 'charge' => 'Up to 40W wired, MagSafe',
        'camera' => '48MP Fusion main + 48MP ultra wide + 48MP 4x telephoto', 'front' => '18MP Center Stage', 'os' => 'iOS 26 at launch',
        'notes' => 'Aluminium unibody with vapour chamber cooling, Ceramic Shield 2 back, IP68',
        'dxo' => ['path' => 'Apple/iPhone-17-Pro-Max', 'display' => 151]],

    /* ────────────────────────── Samsung Galaxy S / Note ────────────────────────── */

    ['name' => 'Samsung Galaxy S10+', 'slug' => 'samsung-galaxy-s10-plus', 'brand' => 'Samsung', 'date' => '2019-03-08', 'launch' => ['$999', 'US', '8GB/128GB'],
        'display' => '6.4-inch Dynamic AMOLED, QHD+, Infinity-O punch-hole', 'chip' => 'Exynos 9820 (8nm) in Asia including Nepal; Snapdragon 855 in the US and China', 'ram' => '8GB',
        'battery' => '4100mAh', 'charge' => '15W wired, 15W wireless, reverse wireless',
        'camera' => '12MP dual-aperture main + 12MP 2x telephoto + 16MP ultra wide', 'front' => '10MP + 8MP depth', 'os' => 'Android 9, One UI 1.1',
        'notes' => 'Ultrasonic in-display fingerprint, headphone jack, IP68'],

    ['name' => 'Samsung Galaxy Note20 Ultra', 'slug' => 'samsung-galaxy-note20-ultra', 'brand' => 'Samsung', 'date' => '2020-08-21', 'launch' => ['$1,299', 'US', '12GB/128GB 5G'],
        'display' => '6.9-inch Dynamic AMOLED 2X, QHD+, 120Hz', 'chip' => 'Exynos 990 (7nm) in most markets; Snapdragon 865+ in the US', 'ram' => '12GB',
        'battery' => '4500mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '108MP main + 12MP 5x periscope telephoto + 12MP ultra wide + laser autofocus', 'front' => '10MP', 'os' => 'Android 10, One UI 2.5',
        'notes' => 'S Pen with 9ms latency, Gorilla Glass Victus, IP68',
        'why' => 'The last Galaxy Note. From the S22 Ultra on, the S Pen moved into the Galaxy S Ultra line.'],

    ['name' => 'Samsung Galaxy S22', 'slug' => 'samsung-galaxy-s22', 'brand' => 'Samsung', 'date' => '2022-02-25', 'launch' => ['$799', 'US', '8GB/128GB'],
        'display' => '6.1-inch Dynamic AMOLED 2X, FHD+, 120Hz', 'chip' => 'Snapdragon 8 Gen 1 or Exynos 2200 (4nm), depending on market', 'ram' => '8GB',
        'battery' => '3700mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '10MP', 'os' => 'Android 12, One UI 4.1',
        'notes' => 'Compact flagship, Armor Aluminum frame, IP68'],

    ['name' => 'Samsung Galaxy S23', 'slug' => 'samsung-galaxy-s23', 'brand' => 'Samsung', 'date' => '2023-02-17', 'launch' => ['$799', 'US', '8GB/128GB'],
        'display' => '6.1-inch Dynamic AMOLED 2X, FHD+, 120Hz', 'chip' => 'Snapdragon 8 Gen 2 for Galaxy (4nm) in all markets', 'ram' => '8GB',
        'battery' => '3900mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '12MP', 'os' => 'Android 13, One UI 5.1',
        'notes' => 'Snapdragon worldwide (no Exynos), IP68',
        'dxo' => ['path' => 'Samsung/Galaxy-S23', 'camera' => 133, 'display' => 149, 'battery' => 109, 'v5' => true]],

    ['name' => 'Samsung Galaxy S24', 'slug' => 'samsung-galaxy-s24', 'brand' => 'Samsung', 'date' => '2024-01-31', 'launch' => ['$799', 'US', '8GB/128GB'],
        'display' => '6.2-inch Dynamic AMOLED 2X, FHD+, 1–120Hz', 'chip' => 'Exynos 2400 (4nm) in Asia including Nepal; Snapdragon 8 Gen 3 for Galaxy in the US and China', 'ram' => '8GB',
        'battery' => '4000mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '12MP', 'os' => 'Android 14, One UI 6.1',
        'notes' => 'Galaxy AI, seven years of OS updates, IP68'],

    ['name' => 'Samsung Galaxy S25 Edge', 'slug' => 'samsung-galaxy-s25-edge', 'brand' => 'Samsung', 'date' => '2025-05-30', 'launch' => ['$1,099', 'US', '12GB/256GB'],
        'display' => '6.7-inch Dynamic AMOLED 2X, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Elite for Galaxy (3nm)', 'ram' => '12GB',
        'battery' => '3900mAh', 'charge' => '25W wired, wireless',
        'camera' => '200MP main + 12MP ultra wide', 'front' => '12MP', 'os' => 'Android 15, One UI 7',
        'notes' => '5.8mm thin, 163g, titanium frame, IP68',
        'why' => 'Samsung\'s thinnest Galaxy S phone. It traded the telephoto camera and some battery for a 5.8mm body.',
        'dxo' => ['path' => 'Samsung/Galaxy-S25-Edge', 'camera' => 140, 'display' => 158]],

    /* ────────────────────────── Samsung foldables ────────────────────────── */

    ['name' => 'Samsung Galaxy Fold', 'slug' => 'samsung-galaxy-fold', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2019-09-06', 'launch' => ['$1,980', 'US', '12GB/512GB'],
        'display' => '7.3-inch foldable Dynamic AMOLED inside + 4.6-inch cover screen', 'chip' => 'Snapdragon 855 (7nm)', 'ram' => '12GB',
        'battery' => '4380mAh', 'charge' => '15W wired, wireless',
        'camera' => '12MP main + 12MP 2x telephoto + 16MP ultra wide', 'front' => '10MP + 8MP depth inside, 10MP cover', 'os' => 'Android 9, One UI',
        'notes' => 'Samsung\'s first foldable; the launch was delayed to fix the hinge and screen protector'],

    ['name' => 'Samsung Galaxy Z Flip', 'slug' => 'samsung-galaxy-z-flip', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2020-02-14', 'launch' => ['$1,380', 'US', '8GB/256GB'],
        'display' => '6.7-inch foldable Dynamic AMOLED + 1.1-inch cover display', 'chip' => 'Snapdragon 855+ (7nm)', 'ram' => '8GB',
        'battery' => '3300mAh', 'charge' => '15W wired, wireless',
        'camera' => '12MP main + 12MP ultra wide', 'front' => '10MP', 'os' => 'Android 10, One UI 2',
        'notes' => 'First phone with Ultra Thin Glass on the folding screen, Flex Mode'],

    ['name' => 'Samsung Galaxy Z Fold2', 'slug' => 'samsung-galaxy-z-fold2', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2020-09-18', 'launch' => ['$1,999', 'US', '12GB/256GB'],
        'display' => '7.6-inch foldable Dynamic AMOLED 2X, 120Hz + 6.2-inch cover screen', 'chip' => 'Snapdragon 865+ (7nm)', 'ram' => '12GB',
        'battery' => '4500mAh', 'charge' => '25W wired, 11W wireless',
        'camera' => '12MP main + 12MP ultra wide + 12MP 2x telephoto', 'front' => '10MP inside, 10MP cover', 'os' => 'Android 10, One UI 2.5',
        'notes' => 'Full-size cover screen and Ultra Thin Glass inside'],

    ['name' => 'Samsung Galaxy Z Fold3', 'slug' => 'samsung-galaxy-z-fold3', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2021-08-27', 'launch' => ['$1,799', 'US', '12GB/256GB'],
        'display' => '7.6-inch foldable Dynamic AMOLED 2X, 120Hz + 6.2-inch 120Hz cover screen', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '12GB',
        'battery' => '4400mAh', 'charge' => '25W wired, 10W wireless',
        'camera' => '12MP main + 12MP ultra wide + 12MP 2x telephoto', 'front' => '4MP under-display inside, 10MP cover', 'os' => 'Android 11, One UI 3.1.1',
        'notes' => 'First water-resistant foldable (IPX8), S Pen Fold Edition support',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Fold3-5G', 'camera' => 120, 'selfie' => 83, 'battery' => 92, 'v5' => true]],

    ['name' => 'Samsung Galaxy Z Flip3', 'slug' => 'samsung-galaxy-z-flip3', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2021-08-27', 'launch' => ['$999', 'US', '8GB/128GB'],
        'display' => '6.7-inch foldable Dynamic AMOLED 2X, 120Hz + 1.9-inch cover screen', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '8GB',
        'battery' => '3300mAh', 'charge' => '15W wired, 10W wireless',
        'camera' => '12MP main + 12MP ultra wide', 'front' => '10MP', 'os' => 'Android 11, One UI 3.1.1',
        'notes' => 'IPX8, first foldable under $1,000',
        'why' => 'The Flip3 cut the price to $999 and became the first foldable to sell in big numbers.'],

    ['name' => 'Samsung Galaxy Z Fold4', 'slug' => 'samsung-galaxy-z-fold4', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2022-08-26', 'launch' => ['$1,799', 'US', '12GB/256GB'],
        'display' => '7.6-inch foldable Dynamic AMOLED 2X, 120Hz + 6.2-inch 120Hz cover screen', 'chip' => 'Snapdragon 8+ Gen 1 (4nm)', 'ram' => '12GB',
        'battery' => '4400mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '4MP under-display inside, 10MP cover', 'os' => 'Android 12L, One UI 4.1.1',
        'notes' => 'Taskbar for multitasking, IPX8',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Fold4', 'camera' => 124, 'display' => 130, 'battery' => 99, 'v5' => true]],

    ['name' => 'Samsung Galaxy Z Flip4', 'slug' => 'samsung-galaxy-z-flip4', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2022-08-26', 'launch' => ['$999', 'US', '8GB/128GB'],
        'display' => '6.7-inch foldable Dynamic AMOLED 2X, 120Hz + 1.9-inch cover screen', 'chip' => 'Snapdragon 8+ Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '3700mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '12MP main + 12MP ultra wide', 'front' => '10MP', 'os' => 'Android 12, One UI 4.1.1',
        'notes' => 'IPX8, Flex Mode'],

    ['name' => 'Samsung Galaxy Z Fold5', 'slug' => 'samsung-galaxy-z-fold5', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2023-08-11', 'launch' => ['$1,799', 'US', '12GB/256GB'],
        'display' => '7.6-inch foldable Dynamic AMOLED 2X, 120Hz + 6.2-inch 120Hz cover screen', 'chip' => 'Snapdragon 8 Gen 2 for Galaxy (4nm)', 'ram' => '12GB',
        'battery' => '4400mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '4MP under-display inside, 10MP cover', 'os' => 'Android 13, One UI 5.1.1',
        'notes' => 'Flex Hinge closes flat with no gap, IPX8',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Fold5', 'camera' => 128, 'display' => 142, 'battery' => 114, 'v5' => true]],

    ['name' => 'Samsung Galaxy Z Flip5', 'slug' => 'samsung-galaxy-z-flip5', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2023-08-11', 'launch' => ['$999', 'US', '8GB/256GB'],
        'display' => '6.7-inch foldable Dynamic AMOLED 2X, 120Hz + 3.4-inch Flex Window cover screen', 'chip' => 'Snapdragon 8 Gen 2 for Galaxy (4nm)', 'ram' => '8GB',
        'battery' => '3700mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '12MP main + 12MP ultra wide', 'front' => '10MP', 'os' => 'Android 13, One UI 5.1.1',
        'notes' => 'Large Flex Window cover screen, gapless hinge, IPX8',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Flip5', 'camera' => 127, 'display' => 144, 'battery' => 109, 'v5' => true]],

    ['name' => 'Samsung Galaxy Z Fold6', 'slug' => 'samsung-galaxy-z-fold6', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2024-07-24', 'launch' => ['$1,899', 'US', '12GB/256GB'],
        'display' => '7.6-inch foldable Dynamic AMOLED 2X, 120Hz + 6.3-inch 120Hz cover screen', 'chip' => 'Snapdragon 8 Gen 3 for Galaxy (4nm)', 'ram' => '12GB',
        'battery' => '4400mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide + 10MP 3x telephoto', 'front' => '4MP under-display inside, 10MP cover', 'os' => 'Android 14, One UI 6.1.1',
        'notes' => 'Galaxy AI, IP48 dust and water resistance, 239g',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Fold6', 'camera' => 130, 'display' => 154]],

    ['name' => 'Samsung Galaxy Z Flip6', 'slug' => 'samsung-galaxy-z-flip6', 'brand' => 'Samsung', 'family' => 'Samsung Fold', 'date' => '2024-07-24', 'launch' => ['$1,099', 'US', '12GB/256GB'],
        'display' => '6.7-inch foldable Dynamic AMOLED 2X, 120Hz + 3.4-inch Flex Window', 'chip' => 'Snapdragon 8 Gen 3 for Galaxy (4nm)', 'ram' => '12GB',
        'battery' => '4000mAh', 'charge' => '25W wired, 15W wireless',
        'camera' => '50MP main + 12MP ultra wide', 'front' => '10MP', 'os' => 'Android 14, One UI 6.1.1',
        'notes' => 'Vapour chamber cooling, Galaxy AI, IP48',
        'dxo' => ['path' => 'Samsung/Galaxy-Z-Flip6', 'camera' => 129, 'display' => 150]],

    /* ─────────────────────────────── Xiaomi ─────────────────────────────── */

    ['name' => 'Xiaomi Mi 8', 'slug' => 'xiaomi-mi-8', 'brand' => 'Xiaomi', 'date' => '2018-06-05', 'launch' => ['CNY 2,699', 'China', '6GB/64GB'],
        'display' => '6.21-inch Super AMOLED, FHD+, notch', 'chip' => 'Snapdragon 845 (10nm)', 'ram' => '6GB',
        'battery' => '3400mAh', 'charge' => '18W Quick Charge 4+',
        'camera' => '12MP main + 12MP 2x telephoto', 'front' => '20MP', 'os' => 'MIUI 10, Android 8.1',
        'notes' => 'First phone with dual-frequency GPS, Xiaomi\'s 8th-anniversary flagship'],

    ['name' => 'Xiaomi Mi 9', 'slug' => 'xiaomi-mi-9', 'brand' => 'Xiaomi', 'date' => '2019-02-26', 'launch' => ['€449', 'Europe', '6GB/64GB'],
        'display' => '6.39-inch AMOLED, FHD+, waterdrop notch, in-display fingerprint', 'chip' => 'Snapdragon 855 (7nm)', 'ram' => '6GB',
        'battery' => '3300mAh', 'charge' => '27W wired, 20W wireless',
        'camera' => '48MP main + 16MP ultra wide + 12MP 2x telephoto', 'front' => '20MP', 'os' => 'MIUI 10, Android 9',
        'notes' => 'Among the first Snapdragon 855 phones; triple camera at a mid-range price'],

    ['name' => 'Xiaomi Mi 10 Pro', 'slug' => 'xiaomi-mi-10-pro', 'brand' => 'Xiaomi', 'date' => '2020-02-13', 'launch' => ['€999', 'Europe', '8GB/256GB'],
        'display' => '6.67-inch curved AMOLED, FHD+, 90Hz', 'chip' => 'Snapdragon 865 (7nm)', 'ram' => '8GB',
        'battery' => '4500mAh', 'charge' => '50W wired, 30W wireless',
        'camera' => '108MP main + 20MP ultra wide + 12MP 2x portrait + 8MP 5x periscope telephoto', 'front' => '20MP', 'os' => 'MIUI 11, Android 10',
        'notes' => 'Xiaomi\'s first €999 phone; 108MP main camera',
        'dxo' => ['path' => 'Xiaomi/Mi-10-Pro', 'selfie' => 109]],

    ['name' => 'Xiaomi Mi 11 Ultra', 'slug' => 'xiaomi-mi-11-ultra', 'brand' => 'Xiaomi', 'date' => '2021-04-02', 'launch' => ['€1,199', 'Europe', '12GB/256GB'],
        'display' => '6.81-inch curved AMOLED, QHD+, 120Hz + 1.1-inch rear display', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '67W wired, 67W wireless',
        'camera' => '50MP main (1/1.12-inch Samsung GN2) + 48MP ultra wide + 48MP 5x periscope telephoto', 'front' => '20MP', 'os' => 'MIUI 12.5, Android 11',
        'notes' => 'Rear selfie display, ceramic back, IP68',
        'why' => 'The first Xiaomi "Ultra", with one of the biggest camera sensors of its time and a small screen beside the cameras.',
        'dxo' => ['path' => 'Xiaomi/Mi-11-Ultra', 'camera' => 141, 'selfie' => 125, 'display' => 125, 'battery' => 108, 'v5' => true]],

    ['name' => 'Xiaomi 12 Pro', 'slug' => 'xiaomi-12-pro', 'brand' => 'Xiaomi', 'date' => '2021-12-31', 'launch' => ['$999', 'US', '8GB/128GB (global price)'],
        'display' => '6.73-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '4600mAh', 'charge' => '120W wired, 50W wireless',
        'camera' => '50MP main + 50MP ultra wide + 50MP 2x telephoto', 'front' => '32MP', 'os' => 'MIUI 13, Android 12',
        'notes' => 'Triple 50MP cameras, 120W HyperCharge',
        'dxo' => ['path' => 'Xiaomi/12-Pro', 'camera' => 129, 'display' => 123, 'battery' => 76, 'v5' => true]],

    ['name' => 'Xiaomi 13 Pro', 'slug' => 'xiaomi-13-pro', 'brand' => 'Xiaomi', 'date' => '2022-12-14', 'launch' => ['€1,299', 'Europe', '12GB/256GB'],
        'display' => '6.73-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '12GB',
        'battery' => '4820mAh', 'charge' => '120W wired, 50W wireless',
        'camera' => '50MP 1-inch main (Sony IMX989) + 50MP ultra wide + 50MP 3.2x floating telephoto, Leica', 'front' => '32MP', 'os' => 'MIUI 14, Android 13',
        'notes' => 'First Leica-branded Xiaomi sold globally, IP68',
        'dxo' => ['path' => 'Xiaomi/13-Pro', 'camera' => 136, 'display' => 131, 'battery' => 126, 'v5' => true]],

    ['name' => 'Xiaomi 13 Ultra', 'slug' => 'xiaomi-13-ultra', 'brand' => 'Xiaomi', 'date' => '2023-04-18', 'launch' => ['CNY 5,999', 'China', '12GB/256GB'],
        'display' => '6.73-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '90W wired, 50W wireless',
        'camera' => '50MP 1-inch main with variable aperture + 50MP ultra wide + 50MP 3.2x telephoto + 50MP 5x periscope, Leica', 'front' => '32MP', 'os' => 'MIUI 14, Android 13',
        'notes' => 'Four 50MP cameras, optional photography grip kit, IP68',
        'dxo' => ['path' => 'Xiaomi/13-Ultra', 'camera' => 140, 'selfie' => 128, 'display' => 130, 'battery' => 133, 'v5' => true]],

    ['name' => 'Xiaomi 14', 'slug' => 'xiaomi-14', 'brand' => 'Xiaomi', 'date' => '2023-10-31', 'launch' => ['€999', 'Europe', '12GB/256GB'],
        'display' => '6.36-inch LTPO OLED, 1.5K, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '4610mAh', 'charge' => '90W wired, 50W wireless',
        'camera' => '50MP main (Light Fusion 900) + 50MP ultra wide + 50MP 3.2x telephoto, Leica', 'front' => '32MP', 'os' => 'HyperOS, Android 14',
        'notes' => 'Compact flagship, first phone with HyperOS, IP68'],

    ['name' => 'Xiaomi 14 Ultra', 'slug' => 'xiaomi-14-ultra', 'brand' => 'Xiaomi', 'date' => '2024-02-22', 'launch' => ['€1,499', 'Europe', '16GB/512GB'],
        'display' => '6.73-inch LTPO AMOLED, QHD+, 1–120Hz, 3,000 nits peak', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '16GB',
        'battery' => '5000mAh (global); 5300mAh in China', 'charge' => '90W wired, 80W wireless',
        'camera' => '50MP 1-inch main (Sony LYT-900), f/1.63–4.0 variable aperture + 50MP ultra wide + 50MP 3.2x + 50MP 5x periscope, Leica', 'front' => '32MP', 'os' => 'HyperOS, Android 14',
        'notes' => 'Stepless variable aperture, titanium special edition, IP68',
        'dxo' => ['path' => 'Xiaomi/14-Ultra', 'camera' => 149, 'display' => 140, 'battery' => 123, 'v5' => true]],

    ['name' => 'Xiaomi 15', 'slug' => 'xiaomi-15', 'brand' => 'Xiaomi', 'date' => '2024-10-29', 'launch' => ['€999', 'Europe', '12GB/256GB'],
        'display' => '6.36-inch LTPO OLED, 1.5K, 1–120Hz', 'chip' => 'Snapdragon 8 Elite (3nm)', 'ram' => '12GB',
        'battery' => '5240mAh (global); 5400mAh in China', 'charge' => '90W wired, 50W wireless',
        'camera' => '50MP main (Light Fusion 900) + 50MP ultra wide + 60MP 2.6x telephoto, Leica', 'front' => '32MP', 'os' => 'HyperOS 2, Android 15',
        'notes' => 'Compact flagship, IP68'],

    ['name' => 'Xiaomi 15 Ultra', 'slug' => 'xiaomi-15-ultra', 'brand' => 'Xiaomi', 'date' => '2025-02-27', 'launch' => ['€1,499', 'Europe', '16GB/512GB'],
        'display' => '6.73-inch LTPO AMOLED, WQHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Elite (3nm)', 'ram' => '16GB',
        'battery' => '5410mAh (global); 6000mAh in China', 'charge' => '90W wired, 80W wireless',
        'camera' => '50MP 1-inch main (Sony LYT-900) + 50MP ultra wide + 50MP 3x telephoto + 200MP 4.3x periscope, Leica', 'front' => '32MP', 'os' => 'HyperOS 2, Android 15',
        'notes' => '200MP periscope telephoto, IP68',
        'dxo' => ['path' => 'Xiaomi/15-Ultra', 'camera' => 159, 'display' => 150]],

    /* ─────────────────────────────── Huawei ─────────────────────────────── */

    ['name' => 'Huawei P20 Pro', 'slug' => 'huawei-p20-pro', 'brand' => 'Huawei', 'date' => '2018-04-06', 'launch' => ['€899', 'Europe', '6GB/128GB'],
        'display' => '6.1-inch OLED, FHD+, notch', 'chip' => 'HiSilicon Kirin 970 (10nm)', 'ram' => '6GB',
        'battery' => '4000mAh', 'charge' => '22.5W SuperCharge',
        'camera' => '40MP main (1/1.7-inch) + 20MP monochrome + 8MP 3x telephoto, Leica', 'front' => '24MP', 'os' => 'EMUI 8.1, Android 8.1',
        'notes' => 'Twilight gradient finish, IP67, Google apps included',
        'why' => 'One of the first phones with three rear cameras and a night mode, which set the template for flagship cameras afterwards.',
        'dxo' => ['path' => 'Huawei/P20-Pro', 'selfie' => 91]],

    ['name' => 'Huawei Mate 20 Pro', 'slug' => 'huawei-mate-20-pro', 'brand' => 'Huawei', 'date' => '2018-10-16', 'launch' => ['€1,049', 'Europe', '6GB/128GB'],
        'display' => '6.39-inch curved OLED, QHD+, notch', 'chip' => 'HiSilicon Kirin 980 (7nm)', 'ram' => '6GB',
        'battery' => '4200mAh', 'charge' => '40W SuperCharge, 15W wireless, reverse wireless',
        'camera' => '40MP main + 20MP ultra wide + 8MP 3x telephoto, Leica', 'front' => '24MP', 'os' => 'EMUI 9, Android 9',
        'notes' => '3D face unlock, in-display fingerprint, IP68, Google apps included',
        'dxo' => ['path' => 'Huawei/Mate-20-Pro', 'selfie' => 96]],

    ['name' => 'Huawei P30 Pro', 'slug' => 'huawei-p30-pro', 'brand' => 'Huawei', 'date' => '2019-04-05', 'launch' => ['€999', 'Europe', '8GB/128GB'],
        'display' => '6.47-inch curved OLED, FHD+, waterdrop notch', 'chip' => 'HiSilicon Kirin 980 (7nm)', 'ram' => '8GB',
        'battery' => '4200mAh', 'charge' => '40W SuperCharge, 15W wireless',
        'camera' => '40MP RYYB main + 20MP ultra wide + 8MP 5x periscope telephoto + ToF, Leica', 'front' => '32MP', 'os' => 'EMUI 9.1, Android 9',
        'notes' => 'First 5x periscope zoom on a mainstream phone, IP68, Google apps included',
        'dxo' => ['path' => 'Huawei/P30-Pro', 'selfie' => 118]],

    ['name' => 'Huawei Mate 30 Pro', 'slug' => 'huawei-mate-30-pro', 'brand' => 'Huawei', 'date' => '2019-09-19', 'launch' => ['€1,099', 'Europe', '8GB/256GB'],
        'display' => '6.53-inch Horizon OLED, 88° curved edges', 'chip' => 'HiSilicon Kirin 990 (7nm)', 'ram' => '8GB',
        'battery' => '4500mAh', 'charge' => '40W wired, 27W wireless',
        'camera' => '40MP cine ultra wide + 40MP main + 8MP 3x telephoto + 3D depth, Leica', 'front' => '32MP', 'os' => 'EMUI 10, Android 10 without Google apps',
        'notes' => 'First Huawei flagship without Google Play services, because of US sanctions',
        'dxo' => ['path' => 'Huawei/Mate-30-Pro-5G', 'selfie' => 112]],

    ['name' => 'Huawei P40 Pro', 'slug' => 'huawei-p40-pro', 'brand' => 'Huawei', 'date' => '2020-04-07', 'launch' => ['€999', 'Europe', '8GB/256GB'],
        'display' => '6.58-inch curved OLED, 90Hz', 'chip' => 'HiSilicon Kirin 990 5G (7nm)', 'ram' => '8GB',
        'battery' => '4200mAh', 'charge' => '40W wired, 27W wireless',
        'camera' => '50MP RYYB main (1/1.28-inch) + 40MP cine ultra wide + 12MP 5x periscope + ToF, Leica', 'front' => '32MP + depth', 'os' => 'EMUI 10.1, Android 10 with Huawei Mobile Services (no Google apps)',
        'notes' => 'No Google Play services, IP68',
        'dxo' => ['path' => 'Huawei/P40-Pro', 'camera' => 129, 'selfie' => 141, 'v5' => true]],

    /* ─────────────────────────────── Honor ─────────────────────────────── */

    ['name' => 'Honor 10', 'slug' => 'honor-10', 'brand' => 'Honor', 'date' => '2018-05-15', 'launch' => ['€399', 'Europe', '4GB/128GB'],
        'display' => '5.84-inch LCD, FHD+, notch', 'chip' => 'HiSilicon Kirin 970 (10nm)', 'ram' => '4GB',
        'battery' => '3400mAh', 'charge' => '22.5W SuperCharge',
        'camera' => '16MP colour + 24MP monochrome, AI scene detection', 'front' => '24MP', 'os' => 'EMUI 8.1, Android 8.1',
        'notes' => 'Aurora glass back, ultrasonic fingerprint under the glass'],

    ['name' => 'Honor View20', 'slug' => 'honor-view20', 'brand' => 'Honor', 'date' => '2018-12-26', 'launch' => ['€569', 'Europe', '6GB/128GB'],
        'display' => '6.4-inch LCD, FHD+, punch-hole', 'chip' => 'HiSilicon Kirin 980 (7nm)', 'ram' => '6GB',
        'battery' => '4000mAh', 'charge' => '22.5W SuperCharge',
        'camera' => '48MP main (Sony IMX586) + 3D ToF', 'front' => '25MP', 'os' => 'Magic UI 2, Android 9',
        'notes' => 'One of the first phones with a punch-hole selfie camera and a 48MP sensor'],

    ['name' => 'Honor 20 Pro', 'slug' => 'honor-20-pro', 'brand' => 'Honor', 'date' => '2019-05-21', 'launch' => ['€599', 'Europe', '8GB/256GB'],
        'display' => '6.26-inch LCD, FHD+, punch-hole', 'chip' => 'HiSilicon Kirin 980 (7nm)', 'ram' => '8GB',
        'battery' => '4000mAh', 'charge' => '22.5W SuperCharge',
        'camera' => '48MP f/1.4 main + 16MP ultra wide + 8MP 3x telephoto + 2MP macro', 'front' => '32MP', 'os' => 'Magic UI 2.1, Android 9',
        'notes' => 'Side-mounted fingerprint sensor, holographic back',
        'dxo' => ['path' => 'Honor/20-Pro', 'selfie' => 112]],

    ['name' => 'Honor Magic3 Pro', 'slug' => 'honor-magic3-pro', 'brand' => 'Honor', 'date' => '2021-08-12', 'launch' => ['CNY 5,999', 'China', '8GB/256GB'],
        'display' => '6.76-inch curved OLED, 120Hz', 'chip' => 'Snapdragon 888+ (5nm)', 'ram' => '8GB',
        'battery' => '4600mAh', 'charge' => '66W wired, 50W wireless',
        'camera' => '50MP main + 64MP ultra wide + 64MP monochrome + 64MP 3.5x periscope + ToF', 'front' => '13MP + 3D depth', 'os' => 'Magic UI 5, Android 11',
        'notes' => 'Honor\'s first flagship after separating from Huawei, with Google services restored on the global model'],

    ['name' => 'Honor Magic4 Pro', 'slug' => 'honor-magic4-pro', 'brand' => 'Honor', 'date' => '2022-02-28', 'launch' => ['€1,099', 'Europe', '8GB/256GB'],
        'display' => '6.81-inch LTPO OLED, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '4600mAh', 'charge' => '100W wired, 100W wireless',
        'camera' => '50MP main + 50MP ultra wide + 64MP 3.5x periscope + ToF', 'front' => '12MP + 3D ToF', 'os' => 'Magic UI 6, Android 12',
        'notes' => '100W wireless charging, IP68'],

    ['name' => 'Honor Magic5 Pro', 'slug' => 'honor-magic5-pro', 'brand' => 'Honor', 'date' => '2023-02-27', 'launch' => ['€1,199', 'Europe', '12GB/512GB'],
        'display' => '6.81-inch LTPO OLED, 1–120Hz, 1,800 nits', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '12GB',
        'battery' => '5100mAh', 'charge' => '66W wired, 50W wireless',
        'camera' => '50MP main + 50MP ultra wide + 50MP 3.5x periscope', 'front' => '12MP + 3D depth', 'os' => 'MagicOS 7.1, Android 13',
        'notes' => 'IP68',
        'dxo' => ['path' => 'Honor/Magic5-Pro', 'camera' => 152, 'selfie' => 123, 'display' => 151, 'battery' => 141, 'v5' => true]],

    ['name' => 'Honor Magic6 Pro', 'slug' => 'honor-magic6-pro', 'brand' => 'Honor', 'date' => '2024-01-11', 'launch' => ['€1,299', 'Europe', '12GB/512GB'],
        'display' => '6.8-inch LTPO OLED, 1–120Hz, 5,000 nits peak', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '5600mAh silicon-carbon', 'charge' => '80W wired, 66W wireless',
        'camera' => '50MP variable-aperture main + 50MP ultra wide + 180MP 2.5x periscope', 'front' => '50MP + 3D depth', 'os' => 'MagicOS 8, Android 14',
        'notes' => 'Silicon-carbon battery, IP68',
        'dxo' => ['path' => 'Honor/Magic6-Pro', 'camera' => 158, 'selfie' => 151, 'display' => 157, 'battery' => 157, 'v5' => true]],

    ['name' => 'Honor Magic7 Pro', 'slug' => 'honor-magic7-pro', 'brand' => 'Honor', 'date' => '2024-10-30', 'launch' => ['€1,299', 'Europe', '12GB/512GB'],
        'display' => '6.8-inch LTPO OLED, 1–120Hz', 'chip' => 'Snapdragon 8 Elite (3nm)', 'ram' => '12GB',
        'battery' => '5270mAh (global); 5850mAh in China', 'charge' => '100W wired, 80W wireless',
        'camera' => '50MP main + 50MP ultra wide + 200MP 3x periscope', 'front' => '50MP + 3D depth', 'os' => 'MagicOS 9, Android 15',
        'notes' => 'IP68 and IP69',
        'dxo' => ['path' => 'Honor/Magic7-Pro', 'camera' => 148, 'display' => 153]],

    ['name' => 'Honor Magic V2', 'slug' => 'honor-magic-v2', 'brand' => 'Honor', 'family' => 'Honor Fold', 'date' => '2023-07-12', 'launch' => ['€1,999', 'Europe', '16GB/512GB'],
        'display' => '7.92-inch foldable LTPO OLED + 6.43-inch cover screen, 120Hz', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '16GB',
        'battery' => '5000mAh silicon-carbon', 'charge' => '66W wired',
        'camera' => '50MP main + 50MP ultra wide + 20MP 2.5x telephoto', 'front' => '16MP inside, 16MP cover', 'os' => 'MagicOS 7.2, Android 13',
        'notes' => '9.9mm folded, 231g: the thinnest book-style foldable at launch',
        'dxo' => ['path' => 'Honor/Magic-V2', 'camera' => 117, 'display' => 151, 'v5' => true]],

    ['name' => 'Honor Magic V3', 'slug' => 'honor-magic-v3', 'brand' => 'Honor', 'family' => 'Honor Fold', 'date' => '2024-07-12', 'launch' => ['€1,999', 'Europe', '12GB/512GB'],
        'display' => '7.92-inch foldable LTPO OLED + 6.43-inch cover screen, 120Hz', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '5150mAh silicon-carbon', 'charge' => '66W wired, 50W wireless',
        'camera' => '50MP main + 40MP ultra wide + 50MP 3.5x periscope', 'front' => '20MP inside, 20MP cover', 'os' => 'MagicOS 8.0.1, Android 14',
        'notes' => '9.2mm folded, 226g, IPX8'],

    /* ─────────────────────────────── vivo ─────────────────────────────── */

    ['name' => 'vivo NEX S', 'slug' => 'vivo-nex-s', 'brand' => 'vivo', 'date' => '2018-06-12', 'launch' => ['CNY 4,498', 'China', '8GB/128GB'],
        'display' => '6.59-inch Super AMOLED, FHD+, no notch', 'chip' => 'Snapdragon 845 (10nm)', 'ram' => '8GB',
        'battery' => '4000mAh', 'charge' => '22.5W',
        'camera' => '12MP main + 5MP depth', 'front' => '8MP pop-up', 'os' => 'Funtouch OS 4, Android 8.1',
        'notes' => 'Pop-up selfie camera, in-display fingerprint, screen-sound speaker',
        'why' => 'It brought the motorised pop-up selfie camera into the mainstream, which let it have a full screen with no notch.'],

    ['name' => 'vivo X50 Pro', 'slug' => 'vivo-x50-pro', 'brand' => 'vivo', 'date' => '2020-07-16', 'launch' => ['₹49,990', 'India', '8GB/256GB'],
        'display' => '6.56-inch curved AMOLED, FHD+, 90Hz', 'chip' => 'Snapdragon 765G (7nm)', 'ram' => '8GB',
        'battery' => '4315mAh', 'charge' => '33W',
        'camera' => '48MP gimbal-stabilised main + 8MP 5x periscope + 13MP 2x portrait + 8MP ultra wide', 'front' => '32MP', 'os' => 'Funtouch OS 10.5, Android 10',
        'notes' => 'First phone with a gimbal-style stabilised main camera'],

    ['name' => 'vivo X60 Pro+', 'slug' => 'vivo-x60-pro-plus', 'brand' => 'vivo', 'date' => '2021-01-21', 'launch' => ['₹69,990', 'India', '12GB/256GB'],
        'display' => '6.56-inch AMOLED, FHD+, 120Hz', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '12GB',
        'battery' => '4200mAh', 'charge' => '55W',
        'camera' => '50MP main (Samsung GN1) + 48MP gimbal ultra wide + 32MP 2x portrait + 8MP 5x periscope, Zeiss', 'front' => '32MP', 'os' => 'Funtouch OS 11, Android 11',
        'notes' => 'vivo\'s first Zeiss-branded cameras, vegan leather back'],

    ['name' => 'vivo X70 Pro+', 'slug' => 'vivo-x70-pro-plus', 'brand' => 'vivo', 'date' => '2021-09-09', 'launch' => ['₹79,990', 'India', '12GB/256GB'],
        'display' => '6.78-inch LTPO AMOLED, QHD+, 120Hz', 'chip' => 'Snapdragon 888+ (5nm)', 'ram' => '12GB',
        'battery' => '4500mAh', 'charge' => '55W wired, 50W wireless',
        'camera' => '50MP main (GN1) + 48MP gimbal ultra wide + 12MP 2x portrait + 8MP 5x periscope, Zeiss T* coating', 'front' => '32MP', 'os' => 'Funtouch OS 12, Android 11',
        'notes' => 'vivo V1 imaging chip, IP68'],

    ['name' => 'vivo X80 Pro', 'slug' => 'vivo-x80-pro', 'brand' => 'vivo', 'date' => '2022-04-25', 'launch' => ['₹79,999', 'India', '12GB/256GB'],
        'display' => '6.78-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '12GB',
        'battery' => '4700mAh', 'charge' => '80W wired, 50W wireless',
        'camera' => '50MP main (GNV) + 48MP ultra wide + 12MP 2x gimbal portrait + 8MP 5x periscope, Zeiss', 'front' => '32MP', 'os' => 'Funtouch OS 12, Android 12',
        'notes' => 'vivo V1+ imaging chip, IP68'],

    ['name' => 'vivo X90 Pro', 'slug' => 'vivo-x90-pro', 'brand' => 'vivo', 'date' => '2022-11-22', 'launch' => ['₹84,999', 'India', '12GB/256GB'],
        'display' => '6.78-inch curved AMOLED, 120Hz', 'chip' => 'MediaTek Dimensity 9200 (4nm)', 'ram' => '12GB',
        'battery' => '4870mAh', 'charge' => '120W wired, 50W wireless',
        'camera' => '50MP 1-inch main (Sony IMX989) + 50MP 2x portrait + 12MP ultra wide, Zeiss', 'front' => '32MP', 'os' => 'Funtouch OS 13, Android 13',
        'notes' => '1-inch camera sensor, vivo V2 imaging chip, IP68'],

    ['name' => 'vivo X100 Pro', 'slug' => 'vivo-x100-pro', 'brand' => 'vivo', 'date' => '2023-11-13', 'launch' => ['₹89,999', 'India', '16GB/512GB'],
        'display' => '6.78-inch LTPO AMOLED, 1–120Hz, 3,000 nits', 'chip' => 'MediaTek Dimensity 9300 (4nm)', 'ram' => '16GB',
        'battery' => '5400mAh', 'charge' => '100W wired, 50W wireless',
        'camera' => '50MP 1-inch main (IMX989) + 50MP ultra wide + 50MP 4.3x APO periscope, Zeiss', 'front' => '32MP', 'os' => 'Funtouch OS 14, Android 14',
        'notes' => 'Zeiss APO telephoto, vivo V3 imaging chip, IP68',
        'dxo' => ['path' => 'Vivo/X100-Pro', 'camera' => 150, 'display' => 153, 'v5' => true]],

    ['name' => 'vivo X200 Pro', 'slug' => 'vivo-x200-pro', 'brand' => 'vivo', 'date' => '2024-10-14', 'launch' => ['₹94,999', 'India', '16GB/512GB'],
        'display' => '6.78-inch LTPO AMOLED, 1–120Hz', 'chip' => 'MediaTek Dimensity 9400 (3nm)', 'ram' => '16GB',
        'battery' => '6000mAh (India/global)', 'charge' => '90W wired, 30W wireless',
        'camera' => '50MP main (Sony LYT-818) + 50MP ultra wide + 200MP 3.7x Zeiss APO periscope', 'front' => '32MP', 'os' => 'Funtouch OS 15, Android 15',
        'notes' => '200MP periscope telephoto, IP68 and IP69'],

    ['name' => 'vivo X Fold3 Pro', 'slug' => 'vivo-x-fold3-pro', 'brand' => 'vivo', 'date' => '2024-03-26', 'launch' => ['₹1,59,999', 'India', '16GB/512GB'],
        'display' => '8.03-inch foldable AMOLED + 6.53-inch cover screen, 120Hz', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '16GB',
        'battery' => '5700mAh', 'charge' => '100W wired, 50W wireless',
        'camera' => '50MP main + 64MP 3x telephoto + 50MP ultra wide, Zeiss', 'front' => '32MP inside, 32MP cover', 'os' => 'Funtouch OS 14, Android 14',
        'notes' => 'vivo\'s first foldable sold outside China, 236g, IPX8'],

    /* ─────────────────────────────── OPPO ─────────────────────────────── */

    ['name' => 'OPPO Find X', 'slug' => 'oppo-find-x', 'brand' => 'OPPO', 'date' => '2018-06-19', 'launch' => ['€999', 'Europe', '8GB/256GB'],
        'display' => '6.42-inch curved AMOLED, FHD+, no notch', 'chip' => 'Snapdragon 845 (10nm)', 'ram' => '8GB',
        'battery' => '3730mAh', 'charge' => '20W VOOC (50W SuperVOOC on the Lamborghini Edition)',
        'camera' => '16MP + 20MP, inside a motorised slider', 'front' => '25MP (motorised)', 'os' => 'ColorOS 5.1, Android 8.1',
        'notes' => 'Whole camera module slides out of the top; 3D face unlock',
        'why' => 'It hid all its cameras in a motorised slider, so the front was nearly all screen.'],

    ['name' => 'OPPO Reno 10x Zoom', 'slug' => 'oppo-reno-10x-zoom', 'brand' => 'OPPO', 'date' => '2019-04-24', 'launch' => ['€799', 'Europe', '6GB/128GB'],
        'display' => '6.6-inch AMOLED, FHD+, no notch', 'chip' => 'Snapdragon 855 (7nm)', 'ram' => '6GB',
        'battery' => '4065mAh', 'charge' => '20W VOOC 3.0',
        'camera' => '48MP main + 13MP 5x periscope (10x hybrid) + 8MP ultra wide', 'front' => '16MP shark-fin pop-up', 'os' => 'ColorOS 6, Android 9',
        'notes' => 'Shark-fin pop-up selfie camera'],

    ['name' => 'OPPO Find X2 Pro', 'slug' => 'oppo-find-x2-pro', 'brand' => 'OPPO', 'date' => '2020-03-06', 'launch' => ['€1,199', 'Europe', '12GB/512GB'],
        'display' => '6.7-inch AMOLED, QHD+, 120Hz', 'chip' => 'Snapdragon 865 (7nm)', 'ram' => '12GB',
        'battery' => '4260mAh', 'charge' => '65W SuperVOOC 2.0',
        'camera' => '48MP main (IMX689) + 48MP ultra wide + 13MP 5x periscope', 'front' => '32MP', 'os' => 'ColorOS 7.1, Android 10',
        'notes' => 'Ceramic or vegan leather back, IP68',
        'dxo' => ['path' => 'Oppo/Find-X2-Pro', 'selfie' => 97]],

    ['name' => 'OPPO Find X3 Pro', 'slug' => 'oppo-find-x3-pro', 'brand' => 'OPPO', 'date' => '2021-03-11', 'launch' => ['€1,149', 'Europe', '12GB/256GB'],
        'display' => '6.7-inch LTPO AMOLED, QHD+, 5–120Hz, 10-bit', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '12GB',
        'battery' => '4500mAh', 'charge' => '65W wired, 30W wireless',
        'camera' => '50MP main (IMX766) + 50MP ultra wide (IMX766) + 13MP 2x telephoto + 3MP 60x microscope', 'front' => '32MP', 'os' => 'ColorOS 11.2, Android 11',
        'notes' => 'Microscope camera, one-piece curved glass back, IP68',
        'dxo' => ['path' => 'Oppo/Find-X3-Pro', 'camera' => 128, 'display' => 125, 'battery' => 105, 'v5' => true]],

    ['name' => 'OPPO Find X5 Pro', 'slug' => 'oppo-find-x5-pro', 'brand' => 'OPPO', 'date' => '2022-02-24', 'launch' => ['€1,299', 'Europe', '12GB/256GB'],
        'display' => '6.7-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '80W wired, 50W wireless',
        'camera' => '50MP main + 50MP ultra wide + 13MP 2x telephoto, Hasselblad colour', 'front' => '32MP', 'os' => 'ColorOS 12.1, Android 12',
        'notes' => 'MariSilicon X imaging chip, ceramic back, IP68',
        'dxo' => ['path' => 'Oppo/Find-X5-Pro', 'camera' => 130, 'selfie' => 116, 'display' => 137, 'battery' => 116, 'v5' => true]],

    ['name' => 'OPPO Find X6 Pro', 'slug' => 'oppo-find-x6-pro', 'brand' => 'OPPO', 'date' => '2023-03-21', 'launch' => ['CNY 5,999', 'China', '12GB/256GB'],
        'display' => '6.82-inch LTPO AMOLED, QHD+, 1–120Hz, 2,500 nits', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '100W wired, 50W wireless',
        'camera' => '50MP 1-inch main (IMX989) + 50MP ultra wide + 50MP 2.8x periscope, Hasselblad', 'front' => '32MP', 'os' => 'ColorOS 13.1, Android 13',
        'notes' => 'Sold in China only; IP68',
        'dxo' => ['path' => 'Oppo/Find-X6-Pro', 'camera' => 153, 'display' => 138, 'battery' => 151, 'v5' => true]],

    ['name' => 'OPPO Find X7 Ultra', 'slug' => 'oppo-find-x7-ultra', 'brand' => 'OPPO', 'date' => '2024-01-08', 'launch' => ['CNY 5,999', 'China', '12GB/256GB'],
        'display' => '6.82-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '100W wired, 50W wireless',
        'camera' => '50MP 1-inch main (LYT-900) + 50MP ultra wide + 50MP 2.8x periscope + 50MP 6x periscope, Hasselblad', 'front' => '32MP', 'os' => 'ColorOS 14, Android 14',
        'notes' => 'First phone with two periscope telephoto cameras; sold in China only; IP68',
        'dxo' => ['path' => 'Oppo/Find-X7-Ultra', 'camera' => 157, 'display' => 144, 'battery' => 160, 'v5' => true]],

    ['name' => 'OPPO Find X8 Pro', 'slug' => 'oppo-find-x8-pro', 'brand' => 'OPPO', 'date' => '2024-10-24', 'launch' => ['₹99,999', 'India', '16GB/512GB'],
        'display' => '6.78-inch LTPO AMOLED, 1–120Hz', 'chip' => 'MediaTek Dimensity 9400 (3nm)', 'ram' => '16GB',
        'battery' => '5910mAh (global)', 'charge' => '80W wired, 50W wireless',
        'camera' => '50MP main (LYT-808) + 50MP ultra wide + 50MP 3x periscope + 50MP 6x periscope, Hasselblad', 'front' => '32MP', 'os' => 'ColorOS 15, Android 15',
        'notes' => 'Quick Button camera control, IP68 and IP69',
        'dxo' => ['path' => 'Oppo/Find-X8-Pro', 'camera' => 157, 'display' => 152, 'battery' => 155, 'v5' => true]],

    /* ─────────────────────────────── OnePlus ─────────────────────────────── */

    ['name' => 'OnePlus 6T', 'slug' => 'oneplus-6t', 'brand' => 'OnePlus', 'date' => '2018-11-01', 'launch' => ['$549', 'US', '6GB/128GB'],
        'display' => '6.41-inch Optic AMOLED, FHD+, waterdrop notch', 'chip' => 'Snapdragon 845 (10nm)', 'ram' => '6GB',
        'battery' => '3700mAh', 'charge' => '20W Fast Charge',
        'camera' => '16MP main + 20MP secondary', 'front' => '16MP', 'os' => 'OxygenOS 9, Android 9',
        'notes' => 'OnePlus\'s first in-display fingerprint sensor; dropped the headphone jack; first OnePlus sold by a US carrier'],

    ['name' => 'OnePlus 7T Pro', 'slug' => 'oneplus-7t-pro', 'brand' => 'OnePlus', 'date' => '2019-10-10', 'launch' => ['₹53,999', 'India', '8GB/256GB'],
        'display' => '6.67-inch curved Fluid AMOLED, QHD+, 90Hz', 'chip' => 'Snapdragon 855+ (7nm)', 'ram' => '8GB',
        'battery' => '4085mAh', 'charge' => '30W Warp Charge 30T',
        'camera' => '48MP main + 8MP 3x telephoto + 16MP ultra wide', 'front' => '16MP pop-up', 'os' => 'OxygenOS 10, Android 10',
        'notes' => 'Pop-up selfie camera, notch-free 90Hz screen'],

    ['name' => 'OnePlus 10 Pro', 'slug' => 'oneplus-10-pro', 'brand' => 'OnePlus', 'date' => '2022-01-13', 'launch' => ['$899', 'US', '8GB/128GB'],
        'display' => '6.7-inch LTPO2 AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '5000mAh', 'charge' => '80W wired (65W in the US), 50W wireless',
        'camera' => '48MP main + 50MP 150° ultra wide + 8MP 3.3x telephoto, Hasselblad', 'front' => '32MP', 'os' => 'OxygenOS 12.1, Android 12',
        'notes' => 'Second-generation Hasselblad camera',
        'dxo' => ['path' => 'OnePlus/10-Pro', 'camera' => 122, 'display' => 124, 'battery' => 100, 'v5' => true]],

    ['name' => 'OnePlus 12', 'slug' => 'oneplus-12', 'brand' => 'OnePlus', 'date' => '2023-12-05', 'launch' => ['$799', 'US', '12GB/256GB'],
        'display' => '6.82-inch LTPO AMOLED, QHD+, 1–120Hz, 4,500 nits peak', 'chip' => 'Snapdragon 8 Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '5400mAh', 'charge' => '100W wired (80W in the US), 50W wireless',
        'camera' => '50MP main (LYT-808) + 48MP ultra wide + 64MP 3x periscope, Hasselblad', 'front' => '32MP', 'os' => 'OxygenOS 14, Android 14',
        'notes' => 'IP65, wireless charging returns'],

    ['name' => 'OnePlus Open', 'slug' => 'oneplus-open', 'brand' => 'OnePlus', 'date' => '2023-10-19', 'launch' => ['$1,699', 'US', '16GB/512GB'],
        'display' => '7.82-inch foldable LTPO AMOLED + 6.31-inch cover screen, 120Hz', 'chip' => 'Snapdragon 8 Gen 2 (4nm)', 'ram' => '16GB',
        'battery' => '4805mAh', 'charge' => '67W wired',
        'camera' => '48MP main + 48MP ultra wide + 64MP 3x telephoto, Hasselblad', 'front' => '20MP inside, 32MP cover', 'os' => 'OxygenOS 13.2, Android 13',
        'notes' => 'OnePlus\'s first foldable, 239g, Alert Slider',
        'dxo' => ['path' => 'OnePlus/Open', 'display' => 137]],

    /* ─────────────────────────────── Realme ─────────────────────────────── */

    ['name' => 'Realme X2 Pro', 'slug' => 'realme-x2-pro', 'brand' => 'Realme', 'date' => '2019-10-15', 'launch' => ['₹29,999', 'India', '6GB/64GB'],
        'display' => '6.5-inch Super AMOLED, FHD+, 90Hz', 'chip' => 'Snapdragon 855+ (7nm)', 'ram' => '6GB',
        'battery' => '4000mAh', 'charge' => '50W SuperDart',
        'camera' => '64MP main + 13MP 2x telephoto + 8MP ultra wide + 2MP depth', 'front' => '16MP', 'os' => 'ColorOS 6.1, Android 9',
        'notes' => 'Realme\'s first flagship, with 90Hz and 50W charging at a mid-range price'],

    ['name' => 'Realme GT 5G', 'slug' => 'realme-gt-5g', 'brand' => 'Realme', 'date' => '2021-03-04', 'launch' => ['€449', 'Europe', '8GB/128GB'],
        'display' => '6.43-inch Super AMOLED, FHD+, 120Hz', 'chip' => 'Snapdragon 888 (5nm)', 'ram' => '8GB',
        'battery' => '4500mAh', 'charge' => '65W SuperDart',
        'camera' => '64MP main + 8MP ultra wide + 2MP macro', 'front' => '16MP', 'os' => 'Realme UI 2.0, Android 11',
        'notes' => 'Snapdragon 888 at a mid-range price, vegan leather option'],

    ['name' => 'Realme GT 2 Pro', 'slug' => 'realme-gt-2-pro', 'brand' => 'Realme', 'date' => '2022-01-04', 'launch' => ['₹49,999', 'India', '8GB/128GB'],
        'display' => '6.7-inch LTPO AMOLED, QHD+, 1–120Hz', 'chip' => 'Snapdragon 8 Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '5000mAh', 'charge' => '65W SuperDart',
        'camera' => '50MP main (IMX766) + 50MP 150° ultra wide + 3MP microscope', 'front' => '32MP', 'os' => 'Realme UI 3.0, Android 12',
        'notes' => 'Bio-based polymer "paper" back'],

    ['name' => 'Realme GT 6', 'slug' => 'realme-gt-6', 'brand' => 'Realme', 'date' => '2024-06-20', 'launch' => ['₹40,999', 'India', '8GB/256GB'],
        'display' => '6.78-inch LTPO AMOLED, 120Hz, 6,000 nits peak', 'chip' => 'Snapdragon 8s Gen 3 (4nm)', 'ram' => '8GB',
        'battery' => '5500mAh', 'charge' => '120W SuperVOOC',
        'camera' => '50MP main (LYT-808) + 50MP 2x telephoto + 8MP ultra wide', 'front' => '32MP', 'os' => 'Realme UI 5.0, Android 14',
        'notes' => 'Flagship-class camera and very bright screen at a mid-range price'],

    ['name' => 'Realme GT 7 Pro', 'slug' => 'realme-gt-7-pro', 'brand' => 'Realme', 'date' => '2024-11-04', 'launch' => ['₹59,999', 'India', '12GB/256GB'],
        'display' => '6.78-inch Samsung Eco² OLED Plus, 120Hz, 6,500 nits peak', 'chip' => 'Snapdragon 8 Elite (3nm)', 'ram' => '12GB',
        'battery' => '5800mAh (India); 6500mAh in China', 'charge' => '120W',
        'camera' => '50MP main (LYT-808) + 50MP 3x periscope + 8MP ultra wide', 'front' => '16MP', 'os' => 'Realme UI 6.0, Android 15',
        'notes' => 'One of the first Snapdragon 8 Elite phones, IP68 and IP69'],

    /* ─────────────────────────── Nothing, Tecno, Motorola ─────────────────────────── */

    ['name' => 'Nothing Phone (1)', 'slug' => 'nothing-phone-1', 'brand' => 'Nothing', 'date' => '2022-07-21', 'launch' => ['£399', 'UK', '8GB/128GB'],
        'display' => '6.55-inch OLED, FHD+, 120Hz', 'chip' => 'Snapdragon 778G+ (6nm)', 'ram' => '8GB',
        'battery' => '4500mAh', 'charge' => '33W wired, 15W wireless',
        'camera' => '50MP main (IMX766) + 50MP ultra wide', 'front' => '16MP', 'os' => 'Nothing OS 1.0, Android 12',
        'notes' => 'Glyph Interface light strips on a transparent back',
        'why' => 'Nothing\'s first phone, known for the Glyph lights on its see-through back.'],

    ['name' => 'Nothing Phone (2)', 'slug' => 'nothing-phone-2', 'brand' => 'Nothing', 'date' => '2023-07-17', 'launch' => ['£579', 'UK', '8GB/128GB'],
        'display' => '6.7-inch LTPO OLED, FHD+, 1–120Hz', 'chip' => 'Snapdragon 8+ Gen 1 (4nm)', 'ram' => '8GB',
        'battery' => '4700mAh', 'charge' => '45W wired, 15W wireless',
        'camera' => '50MP main (IMX890) + 50MP ultra wide', 'front' => '32MP', 'os' => 'Nothing OS 2.0, Android 13',
        'notes' => 'Glyph Interface, IP54'],

    ['name' => 'Tecno Phantom X2 Pro', 'slug' => 'tecno-phantom-x2-pro', 'brand' => 'Tecno', 'date' => '2022-12-07', 'launch' => ['₹49,999', 'India', '12GB/256GB'],
        'display' => '6.8-inch curved AMOLED, FHD+, 120Hz', 'chip' => 'MediaTek Dimensity 9000 (4nm)', 'ram' => '12GB',
        'battery' => '5160mAh', 'charge' => '45W',
        'camera' => '50MP main (GNV) + 50MP 2.5x retractable portrait lens + 13MP ultra wide', 'front' => '32MP', 'os' => 'HiOS 12, Android 12',
        'notes' => 'First phone with a retractable portrait lens'],

    ['name' => 'Tecno Phantom V Fold', 'slug' => 'tecno-phantom-v-fold', 'brand' => 'Tecno', 'date' => '2023-02-28', 'launch' => ['₹88,888', 'India', '12GB/256GB'],
        'display' => '7.85-inch foldable LTPO AMOLED + 6.42-inch cover screen, 120Hz', 'chip' => 'MediaTek Dimensity 9000+ (4nm)', 'ram' => '12GB',
        'battery' => '5000mAh', 'charge' => '45W',
        'camera' => '50MP main + 50MP 2x portrait + 13MP ultra wide', 'front' => '16MP inside, 32MP cover', 'os' => 'HiOS 13 Fold, Android 13',
        'notes' => 'One of the cheapest book-style foldables at launch'],

    ['name' => 'Motorola Edge 50 Ultra', 'slug' => 'motorola-edge-50-ultra', 'brand' => 'Motorola', 'date' => '2024-04-16', 'launch' => ['€999', 'Europe', '12GB/512GB'],
        'display' => '6.7-inch curved pOLED, 1.5K, 144Hz', 'chip' => 'Snapdragon 8s Gen 3 (4nm)', 'ram' => '12GB',
        'battery' => '4500mAh', 'charge' => '125W wired, 50W wireless',
        'camera' => '50MP main + 50MP ultra wide + 64MP 3x periscope', 'front' => '50MP', 'os' => 'Hello UI, Android 14',
        'notes' => 'Real wood back option, IP68'],
];

return array_map($flagship, $phones);
