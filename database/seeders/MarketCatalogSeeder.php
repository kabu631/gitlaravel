<?php

namespace Database\Seeders;

use App\Models\AccessoryType;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\Slider;
use App\Models\TechGuide;
use App\Models\UpcomingLaunch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Nepal market catalogue, researched November 2026.
 *
 * Content lives in database/seeders/data/catalog/*.php so editors can review it in one place.
 * The seeder is idempotent: records are matched by slug and updated in place, so it can be
 * re-run after correcting a price or a spec. Gadgets that are not part of this dataset are only
 * touched to clear their featured/trending flags (the new line-up takes over those slots).
 *
 *   php artisan db:seed --class=MarketCatalogSeeder
 */
class MarketCatalogSeeder extends Seeder
{
    private const DATA_DIR = __DIR__ . '/data/catalog';

    private ?int $authorId = null;

    /** @var array<string, string> slug => official page each product image came from */
    private array $imageCredits = [];
    private array $imageAttributions = [];

    public function run(): void
    {
        $gadgets = array_merge(
            require self::DATA_DIR . '/phones.php',
            require self::DATA_DIR . '/laptops.php',
            require self::DATA_DIR . '/audio_wearables.php',
            require self::DATA_DIR . '/gaming_accessories.php',
            require self::DATA_DIR . '/upcoming.php',
            require self::DATA_DIR . '/gadgetbyte.php',
            require self::DATA_DIR . '/bulk_market.php',
            require self::DATA_DIR . '/archive.php',
            require self::DATA_DIR . '/flagships_upcoming.php',
            require self::DATA_DIR . '/flagships_archive.php',
        );
        $gadgets = $this->mergeDuplicateRows($gadgets);
        $editorial = require self::DATA_DIR . '/editorial.php';
        $reviews = array_merge($editorial['reviews'] ?? [], require self::DATA_DIR . '/reviews_more.php', require self::DATA_DIR . '/reviews_budget_mid.php');
        $news = array_merge($editorial['news'] ?? [], require self::DATA_DIR . '/news_more.php');
        $guides = array_merge($editorial['guides'] ?? [], require self::DATA_DIR . '/guides_more.php', require self::DATA_DIR . '/guides_generated.php');

        $this->authorId = User::where('is_admin', true)->value('id') ?? User::value('id');
        $this->imageCredits = is_file(self::DATA_DIR . '/image_credits.php') ? require self::DATA_DIR . '/image_credits.php' : [];
        $this->imageAttributions = is_file(self::DATA_DIR . '/image_attributions.php') ? require self::DATA_DIR . '/image_attributions.php' : [];

        DB::transaction(function () use ($gadgets, $reviews, $news, $guides) {
            $categories = $this->ensureCategories();

            $seeded = [];
            foreach ($gadgets as $row) {
                $seeded[] = $this->upsertGadget($row, $categories)->id;
            }

            // The researched line-up replaces the older demo devices in the featured/trending slots.
            Gadget::whereNotIn('id', $seeded)->update(['is_featured' => false, 'is_trending' => false]);

            $this->seedUpcomingLaunches($gadgets);
            $this->seedReviews($reviews);
            $this->seedNews($news);
            $this->seedGuides($guides);
            $this->refreshDemoSlides();
            $this->applyEditorialImages();
            $this->fixLegacyProductImages(array_column($gadgets, 'slug'));
            $this->applyBrandLogos();
        });

        $this->command?->info(sprintf('Seeded %d devices, %d reviews, %d news articles and %d guides.',
            count($gadgets), count($reviews), count($news), count($guides)));
    }

    /**
     * Several data files can describe the same device under one slug (e.g. a GadgetByte Nepal listing and
     * a flagship-archive entry). Files are loaded from most to least Nepal-specific, so the first row wins;
     * a later duplicate only adds spec lines and sources the first row lacks (such as DXOMARK scores).
     */
    private function mergeDuplicateRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $slug = $row['slug'];
            if (! isset($out[$slug])) {
                $out[$slug] = $row;
                continue;
            }
            foreach ($row['specs']['extra'] ?? [] as $label => $value) {
                $out[$slug]['specs']['extra'][$label] ??= $value;
            }
            $known = array_column($out[$slug]['sources'] ?? [], 'url');
            foreach ($row['sources'] ?? [] as $source) {
                if (! in_array($source['url'] ?? null, $known, true)) {
                    $out[$slug]['sources'][] = $source;
                }
            }
        }

        return array_values($out);
    }

    /** @return array<string, Category> keyed by slug */
    private function ensureCategories(): array
    {
        $wanted = [
            'mobile'     => 'Mobile',
            'laptop'     => 'Laptop',
            'earbuds'    => 'Earbuds',
            'smartwatch' => 'Smartwatch',
            'accessory'  => 'Accessory',
            'gaming'     => 'Gaming',
            'tablet'     => 'Tablet',
            'games'      => 'Games',
            'headphones' => 'Headphones',
        ];

        $out = [];
        foreach ($wanted as $slug => $name) {
            $out[$slug] = Category::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        return $out;
    }

    private function upsertGadget(array $row, array $categories): Gadget
    {
        $brand = Brand::firstOrCreate(
            ['slug' => Str::slug($row['brand'])],
            ['name' => $row['brand']],
        );
        $category = $categories[$row['category']];
        $brand->categories()->syncWithoutDetaching([$category->id]);

        $variants = $row['variants'] ?? [];
        $prices = array_column($variants, 'price');
        $price = $row['price'] ?? ($prices ? min($prices) : 0);

        $image = $this->imagePath($row['slug']);

        if (! empty($row['accessory_type'])) {
            AccessoryType::firstOrCreate(
                ['slug' => $row['accessory_type']],
                ['name' => Str::of($row['accessory_type'])->replace('-', ' ')->title()->toString()],
            );
        }

        $attributes = [
            'brand_id'          => $brand->id,
            'category_id'       => $category->id,
            'name'              => $row['name'],
            'price'             => $price,
            'old_price'         => $row['old_price'] ?? null,
            'price_is_estimate' => $row['estimate'] ?? false,
            'launch_price_note' => $row['launch_price_note'] ?? null,
            'release_date'      => $row['release_date'] ?? null,
            'release_status'    => $row['status'] ?? Gadget::STATUS_AVAILABLE,
            'expected_launch'   => $row['expected_launch'] ?? null,
            'is_featured'       => $row['featured'] ?? false,
            'is_trending'       => $row['trending'] ?? false,
            'description'       => trim($row['description']),
            'faqs'              => array_map(fn ($f) => ['question' => $f[0], 'answer' => $f[1]], $row['faqs'] ?? []),
            'sources'           => $row['sources'] ?? [],
            'accessory_type'    => $row['accessory_type'] ?? null,
        ];
        if ($image) {
            $attributes['image'] = $image;
        }

        $gadget = Gadget::firstWhere('slug', $row['slug']);
        if ($gadget) {
            $gadget->fill($attributes)->save();
        } else {
            $gadget = Gadget::create($attributes + ['slug' => $row['slug']]);
        }

        // Bulk rows carry the date they were listed (or released), so "Newest" sorting stays honest:
        // a 2018 phone added today must not appear above this month's launches.
        if (! empty($row['listed_at'])) {
            $this->stamp($gadget, $row['listed_at']);
        }

        // Specs
        $specs = $row['specs'] ?? [];
        $extra = $specs['extra'] ?? null;
        unset($specs['extra']);
        // Column limits on spec_sheets; a few imported spec lines are longer than the column.
        $limits = ['ram' => 100, 'os' => 100, 'weight' => 100, 'camera' => 500, 'connectivity' => 500];
        foreach ($specs as $key => $value) {
            if (is_string($value)) {
                $specs[$key] = Str::limit($value, ($limits[$key] ?? 255) - 3, '...');
            }
        }
        $gadget->specs()->updateOrCreate([], $specs + ['extra_specs' => $extra]);

        // SKU variants: the price switches when the reader picks a configuration.
        $gadget->productVariants()->delete();
        $gadget->variants()->delete(); // retire the legacy variant table for these devices
        $usedSkus = [];
        foreach ($variants as $i => $v) {
            // Long laptop slugs plus a configuration overflow the column, so long SKUs end in a short hash.
            $sku = Str::slug($row['slug'] . '-' . implode('-', array_filter([$v['edition'] ?? null, $v['color'] ?? null, $v['ram'] ?? null, $v['storage'] ?? null])));
            if (strlen($sku) > 90) {
                $sku = substr($sku, 0, 81) . '-' . substr(md5($sku), 0, 8);
            }
            if (isset($usedSkus[$sku])) {
                $sku = substr($sku, 0, 86) . '-' . $i;
            }
            $usedSkus[$sku] = true;
            $gadget->productVariants()->create([
                'sku'              => strtoupper($sku),
                'edition'          => $v['edition'] ?? null,
                'color'            => $v['color'] ?? null,
                'ram'              => $v['ram'] ?? null,
                'storage'          => $v['storage'] ?? null,
                'price'            => $v['price'],
                'discounted_price' => null,
                'stock_quantity'   => 0,
                'is_active'        => true,
            ]);
        }

        // Price history: only dated, sourced data points. Replaces the automatic "created today" row.
        $gadget->priceHistory()->delete();
        foreach ($row['history'] ?? [] as [$date, $amount]) {
            $gadget->priceHistory()->create(['date' => $date, 'price' => $amount]);
        }

        // Gallery: the cover image doubles as the first gallery item so it carries alt text and a credit.
        $gadget->images()->delete();
        if ($image) {
            $gadget->images()->create([
                'image'      => $image,
                'alt_text'   => $row['image_alt'] ?? "{$row['name']} official product image",
                'source_url' => $row['image_credit'] ?? $this->imageCredits[$row['slug']] ?? null,
                'credit'     => $this->imageAttributions[$row['slug']] ?? null,
                'order'      => 0,
            ]);
        }

        // Per-page SEO overrides (title, description, keywords, alt text).
        if (! empty($row['seo'])) {
            $gadget->seo()->updateOrCreate([], [
                'meta_title'       => $row['seo']['title'],
                'meta_description' => $row['seo']['description'],
                'meta_keywords'    => $row['seo']['keywords'] ?? null,
                'image_alt'        => $row['image_alt'] ?? null,
                'og_type'          => 'product',
            ]);
        }

        return $gadget;
    }

    private function imagePath(string $slug): ?string
    {
        foreach (['webp', 'jpg', 'png'] as $ext) {
            $path = "gadgets/2026/{$slug}.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function seedUpcomingLaunches(array $gadgets): void
    {
        // Only devices that are still to come to Nepal. Discontinued reference devices (archive) must not
        // show up as "Expected" launches.
        $upcoming = array_values(array_filter($gadgets, fn ($g) => in_array(
            $g['status'] ?? Gadget::STATUS_AVAILABLE,
            [Gadget::STATUS_ANNOUNCED, Gadget::STATUS_UPCOMING],
            true,
        )));
        if (! $upcoming) {
            return;
        }

        // Older entries described launches that have since happened; keep them but hide them.
        UpcomingLaunch::query()->update(['is_active' => false]);

        foreach ($upcoming as $i => $g) {
            $gadget = Gadget::firstWhere('slug', $g['slug']);
            UpcomingLaunch::updateOrCreate(
                ['name' => $g['name']],
                [
                    'gadget_id'     => $gadget?->id,
                    'brand'         => $g['brand'],
                    'category'      => ucfirst($g['category'] === 'mobile' ? 'Smartphones' : $g['category']),
                    'expected_date' => $g['expected_launch'] ?? 'To be announced',
                    'est_price'     => 'Rs ' . number_format($gadget?->price ?? 0, 0, '.', ','),
                    'confidence'    => $g['status'] === Gadget::STATUS_ANNOUNCED ? 95 : 75,
                    'badge'         => $g['status'] === Gadget::STATUS_ANNOUNCED ? 'Announced' : 'Expected',
                    'highlight'     => $g['launch_highlight'] ?? null,
                    'tag_color'     => 'emerald',
                    'is_active'     => true,
                    'sort_order'    => $i + 1,
                ],
            );
        }
    }

    private function seedReviews(array $reviews): void
    {
        foreach ($reviews as $r) {
            $gadget = Gadget::firstWhere('slug', $r['gadget']);
            if (! $gadget) {
                continue;
            }
            $review = Review::updateOrCreate(['slug' => $r['slug']], [
                'gadget_id'    => $gadget->id,
                'user_id'      => $this->authorId,
                'title'        => $r['title'],
                'content'      => trim($r['content']),
                'rating'       => $r['rating'],
                'pros'         => implode("\n", $r['pros']),
                'cons'         => implode("\n", $r['cons']),
                'verdict'      => trim(strip_tags($r['verdict'])), // listed as plain text on review cards
                'is_published' => true,
            ]);
            $this->stamp($review, $r['date']);
            $this->seo($review, $r['seo'] ?? null, 'article');
        }
    }

    private function seedNews(array $articles): void
    {
        foreach ($articles as $a) {
            $article = NewsArticle::updateOrCreate(['slug' => $a['slug']], [
                'user_id'          => $this->authorId,
                'title'            => $a['title'],
                'content'          => trim($a['content']),
                'category'         => $a['category'],
                'is_published'     => true,
                'meta_description' => Str::limit($a['meta_description'], 158, ''),
            ]);
            $this->stamp($article, $a['date']);
            $this->seo($article, $a['seo'] ?? null, 'article');
        }
    }

    private function seedGuides(array $guides): void
    {
        foreach ($guides as $g) {
            $guide = TechGuide::updateOrCreate(['slug' => $g['slug']], [
                'user_id'      => $this->authorId,
                'title'        => $g['title'],
                'type'         => $g['type'],
                'content'      => trim($g['content']),
                'is_published' => true,
            ]);
            $this->stamp($guide, $g['date']);
            $this->seo($guide, $g['seo'] ?? null, 'article');

            // Generated guides use their top pick's official product image as the cover. An editor's own
            // cover (anything outside gadgets/) is never replaced.
            if (! empty($g['cover_gadgets']) && (! $guide->thumbnail || str_starts_with($guide->thumbnail, 'gadgets/'))) {
                $cover = Gadget::whereIn('slug', $g['cover_gadgets'])->whereNotNull('image')->pluck('image', 'slug');
                $first = collect($g['cover_gadgets'])->first(fn ($slug) => isset($cover[$slug]));
                if ($first) {
                    $guide->timestamps = false;
                    $guide->forceFill(['thumbnail' => $cover[$first]])->saveQuietly();
                    $guide->timestamps = true;
                }
            }
        }
    }

    /**
     * The original install shipped four placeholder hero slides (Galaxy S24 Ultra, MacBook Air M3...)
     * over generic stock photos. Each is replaced by a real story with the manufacturer's own image.
     * Slides are matched by their demo titles (or this seeder's earlier titles), so slides an editor
     * has written are never touched.
     */
    private function refreshDemoSlides(): void
    {
        $slides = [
            [
                'match' => ['Samsung Galaxy S24 Ultra', 'GTA VI is out now'],
                'title' => 'GTA VI is out now', 'subtitle' => 'On PlayStation 5 and Xbox Series consoles', 'badge' => 'Out now',
                'description' => "Rockstar's record-breaking launch is here. See what it costs in Nepal and which PS5 to buy for it.",
                'image' => 'sliders/slide-gta-6.jpg',
                'btn1_text' => 'GTA VI price in Nepal', 'btn1_url' => '/products/gta-6',
                'btn2_text' => 'Which PS5 to buy', 'btn2_url' => '/reviews/ps5-digital-slim-pro-review-nepal',
            ],
            [
                'match' => ['MacBook Air M3', 'iPhone 18 Pro and Pro Max'],
                'title' => 'iPhone 18 Pro and Pro Max', 'subtitle' => 'Official prices in Nepal', 'badge' => 'Now in Nepal',
                'description' => 'From Rs 2,59,900. Every storage option, full specs, and how the Pro Max compares with the Galaxy S26 Ultra.',
                'image' => 'sliders/slide-iphone-18-pro.jpg',
                'btn1_text' => 'See prices & specs', 'btn1_url' => '/products/iphone-18-pro-max',
                'btn2_text' => 'Compare with S26 Ultra', 'btn2_url' => '/compare?g1=iphone-18-pro-max&g2=samsung-galaxy-s26-ultra',
            ],
            [
                'match' => ['Price Tracker', 'Prices you can check', 'Galaxy S26 Ultra: still the one to beat?'],
                'title' => 'Galaxy S26 Ultra: still the one to beat?', 'subtitle' => 'Our verdict for Nepal', 'badge' => 'Review',
                'description' => 'Rs 2,24,999 with an S Pen, a 200MP camera and seven years of updates. Buy now, or wait for the S27 Ultra?',
                'image' => 'sliders/slide-galaxy-s26-ultra.jpg',
                'btn1_text' => 'Read the review', 'btn1_url' => '/reviews/samsung-galaxy-s26-ultra-review-nepal',
                'btn2_text' => 'See prices', 'btn2_url' => '/products/samsung-galaxy-s26-ultra',
            ],
            [
                'match' => ['AI-Powered Gadget Comparison', 'Coming soon to Nepal', 'The laptop most people should buy'],
                'title' => 'The laptop most people should buy', 'subtitle' => 'MacBook Air M5 review', 'badge' => 'Review',
                'description' => 'Silent, fanless and good for a full working day on battery, from Rs 2,34,900 in Nepal. Our verdict and the alternatives.',
                'image' => 'sliders/slide-macbook-air-m5.jpg',
                'btn1_text' => 'Read the review', 'btn1_url' => '/reviews/macbook-air-m5-13-review-nepal',
                'btn2_text' => 'Best laptops in Nepal', 'btn2_url' => '/guides/best-laptops-nepal-students-gamers',
            ],
        ];

        foreach (Slider::all() as $slider) {
            foreach ($slides as $slide) {
                if (! collect($slide['match'])->contains(fn ($t) => Str::startsWith($slider->title, $t))) {
                    continue;
                }
                $content = Arr::except($slide, 'match');
                if (! Storage::disk('public')->exists($content['image'])) {
                    unset($content['image']); // keep the current image rather than point at a missing file
                }
                $slider->update($content);
            }
        }
    }

    /**
     * Give blog posts, guides and news articles a cover that matches what they're about
     * (see data/catalog/editorial_images.php). Only records that exist, and only files that exist.
     */
    private function applyEditorialImages(): void
    {
        $map = is_file(self::DATA_DIR . '/editorial_images.php') ? require self::DATA_DIR . '/editorial_images.php' : [];
        $targets = [
            'blog'   => [BlogPost::class, 'cover_image'],
            'guides' => [TechGuide::class, 'thumbnail'],
            'news'   => [NewsArticle::class, 'thumbnail'],
        ];

        foreach ($targets as $kind => [$model, $column]) {
            foreach ($map[$kind] ?? [] as $slug => $entry) {
                if (Storage::disk('public')->exists($entry['image'])) {
                    $model::where('slug', $slug)->update([$column => $entry['image']]);
                }
            }
        }
    }

    /**
     * Brand "logos" from the original install were stock photos, mostly of other brands' products
     * (an iPhone X as Xiaomi's logo, AirPods as Sony's). Use the real marks from brand_logos.php;
     * where we have none, clear the photo so the UI shows the brand's initial instead.
     */
    private function applyBrandLogos(): void
    {
        $logos = is_file(self::DATA_DIR . '/brand_logos.php') ? require self::DATA_DIR . '/brand_logos.php' : [];
        $stockPhotos = ['apple', 'boat', 'dell', 'hp', 'jbl', 'noise', 'oneplus', 'oppo', 'realme', 'samsung', 'sony', 'xiaomi'];

        foreach (Brand::all() as $brand) {
            $logo = $logos[$brand->slug]['logo'] ?? null;
            if ($logo && Storage::disk('public')->exists($logo)) {
                $brand->update(['logo' => $logo]);
            } elseif ($brand->logo === "brands/{$brand->slug}.jpg" && in_array($brand->slug, $stockPhotos, true)) {
                $brand->update(['logo' => null]);
            }
        }
    }

    /**
     * The 13 demo devices from the original install carried stock photos of other products
     * (an iPhone 11 Pro on the "iPhone 15 Pro", an Apple Watch on the "Galaxy Watch 6"...).
     * Use the manufacturer's image where we have one; otherwise show no image rather than a wrong one.
     */
    private function fixLegacyProductImages(array $catalogSlugs): void
    {
        $legacy = [
            'samsung-galaxy-s24', 'iphone-15-pro', 'xiaomi-14', 'oneplus-12', 'oppo-reno-11-pro', 'realme-12-pro',
            'samsung-galaxy-book4-pro', 'hp-pavilion-15', 'dell-inspiron-15-3520', 'sony-wf-1000xm5',
            'boat-airdopes-141', 'samsung-galaxy-watch-6', 'noise-colorfit-ultra-3',
        ];

        foreach (Gadget::whereIn('slug', array_diff($legacy, $catalogSlugs))->get() as $gadget) {
            $image = $this->imagePath($gadget->slug);
            $gadget->images()->delete();
            $gadget->update(['image' => $image]);
            if ($image) {
                $gadget->images()->create([
                    'image'      => $image,
                    'alt_text'   => "{$gadget->name} official product image",
                    'source_url' => $this->imageCredits[$gadget->slug] ?? null,
                    'order'      => 0,
                ]);
            }
        }
    }

    /** Publication dates matter for news — keep the researched date rather than "now". */
    private function stamp($model, string $date): void
    {
        $at = Carbon::parse($date);
        $model->timestamps = false;
        $model->forceFill(['created_at' => $at, 'updated_at' => $at])->save();
        $model->timestamps = true;
    }

    private function seo($model, ?array $seo, string $type): void
    {
        if (! $seo || ! method_exists($model, 'seo')) {
            return;
        }
        $model->seo()->updateOrCreate([], [
            'meta_title'       => $seo['title'],
            'meta_description' => $seo['description'],
            'meta_keywords'    => $seo['keywords'] ?? null,
            'og_type'          => $type,
        ]);
    }
}
