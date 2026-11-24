<?php

/*
 * Reviews, news and guides that go with the November 2026 catalogue.
 *
 * Reviews: we have not had these devices in for testing, so each review opens with a
 * "How we rated it" note. Scores weigh official specs and Nepal prices against rivals on sale
 * here. Update the note (and the score, if needed) once a device has been tested hands-on.
 *
 * News: Dates are the dates we wrote them, not the
 * dates of the events they describe.
 */

$method = fn (string $what) => "<p><em>How we rated it:</em> We haven't run our own hands-on test of {$what} yet. This review weighs the official specifications and current Nepal prices against the rivals you can buy here. We'll update it once we've spent time with one.</p>";

$sources = function (array $links): string {
    $items = array_map(fn ($l) => '<li><a href="' . $l[1] . '" rel="nofollow noopener" target="_blank">' . $l[0] . '</a></li>', $links);

    return "<h3>Sources</h3>\n<ul>" . implode('', $items) . '</ul>';
};

return [

    /* ═══════════════════════════════ Reviews ═══════════════════════════════ */

    'reviews' => [

        [
            'gadget' => 'iphone-18-pro-max',
            'slug' => 'iphone-18-pro-max-review-nepal',
            'title' => 'iPhone 18 Pro Max Review: Is It Worth Rs 2,79,900 in Nepal?',
            'rating' => 8.6,
            'date' => '2026-11-22',
            'content' => $method('the iPhone 18 Pro Max') . <<<'HTML'
<p>The iPhone 18 Pro Max went on sale in Nepal on 29 September 2026 at <strong>Rs 2,79,900</strong> for 256GB. That's Rs 20,000 more than the smaller <a href="/products/iphone-18-pro">iPhone 18 Pro</a>, and Rs 54,900 more than a base <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a>. So the question isn't whether it's a good phone. It's whether it is good enough to justify that gap.</p>

<h2>What the money gets you</h2>
<p>The A20 Pro is Apple's first 2nm chip, and the 6.9-inch LTPO display reaches 3,000 nits with an anti-reflective coating. That coating is the upgrade you'll notice most outdoors in the Kathmandu sun. All three rear cameras are 48MP: a main camera with a variable aperture, an ultra wide and a 5x periscope telephoto. Charging finally reaches 50W wired, with 25W on MagSafe.</p>

<h2>Pro Max or Pro?</h2>
<p>The camera is the main reason to pick the Max. The 18 Pro uses a 4x telephoto, while the Max gets 5x, which helps at concerts, sports and wildlife shots in Chitwan. You also get the bigger screen. The cost is weight: at 249g, this is a heavy phone to hold up for a long video call or to carry in a shirt pocket.</p>

<h2>Choosing storage</h2>
<p>Storage upgrades are where Apple makes its margin. 512GB costs Rs 3,22,900 (Rs 43,000 more), 1TB costs Rs 4,07,900 and 2TB costs Rs 5,34,900. If you shoot a lot of 4K video, 512GB is the sensible step. Otherwise, 256GB plus an iCloud plan costs far less over the phone's life.</p>

<h2>The alternatives</h2>
<p>The Galaxy S26 Ultra gives you a built-in S Pen, a 200MP main camera and seven years of updates for much less money. The <a href="/products/vivo-x300-pro">vivo X300 Pro</a> (Rs 1,99,999) has a 200MP periscope and a 6,510mAh battery. If you're already in the Apple ecosystem with a Mac, AirPods and an Apple Watch, the iPhone is the easier choice. Coming from Android, it's harder to justify on value alone. <a href="/compare?g1=iphone-18-pro-max&g2=samsung-galaxy-s26-ultra">Compare it side by side with the S26 Ultra</a>.</p>
HTML,
            'pros' => ['5x periscope telephoto with 48MP sensor', 'Bright 3,000-nit display with anti-reflective coating', 'A20 Pro is Apple\'s first 2nm chip', '50W wired charging is a real step up for iPhone'],
            'cons' => ['Heavy at 249g', 'Storage upgrades are very expensive in Nepal', 'Costs Rs 54,900 more than a base Galaxy S26 Ultra'],
            'verdict' => '<p>The best iPhone you can buy in Nepal, and the zoom camera alone separates it from the 18 Pro. Buy it if you live in Apple\'s ecosystem. Android buyers get more for less with the Galaxy S26 Ultra.</p>',
            'seo' => [
                'title' => 'iPhone 18 Pro Max Review (Nepal): Worth Rs 2,79,900?',
                'description' => 'iPhone 18 Pro Max review for Nepali buyers: 5x zoom, A20 Pro, 50W charging, storage pricing from Rs 2,79,900, and how it compares with the S26 Ultra.',
                'keywords' => 'iphone 18 pro max review, iphone 18 pro max nepal review',
            ],
        ],

        [
            'gadget' => 'samsung-galaxy-s26-ultra',
            'slug' => 'samsung-galaxy-s26-ultra-review-nepal',
            'title' => 'Galaxy S26 Ultra Review: Still the Android Flagship to Beat in Nepal?',
            'rating' => 8.7,
            'date' => '2026-11-22',
            'content' => $method('the Galaxy S26 Ultra') . <<<'HTML'
<p>Nine months after launch, the Galaxy S26 Ultra is still the Android phone most Nepali buyers ask about first. It now costs <strong>Rs 2,24,999</strong> for 12GB/256GB, up from Rs 2,12,999 earlier in the year. The 512GB model is Rs 2,59,999 and the 16GB/1TB model is Rs 2,93,999.</p>

<h2>Why it's still the default pick</h2>
<p>No other phone sold here combines this many things: a 6.9-inch QHD+ display with Samsung's Privacy Display, a 200MP main camera, both 3x and 5x telephoto lenses, a built-in S Pen and <strong>seven years</strong> of Android updates. That last point matters if you keep a phone for four or five years, as many people do here.</p>
<p>The Snapdragon 8 Elite Gen 5 for Galaxy is among the fastest chips in any phone. At 214g and 7.9mm, it's also noticeably lighter and thinner than the <a href="/products/iphone-18-pro-max">iPhone 18 Pro Max</a>.</p>

<h2>Where rivals have caught up</h2>
<p>Battery is the weak spot. Samsung stays at 5,000mAh, while Chinese flagships at a lower price have moved to silicon-carbon cells: the <a href="/products/vivo-x300-pro">vivo X300 Pro</a> has 6,510mAh. Charging is 60W wired and 25W wireless, which is fine but not class-leading. The 3x camera is only 10MP.</p>

<h2>Buy now or wait for the S27 Ultra?</h2>
<p>Leaks point to a <a href="/products/samsung-galaxy-s27-ultra">Galaxy S27 Ultra</a> in January or February 2027, with a bigger battery. Our estimate is that it will cost more in Nepal, around Rs 2,40,000. If you need a phone now, the S26 Ultra is a safe buy with years of updates ahead. If you can wait three months, wait: either for the S27 Ultra or for the S26 Ultra to drop in price.</p>
HTML,
            'pros' => ['Most complete feature set of any Android phone in Nepal', 'Seven years of OS updates', 'Built-in S Pen', 'Lighter and thinner than the iPhone 18 Pro Max'],
            'cons' => ['5,000mAh battery trails cheaper Chinese flagships', 'Price went up during 2026', 'S27 Ultra is only a few months away'],
            'verdict' => '<p>Still the safest high-end Android phone you can buy in Nepal. The update promise and the S Pen keep it ahead. Unless you need a phone right now, though, wait to see what the S27 Ultra does to its price.</p>',
            'seo' => [
                'title' => 'Galaxy S26 Ultra Review (Nepal): Still Worth Buying in Late 2026?',
                'description' => 'Galaxy S26 Ultra review for Nepal: Rs 2,24,999 price, S Pen, 200MP camera, seven years of updates — and whether to wait for the S27 Ultra.',
                'keywords' => 'galaxy s26 ultra review, s26 ultra review nepal',
            ],
        ],

        [
            'gadget' => 'redmi-note-17-pro-max-5g',
            'slug' => 'redmi-note-17-pro-max-review-nepal',
            'title' => 'Redmi Note 17 Pro Max Review: A 10,000mAh Battery for Rs 69,999',
            'rating' => 8.4,
            'date' => '2026-11-22',
            'content' => $method('the Redmi Note 17 Pro Max') . <<<'HTML'
<p>Redmi Note phones are among the best-selling phones in Nepal every year, and the Note 17 Pro Max gives the series its biggest upgrade in years: a <strong>10,000mAh</strong> silicon-carbon battery. It costs <strong>Rs 69,999</strong> for 8GB/256GB and Rs 79,999 for 12GB/256GB.</p>

<h2>The battery changes how you use it</h2>
<p>Ten thousand milliamp-hours is double what most phones had two years ago. Light users can go two days between charges, which helps on long bus journeys, treks and days with power cuts. When you do plug in, it charges at 100W. It can also charge another phone or earbuds at 27W over a cable.</p>

<h2>Screen and build</h2>
<p>The 6.83-inch 1.5K AMOLED reaches 3,500 nits and is covered with Gorilla Glass Victus 2. The phone carries IP68 and IP69K ratings, so rain and dust aren't a concern and it can handle high-pressure water jets. That's unusual protection at this price.</p>

<h2>What you give up</h2>
<p>The Snapdragon 6 Gen 5 is a mid-range chip. It handles social apps, YouTube and casual games without trouble, but it isn't a gaming phone. The cameras are a 50MP main with OIS and an 8MP ultra wide, with no telephoto. If zoom matters, the <a href="/products/nothing-phone-4a">Nothing Phone 4a</a> has a 3.5x periscope at the same Rs 69,999.</p>

<h2>Rivals at this price</h2>
<p>The <a href="/products/realme-p4-power">Realme P4 Power</a> (Rs 76,999) matches the battery with a 10,001mAh cell and adds a 144Hz screen. The <a href="/products/honor-x9d">Honor X9d</a> (Rs 54,999) has an 8,300mAh battery for Rs 15,000 less. And the <a href="/products/redmi-note-17-pro-5g">Redmi Note 17 Pro</a> (Rs 57,999) keeps most of the Max's features with an 8,340mAh battery.</p>
HTML,
            'pros' => ['10,000mAh battery with 100W charging', 'IP68 and IP69K protection', 'Bright 1.5K AMOLED with Gorilla Glass Victus 2', 'Reverse charging for other devices'],
            'cons' => ['No telephoto camera; ultra wide is only 8MP', 'Mid-range chip, not built for heavy gaming', 'Realme P4 Power has a faster 144Hz screen'],
            'verdict' => '<p>If battery life is your top priority, this is the phone to buy around Rs 70,000. Photographers who want zoom should look at the Nothing Phone 4a instead.</p>',
            'seo' => [
                'title' => 'Redmi Note 17 Pro Max Review (Nepal): 10,000mAh for Rs 69,999',
                'description' => 'Redmi Note 17 Pro Max review: 10,000mAh battery, 100W charging, IP69K and a 3,500-nit AMOLED for Rs 69,999 in Nepal. What you give up and the rivals.',
                'keywords' => 'redmi note 17 pro max review, redmi note 17 pro max nepal review',
            ],
        ],

        [
            'gadget' => 'macbook-air-m5-13',
            'slug' => 'macbook-air-m5-13-review-nepal',
            'title' => 'MacBook Air M5 Review: The Laptop Most People in Nepal Should Buy',
            'rating' => 9.0,
            'date' => '2026-11-22',
            'content' => $method('the MacBook Air M5') . <<<'HTML'
<p>The 13-inch MacBook Air with M5 arrived in Nepal on 10 April 2026 at <strong>Rs 2,34,900</strong> for 16GB of memory and 512GB of storage. For students, writers, developers and office workers, it's the laptop we'd point to first. That's not because it's cheap, but because it is hard to regret.</p>

<h2>Why it works so well</h2>
<p>It has no fan, so it makes no noise, and the M5 (10-core CPU, up to a 10-core GPU) stays fast for everyday work and light photo or video editing. The 16GB base memory is the right amount for the next five years, where 8GB machines will start to struggle. Battery life easily covers a working day away from a socket, which counts for a lot when the power goes out.</p>

<h2>The compromises</h2>
<p>The 13.6-inch display (2560×1664, 500 nits) is sharp and colour-accurate, but it runs at 60Hz rather than 120Hz, and it isn't bright enough for HDR work. Going from 512GB to 1TB costs Rs 55,000 (Rs 2,89,900 total). An external SSD is the cheaper fix if you only need space for archives.</p>

<h2>What else to consider</h2>
<ul>
<li><strong>Less money:</strong> the <a href="/products/macbook-neo">MacBook Neo</a> (Rs 1,34,999) uses an iPhone-class A18 Pro chip with 8GB of memory. That's fine for study and browsing, but the 256GB model has no Touch ID or keyboard backlight.</li>
<li><strong>Windows with OLED:</strong> the <a href="/products/asus-zenbook-14-oled-um3406ga">Asus Zenbook 14 OLED</a> (Rs 2,54,999) gives you 32GB and 1TB with an OLED touchscreen.</li>
<li><strong>Bigger screen:</strong> the <a href="/products/macbook-air-m5-15">15-inch Air</a> costs Rs 2,69,900.</li>
<li><strong>Heavy video work:</strong> the <a href="/products/macbook-pro-14-m5">MacBook Pro 14 (M5)</a> adds a fan and a 120Hz XDR display.</li>
</ul>
HTML,
            'pros' => ['Silent fanless design', '16GB memory as standard', 'Battery lasts a full working day', 'M5 has headroom for years of updates'],
            'cons' => ['60Hz display, not suited to HDR editing', 'Rs 55,000 to go from 512GB to 1TB', 'No gaming to speak of on macOS'],
            'verdict' => '<p>The easiest laptop recommendation in Nepal right now. Unless you game on Windows or edit video for a living, buy the base 16GB/512GB model and keep it for years.</p>',
            'seo' => [
                'title' => 'MacBook Air M5 Review (Nepal): The Best Laptop for Most People',
                'description' => 'MacBook Air M5 13-inch review for Nepal: Rs 2,34,900 for 16GB/512GB, fanless M5, all-day battery — compromises and the alternatives worth considering.',
                'keywords' => 'macbook air m5 review, macbook air m5 nepal review',
            ],
        ],

        [
            'gadget' => 'hp-victus-15',
            'slug' => 'hp-victus-15-review-nepal',
            'title' => 'HP Victus 15 Review: The Cheapest Real Gaming Laptop in Nepal',
            'rating' => 7.8,
            'date' => '2026-11-22',
            'content' => $method('the HP Victus 15') . <<<'HTML'
<p>For a lot of students in Nepal, the first serious gaming laptop is set by budget, not specs. The HP Victus 15 is the cheapest laptop in our catalogue with a proper NVIDIA GPU. It comes in two versions:</p>
<ul>
<li><strong>Rs 1,49,000:</strong> AMD Ryzen 5 8645HS with an RTX 3050 6GB</li>
<li><strong>Rs 1,60,999:</strong> Intel Core i5-13420H with an RTX 4050 6GB</li>
</ul>
<p>Both have 16GB of DDR5-5600, a 512GB PCIe Gen4 SSD and a 15.6-inch 1080p 144Hz screen.</p>

<h2>Spend the extra Rs 12,000</h2>
<p>The RTX 4050 is a generation newer than the 3050 and supports DLSS 3 frame generation, which keeps new games playable at 1080p for longer. Over three or four years of ownership, the extra Rs 12,000 is the best money you'll spend on this laptop. The RTX 3050 version is fine for esports titles like Valorant and older games.</p>

<h2>Plan for storage</h2>
<p>512GB fills up fast. A few modern games can take up most of it once Windows and your apps are installed. Budget for a second SSD early.</p>

<h2>If you can stretch the budget</h2>
<p>The <a href="/products/lenovo-loq-15-2025">Lenovo LOQ 15</a> (Rs 1,94,999) moves up to an RTX 5050 at 100W with a 100% sRGB screen. That's a better long-term buy if Rs 45,000 more is possible. If you mostly want to play <a href="/products/gta-6">GTA VI</a>, note that it's only out on consoles for now, and a <a href="/products/sony-playstation-5">PS5</a> costs less than either laptop.</p>
HTML,
            'pros' => ['Lowest price for an RTX laptop in our catalogue', '16GB DDR5 and a 144Hz screen as standard', 'RTX 4050 version is good value'],
            'cons' => ['512GB SSD fills quickly', 'RTX 3050 version is showing its age', 'Intel version uses an older 13th-gen chip'],
            'verdict' => '<p>A sensible first gaming laptop if your budget ends at about Rs 1.6 lakh. Buy the RTX 4050 version, and add storage early.</p>',
            'seo' => [
                'title' => 'HP Victus 15 Review (Nepal): Best Budget Gaming Laptop?',
                'description' => 'HP Victus 15 review for Nepal: RTX 3050 at Rs 1,49,000 or RTX 4050 at Rs 1,60,999. Which to buy, storage advice and the Lenovo LOQ alternative.',
                'keywords' => 'hp victus 15 review, budget gaming laptop nepal',
            ],
        ],

        [
            'gadget' => 'sony-playstation-5',
            'slug' => 'ps5-digital-slim-pro-review-nepal',
            'title' => 'PS5 in Nepal (2026): Digital vs Slim Disc vs PS5 Pro — Which to Buy',
            'rating' => 8.8,
            'date' => '2026-11-22',
            'content' => $method('every PS5 model side by side') . <<<'HTML'
<p>With <a href="/products/gta-6">GTA VI</a> out, a lot of people in Nepal are buying their first PS5. As of 20 November 2026 the three models cost:</p>
<ul>
<li><strong>Digital Edition (825GB):</strong> Rs 95,000</li>
<li><strong>PS5 Slim Disc Edition (1TB):</strong> Rs 1,39,999</li>
<li><strong>PS5 Pro (2TB):</strong> Rs 1,45,000</li>
</ul>

<h2>The surprising result: the Pro beats the Slim Disc on value</h2>
<p>At these listings, the Pro costs only about Rs 5,000 more than the Slim Disc. For that you get double the storage (2TB), a 16.7-TFLOPS GPU instead of 10.3, and better image quality in games with PS5 Pro enhancements. The catch is that the Pro has no disc drive. Sony's detachable drive is sold separately. If you only buy digital games, the Slim Disc is hard to recommend at this price.</p>

<h2>The Digital Edition is the budget pick</h2>
<p>At Rs 95,000 it's Rs 50,000 cheaper than the Pro, and it plays every PS5 game, including GTA VI. The trade-off is storage: 825GB doesn't last long with today's game sizes, so plan to add an M.2 SSD later. Every PS5 model has a slot for one.</p>

<h2>RAM doesn't change between models</h2>
<p>All three have 16GB of GDDR6. The Pro adds 2GB of DDR5 for system tasks, but the real upgrade is the GPU. If a shop advertises a "higher RAM" PS5, that isn't how Sony sells them.</p>

<h2>Buying tips for Nepal</h2>
<p>Retail prices vary with stock and the season. Compare at least two sellers, ask for the warranty terms in writing, and check that the console's region matches the PlayStation Store account you plan to use. A spare <a href="/products/sony-dualsense-wireless-controller">DualSense controller</a> costs Rs 9,500.</p>
HTML,
            'pros' => ['Huge game library, now including GTA VI', 'PS5 Pro is only about Rs 5,000 more than the Slim Disc at current listings', 'M.2 SSD slot on every model'],
            'cons' => ['Prices rose during 2026', '825GB Digital Edition fills up quickly', 'Disc drive costs extra on the Digital and Pro'],
            'verdict' => '<p>Buy the Digital Edition on a budget, or the Pro if you have a 4K TV. At current Nepal prices the Slim Disc only makes sense if you rely on physical games.</p>',
            'seo' => [
                'title' => 'PS5 Digital vs Slim vs Pro in Nepal (2026): Which Should You Buy?',
                'description' => 'PS5 Digital Rs 95,000, Slim Disc Rs 1,39,999 or PS5 Pro Rs 1,45,000? Our Nepal buying verdict on storage, RAM, GPU and value for GTA VI.',
                'keywords' => 'ps5 vs ps5 pro nepal, which ps5 to buy, ps5 review nepal',
            ],
        ],
    ],

    /* ═══════════════════════════════ News ═══════════════════════════════ */

    'news' => [

        [
            'slug' => 'gta-6-launch-record-sales-how-to-buy-nepal',
            'title' => 'GTA VI Is Out: A Record Launch, and How to Buy It in Nepal',
            'category' => 'gaming',
            'date' => '2026-11-22',
            'meta_description' => 'GTA VI launched on 19 November 2026 with record first-day sales. How Nepali players can buy it, what it costs and which console to get.',
            'content' => <<<'HTML'
<p>Grand Theft Auto VI finally launched on <strong>19 November 2026</strong> for PlayStation 5 and Xbox Series X|S, after being delayed twice from its original 2025 window. There is still no PC version.</p>

<h2>A record-breaking first day</h2>
<p>Variety reported that Take-Two counted <strong>$3.79 billion</strong> in sales within the first 24 hours. Sensor Tower's independent estimate was lower, at about 24.6 million copies and $2.36 billion on launch day including pre-orders, with roughly 18.4 million of those copies on PS5. The two figures measure different things, but by either count it's one of the biggest entertainment launches ever.</p>

<h2>How to buy GTA VI in Nepal</h2>
<p>Rockstar doesn't sell the game at a Nepal-specific price, so players here have two options:</p>
<ul>
<li><strong>The PlayStation Store</strong>, paying $79.99 for the Standard Edition or $99.99 for the Ultimate Edition with a dollar card.</li>
<li><strong>A local game shop.</strong> Expect about Rs 13,499 for the Standard Edition and Rs 16,999 for the Ultimate.</li>
</ul>
<p>Be careful with very cheap "secondary account" offers. You are sharing someone else's account, and access can disappear if they change the password.</p>

<h2>Which console?</h2>
<p>GTA VI runs on every PS5 model. See our <a href="/reviews/ps5-digital-slim-pro-review-nepal">PS5 buying verdict</a>. The Digital Edition at Rs 95,000 is the cheapest way in, and the PS5 Pro gives the best image on a 4K TV. Full details are on our <a href="/products/gta-6">GTA VI page</a>.</p>
HTML
            . "\n" . $sources([
                ['Variety – GTA 6 first-day sales', 'https://variety.com/2026/gaming/news/gta-6-sales-billions-first-day-1236954198/'],
                ['HotHardware – Sensor Tower launch estimate', 'https://hothardware.com/news/gta-6-launch-haul-dwarfs-gta-5s-day-one-record'],
            ]),
            'seo' => [
                'title' => 'GTA VI Launch: Record Sales and How to Buy GTA 6 in Nepal',
                'description' => 'GTA VI launched on 19 November 2026 with record sales. Nepal prices (Rs 13,499 Standard), buying options and which PS5 to get.',
                'keywords' => 'gta 6 nepal, gta vi launch, gta 6 price nepal, gta 6 sales',
            ],
        ],

        [
            'slug' => 'ps5-price-in-nepal-november-2026',
            'title' => 'PS5 Prices in Nepal After Sony\'s 2026 Increases',
            'category' => 'price-trends',
            'date' => '2026-11-22',
            'meta_description' => 'Current PS5 prices in Nepal: Digital Rs 95,000, Slim Disc Rs 1,39,999 and PS5 Pro Rs 1,45,000, after Sony raised prices in several regions in 2026.',
            'content' => <<<'HTML'
<p>Demand for PlayStation 5 consoles has jumped in Kathmandu with GTA VI's release, and prices are higher than they were a year ago. Sony raised PS5 prices in several regions in spring 2026, citing economic pressures and production costs. These are the prices as of 20 November 2026:</p>
<table>
<thead><tr><th>Model</th><th>Storage</th><th>Price in Nepal</th></tr></thead>
<tbody>
<tr><td>PS5 Digital Edition</td><td>825GB</td><td>Rs 95,000</td></tr>
<tr><td>PS5 Slim Disc Edition</td><td>1TB</td><td>Rs 1,39,999</td></tr>
<tr><td>PS5 Pro</td><td>2TB</td><td>Rs 1,45,000</td></tr>
<tr><td>DualSense controller</td><td>–</td><td>Rs 9,500</td></tr>
</tbody>
</table>
<p>The odd one out is the Slim Disc, which is listed only about Rs 5,000 below the far more powerful Pro. Shop prices move with stock, so it's worth checking a couple of sellers. If the gap is that small where you buy, the Pro is the better deal, unless you need a disc drive. Our <a href="/reviews/ps5-digital-slim-pro-review-nepal">PS5 buying verdict</a> goes through the choice in detail.</p>
<p>You can switch between models and see each price on the <a href="/products/sony-playstation-5">PS5 product page</a>.</p>
HTML
            . "\n" . $sources([
                ['PlayStation Blog – 2026 price changes (Southeast Asia)', 'https://blog.playstation.com/2026/03/27/20260327-ps/'],
            ]),
            'seo' => [
                'title' => 'PS5 Price in Nepal (November 2026): Digital, Slim & Pro',
                'description' => 'PS5 prices in Nepal for November 2026: Digital Rs 95,000, Slim Disc Rs 1,39,999, PS5 Pro Rs 1,45,000 — and why the Pro may be the better deal.',
                'keywords' => 'ps5 price nepal november 2026, ps5 price hike nepal',
            ],
        ],

        [
            'slug' => 'iphone-18-pro-pro-max-iphone-duo-nepal-price',
            'title' => 'iPhone 18 Pro, Pro Max and iPhone Duo: Official Prices in Nepal',
            'category' => 'mobile',
            'date' => '2026-11-22',
            'meta_description' => 'Official Nepal prices for the iPhone 18 Pro (from Rs 2,59,900), iPhone 18 Pro Max (from Rs 2,79,900) and the foldable iPhone Duo (from Rs 4,30,900).',
            'content' => <<<'HTML'
<p>Apple's autumn 2026 iPhones are all on sale in Nepal now. The iPhone 18 Pro and Pro Max arrived on 29 September, and Apple's first foldable, the iPhone Duo, followed on 30 October. Here's what each costs.</p>

<h2>iPhone 18 Pro</h2>
<p>256GB Rs 2,59,900 · 512GB Rs 3,01,900 · 1TB Rs 3,86,900 · 2TB Rs 5,13,900. It has a 6.3-inch display, the A20 Pro chip and a 4x telephoto. <a href="/products/iphone-18-pro">Full specs</a>.</p>

<h2>iPhone 18 Pro Max</h2>
<p>256GB Rs 2,79,900 · 512GB Rs 3,22,900 · 1TB Rs 4,07,900 · 2TB Rs 5,34,900. It moves up to a 6.9-inch screen and a 5x periscope. Read our <a href="/reviews/iphone-18-pro-max-review-nepal">iPhone 18 Pro Max review</a>.</p>

<h2>iPhone Duo</h2>
<p>256GB Rs 4,30,900 · 512GB Rs 4,72,900 · 1TB Rs 5,57,900 · 2TB Rs 6,84,900. The foldable has a 7.6-inch inner screen, a 5.4-inch cover screen and a titanium hinge. It uses Touch ID on the side instead of Face ID. <a href="/products/iphone-duo">Full specs</a>.</p>

<h2>Where's the regular iPhone 18?</h2>
<p>Apple split its line-up this year. The standard <a href="/products/iphone-18">iPhone 18</a> and the <a href="/products/iphone-18e">iPhone 18e</a> are expected in spring 2027. Until then, the <a href="/products/iphone-17">iPhone 17</a> (Rs 1,64,999) is the newest non-Pro iPhone.</p>
HTML,
            'seo' => [
                'title' => 'iPhone 18 Pro, Pro Max & iPhone Duo Price in Nepal: All Variants',
                'description' => 'Official Nepal prices: iPhone 18 Pro from Rs 2,59,900, Pro Max from Rs 2,79,900 and the foldable iPhone Duo from Rs 4,30,900. Every storage option.',
                'keywords' => 'iphone 18 pro price in nepal, iphone duo price in nepal, iphone 18 nepal',
            ],
        ],

        [
            'slug' => 'macbook-pro-m6-oled-macbook-pro-expected-price-nepal',
            'title' => 'M6 MacBook Pro and OLED MacBook Pro: Expected Prices in Nepal',
            'category' => 'laptop',
            'date' => '2026-11-22',
            'meta_description' => 'Apple announced the M6 MacBook Pro 14 and a tandem-OLED MacBook Pro with touch. Expected Nepal prices start at Rs 3,85,999 and Rs 5,35,999.',
            'content' => <<<'HTML'
<p>Apple announced two new MacBook Pro models at the end of October 2026, a day apart. Neither is officially on sale in Nepal yet, so the prices below are estimates converted from US prices, not official figures.</p>

<h2>MacBook Pro 14 with M6</h2>
<p>This model keeps the familiar design and mini-LED XDR screen, with a new M6 chip (12-core CPU and GPU), Wi-Fi 7 and battery life of up to 24 hours. It starts at $1,999 in the US with 16GB of memory and 1TB of storage. <strong>Expected Nepal price: about Rs 3,85,999</strong>, rising to Rs 4,81,999 with 2TB. <a href="/products/macbook-pro-14-m6">See every configuration</a>.</p>

<h2>MacBook Pro with ProLED XDR display</h2>
<p>This is the bigger news: a tandem-OLED touchscreen, the first touchscreen on a Mac, plus a Dynamic Island, offered with M5 Pro and M5 Max chips in 14 and 16-inch sizes. US prices start at $2,899. <strong>Expected Nepal price: about Rs 5,35,999</strong> for the 14-inch M5 Pro, up to Rs 8,79,999 for the 16-inch M5 Max. <a href="/products/macbook-pro-proled-xdr">Specs and expected prices</a>.</p>

<h2>Should you wait?</h2>
<p>If you need a Mac for work now, the <a href="/products/macbook-pro-14-m5">M5 MacBook Pro 14</a> is on sale here at Rs 3,76,500, and the M6 is an incremental step up. Most people don't need a Pro at all. The <a href="/reviews/macbook-air-m5-13-review-nepal">MacBook Air M5</a> is still our top pick.</p>
HTML,
            'seo' => [
                'title' => 'MacBook Pro M6 & OLED MacBook Pro: Expected Price in Nepal',
                'description' => 'M6 MacBook Pro 14 expected from Rs 3,85,999 and the OLED touchscreen MacBook Pro from Rs 5,35,999 in Nepal. What\'s new and whether to wait.',
                'keywords' => 'macbook pro m6 nepal, oled macbook pro nepal price, macbook pro 2026 nepal',
            ],
        ],

        [
            'slug' => 'galaxy-s27-ultra-leaks-launch-date-nepal-price',
            'title' => 'Galaxy S27 Ultra: What the Leaks Say About Launch, Specs and Price',
            'category' => 'rumors',
            'date' => '2026-11-23',
            'meta_description' => 'Galaxy S27 Ultra leaks point to a January–February 2027 launch, a 5,700mAh battery and a 2nm Snapdragon. Our Nepal price estimate and what to believe.',
            'content' => <<<'HTML'
<p>Samsung hasn't said anything officially about the Galaxy S27 series, but the leaks have settled into a fairly consistent picture. Here's what's being reported, and how much weight to give it.</p>

<h2>Launch timing</h2>
<p>Most reports expect a Galaxy Unpacked event in <strong>January or February 2027</strong>. One tipster says early January, while others expect late February. The <a href="/products/samsung-galaxy-s26-ultra">S26 Ultra</a> was unveiled on 25 February 2026. Four models are expected: the S27, S27+, a new S27 Pro and the S27 Ultra.</p>

<h2>Reported upgrades</h2>
<ul>
<li><strong>Battery:</strong> about 5,700mAh, up from 5,000mAh. This is the most widely repeated leak, and the most welcome one.</li>
<li><strong>Chip:</strong> a 2nm Snapdragon 8 Elite Gen 6 for Galaxy.</li>
<li><strong>Design:</strong> CAD renders show the three cameras in a single horizontal strip with a smaller bump.</li>
<li><strong>S Pen:</strong> stays, despite earlier rumours that it would go.</li>
</ul>

<h2>Expected price in Nepal</h2>
<p>US reports suggest $1,399–1,499, up from $1,299, and Indian outlets estimate about ₹1,49,990. The S26 Ultra launched here at Rs 2,24,999, so our estimate for the S27 Ultra is <strong>around Rs 2,40,000</strong>. We'll replace it with the official price as soon as Samsung Nepal announces one. <a href="/products/samsung-galaxy-s27-ultra">Follow the S27 Ultra page</a> for updates.</p>
HTML
            . "\n" . $sources([
                ['Digit – Galaxy S27 Ultra leaks and India pricing', 'https://www.digit.in/news/mobile-phones/samsung-galaxy-s27-ultra-leaks-price-in-india-launch-timeline-design-camera-and-other-details-we-know.html'],
                ['Tom\'s Guide – Galaxy S27 Ultra rumours', 'https://tomsguide.com/phones/samsung-phones/samsung-galaxy-s27-ultra-rumors-release-display-upgrades-and-everything-we-know-so-far'],
                ['Android Headlines – S27 Ultra CAD renders', 'https://www.androidheadlines.com/exclusive-samsung-galaxy-s27-ultra-official-cad-renders-leak'],
                ['PhoneArena – Galaxy S27 Ultra release date and price expectations', 'https://www.phonearena.com/galaxy-s27-ultra-release-date-price-features-news'],
            ]),
            'seo' => [
                'title' => 'Galaxy S27 Ultra Leaks: Launch Date, Specs & Expected Nepal Price',
                'description' => 'Galaxy S27 Ultra is expected in January–February 2027 with a 5,700mAh battery and 2nm chip. Expected Nepal price around Rs 2,40,000.',
                'keywords' => 'galaxy s27 ultra leaks, s27 ultra price in nepal, s27 ultra launch date',
            ],
        ],

        [
            'slug' => 'iphone-18-iphone-18e-spring-2027-what-we-know',
            'title' => 'iPhone 18 and iPhone 18e Expected in Spring 2027: What We Know',
            'category' => 'rumors',
            'date' => '2026-11-23',
            'meta_description' => 'The standard iPhone 18 and iPhone 18e are expected in spring 2027 with the A20 chip. Rumoured specs and our expected Nepal prices.',
            'content' => <<<'HTML'
<p>For the first time, Apple didn't launch its whole iPhone line-up together. The <a href="/products/iphone-18-pro">iPhone 18 Pro</a>, Pro Max and the foldable iPhone Duo came in autumn 2026. The cheaper models are expected in spring 2027.</p>

<h2>iPhone 18</h2>
<p>Bloomberg's Mark Gurman has repeatedly reported a spring 2027 launch, and later supply-chain reports point to around March. The rumoured specs are a 2nm A20 chip (a small step below the Pro's A20 Pro), a 6.3-inch display, two 48MP rear cameras, a 24MP front camera and more RAM than the iPhone 17's 8GB.</p>
<p><strong>Our Nepal estimate: about Rs 1,75,000.</strong> The <a href="/products/iphone-17">iPhone 17</a> sells for Rs 1,64,999 at a US price of $799, and reports expect the iPhone 18 at $849–899. <a href="/products/iphone-18">iPhone 18 page</a>.</p>

<h2>iPhone 18e</h2>
<p>The 18e is expected to stay close to the <a href="/products/iphone-17e">iPhone 17e</a>, with the A20 chip, a single 48MP camera and Apple's C2 modem. One leak suggests another 60Hz screen. <strong>Our Nepal estimate: about Rs 1,35,000</strong> if Apple holds the $599 US price, or closer to Rs 1,50,000 if it moves to $649–699. <a href="/products/iphone-18e">iPhone 18e page</a>.</p>

<p>None of this is confirmed by Apple. Reports also note that Apple could still change its plans.</p>
HTML
            . "\n" . $sources([
                ['MacRumors – iPhone 18 roundup', 'https://www.macrumors.com/roundup/iphone-18/'],
                ['Macworld – iPhone 18 and 18e: release date, specs, price', 'https://www.macworld.com/article/3188152/iphone-18e-18-2027-release-date-specs-price-cameras.html'],
                ['9to5Mac – low-cost iPhone 18 details from leaker', 'https://9to5mac.com/2026/10/27/apples-low-cost-iphone-18-details-revealed-by-leaker/'],
            ]),
            'seo' => [
                'title' => 'iPhone 18 & iPhone 18e: Spring 2027 Launch, Specs, Nepal Price',
                'description' => 'iPhone 18 and 18e are expected in spring 2027 with the A20 chip. Rumoured specs and expected Nepal prices of about Rs 1,75,000 and Rs 1,35,000.',
                'keywords' => 'iphone 18 launch date, iphone 18e nepal, iphone 18 price in nepal',
            ],
        ],

        [
            'slug' => 'why-gadget-prices-rose-nepal-2026',
            'title' => 'Why Phones, Laptops and Consoles Got More Expensive in Nepal in 2026',
            'category' => 'price-trends',
            'date' => '2026-11-23',
            'meta_description' => 'From the Galaxy S26 Ultra to the MacBook Pro and PS5, 2026 brought price increases in Nepal. The dated hikes, the reasons, and what to do.',
            'content' => <<<'HTML'
<p>If a device you priced in January now costs more, you're not imagining it. 2026 was a year of price rises rather than falls, in Nepal and worldwide. Here are the changes we could date and source.</p>

<h2>The increases we tracked</h2>
<ul>
<li><strong><a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a>:</strong> the 256GB model went up from Rs 2,12,999 to Rs 2,24,999, and the 512GB model up from Rs 2,42,999 to Rs 2,59,999.</li>
<li><strong><a href="/products/samsung-galaxy-s26">Galaxy S26</a>:</strong> recorded at Rs 1,33,999 in June, now Rs 1,39,999.</li>
<li><strong><a href="/products/macbook-pro-14-m5">MacBook Pro 14 (M5)</a>:</strong> up on 1 July 2026 to Rs 3,76,500. The rise came with the global memory shortage.</li>
<li><strong><a href="/products/vivo-x300-pro">vivo X300 Pro</a>:</strong> from Rs 1,79,999 to Rs 1,99,999 on 1 September 2026.</li>
<li><strong><a href="/products/sony-playstation-5">PlayStation 5</a>:</strong> Sony raised prices in several regions in spring 2026. The Slim Disc is now listed at Rs 1,39,999.</li>
<li><strong><a href="/products/nintendo-switch-2">Nintendo Switch 2</a>:</strong> the US price went from $449.99 to $499 on 1 September 2026, after Japan's rise in May. Imports to Nepal follow those prices.</li>
</ul>

<h2>Why it's happening</h2>
<p>The biggest single cause is memory. Demand for AI data-centre hardware has tightened the supply of RAM and flash storage, and manufacturers have passed those costs on. Apple raised Mac and iPad prices in June 2026, and the same pressure shows up in phones, where more RAM and storage now cost more. Exchange rates and import costs add to the effect in Nepal.</p>

<h2>What this means for buyers</h2>
<ul>
<li><strong>Don't count on quick drops.</strong> With memory still expensive, several devices went up in price this year rather than down.</li>
<li><strong>Buy the storage you need up front.</strong> Upgrades cost more than ever. See the storage prices on any product page.</li>
<li><strong>Watch the price history.</strong> Every product page on this site now shows the dated prices we've recorded, and the chart fills in as prices change.</li>
</ul>
HTML
            . "\n" . $sources([
                ['Digital Trends – Switch 2 US price increase', 'https://www.digitaltrends.com/gaming/nintendo-just-announced-a-price-hike-for-the-switch-2-in-the-us/'],
                ['PlayStation Blog – 2026 price changes (Southeast Asia)', 'https://blog.playstation.com/2026/03/27/20260327-ps/'],
            ]),
            'seo' => [
                'title' => 'Why Gadget Prices Rose in Nepal in 2026 (Phones, Laptops, PS5)',
                'description' => 'Galaxy S26 Ultra, MacBook Pro, vivo X300 Pro and PS5 all got pricier in Nepal in 2026. The dated hikes, the memory shortage behind them, and buying advice.',
                'keywords' => 'price hike nepal 2026, mobile price increase nepal, laptop price nepal 2026',
            ],
        ],
    ],

    /* ═══════════════════════════════ Guides ═══════════════════════════════ */

    'guides' => [

        [
            'slug' => 'best-phones-in-nepal-by-budget',
            'title' => 'Best Phones in Nepal by Budget (November 2026)',
            'type' => 'buying-guide',
            'date' => '2026-11-23',
            'content' => <<<'HTML'
<p>These picks use prices checked on 22 November 2026. Each one links to the full specs, every storage option and the price history we've recorded. Prices change often, so check the product page before you buy.</p>

<h2>Under Rs 40,000</h2>
<p><strong><a href="/products/redmi-17-5g">Redmi 17 5G</a> — from Rs 33,999.</strong> A 7,500mAh battery and a 6.9-inch 120Hz screen are rare at this price. The screen is HD+ LCD rather than AMOLED, which is the main compromise.</p>

<h2>Rs 40,000 – 60,000</h2>
<ul>
<li><strong><a href="/products/samsung-galaxy-a17-5g">Galaxy A17 5G</a> — Rs 44,999.</strong> A Super AMOLED screen, an OIS main camera and Samsung's software support. The safe brand choice.</li>
<li><strong><a href="/products/honor-x9d">Honor X9d</a> — Rs 54,999.</strong> An 8,300mAh battery, a 6,000-nit screen and drop protection rated to 2.5m. The toughest phone here.</li>
<li><strong><a href="/products/redmi-note-17-pro-5g">Redmi Note 17 Pro</a> — Rs 57,999.</strong> An 8,340mAh battery with 67W charging and IP68/IP69K protection.</li>
</ul>

<h2>Rs 60,000 – 80,000</h2>
<ul>
<li><strong><a href="/products/redmi-note-17-pro-max-5g">Redmi Note 17 Pro Max</a> — Rs 69,999.</strong> 10,000mAh and 100W charging. Read our <a href="/reviews/redmi-note-17-pro-max-review-nepal">review</a>.</li>
<li><strong><a href="/products/nothing-phone-4a">Nothing Phone 4a</a> — Rs 69,999.</strong> The only phone in this range with a 3.5x periscope zoom.</li>
<li><strong><a href="/products/samsung-galaxy-a37-5g">Galaxy A37 5G</a> — Rs 74,999.</strong> IP68 and a 50MP OIS camera with long Samsung support.</li>
<li><strong><a href="/products/realme-p4-power">Realme P4 Power</a> — Rs 76,999.</strong> A 10,001mAh battery and a 144Hz screen.</li>
</ul>

<h2>Rs 80,000 – 1,50,000</h2>
<ul>
<li><strong><a href="/products/oneplus-15r">OnePlus 15R</a> — Rs 98,499.</strong> Flagship-level speed for under Rs 1 lakh.</li>
<li><strong><a href="/products/nothing-phone-3">Nothing Phone 3</a> — Rs 1,09,999.</strong> For people who want something that looks different.</li>
<li><strong><a href="/products/iphone-17e">iPhone 17e</a> — Rs 1,32,499.</strong> The cheapest current iPhone.</li>
<li><strong><a href="/products/samsung-galaxy-s26">Galaxy S26</a> — Rs 1,39,999.</strong> A compact Samsung flagship.</li>
</ul>

<h2>Flagships</h2>
<ul>
<li><strong><a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a> — Rs 2,24,999.</strong> The most complete Android phone. <a href="/reviews/samsung-galaxy-s26-ultra-review-nepal">Review</a>.</li>
<li><strong><a href="/products/vivo-x300-pro">vivo X300 Pro</a> or <a href="/products/xiaomi-17-ultra">Xiaomi 17 Ultra</a> — Rs 1,99,999 each.</strong> Camera-first flagships for less than the Samsung.</li>
<li><strong><a href="/products/iphone-18-pro-max">iPhone 18 Pro Max</a> — Rs 2,79,900.</strong> The best iPhone. <a href="/reviews/iphone-18-pro-max-review-nepal">Review</a>.</li>
</ul>

<h2>Worth waiting for?</h2>
<p>The <a href="/products/samsung-galaxy-s27-ultra">Galaxy S27 Ultra</a> is expected in January–February 2027, and the <a href="/products/iphone-18">iPhone 18</a> in spring 2027. If you're buying at the very top end and can wait a few months, it may be worth it. Browse all <a href="/products?status=upcoming">upcoming phones</a>.</p>
HTML,
            'seo' => [
                'title' => 'Best Phones in Nepal by Budget (November 2026)',
                'description' => 'The best phones in Nepal for every budget: under Rs 40,000, Rs 60,000, Rs 80,000, Rs 1.5 lakh and flagships, with current prices and links to full specs.',
                'keywords' => 'best phone in nepal, best mobile under 50000 nepal, best phone under 30000 nepal, best phone nepal 2026',
            ],
        ],

        [
            'slug' => 'best-laptops-nepal-students-gamers',
            'title' => 'Best Laptops in Nepal for Students, Work and Gaming (2026)',
            'type' => 'buying-guide',
            'date' => '2026-11-23',
            'content' => <<<'HTML'
<p>Laptop prices in Nepal rose during 2026, which makes choosing the right one first time more important. Prices below were checked on 22 November 2026.</p>

<h2>Three rules before you buy</h2>
<ol>
<li><strong>16GB of RAM is the minimum</strong> for a laptop you want to keep for four or more years. 8GB is fine only for study and browsing.</li>
<li><strong>Buy from an authorised seller</strong> and get the warranty terms in writing. Grey imports may be cheaper, but repairs can be a problem.</li>
<li><strong>Think about power cuts.</strong> Battery life matters more here than spec sheets suggest.</li>
</ol>

<h2>For students on a budget</h2>
<ul>
<li><strong><a href="/products/macbook-neo">MacBook Neo</a> — Rs 1,34,999.</strong> Apple's cheapest laptop, with an A18 Pro chip and 8GB of memory. Fine for classes, writing and browsing. The 256GB model has no Touch ID or keyboard backlight.</li>
<li><strong><a href="/products/dell-xps-13-2026">Dell XPS 13 (2026)</a> — Rs 1,34,999.</strong> A premium build and a 2.5K touchscreen, but only 8GB of RAM.</li>
<li><strong><a href="/products/lenovo-yoga-slim-7i-aura-edition">Lenovo Yoga Slim 7i Aura Edition</a> — Rs 1,64,999.</strong> Our value pick: 16GB, an OLED screen and Intel's efficient Core Ultra 5 226V.</li>
</ul>

<h2>For work that lasts</h2>
<ul>
<li><strong><a href="/products/macbook-air-m5-13">MacBook Air 13 (M5)</a> — Rs 2,34,900.</strong> Our top recommendation for most people. <a href="/reviews/macbook-air-m5-13-review-nepal">Read the review</a>.</li>
<li><strong><a href="/products/asus-zenbook-14-oled-um3406ga">Asus Zenbook 14 OLED</a> — Rs 2,54,999.</strong> 32GB and 1TB with an OLED touchscreen. The Windows alternative.</li>
</ul>

<h2>For gaming</h2>
<ul>
<li><strong><a href="/products/hp-victus-15">HP Victus 15</a> — from Rs 1,49,000.</strong> The cheapest way into RTX gaming. Pick the RTX 4050 version. <a href="/reviews/hp-victus-15-review-nepal">Review</a>.</li>
<li><strong><a href="/products/lenovo-loq-15-2025">Lenovo LOQ 15</a> — Rs 1,94,999.</strong> An RTX 5050 at 100W and a 100% sRGB screen.</li>
<li><strong><a href="/products/acer-nitro-v-16-ai">Acer Nitro V 16 AI</a> — Rs 2,30,999</strong> and <strong><a href="/products/lenovo-legion-5-15ahp10">Lenovo Legion 5</a> — Rs 2,59,999.</strong> The mid-range step up.</li>
</ul>

<h2>For video editors and developers</h2>
<p>The <a href="/products/macbook-pro-14-m5">MacBook Pro 14 (M5)</a> (Rs 3,76,500) adds a fan and a 120Hz XDR display. Apple has also announced an <a href="/products/macbook-pro-14-m6">M6 model</a>, expected from around Rs 3,85,999, which isn't in Nepal yet.</p>
<p>Not sure between two? <a href="/compare">Compare any two laptops</a> side by side.</p>
HTML,
            'seo' => [
                'title' => 'Best Laptops in Nepal 2026: Students, Work & Gaming Picks',
                'description' => 'The best laptops in Nepal for 2026, from the Rs 1,34,999 MacBook Neo to RTX gaming laptops from Rs 1,49,000. Prices, picks and buying rules.',
                'keywords' => 'best laptop in nepal, best laptop for students nepal, gaming laptop price in nepal, best laptop under 1.5 lakh nepal',
            ],
        ],

        [
            'slug' => 'how-to-check-phone-is-official-in-nepal',
            'title' => 'How to Check a Phone Is Official in Nepal Before You Buy',
            'type' => 'how-to',
            'date' => '2026-11-23',
            'content' => <<<'HTML'
<p>Nepal blocks mobile phones that aren't registered with the Nepal Telecommunications Authority (NTA). That's the job of its Mobile Device Management System (MDMS). A cheap phone from an unofficial seller can stop working on Nepali networks, and it usually comes without a valid local warranty. These checks take five minutes in the shop.</p>

<h2>1. Find the IMEI</h2>
<p>Dial <strong>*#06#</strong> on the phone. It shows the IMEI number, or two numbers on a dual-SIM phone. Check that it matches the IMEI printed on the box, and the one under Settings › About phone.</p>

<h2>2. Check the IMEI with NTA</h2>
<p>Enter the IMEI on NTA's MDMS website (<a href="https://mdms.nta.gov.np" rel="nofollow noopener" target="_blank">mdms.nta.gov.np</a>). An officially imported phone should already be registered. If it isn't, ask the seller why before you pay.</p>

<h2>3. Ask for a VAT bill and the warranty card</h2>
<p>An official retailer gives you a VAT bill and the brand's warranty card, usually from the brand's authorised distributor in Nepal. Without these, warranty repairs can be refused.</p>

<h2>4. For iPhones, check Apple's coverage page</h2>
<p>Enter the serial number at <a href="https://checkcoverage.apple.com" rel="nofollow noopener" target="_blank">checkcoverage.apple.com</a>. It confirms the model and whether the warranty has already started. If it began months ago, the phone isn't new.</p>

<h2>5. Phones bought abroad</h2>
<p>If you bring a phone back from abroad, register it through the MDMS system so it keeps working on Nepali SIM cards. Follow the current instructions on NTA's site, because the process has changed over the years.</p>

<h2>Signs of a risky deal</h2>
<ul>
<li>A price far below what our product pages show. Compare, for example, the <a href="/products/iphone-17">iPhone 17</a> or <a href="/products/samsung-galaxy-s26-ultra">Galaxy S26 Ultra</a> pages.</li>
<li>The seller won't let you check the IMEI before paying.</li>
<li>The box has been resealed, or the IMEI on the box doesn't match the phone.</li>
</ul>
HTML,
            'seo' => [
                'title' => 'How to Check if a Phone Is Official in Nepal (IMEI & MDMS)',
                'description' => 'Check a phone\'s IMEI with *#06#, verify it on NTA\'s MDMS site, and get a VAT bill and warranty card. A five-minute guide to avoid blocked phones in Nepal.',
                'keywords' => 'check imei nepal, mdms nepal, nta phone registration, official phone nepal',
            ],
        ],
    ],
];
