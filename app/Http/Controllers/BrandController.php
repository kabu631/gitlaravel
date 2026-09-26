<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use App\Models\Brand;
use App\Models\Gadget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('gadgets')->orderBy('name')->get(['id', 'name', 'slug', 'logo']);

        return Inertia::render('Brands/Index', [
            'brands' => $brands,
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

        $query = Gadget::with(['brand', 'category'])
            ->where('brand_id', $brand->id)
            ->when($request->sort === 'price_asc',  fn($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'popular',    fn($q) => $q->orderByDesc('views_count'))
            ->when(!$request->sort,                 fn($q) => $q->latest());

        return Inertia::render('Brands/Show', [
            'brand'   => $brand,
            'gadgets' => $query->paginate(\App\Support\PerPage::resolve($request, 20))->withQueryString(),
            'filters' => $request->only(['sort']),

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
