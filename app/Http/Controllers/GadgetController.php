<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\UserComment;
use App\Models\BankPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GadgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Gadget::with(['brand', 'category', 'specs'])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $request->category)))
            ->when($request->brand,    fn($q) => $q->whereHas('brand',    fn($q2) => $q2->where('slug', $request->brand)))
            ->when($request->search,   fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price))
            ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price));

        $sort = $request->sort ?? 'latest';
        match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->orderByDesc('views_count'),
            default      => $query->latest(),
        };

        $categoryLabel = $request->category ? ucfirst($request->category) . ' ' : '';

        return Inertia::render('Gadgets/Index', [
            'gadgets'    => $query->paginate(20)->withQueryString(),
            'categories' => Category::withCount('gadgets')->get(),
            'brands'     => \App\Models\Brand::withCount('gadgets')->orderByDesc('gadgets_count')->get(),
            'filters'    => $request->only(['category', 'brand', 'search', 'min_price', 'max_price', 'sort']),

            'seo' => [
                'title'       => "{$categoryLabel}Gadgets & Tech Products in Nepal",
                'description' => "Browse {$categoryLabel}smartphones, laptops, and accessories with prices, specs, and reviews. Filter by brand, category, and price range.",
                'canonical'   => route('gadgets.index', $request->only(['category', 'brand'])),
                'type'        => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $gadget = Gadget::with(['brand', 'category', 'specs', 'images', 'priceHistory'])
            ->withCount('comments')
            ->where('slug', $slug)
            ->firstOrFail();

        $gadget->increment('views_count');

        $review   = $gadget->reviews()->where('is_published', true)->first();
        $comments = $gadget->comments()->with('user')->latest()->get();
        $related  = Gadget::with(['brand', 'specs'])
            ->where('category_id', $gadget->category_id)
            ->where('id', '!=', $gadget->id)
            ->take(6)->get();

        $trending = Gadget::with(['brand', 'specs'])
            ->where('is_trending', true)
            ->where('id', '!=', $gadget->id)
            ->latest()->take(4)->get(['id', 'name', 'slug', 'image', 'price', 'brand_id']);

        $latestNews = NewsArticle::where('is_published', true)
            ->latest()->take(3)->get(['id', 'title', 'slug', 'thumbnail', 'category', 'created_at']);

        $inWishlist = auth()->check()
            ? auth()->user()->wishlist()->where('gadget_id', $gadget->id)->exists()
            : false;

        $hasCommented = auth()->check()
            ? $gadget->comments()->where('user_id', auth()->id())->exists()
            : false;

        $appUrl     = config('app.url');
        $desc       = $gadget->description
            ? Str::limit(strip_tags($gadget->description), 155)
            : "Buy {$gadget->name} in Nepal. Check full specs, user reviews, and the best price at " . config('app.name') . '.';
        $imageUrl   = $gadget->image ? Storage::url($gadget->image) : null;
        $absImage   = $imageUrl ? (Str::startsWith($imageUrl, 'http') ? $imageUrl : $appUrl . $imageUrl) : null;
        $canonical  = route('gadgets.show', $gadget->slug);

        // Load product variants (SKU-based system) — active only, ordered by price
        $productVariants = $gadget->productVariants()->where('is_active', true)->get()->map(fn($v) => [
            'id'               => $v->id,
            'sku'              => $v->sku,
            'color'            => $v->color,
            'ram'              => $v->ram,
            'storage'          => $v->storage,
            'size'             => $v->size,
            'price'            => (float) $v->price,
            'discounted_price' => $v->discounted_price ? (float) $v->discounted_price : null,
            'effective_price'  => (float) ($v->discounted_price ?? $v->price),
            'stock_quantity'   => $v->stock_quantity,
            'is_in_stock'      => $v->stock_quantity > 0,
            'variant_image'    => $v->variant_image,
            'is_active'        => $v->is_active,
        ]);

        return Inertia::render('Gadgets/Show', [
            'gadget'          => $gadget,
            'productVariants' => $productVariants,
            'variantsByType'  => $gadget->variantsByType(), // legacy fallback
            'priceHistory'    => $gadget->priceHistory->map(fn($p) => ['date' => $p->date->format('Y-m-d'), 'price' => (float) $p->price]),
            'review'          => $review,
            'comments'        => $comments,
            'related'         => $related,
            'trending'        => $trending,
            'latestNews'      => $latestNews,
            'bankPartners'    => BankPartner::active()->get(),
            'inWishlist'      => $inWishlist,
            'hasCommented'    => $hasCommented,

            'seo' => [
                'title'       => "{$gadget->name} — Price in Nepal, Full Specs & Review",
                'description' => $desc,
                'image'       => $absImage,
                'canonical'   => $canonical,
                'type'        => 'product',
                'json_ld'     => [
                    '@context' => 'https://schema.org',
                    '@graph'   => [
                        [
                            '@type'       => 'Product',
                            'name'        => $gadget->name,
                            'description' => $desc,
                            'image'       => $absImage ? [$absImage] : [],
                            'url'         => $canonical,
                            'brand'       => ['@type' => 'Brand', 'name' => $gadget->brand?->name ?? ''],
                            'offers'      => [
                                '@type'         => 'Offer',
                                'price'         => (string) $gadget->price,
                                'priceCurrency' => 'NPR',
                                'availability'  => 'https://schema.org/InStock',
                                'url'           => $canonical,
                                'seller'        => ['@type' => 'Organization', 'name' => config('app.name')],
                            ],
                        ],
                        [
                            '@type'           => 'BreadcrumbList',
                            'itemListElement' => [
                                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => $appUrl],
                                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('gadgets.index')],
                                ['@type' => 'ListItem', 'position' => 3, 'name' => $gadget->name, 'item' => $canonical],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function addComment(Request $request, string $slug)
    {
        $gadget = Gadget::where('slug', $slug)->firstOrFail();
        $request->validate(['rating' => 'required|integer|min:1|max:10', 'comment' => 'required|string|max:2000']);

        UserComment::updateOrCreate(
            ['gadget_id' => $gadget->id, 'user_id' => auth()->id()],
            $request->only(['rating', 'comment'])
        );

        return back()->with('success', 'Review submitted.');
    }
}
