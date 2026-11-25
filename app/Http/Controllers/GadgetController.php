<?php

namespace App\Http\Controllers;

use App\Support\Seo;
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
        // Card columns only (no description/FAQ/sources), but the full spec sheet: the list view
        // and the battery/camera/gaming highlight filters read display, camera and extra_specs.
        $query = Gadget::forCard()->with('specs')
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q2) => $q2->where('slug', $request->category)))
            ->when($request->brand,    fn($q) => $q->whereHas('brand',    fn($q2) => $q2->where('slug', $request->brand)))
            // Model names and chipsets both count ("Dimensity", "Core Ultra" match on the spec sheet).
            ->when($request->search,   fn($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$request->search}%")
                ->orWhereHas('specs', fn ($sp) => $sp->where('processor', 'like', "%{$request->search}%"))))
            ->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price))
            // Devices without a Nepal price are stored as 0; they don't belong in a price range.
            ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price)->where('price', '>', 0))
            ->when(
                in_array($request->status, array_keys(Gadget::STATUSES), true),
                fn($q) => $q->where('release_status', $request->status),
                // The default view is what you can buy or expect to buy; old models live under "Discontinued".
                fn($q) => $q->where('release_status', '!=', Gadget::STATUS_DISCONTINUED),
            );

        $sort = $request->sort ?? 'latest';
        match ($sort) {
            'price_asc'  => $query->orderByRaw('price = 0')->orderBy('price'), // "no Nepal price" goes last
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->orderByDesc('views_count'),
            default      => $query->latest(),
        };

        $categoryModel = $request->category ? Category::where('slug', $request->category)->first() : null;
        $categoryLabel = $request->category ? ($categoryModel?->name ?? ucfirst($request->category)) . ' ' : '';

        // Facet counts follow the other active filters, so "Lenovo 199" never shows under Smartwatch.
        $status = in_array($request->status, array_keys(Gadget::STATUSES), true) ? $request->status : null;
        $inStatus = fn ($q) => $status
            ? $q->where('release_status', $status)
            : $q->where('release_status', '!=', Gadget::STATUS_DISCONTINUED);
        $inCategory = fn ($q) => $q->when($request->category, fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $request->category)));
        $inBrand = fn ($q) => $q->when($request->brand, fn ($q) => $q->whereHas('brand', fn ($q2) => $q2->where('slug', $request->brand)));

        return Inertia::render('Gadgets/Index', [
            'gadgets'    => $query->paginate(\App\Support\PerPage::resolve($request, 25))->withQueryString(),
            'categories' => Category::withCount(['gadgets' => fn ($q) => $inBrand($inStatus($q))])->get()
                ->filter(fn ($c) => $c->gadgets_count > 0 || $c->slug === $request->category)->values(),
            'brands'     => \App\Models\Brand::withCount(['gadgets' => fn ($q) => $inCategory($inStatus($q))])
                ->whereHas('gadgets', fn ($q) => $inCategory($inStatus($q)))->orderByDesc('gadgets_count')->get(),
            'filters'    => $request->only(['category', 'brand', 'search', 'min_price', 'max_price', 'sort', 'status']),
            'statusCounts' => $inBrand($inCategory(Gadget::query()))->selectRaw('release_status, count(*) as total')->groupBy('release_status')->pluck('total', 'release_status'),

            'seo' => Seo::for($categoryModel, [
                'title'       => "{$categoryLabel}Gadgets & Tech Products in Nepal",
                'description' => "Browse {$categoryLabel}smartphones, laptops, and accessories with prices, specs, and reviews. Filter by brand, category, and price range.",
                'canonical'   => route('gadgets.index', $request->only(['category', 'brand'])),
                'type'        => 'website',
            ]),
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
        $comments = $gadget->comments()->with('user:id,name')->latest()->get();
        // Current devices first, closest in price (newest first when this one has no price to compare).
        $related  = Gadget::forCard()
            ->where('category_id', $gadget->category_id)
            ->where('id', '!=', $gadget->id)
            ->orderByRaw('release_status = ?', [Gadget::STATUS_DISCONTINUED])
            ->when(
                $gadget->price > 0,
                fn ($q) => $q->orderByRaw('price <= 0')->orderByRaw('ABS(price - ?)', [(float) $gadget->price]),
                fn ($q) => $q->latest(),
            )
            ->take(6)->get();

        $trending = Gadget::query()
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
        $isUpcoming = $gadget->release_status === Gadget::STATUS_UPCOMING;
        $desc       = $gadget->description
            ? Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($gadget->description))), 155)
            : ($isUpcoming
                ? "{$gadget->name}: expected launch date, expected price in Nepal and rumoured specifications."
                : "{$gadget->name} price in Nepal, full specifications, variants and our verdict.");
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
            'edition'          => $v->edition,
            'size'             => $v->size,
            'price'            => (float) $v->price,
            'discounted_price' => $v->discounted_price ? (float) $v->discounted_price : null,
            'effective_price'  => (float) ($v->discounted_price ?? $v->price),
            'stock_quantity'   => $v->stock_quantity,
            'is_in_stock'      => $v->stock_quantity > 0,
            'variant_image'    => $v->variant_image,
            'is_active'        => $v->is_active,
        ]);

        $titleSuffix = match ($gadget->release_status) {
            Gadget::STATUS_UPCOMING  => 'Expected Price in Nepal, Launch Date & Specs',
            Gadget::STATUS_ANNOUNCED => 'Expected Price in Nepal & Full Specs',
            default                  => 'Price in Nepal, Specs & Review',
        };

        // The page only needs the date the facts were checked, and photo links only where a licence asks for a credit.
        $checkedOn = collect($gadget->sources)->pluck('accessed')->filter()->max();
        $gadget->images->each(fn ($image) => $image->credit ? null : $image->makeHidden('source_url'));

        return Inertia::render('Gadgets/Show', [
            'gadget'          => $gadget,
            'checkedOn'       => $checkedOn,
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

            'seo' => Seo::for($gadget, [
                'title'       => "{$gadget->name} {$titleSuffix}",
                'description' => $desc,
                'image'       => $absImage,
                'image_alt'   => $gadget->images->first()?->alt_text ?: $gadget->name,
                'canonical'   => $canonical,
                'type'        => 'product',
                'json_ld'     => $this->buildProductJsonLd($gadget, $productVariants, $review, $comments, $desc, $absImage, $canonical),
            ]),
        ]);
    }

    /**
     * Product page structured data: Product (+ AggregateOffer, editorial Review, AggregateRating),
     * BreadcrumbList and, when the product has FAQs, FAQPage.
     *
     * Git Infosys does not sell devices, so offers carry no seller. Upcoming devices and estimated
     * prices get no offer at all, because an Offer asserts the item can be bought at that price.
     */
    private function buildProductJsonLd(Gadget $gadget, $productVariants, $review, $comments, string $desc, ?string $absImage, string $canonical): array
    {
        $appUrl = config('app.url');

        $product = [
            '@type'       => 'Product',
            '@id'         => $canonical . '#product',
            'name'        => $gadget->name,
            'description' => $desc,
            'image'       => collect([$absImage])
                ->merge($gadget->images->map(fn ($i) => url(Storage::url($i->image))))
                ->filter()->unique()->values()->all(),
            'url'         => $canonical,
            'sku'         => $gadget->slug,
            'category'    => $gadget->category?->name,
            'brand'       => ['@type' => 'Brand', 'name' => $gadget->brand?->name ?? ''],
        ];

        $prices = $productVariants->pluck('effective_price')->filter()->values();
        if ($prices->isEmpty() && (float) $gadget->price > 0) {
            $prices = collect([(float) $gadget->price]);
        }

        $sellable = $gadget->release_status === Gadget::STATUS_AVAILABLE && ! $gadget->price_is_estimate && $prices->isNotEmpty();
        if ($sellable) {
            $availability = $gadget->out_of_stock_at || $productVariants->isNotEmpty() && ! $productVariants->contains('is_in_stock', true)
                && $productVariants->sum('stock_quantity') > 0
                ? 'https://schema.org/OutOfStock'
                : 'https://schema.org/InStock';

            $product['offers'] = $prices->count() > 1
                ? [
                    '@type'         => 'AggregateOffer',
                    'priceCurrency' => 'NPR',
                    'lowPrice'      => (string) $prices->min(),
                    'highPrice'     => (string) $prices->max(),
                    'offerCount'    => $prices->count(),
                    'availability'  => $availability,
                    'url'           => $canonical,
                ]
                : [
                    '@type'         => 'Offer',
                    'priceCurrency' => 'NPR',
                    'price'         => (string) $prices->first(),
                    'availability'  => $availability,
                    'url'           => $canonical,
                ];
        }

        if ($review) {
            $product['review'] = [
                '@type'         => 'Review',
                'name'          => $review->title,
                'url'           => route('reviews.show', $review->slug),
                'datePublished' => $review->created_at?->toAtomString(),
                'author'        => ['@type' => 'Organization', 'name' => config('app.name'), 'url' => $appUrl],
                'reviewRating'  => [
                    '@type'       => 'Rating',
                    'ratingValue' => (string) $review->rating,
                    'bestRating'  => '10',
                    'worstRating' => '1',
                ],
                'reviewBody'    => Str::limit(strip_tags((string) ($review->verdict ?: $review->content)), 300),
            ];
        }

        if ($comments->isNotEmpty()) {
            $product['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) round($comments->avg('rating'), 1),
                'reviewCount' => $comments->count(),
                'bestRating'  => '10',
                'worstRating' => '1',
            ];
        }

        $crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $appUrl]];
        $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Devices', 'item' => route('gadgets.index')];
        if ($gadget->category) {
            $crumbs[] = ['@type' => 'ListItem', 'position' => 3, 'name' => $gadget->category->name, 'item' => route('gadgets.index', ['category' => $gadget->category->slug])];
        }
        $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $gadget->name, 'item' => $canonical];

        $graph = [$product, ['@type' => 'BreadcrumbList', 'itemListElement' => $crumbs]];

        $faqs = collect($gadget->faqs ?? [])->filter(fn ($f) => filled($f['question'] ?? null) && filled($f['answer'] ?? null));
        if ($faqs->isNotEmpty()) {
            $graph[] = [
                '@type'      => 'FAQPage',
                '@id'        => $canonical . '#faq',
                'mainEntity' => $faqs->map(fn ($f) => [
                    '@type'          => 'Question',
                    'name'           => $f['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['answer']],
                ])->values()->all(),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
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
