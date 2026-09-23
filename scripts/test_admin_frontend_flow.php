<?php

/**
 * Git Infosys — Live HTTP & End-to-End Integration Test Suite
 * Tests Filament Admin resources, authentication, and live data flow to user frontend pages.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\Slider;
use App\Models\TechGuide;
use App\Models\UpcomingLaunch;
use App\Models\User;
use Illuminate\Support\Str;

$baseUrl = 'http://127.0.0.1:8000';
$cookieFile = tempnam(sys_get_temp_dir(), 'gi_cookies_');

$passed = 0;
$failed = 0;
$testResults = [];

function runTest(string $name, callable $test) {
    global $passed, $failed, $testResults;
    echo "\n------------------------------------------------------------\n";
    echo "RUNNING: $name\n";
    $start = microtime(true);
    try {
        $test();
        $duration = round((microtime(true) - $start) * 1000, 1);
        echo "✅ PASS ($duration ms)\n";
        $passed++;
        $testResults[] = ['name' => $name, 'status' => 'PASS', 'duration' => $duration];
    } catch (Throwable $e) {
        $duration = round((microtime(true) - $start) * 1000, 1);
        echo "❌ FAIL ($duration ms): " . $e->getMessage() . "\n";
        echo "   at " . $e->getFile() . ":" . $e->getLine() . "\n";
        $failed++;
        $testResults[] = ['name' => $name, 'status' => 'FAIL', 'duration' => $duration, 'error' => $e->getMessage()];
    }
}

function assertCondition($condition, string $message = 'Assertion failed') {
    if (!$condition) {
        throw new Exception($message);
    }
}

function httpGet(string $path, array $queryParams = [], bool $useCookies = false): array {
    global $baseUrl, $cookieFile;
    $url = $baseUrl . $path;
    if (!empty($queryParams)) {
        $url .= '?' . http_build_query($queryParams);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($useCookies) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    } else {
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        ]);
    }
    
    $raw = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    $headerOut = curl_getinfo($ch, CURLINFO_HEADER_OUT);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body = substr($raw, $headerSize);
    curl_close($ch);
    
    $inertiaData = null;
    if ($body && preg_match('/data-page="([^"]+)"/', $body, $matches)) {
        $inertiaData = json_decode(htmlspecialchars_decode($matches[1]), true);
    }
    
    return [
        'status' => $status,
        'redirect' => $redirectUrl,
        'body' => $body,
        'inertia' => $inertiaData,
        'headerOut' => $headerOut,
        'headerIn' => substr($raw, 0, $headerSize)
    ];
}

echo "============================================================\n";
echo "GIT INFOSYS — LIVE ADMIN & USER PANEL INTEGRATION TEST SUITE\n";
echo "Target Base URL: $baseUrl\n";
echo "============================================================\n";

// ── TEST 1: Filament Admin Authentication & Resource Routing ─────────────────
runTest("Test 1: Admin Livewire Authentication & Filament Resource Access via HTTP", function() use ($baseUrl, $cookieFile) {
    // 1. Get login page to extract CSRF token and snapshot
    $ch = curl_init("$baseUrl/secure-admin/login");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $html = curl_exec($ch);
    curl_close($ch);
    
    preg_match('/name="csrf-token" content="([^"]+)"/', $html, $tokenMatch);
    $csrfToken = $tokenMatch[1] ?? '';
    assertCondition(!empty($csrfToken), "Could not extract CSRF token from admin login page");
    
    preg_match('/wire:snapshot="([^"]+)"/', $html, $snapshotMatch);
    $snapshot = htmlspecialchars_decode($snapshotMatch[1] ?? '');
    assertCondition(!empty($snapshot), "Could not extract Livewire snapshot from admin login page");
    
    // 2. Perform Livewire authenticate call
    $updateUrl = route('default-livewire.update');
    $payload = [
        '_token' => $csrfToken,
        'components' => [
            [
                'snapshot' => $snapshot,
                'updates' => [
                    'data.email' => 'admin@gitinfosys.com',
                    'data.password' => 'password'
                ],
                'calls' => [
                    [
                        'path' => '',
                        'method' => 'authenticate',
                        'params' => []
                    ]
                ]
            ]
        ]
    ];
    
    $ch = curl_init($updateUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Livewire: true',
        'X-CSRF-TOKEN: ' . $csrfToken
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    assertCondition($code === 200, "Livewire authenticate request failed with HTTP $code");
    echo "   Authenticated successfully via Livewire as admin@gitinfosys.com\n";
    
    // 3. Verify all Filament resource index pages respond with 200 OK
    $adminResources = [
        '/secure-admin' => 'Dashboard',
        '/secure-admin/gadgets' => 'Gadgets Resource',
        '/secure-admin/reviews' => 'Reviews Resource',
        '/secure-admin/news-articles' => 'News Articles Resource',
        '/secure-admin/tech-guides' => 'Tech Guides Resource',
        '/secure-admin/upcoming-launches' => 'Upcoming Launches Resource',
        '/secure-admin/sliders' => 'Sliders Resource',
        '/secure-admin/brands' => 'Brands Resource',
        '/secure-admin/categories' => 'Categories Resource',
    ];
    
    foreach ($adminResources as $uri => $label) {
        $ch = curl_init("$baseUrl$uri");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        assertCondition($status === 200, "Admin resource '$label' ($uri) returned HTTP $status instead of 200");
        echo "   - $label ($uri): HTTP 200 OK\n";
    }
});

// ── TEST 2: Upcoming Launches Flow ──────────────────────────────────────────
runTest("Test 2: Upcoming Launches Creation & Active/Inactive Homepage Sync", function() {
    $uniqueTag = "AutoPhone_" . Str::random(5);
    $launchName = "OnePlus 13 Pro Max ($uniqueTag)";
    
    // A: Add active launch item
    $launch = UpcomingLaunch::create([
        'name' => $launchName,
        'brand' => 'OnePlus',
        'category' => 'Smartphones',
        'expected_date' => 'November 2026',
        'est_price' => 'Rs. 1,65,000',
        'confidence' => 90,
        'is_active' => true,
        'sort_order' => 0,
    ]);
    
    try {
        $home = httpGet('/');
        assertCondition($home['status'] === 200, "Homepage returned status {$home['status']}");
        $launches = $home['inertia']['props']['upcomingLaunches'] ?? [];
        $launchNames = collect($launches)->pluck('name')->toArray();
        assertCondition(in_array($launchName, $launchNames), "New launch '$launchName' was not found in homepage upcomingLaunches prop. First 3: " . implode(', ', array_slice($launchNames, 0, 3)));
        echo "   Launch '$launchName' appeared on homepage successfully\n";
        
        // B: Toggle active to false
        $launch->update(['is_active' => false]);
        
        $home2 = httpGet('/');
        $launches2 = $home2['inertia']['props']['upcomingLaunches'] ?? [];
        $launchNames2 = collect($launches2)->pluck('name')->toArray();
        assertCondition(!in_array($launchName, $launchNames2), "Deactivated launch '$launchName' should not appear in homepage upcomingLaunches");
        echo "   Launch correctly disappeared when toggled to inactive\n";
    } finally {
        $launch->delete();
    }
});

// ── TEST 3: News Article Category Flow ──────────────────────────────────────
runTest("Test 3: News Article Creation, Category Filtering & Homepage Sync", function() {
    $slug = "quantum-leap-" . Str::random(8);
    $article = NewsArticle::create([
        'title' => "Quantum Silicon Breakthrough ($slug)",
        'slug' => $slug,
        'category' => 'technology',
        'summary' => "Testing technology category filtering across admin and user panel",
        'content' => "Long form content discussing quantum computing advancements in Nepal.",
        'author' => "Lead Tech Editor",
        'is_published' => true,
    ]);
    
    try {
        // A: Check homepage latest news
        $home = httpGet('/');
        $homeNewsSlugs = collect($home['inertia']['props']['news'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($slug, $homeNewsSlugs), "Article '$slug' not found in homepage news prop");
        echo "   Article appeared in homepage Latest News section\n";
        
        // B: Check /news?category=technology
        $techNews = httpGet('/news', ['category' => 'technology']);
        $techSlugs = collect($techNews['inertia']['props']['articles']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($slug, $techSlugs), "Article '$slug' not found in /news?category=technology");
        echo "   Article appeared in /news?category=technology\n";
        
        // C: Check /news?category=ai-ml (should NOT appear)
        $aiNews = httpGet('/news', ['category' => 'ai-ml']);
        $aiSlugs = collect($aiNews['inertia']['props']['articles']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($slug, $aiSlugs), "Article '$slug' should NOT be under category ai-ml");
        echo "   Article correctly isolated from /news?category=ai-ml\n";
        
        // D: Unpublished toggle
        $article->update(['is_published' => false]);
        $unpubNews = httpGet('/news', ['category' => 'technology']);
        $unpubSlugs = collect($unpubNews['inertia']['props']['articles']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($slug, $unpubSlugs), "Unpublished article should NOT be visible on public news page");
        echo "   Unpublished article is correctly hidden from public news\n";
    } finally {
        $article->delete();
    }
});

// ── TEST 4: Tech Guides Type Flow ───────────────────────────────────────────
runTest("Test 4: Tech Guides Creation, Type Filter ('buying-guide' vs 'how-to')", function() {
    $slug = "coding-laptops-guide-" . strtolower(Str::random(8));
    $guide = TechGuide::create([
        'title' => "Ultimate Laptop Buying Guide 2026 ($slug)",
        'slug' => $slug,
        'type' => 'buying-guide',
        'content' => "Extensive buying advice content for software professionals in Nepal.",
        'is_published' => true,
    ]);
    
    try {
        // A: Check all guides
        $allGuides = httpGet('/guides');
        $allSlugs = collect($allGuides['inertia']['props']['guides']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($guide->slug, $allSlugs), "Guide '{$guide->slug}' not found in /guides index");
        echo "   Guide appeared on /guides index\n";
        
        // B: Check /guides?type=buying-guide
        $bgGuides = httpGet('/guides', ['type' => 'buying-guide']);
        $bgSlugs = collect($bgGuides['inertia']['props']['guides']['data'] ?? [])->pluck('slug')->toArray();
        if (!in_array($guide->slug, $bgSlugs)) {
            echo "   [DEBUG] Expected: '{$guide->slug}', found in bgSlugs: " . implode(', ', $bgSlugs) . "\n";
        }
        assertCondition(in_array($guide->slug, $bgSlugs), "Guide '{$guide->slug}' not found in /guides?type=buying-guide");
        echo "   Guide appeared under /guides?type=buying-guide\n";
        
        // C: Check /guides?type=how-to (should NOT appear)
        $htGuides = httpGet('/guides', ['type' => 'how-to']);
        $htSlugs = collect($htGuides['inertia']['props']['guides']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($slug, $htSlugs), "Guide '$slug' should NOT appear under /guides?type=how-to");
        echo "   Guide correctly isolated from /guides?type=how-to\n";
        
        // D: Unpublished toggle
        $guide->update(['is_published' => false]);
        $unpubGuides = httpGet('/guides', ['type' => 'buying-guide']);
        $unpubSlugs = collect($unpubGuides['inertia']['props']['guides']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($slug, $unpubSlugs), "Unpublished guide should NOT appear on public guides page");
        echo "   Unpublished guide correctly hidden from public site\n";
    } finally {
        $guide->delete();
    }
});

// ── TEST 5: Reviews Rating & Editor's Choice Filter ─────────────────────────
runTest("Test 5: Reviews Rating & Editor's Choice Filter (rating >= 8.0)", function() {
    $brand = Brand::first();
    $cat = Category::first();
    $admin = User::where('is_admin', true)->first();
    
    $gadget = Gadget::create([
        'name' => 'Review Target Phone ' . Str::random(4),
        'slug' => 'review-target-phone-' . Str::random(8),
        'brand_id' => $brand->id,
        'category_id' => $cat->id,
        'price' => 88000,
    ]);
    
    $topReview = Review::create([
        'gadget_id' => $gadget->id,
        'user_id' => $admin->id,
        'title' => 'Stellar Performance & Display ' . Str::random(4),
        'slug' => 'stellar-performance-' . Str::random(8),
        'rating' => 9.4,
        'content' => 'Exceptional build, cameras and battery life.',
        'verdict' => 'Highly recommended flagship.',
        'is_published' => true,
    ]);
    
    $midReview = Review::create([
        'gadget_id' => $gadget->id,
        'user_id' => $admin->id,
        'title' => 'Average Midranger ' . Str::random(4),
        'slug' => 'average-midranger-' . Str::random(8),
        'rating' => 6.5,
        'content' => 'Decent performance but underwhelming camera.',
        'verdict' => 'Wait for discounts.',
        'is_published' => true,
    ]);
    
    try {
        // A: Check editors-choice filter (rating >= 8.0)
        $ecRes = httpGet('/reviews', ['filter' => 'editors-choice']);
        $ecSlugs = collect($ecRes['inertia']['props']['reviews']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($topReview->slug, $ecSlugs), "Review with rating 9.4 ('{$topReview->slug}') not found in /reviews?filter=editors-choice");
        assertCondition(!in_array($midReview->slug, $ecSlugs), "Review with rating 6.5 ('{$midReview->slug}') should NOT appear in /reviews?filter=editors-choice");
        echo "   Editor's choice filter correctly included rating 9.4 and excluded rating 6.5\n";
        
        // B: Check /reviews/{slug} detail page
        $detailRes = httpGet('/reviews/' . $topReview->slug);
        assertCondition($detailRes['status'] === 200, "Review detail page returned HTTP {$detailRes['status']}");
        $detailReview = $detailRes['inertia']['props']['review'] ?? null;
        assertCondition($detailReview !== null, "Review prop missing on review detail page");
        echo "   Review detail page /reviews/{$topReview->slug} loaded with status 200\n";
        
        // C: Unpublished toggle
        $topReview->update(['is_published' => false]);
        $ecRes2 = httpGet('/reviews', ['filter' => 'editors-choice']);
        $ecSlugs2 = collect($ecRes2['inertia']['props']['reviews']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($topReview->slug, $ecSlugs2), "Unpublished review should NOT appear in /reviews?filter=editors-choice");
        echo "   Unpublished review correctly hidden\n";
    } finally {
        $topReview->delete();
        $midReview->delete();
        $gadget->delete();
    }
});

// ── TEST 6: Gadgets Catalog, Homepage Featured/Trending & buy_url ───────────
runTest("Test 6: Gadget Featured/Trending & buy_url Display on Detail Page", function() {
    $brand = Brand::first();
    $cat = Category::first();
    $slug = 'flagship-device-' . Str::random(8);
    $buyUrl = 'https://daraz.com.np/test-product-' . Str::random(5);
    
    $gadget = Gadget::create([
        'name' => 'Flagship Test Mobile ' . Str::random(4),
        'slug' => $slug,
        'brand_id' => $brand->id,
        'category_id' => $cat->id,
        'price' => 119999,
        'old_price' => 139999,
        'buy_url' => $buyUrl,
        'is_featured' => true,
        'is_trending' => true,
    ]);
    
    try {
        // A: Check homepage featured & trending props
        $home = httpGet('/');
        $featuredSlugs = collect($home['inertia']['props']['featured'] ?? [])->pluck('slug')->toArray();
        $trendingSlugs = collect($home['inertia']['props']['trending'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($slug, $featuredSlugs), "Gadget '$slug' not found in homepage featured prop");
        assertCondition(in_array($slug, $trendingSlugs), "Gadget '$slug' not found in homepage trending prop");
        echo "   Gadget appeared in homepage Featured and Trending sections\n";
        
        // B: Check /gadgets catalog (which uses GadgetController@index)
        $catalog = httpGet('/gadgets');
        $catalogSlugs = collect($catalog['inertia']['props']['gadgets']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($slug, $catalogSlugs), "Gadget '$slug' not found in /gadgets catalog. First 3: " . implode(', ', array_slice($catalogSlugs, 0, 3)));
        echo "   Gadget appeared in /gadgets catalog\n";
        
        // C: Check gadget detail page /gadgets/{slug} and buy_url prop
        $detail = httpGet('/gadgets/' . $slug);
        assertCondition($detail['status'] === 200, "Gadget detail page returned HTTP {$detail['status']}");
        $detailGadget = $detail['inertia']['props']['gadget'] ?? null;
        assertCondition($detailGadget !== null, "Gadget prop missing on detail page");
        assertCondition(($detailGadget['buy_url'] ?? null) === $buyUrl, "buy_url on detail page does not match expected '$buyUrl'");
        echo "   Gadget detail page loaded successfully with correct buy_url: $buyUrl\n";
        
        // D: Turn off featured/trending
        $gadget->update(['is_featured' => false, 'is_trending' => false]);
        $home2 = httpGet('/');
        $featuredSlugs2 = collect($home2['inertia']['props']['featured'] ?? [])->pluck('slug')->toArray();
        $trendingSlugs2 = collect($home2['inertia']['props']['trending'] ?? [])->pluck('slug')->toArray();
        assertCondition(!in_array($slug, $featuredSlugs2), "Gadget should NOT be in featured when is_featured=false");
        assertCondition(!in_array($slug, $trendingSlugs2), "Gadget should NOT be in trending when is_trending=false");
        echo "   Gadget correctly removed from homepage when toggled off\n";
    } finally {
        $gadget->delete();
    }
});

// ── TEST 7: Sliders Flow ────────────────────────────────────────────────────
runTest("Test 7: Hero Slider Active/Inactive Status on Homepage", function() {
    $tag = "Banner_" . Str::random(5);
    $slider = Slider::create([
        'title' => "Special Grand Tech Fest ($tag)",
        'subtitle' => "Massive discounts on premium gear",
        'link' => "/gadgets",
        'button_text' => "Shop Now",
        'is_active' => true,
        'sort_order' => 0,
    ]);
    
    try {
        $home = httpGet('/');
        $sliders = collect($home['inertia']['props']['sliders'] ?? [])->pluck('title')->toArray();
        assertCondition(in_array($slider->title, $sliders), "Active slider '{$slider->title}' not found in homepage sliders prop");
        echo "   Active slider appeared on homepage hero\n";
        
        $slider->update(['is_active' => false]);
        $home2 = httpGet('/');
        $sliders2 = collect($home2['inertia']['props']['sliders'] ?? [])->pluck('title')->toArray();
        assertCondition(!in_array($slider->title, $sliders2), "Deactivated slider should NOT be in homepage sliders prop");
        echo "   Deactivated slider correctly removed from homepage hero\n";
    } finally {
        $slider->delete();
    }
});

// ── TEST 8: Brand & Category Filter Connectivity ────────────────────────────
runTest("Test 8: Brand and Category Filter Connectivity on /gadgets", function() {
    $brandSlug = "filterbrand-" . Str::random(6);
    $catSlug = "filtercat-" . Str::random(6);
    
    $brand = Brand::create(['name' => "Brand $brandSlug", 'slug' => $brandSlug]);
    $category = Category::create(['name' => "Category $catSlug", 'slug' => $catSlug]);
    
    $gadget = Gadget::create([
        'name' => "Filtered Gadget " . Str::random(4),
        'slug' => "filtered-gadget-" . Str::random(8),
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'price' => 65000,
    ]);
    
    try {
        // Filter by Brand
        $brandRes = httpGet('/gadgets', ['brand' => $brandSlug]);
        $brandItems = collect($brandRes['inertia']['props']['gadgets']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($gadget->slug, $brandItems), "Gadget with brand '$brandSlug' not found when filtering by that brand");
        echo "   Brand filter correctly found gadget: $brandSlug\n";
        
        // Filter by Category
        $catRes = httpGet('/gadgets', ['category' => $catSlug]);
        $catItems = collect($catRes['inertia']['props']['gadgets']['data'] ?? [])->pluck('slug')->toArray();
        assertCondition(in_array($gadget->slug, $catItems), "Gadget with category '$catSlug' not found when filtering by that category");
        echo "   Category filter correctly found gadget: $catSlug\n";
    } finally {
        $gadget->delete();
        $brand->delete();
        $category->delete();
    }
});

if (file_exists($cookieFile)) unlink($cookieFile);

echo "\n============================================================\n";
echo "SUMMARY OF LIVE INTEGRATION RESULTS\n";
echo "============================================================\n";
echo "TOTAL: " . ($passed + $failed) . " | PASSED: $passed | FAILED: $failed\n";

if ($failed > 0) {
    echo "\nFAILED TESTS:\n";
    foreach ($testResults as $r) {
        if ($r['status'] === 'FAIL') {
            echo "- {$r['name']}: {$r['error']}\n";
        }
    }
    exit(1);
} else {
    echo "\n🎉 ALL 8 ADMIN-TO-FRONTEND INTEGRATION TESTS PASSED PERFECTLY!\n";
    exit(0);
}
