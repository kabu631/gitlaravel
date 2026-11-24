<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Product search for the whole catalogue.
 *
 * The catalogue is small enough (a few thousand devices) to keep a compact search index in the cache and score
 * every device in PHP. That gives us what a plain SQL LIKE cannot: any word order, "rtx5060" = "rtx 5060",
 * typo correction from the catalogue's own vocabulary, spec matching ("32gb", "oled", "144hz"), price phrases
 * in Nepali terms ("under 1 lakh", "50k to 80k") and category words ("gaming laptop", "monitor").
 *
 * The index rebuilds itself whenever a device is added or updated (its cache key includes the catalogue's
 * row count and latest update time), so there is nothing to run after seeding.
 */
class CatalogSearch
{
    /** Words that say "which shelf", mapped to a category slug (and optionally an accessory type). */
    private const CATEGORY_WORDS = [
        'phone' => 'mobile', 'phones' => 'mobile', 'mobile' => 'mobile', 'mobiles' => 'mobile',
        'smartphone' => 'mobile', 'smartphones' => 'mobile',
        'laptop' => 'laptop', 'laptops' => 'laptop', 'notebook' => 'laptop', 'notebooks' => 'laptop',
        'tablet' => 'tablet', 'tablets' => 'tablet', 'tab' => 'tablet',
        'earbud' => 'earbuds', 'earbuds' => 'earbuds', 'earphone' => 'earbuds', 'earphones' => 'earbuds', 'tws' => 'earbuds',
        'headphone' => 'headphones', 'headphones' => 'headphones', 'headset' => 'headphones', 'headsets' => 'headphones',
        'smartwatch' => 'smartwatch', 'smartwatches' => 'smartwatch', 'watch' => 'smartwatch', 'watches' => 'smartwatch',
        'monitor' => 'monitor', 'monitors' => 'monitor',
        'desktop' => 'desktop', 'desktops' => 'desktop', 'aio' => 'desktop',
        'printer' => 'printer', 'printers' => 'printer',
        'projector' => 'projector', 'projectors' => 'projector',
        'speaker' => 'speaker', 'speakers' => 'speaker', 'soundbar' => 'speaker',
        'keyboard' => 'peripheral', 'keyboards' => 'peripheral', 'mouse' => 'peripheral', 'mice' => 'peripheral',
        'ssd' => 'storage', 'hdd' => 'storage', 'storage' => 'storage',
        'ups' => 'ups', 'tv' => 'tv', 'tvs' => 'tv', 'television' => 'tv',
        'drone' => 'drone', 'drones' => 'drone',
        'gpu' => 'pc-component', 'gpus' => 'pc-component',
        'console' => 'gaming', 'consoles' => 'gaming',
    ];

    /** Shelf words that also name a kind of product within the shelf: "mouse" should not return keyboards. */
    private const TYPE_WORDS = [
        'keyboard' => 'keyboard', 'keyboards' => 'keyboard', 'mouse' => 'mouse', 'mice' => 'mouse',
        'ssd' => 'ssd', 'hdd' => 'hard drive', 'headset' => 'headset', 'headsets' => 'headset',
        'soundbar' => 'soundbar', 'earphone' => 'earphone', 'earphones' => 'earphone',
    ];

    /** Multi-word shelf names, checked before single words. */
    private const CATEGORY_PHRASES = [
        'graphics card' => 'pc-component', 'graphic card' => 'pc-component', 'video card' => 'pc-component',
        'all in one' => 'desktop', 'mini pc' => 'desktop', 'hard drive' => 'storage', 'smart watch' => 'smartwatch',
        'smart tv' => 'tv', 'mobile phone' => 'mobile',
    ];

    private const STOPWORDS = [
        'a', 'an', 'the', 'for', 'with', 'and', 'of', 'in', 'on', 'to', 'at', 'by', 'is', 'my', 'me', 'i',
        'best', 'top', 'good', 'new', 'latest', 'price', 'prices', 'nepal', 'buy', 'online', 'rs', 'npr',
        'specs', 'specification', 'specifications', 'review', 'reviews', 'vs', 'which', 'what', 'model',
        // Spec labels people type but product data never repeats: "16gb ram", "15.6 inch screen".
        'ram', 'memory', 'inch', 'inches', 'screen', 'display', 'processor', 'cpu', 'chip', 'version', 'variant',
    ];

    private const CHEAP_WORDS = ['cheap', 'cheapest', 'budget', 'affordable', 'lowest', 'low'];

    /** Spec lines worth searching; the rest of the spec sheet is mostly noise for search. */
    private const EXTRA_KEYS = [
        'graphics', 'gpu', 'model', 'model name', 'model number', 'chipset', 'panel type', 'panel size', 'screen size',
        'refresh rate', 'refresh rate (hz)', 'resolution', 'panel resolution', 'curvature', 'hdr', 'hdr support',
        'capacity', 'interface', 'switch', 'switches', 'sensor', 'dpi', 'driver', 'noise cancellation', 'type',
    ];

    private static ?array $memo = null;

    /** @return array{docs: array<int, array>, vocab: array<string, int>} */
    public static function index(): array
    {
        if (self::$memo) {
            return self::$memo;
        }
        $signature = Gadget::query()->selectRaw('COUNT(*) AS n, MAX(updated_at) AS t')->first();
        $key = 'catalog-search:v1:' . $signature->n . ':' . strtotime((string) $signature->t);

        // File store: the index is a few MB, too large for the database cache's max packet size.
        return self::$memo = Cache::store('file')->remember($key, now()->addHours(6), function () {
            $docs = [];
            $vocab = [];
            Gadget::query()
                ->with(['brand:id,name,slug', 'category:id,name,slug', 'specs'])
                ->select(['id', 'name', 'slug', 'image', 'price', 'brand_id', 'category_id', 'release_status', 'accessory_type',
                    'is_featured', 'is_trending', 'views_count', 'created_at'])
                ->chunk(500, function ($chunk) use (&$docs, &$vocab) {
                    foreach ($chunk as $g) {
                        $specs = $g->specs;
                        $specText = '';
                        if ($specs) {
                            $specText = implode(' ', array_filter([$specs->processor, $specs->display, $specs->ram, $specs->storage, $specs->os]));
                            foreach ((array) $specs->extra_specs as $label => $value) {
                                if (in_array(strtolower(trim($label)), self::EXTRA_KEYS, true) && is_string($value)) {
                                    $specText .= ' ' . $value;
                                }
                            }
                        }
                        $name = self::normalize($g->name);
                        $brand = self::normalize($g->brand?->name ?? '');
                        $cat = self::normalize(($g->category?->name ?? '') . ' ' . ($g->category?->slug ?? '') . ' ' . str_replace('-', ' ', (string) $g->accessory_type));
                        $spec = self::normalize(Str::limit($specText, 900, ''));
                        $text = trim("$name $brand $cat $spec");
                        // A gaming laptop has a dedicated GPU, or belongs to a gaming product line.
                        $isGamingLaptop = $g->category?->slug === 'laptop' && (
                            preg_match('/\b(rtx|gtx|rx \d{4}|arc a\d)/', $spec . ' ' . $name)
                            || preg_match('/\b(loq|legion|tuf|rog|strix|zephyrus|nitro|predator|helios|victus|omen|katana|cyborg|alienware|aorus|thin gf|bravo|raider|vector|crosshair|sword|pulse|g15|g16)\b/', $name)
                        );

                        $docs[$g->id] = [
                            'id' => $g->id,
                            'slug' => $g->slug,
                            'name' => $name,
                            'brand' => $brand,
                            'cat' => $g->category?->slug,
                            'type' => $g->accessory_type,
                            'text' => " $text ",
                            'nameWords' => ' ' . $name . ' ',
                            'compact' => str_replace(' ', '', $text),
                            'nameCompact' => str_replace(' ', '', $name . $brand),
                            'price' => (float) $g->price,
                            'status' => $g->release_status,
                            'gaming' => (bool) $isGamingLaptop || in_array($g->category?->slug, ['gaming', 'games'], true),
                            'boost' => ($g->is_featured ? 2 : 0) + ($g->is_trending ? 2 : 0) + min(3, log10(1 + (int) $g->views_count)),
                            'created' => $g->created_at?->timestamp ?? 0,
                        ];
                        foreach (explode(' ', "$name $brand") as $w) {
                            if (strlen($w) >= 3 && ! ctype_digit($w)) {
                                $vocab[$w] = ($vocab[$w] ?? 0) + 1;
                            }
                        }
                    }
                });
            foreach (Category::pluck('name') as $c) {
                foreach (explode(' ', self::normalize($c)) as $w) {
                    $vocab[$w] = ($vocab[$w] ?? 0) + 5;
                }
            }
            foreach (array_keys(self::CATEGORY_WORDS) as $w) {
                $vocab[$w] = ($vocab[$w] ?? 0) + 5;
            }

            return ['docs' => $docs, 'vocab' => $vocab];
        });
    }

    /** Lower-case, strip marks and punctuation, keep letters and digits. */
    public static function normalize(string $s): string
    {
        $s = Str::lower(Str::ascii($s));
        $s = str_replace(['+', '&'], [' plus ', ' and '], $s);
        $s = preg_replace('/[^a-z0-9.]+/', ' ', $s);
        $s = preg_replace('/(?<!\d)\.|\.(?!\d)/', ' ', $s); // keep decimals like 15.6, drop other dots

        return trim(preg_replace('/\s+/', ' ', $s));
    }

    /**
     * Understand a query: price limits, category, intent words, and the remaining search terms.
     *
     * @return array{terms: array<int,string>, min: ?int, max: ?int, category: ?string, type: ?string, gaming: bool, cheap: bool, priceLabel: ?string}
     */
    public static function parse(string $query): array
    {
        $q = ' ' . Str::lower(Str::ascii($query)) . ' ';
        $q = preg_replace('/(rs\.?|npr|nrs)\s*/', ' ', $q);
        $q = str_replace([',', '₹'], '', $q);

        $amount = '(\d+(?:\.\d+)?)\s*(k|thousand|hajar|hazar|lakh|lakhs|lac|lacs|l)?';
        $min = $max = null;

        if (preg_match("/\b(?:between|from)?\s*$amount\s*(?:to|-|and)\s*$amount\b/", $q, $m) && (! empty($m[2]) || ! empty($m[4]) || $m[1] > 999)) {
            $unit = ! empty($m[2]) ? $m[2] : ($m[4] ?? '');
            $min = self::money($m[1], $unit);
            $max = self::money($m[3], $m[4] ?? $unit);
            $q = str_replace($m[0], ' ', $q);
        }
        if (preg_match("/\b(?:under|below|less than|within|upto|up to|max|maximum|<)\s*$amount\b/", $q, $m)) {
            $max = self::money($m[1], $m[2] ?? '');
            $q = str_replace($m[0], ' ', $q);
        }
        if (preg_match("/\b(?:above|over|more than|min|minimum|starting|>)\s*$amount\b/", $q, $m)) {
            $min = self::money($m[1], $m[2] ?? '');
            $q = str_replace($m[0], ' ', $q);
        }
        // A bare amount with a unit ("1 lakh laptop") means "around that price".
        // "2k"/"4k"/"8k" are resolutions, so a bare "k" amount only counts from 10k up.
        if ($min === null && $max === null && preg_match('/\b(\d+(?:\.\d+)?)\s*(k|thousand|hajar|lakh|lakhs|lac|lacs)\b/', $q, $m)
            && ($m[2] !== 'k' || (float) $m[1] >= 10)) {
            $v = self::money($m[1], $m[2]);
            [$min, $max] = [(int) round($v * 0.85), (int) round($v * 1.1)];
            $q = str_replace($m[0], ' ', $q);
        }

        $category = $type = null;
        foreach (self::CATEGORY_PHRASES as $phrase => $slug) {
            if (str_contains($q, " $phrase ")) {
                $category = $slug;
                $q = str_replace(" $phrase ", ' ', $q);
            }
        }

        $terms = [];
        $gaming = $cheap = false;
        foreach (explode(' ', self::normalize($q)) as $w) {
            if ($w === '') {
                continue;
            }
            if (in_array($w, self::CHEAP_WORDS, true)) {
                $cheap = true;
                continue;
            }
            if ($w === 'gaming' || $w === 'gamer') {
                $gaming = true;
                continue;
            }
            if (! $category && isset(self::CATEGORY_WORDS[$w])) {
                $category = self::CATEGORY_WORDS[$w];
                $type = self::TYPE_WORDS[$w] ?? null;
                continue;
            }
            if (in_array($w, self::STOPWORDS, true)) {
                continue;
            }
            $terms[] = $w;
        }
        // "gaming" alone means gaming laptops; with "mouse" or "monitor" it is just a word to match.
        if ($gaming && $category && $category !== 'laptop') {
            $terms[] = 'gaming';
            $gaming = false;
        } elseif ($gaming && ! $category) {
            $category = 'laptop';
        }

        return [
            'terms' => array_values(array_unique($terms)),
            'min' => $min,
            'max' => $max,
            'category' => $category,
            'type' => $type,
            'gaming' => $gaming,
            'cheap' => $cheap,
            'priceLabel' => self::priceLabel($min, $max),
        ];
    }

    private static function money(string $n, ?string $unit): int
    {
        $v = (float) $n;
        $unit = strtolower((string) $unit);

        return (int) round(match (true) {
            in_array($unit, ['k', 'thousand', 'hajar', 'hazar'], true) => $v * 1000,
            in_array($unit, ['lakh', 'lakhs', 'lac', 'lacs', 'l'], true) => $v * 100000,
            default => $v,
        });
    }

    private static function priceLabel(?int $min, ?int $max): ?string
    {
        $fmt = fn ($v) => 'Rs ' . self::nepaliNumber($v);

        return match (true) {
            $min !== null && $max !== null => $fmt($min) . ' – ' . $fmt($max),
            $max !== null => 'Under ' . $fmt($max),
            $min !== null => 'Above ' . $fmt($min),
            default => null,
        };
    }

    public static function nepaliNumber(int $v): string
    {
        $s = (string) $v;
        if (strlen($s) <= 3) {
            return $s;
        }
        $last3 = substr($s, -3);
        $rest = substr($s, 0, -3);

        return preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest) . ',' . $last3;
    }

    /**
     * Run a search.
     *
     * @param  array{category?: ?string, sort?: ?string}  $options  explicit filters from the results page
     * @return array{ids: array<int,int>, total: int, facets: array<string,int>, parsed: array, corrected: ?string, partial: bool}
     */
    public static function search(string $query, array $options = []): array
    {
        $index = self::index();
        $parsed = self::parse($query);
        $docs = $index['docs'];

        // Typo correction: a term no device contains is swapped for the closest catalogue word.
        // A word no device contains and that is not a typo ("students", "office") is dropped rather than
        // allowed to empty the results; the page says which words were ignored.
        $corrections = [];
        $ignored = [];
        foreach ($parsed['terms'] as $i => $term) {
            if (self::termMatchesAny($term, $docs)) {
                continue;
            }
            $fix = self::closestWord($term, $index['vocab']);
            if ($fix) {
                $corrections[$term] = $fix;
                $parsed['terms'][$i] = $fix;
            } else {
                $ignored[] = $term;
                unset($parsed['terms'][$i]);
            }
        }
        $parsed['terms'] = array_values($parsed['terms']);
        if (! $parsed['terms'] && ! $parsed['category'] && $parsed['min'] === null && $parsed['max'] === null && ! $parsed['gaming']) {
            return ['ids' => [], 'total' => 0, 'facets' => [], 'parsed' => $parsed, 'corrected' => null, 'partial' => false, 'ignored' => $ignored];
        }

        $explicit = $options['category'] ?? null;
        $category = $explicit ?: $parsed['category'];
        $scored = self::score($docs, $parsed, null);
        $partial = false;
        if (! $scored && count($parsed['terms']) > 1) {
            $scored = self::score($docs, $parsed, (int) ceil(count($parsed['terms']) / 2));
            $partial = (bool) $scored;
        }
        if ($parsed['gaming']) {
            $scored = array_filter($scored, fn ($s, $id) => $docs[$id]['gaming'], ARRAY_FILTER_USE_BOTH) ?: $scored;
        }
        // Counts per shelf for the filter chips, before narrowing to one shelf.
        $facets = [];
        foreach ($scored as $id => $s) {
            $c = $docs[$id]['cat'];
            $facets[$c] = ($facets[$c] ?? 0) + 1;
        }
        arsort($facets);
        if ($category) {
            $filtered = array_filter($scored, fn ($s, $id) => $docs[$id]['cat'] === $category, ARRAY_FILTER_USE_BOTH);
            // "mouse" narrows Keyboards & Mice to devices with "mouse" in the name.
            if ($parsed['type']) {
                $needle = str_replace(' ', '', $parsed['type']);
                $typed = array_filter($filtered, fn ($s, $id) => str_contains($docs[$id]['nameCompact'], $needle), ARRAY_FILTER_USE_BOTH);
                $filtered = $typed ?: $filtered;
            }
            if (! $filtered && ! $explicit && $parsed['terms']) {
                // "office monitor": no monitor says "office", so keep the shelf and set aside the words it lacks.
                $shelf = array_filter($docs, fn ($d) => $d['cat'] === $category);
                $keep = array_values(array_filter($parsed['terms'], function ($t) use ($shelf) {
                    foreach ($shelf as $d) {
                        if (self::termScore($t, $d) > 0) {
                            return true;
                        }
                    }

                    return false;
                }));
                if ($shelf && count($keep) < count($parsed['terms'])) {
                    $ignored = array_merge($ignored, array_diff($parsed['terms'], $keep));
                    $filtered = self::score($shelf, ['terms' => $keep] + $parsed, null);
                }
            }
            if ($filtered || $explicit) {
                $scored = $filtered;
            } else {
                $category = null; // the shelf word matched nothing at all, so search everything
            }
        }
        $sort = $options['sort'] ?? ($parsed['cheap'] ? 'price_asc' : 'relevance');
        $ids = array_keys($scored);
        usort($ids, function ($a, $b) use ($scored, $docs, $sort) {
            $pa = $docs[$a]['price'] ?: PHP_INT_MAX;
            $pb = $docs[$b]['price'] ?: PHP_INT_MAX;

            return match ($sort) {
                'price_asc' => [$pa, -$scored[$a]] <=> [$pb, -$scored[$b]],
                'price_desc' => [$docs[$b]['price'], $scored[$b]] <=> [$docs[$a]['price'], $scored[$a]],
                'newest' => $docs[$b]['created'] <=> $docs[$a]['created'],
                default => $scored[$b] <=> $scored[$a],
            };
        });

        $correctedQuery = null;
        if ($corrections) {
            $correctedQuery = trim(preg_replace_callback('/\S+/', fn ($m) => $corrections[self::normalize($m[0])] ?? $m[0], $query));
        }

        $parsed['category'] = $category;

        return [
            'ids' => $ids,
            'total' => count($ids),
            'facets' => $facets,
            'parsed' => $parsed,
            'corrected' => $correctedQuery,
            'partial' => $partial,
            'ignored' => $ignored,
        ];
    }

    /** @return array<int, float> id => score, only for devices that match */
    private static function score(array $docs, array $parsed, ?int $minMatched): array
    {
        $terms = $parsed['terms'];
        $phrase = implode(' ', $terms);
        $out = [];
        foreach ($docs as $id => $d) {
            if ($parsed['min'] !== null || $parsed['max'] !== null) {
                if ($d['price'] <= 0) {
                    continue;
                }
                if ($parsed['min'] !== null && $d['price'] < $parsed['min']) {
                    continue;
                }
                if ($parsed['max'] !== null && $d['price'] > $parsed['max']) {
                    continue;
                }
            }
            if ($parsed['category'] && ! $terms && $d['cat'] !== $parsed['category']) {
                continue;
            }

            $score = 0.0;
            $matched = 0;
            foreach ($terms as $i => $t) {
                $s = self::termScore($t, $d, $terms[$i - 1] ?? null);
                if ($s > 0) {
                    $matched++;
                    $score += $s;
                }
            }
            $need = $minMatched ?? count($terms);
            if ($terms && $matched < $need) {
                continue;
            }

            if ($phrase !== '') {
                if ($d['name'] === $phrase || $d['name'] === trim($d['brand'] . ' ' . $phrase)) {
                    $score += 40;
                } elseif (str_starts_with($d['name'], $phrase) || str_starts_with($d['name'], $d['brand'] . ' ' . $phrase)) {
                    $score += 18;
                } elseif (str_contains($d['nameWords'], " $phrase ")) {
                    $score += 12;
                }
                // Shorter names win ties: "iPhone 17" before "iPhone 17 Pro Max" for the query "iphone 17".
                $score += 6 / max(1, substr_count($d['name'], ' ') + 1 - count($terms) + 1);
            }

            $score += match ($d['status']) {
                'available' => 5,
                'announced', 'upcoming' => 2,
                default => -4,
            };
            if ($d['price'] > 0) {
                $score += 1;
            }
            $score += $d['boost'];
            $out[$id] = $score;
        }

        return $out;
    }

    private static function termScore(string $t, array $d, ?string $prev = null): float
    {
        $isNum = ctype_digit($t);
        if (str_contains($d['nameWords'], " $t ")) {
            return 10;
        }
        // A bare number is a model number: it must be in the name ("iphone 17"), or follow the word it belongs to
        // in the specs ("rtx 5060"). Otherwise "iphone 17" would also match an iPhone 15 running "iOS 17".
        if ($isNum) {
            if (str_contains($d['nameCompact'], $t) && strlen($t) >= 3) {
                return 5;
            }

            return $prev && str_contains($d['compact'], $prev . $t) ? 3 : 0;
        }
        if (! $isNum && strlen($t) >= 2 && preg_match('/ ' . preg_quote($t, '/') . '/', $d['nameWords'])) {
            return 7; // prefix of a word in the name: "zen" → zenbook
        }
        if (str_contains(' ' . $d['brand'] . ' ', " $t ")) {
            return 7;
        }
        if (strlen($t) >= 3 && str_contains($d['nameCompact'], $t)) {
            return 6; // "rtx5060", "iphone17", "s26ultra"
        }
        if (str_contains($d['text'], " $t ")) {
            return 3; // spec or category word: "oled", "32gb", "monitor"
        }
        if (! $isNum && strlen($t) >= 3 && preg_match('/ ' . preg_quote($t, '/') . '/', $d['text'])) {
            return 2;
        }
        if (strlen($t) >= 4 && str_contains($d['compact'], $t)) {
            return 1.5;
        }

        return 0;
    }

    private static function termMatchesAny(string $t, array $docs): bool
    {
        if (ctype_digit($t) || strlen($t) < 4) {
            return true; // never "correct" numbers or short model codes
        }
        foreach ($docs as $d) {
            if (str_contains($d['compact'], $t)) {
                return true;
            }
        }

        return false;
    }

    private static function closestWord(string $term, array $vocab): ?string
    {
        // One edit for short words ("lenvo" → lenovo), two for long ones ("samsnug" → samsung).
        $maxDist = strlen($term) >= 7 ? 2 : 1;
        if (strlen($term) >= 5) {
            $maxDist = max($maxDist, 1);
        }
        $best = null;
        $bestDist = PHP_INT_MAX;
        $bestFreq = 0;
        foreach ($vocab as $word => $freq) {
            $word = (string) $word;
            if (abs(strlen($word) - strlen($term)) > $maxDist) {
                continue;
            }
            $dist = levenshtein($term, $word);
            if ($dist > $maxDist) {
                continue;
            }
            if ($dist < $bestDist || ($dist === $bestDist && $freq > $bestFreq)) {
                [$best, $bestDist, $bestFreq] = [$word, $dist, $freq];
            }
        }

        return $best;
    }

    /** Brands whose name matches the query, for "Brands" results and suggestions. */
    public static function brands(string $query, int $limit = 4)
    {
        $terms = self::parse($query)['terms'];
        if (! $terms) {
            return collect();
        }

        return Brand::query()
            ->where(function ($q) use ($terms) {
                foreach ($terms as $t) {
                    $q->orWhere('name', 'like', $t . '%');
                }
            })
            ->withCount('gadgets')
            ->orderByDesc('gadgets_count')
            ->take($limit)
            ->get(['id', 'name', 'slug', 'logo']);
    }
}
