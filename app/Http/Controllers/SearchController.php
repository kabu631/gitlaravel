<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\TechGuide;
use App\Support\CatalogSearch;
use App\Support\PriceDrops;
use App\Support\Seo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SearchController extends Controller
{
    private const PER_PAGE = 24;

    public function index(Request $request)
    {
        $q = Str::limit(trim((string) $request->q), 120, '');
        $category = $request->string('category')->toString() ?: null;
        $sort = in_array($request->sort, ['relevance', 'price_asc', 'price_desc', 'newest'], true) ? $request->sort : null;
        $page = max(1, (int) $request->page);

        $priceTracker = PriceDrops::top(8);

        if ($q === '') {
            return Inertia::render('Search/Index', [
                'query' => '',
                'gadgets' => [],
                'total' => 0,
                'priceTracker' => $priceTracker,
                'seo' => Seo::make([
                    'title' => 'Search Products, Reviews & News',
                    'description' => 'Search Git Infosys for gadgets, reviews, news, and buying guides across all categories.',
                    'canonical' => route('search.index'),
                    'noindex' => true,
                ]),
            ]);
        }

        $result = CatalogSearch::search($q, ['category' => $category, 'sort' => $sort]);
        $pageIds = array_slice($result['ids'], 0, self::PER_PAGE * $page);
        $gadgets = $this->gadgetsInOrder($pageIds);

        $parsed = $result['parsed'];
        $words = $this->contentWords($result['corrected'] ?? $q);
        $guides = $this->matchContent(TechGuide::where('is_published', true), $words, ['title', 'content'], 4)
            ->map->only(['id', 'title', 'slug', 'type', 'updated_at']);
        $articles = $this->matchContent(NewsArticle::where('is_published', true), $words, ['title', 'content'], 6)
            ->map->only(['id', 'title', 'slug', 'category', 'created_at']);
        $reviews = $this->matchContent(Review::with('gadget.brand')->where('is_published', true), $words, ['title', 'content'], 4);
        $posts = $this->matchContent(BlogPost::published(), $words, ['title', 'excerpt', 'content'], 4)
            ->map->only(['id', 'title', 'slug', 'excerpt', 'published_at', 'created_at']);

        $categoryNames = Category::pluck('name', 'slug');
        $facets = collect($result['facets'])
            ->map(fn ($count, $slug) => ['slug' => $slug, 'name' => $categoryNames[$slug] ?? Str::headline((string) $slug), 'count' => $count])
            ->filter(fn ($f) => $f['slug'])->values();

        $total = $result['total'] + $guides->count() + $articles->count() + $reviews->count() + $posts->count();

        return Inertia::render('Search/Index', [
            'query' => $q,
            'gadgets' => $gadgets,
            'gadgetTotal' => $result['total'],
            'hasMore' => $result['total'] > count($pageIds),
            'page' => $page,
            'facets' => $facets,
            'activeCategory' => $category,
            'sort' => $sort ?? ($parsed['cheap'] ? 'price_asc' : 'relevance'),
            'understood' => [
                'category' => $parsed['category'] ? ($categoryNames[$parsed['category']] ?? Str::headline($parsed['category'])) : null,
                'categorySlug' => $parsed['category'],
                'price' => $parsed['priceLabel'],
                'gaming' => $parsed['gaming'],
                'cheap' => $parsed['cheap'],
            ],
            'corrected' => $result['corrected'],
            'partial' => $result['partial'],
            'ignored' => $result['ignored'] ?? [],
            'brands' => CatalogSearch::brands($result['corrected'] ?? $q),
            'guides' => $guides,
            'articles' => $articles,
            'reviews' => $reviews,
            'posts' => $posts,
            'total' => $total,
            'priceTracker' => $priceTracker,
            'seo' => Seo::make([
                'title' => "Search results for \"{$q}\"",
                'noindex' => true,
            ]),
        ]);
    }

    /** Live results for the search box: a handful of devices, brands, shelves and guides as you type. */
    public function suggest(Request $request)
    {
        $q = Str::limit(trim((string) $request->q), 120, '');
        if (mb_strlen($q) < 2) {
            return response()->json(['products' => [], 'brands' => [], 'guides' => [], 'categories' => [], 'corrected' => null]);
        }

        $result = CatalogSearch::search($q);
        $products = $this->gadgetsInOrder(array_slice($result['ids'], 0, 6))->map(fn ($g) => [
            'name' => $g->name,
            'slug' => $g->slug,
            'image' => $g->image ? Storage::url($g->image) : null,
            'price' => (float) $g->price,
            'brand' => $g->brand?->name,
            'category' => $g->category?->name,
            'status' => $g->release_status,
        ]);

        $categoryNames = Category::pluck('name', 'slug');
        $categories = collect($result['facets'])->take(3)->map(fn ($count, $slug) => [
            'slug' => $slug, 'name' => $categoryNames[$slug] ?? Str::headline((string) $slug), 'count' => $count,
        ])->values();

        $guides = $this->matchContent(TechGuide::where('is_published', true), $this->contentWords($result['corrected'] ?? $q), ['title'], 3)
            ->map(fn ($g) => ['title' => $g->title, 'slug' => $g->slug]);

        return response()->json([
            'products' => $products,
            'total' => $result['total'],
            'brands' => CatalogSearch::brands($result['corrected'] ?? $q, 3)->map(fn ($b) => ['name' => $b->name, 'slug' => $b->slug, 'count' => $b->gadgets_count]),
            'categories' => $categories,
            'guides' => $guides,
            'corrected' => $result['corrected'],
            'understood' => array_filter([
                'price' => $result['parsed']['priceLabel'],
                'category' => $result['parsed']['category'] ? ($categoryNames[$result['parsed']['category']] ?? null) : null,
            ]),
        ])->header('Cache-Control', 'public, max-age=60');
    }

    /** Load devices for the given ids, keeping the ranking order. */
    private function gadgetsInOrder(array $ids)
    {
        if (! $ids) {
            return collect();
        }
        $rows = Gadget::forCard()->whereIn('id', $ids)->get()->keyBy('id');

        return collect($ids)->map(fn ($id) => $rows[$id] ?? null)->filter()->values();
    }

    /** Meaningful words of the query for article search (price phrases and filler words removed). */
    private function contentWords(string $q): array
    {
        $parsed = CatalogSearch::parse($q);
        $words = $parsed['terms'];
        // Shelf words help article search ("laptop" in "best gaming laptops"), even though products treat them as filters.
        foreach (explode(' ', CatalogSearch::normalize($q)) as $w) {
            if (in_array($w, ['laptop', 'laptops', 'phone', 'phones', 'mobile', 'tablet', 'tablets', 'earbuds', 'monitor', 'monitors', 'gaming', 'camera', 'smartwatch', 'printer'], true)) {
                $words[] = rtrim($w, 's');
            }
        }

        return array_values(array_unique(array_filter($words, fn ($w) => strlen($w) >= 2)));
    }

    /**
     * Articles where every word appears in one of the columns; titles that contain the words rank first.
     */
    private function matchContent(Builder $query, array $words, array $columns, int $limit)
    {
        if (! $words) {
            return collect();
        }
        foreach ($words as $w) {
            $query->where(function ($q) use ($w, $columns) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'like', "%{$w}%");
                }
            });
        }
        $titleHits = implode(' + ', array_map(fn () => '(title LIKE ?)', $words));

        return $query->orderByRaw("($titleHits) DESC", array_map(fn ($w) => "%{$w}%", $words))
            ->latest()
            ->take($limit)
            ->get();
    }
}
