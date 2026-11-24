<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        // Which categories each brand actually sells in, so the sidebar can narrow the grid without a reload.
        $categoriesByBrand = Gadget::query()
            ->select('brand_id', 'category_id')->distinct()
            ->whereNotNull('category_id')
            ->get()->groupBy('brand_id')
            ->map(fn ($rows) => $rows->pluck('category_id')->values());

        $brands = Brand::orderBy('name')->get(['id', 'name', 'slug', 'logo'])
            ->map(fn ($b) => [
                'id'           => $b->id,
                'name'         => $b->name,
                'slug'         => $b->slug,
                'logo'         => $b->logo,
                'category_ids' => $categoriesByBrand->get($b->id, collect()),
            ]);

        // Same order as the header's category menu; empty categories are left out.
        $categories = Category::whereHas('gadgets')->orderBy('id')->get(['id', 'name', 'slug']);

        return Inertia::render('Brands/Index', [
            'brands'     => $brands,
            'categories' => $categories,
            'filters'    => $request->only(['category']),
            'seo'    => Seo::make([
                'title'       => 'All Brands — Gadget Prices & Reviews in Nepal',
                'description' => 'Browse every smartphone, laptop and gadget brand available in Nepal with specs, prices and reviews.',
                'canonical'   => route('brands.index'),
                'type'        => 'website',
            ]),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $status = in_array($request->status, array_keys(Gadget::STATUSES), true) ? $request->status : null;

        // Same convention as /products: the default view hides discontinued models, which live under "Older models".
        $inStatus = fn ($q) => $status
            ? $q->where('release_status', $status)
            : $q->where('release_status', '!=', Gadget::STATUS_DISCONTINUED);
        $inCategory = fn ($q) => $q->when($request->category, fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $request->category)));
        $ofBrand = fn ($q) => $q->where('brand_id', $brand->id);

        $query = $inStatus($inCategory($ofBrand(Gadget::forCard()->with('specs'))))
            ->when($request->min_price, fn ($q) => $q->where('price', '>=', $request->min_price))
            // Devices without a Nepal price are stored as 0; they don't belong in a price range.
            ->when($request->max_price, fn ($q) => $q->where('price', '<=', $request->max_price)->where('price', '>', 0));

        match ($request->sort) {
            'price_asc'  => $query->orderByRaw('price = 0')->orderBy('price'), // "no Nepal price" goes last
            'price_desc' => $query->orderByDesc('price'),
            'popular'    => $query->orderByDesc('views_count'),
            default      => $query->latest(),
        };

        return Inertia::render('Brands/Show', [
            'brand'   => $brand,
            'gadgets' => $query->paginate(\App\Support\PerPage::resolve($request, 20))->withQueryString(),
            'filters' => $request->only(['category', 'min_price', 'max_price', 'sort', 'status']),

            // Sidebar facets. Counts are only used to hide empty options, never shown.
            'categories' => Category::whereHas('gadgets', fn ($q) => $inStatus($ofBrand($q)))
                ->orderBy('name')->get(['id', 'name', 'slug']),
            'statusCounts' => $inCategory($ofBrand(Gadget::query()))
                ->selectRaw('release_status, count(*) as total')->groupBy('release_status')->pluck('total', 'release_status'),
            'otherBrands' => Brand::where('id', '!=', $brand->id)
                ->whereHas('gadgets')
                ->withCount('gadgets')->orderByDesc('gadgets_count')
                ->get(['id', 'name', 'slug', 'logo'])
                ->map->only(['id', 'name', 'slug', 'logo']),

            'seo' => Seo::for($brand, [
                'title'       => "{$brand->name} Products — Prices & Reviews in Nepal",
                'description' => "Browse all {$brand->name} smartphones, laptops, and accessories available in Nepal. Compare specs, prices, and read reviews.",
                'canonical'   => route('brands.show', $brand->slug),
                'image'       => $brand->logo ? url(\Illuminate\Support\Facades\Storage::url($brand->logo)) : null,
                'image_alt'   => "{$brand->name} logo",
                'type'        => 'website',
                'json_ld'     => [
                    '@context'        => 'https://schema.org',
                    '@type'           => 'CollectionPage',
                    'name'            => "{$brand->name} Products",
                    'description'     => "All {$brand->name} gadgets available in Nepal",
                    'url'             => route('brands.show', $brand->slug),
                    'breadcrumb'      => [
                        '@type'           => 'BreadcrumbList',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => config('app.url')],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => route('gadgets.index')],
                            ['@type' => 'ListItem', 'position' => 3, 'name' => $brand->name, 'item' => route('brands.show', $brand->slug)],
                        ],
                    ],
                ],
            ]),
        ]);
    }
}
