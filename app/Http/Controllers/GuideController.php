<?php

namespace App\Http\Controllers;

use App\Models\TechGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GuideController extends Controller
{
    public function index(Request $request)
    {
        $guides = TechGuide::where('is_published', true)
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()->paginate(12)->withQueryString();

        return Inertia::render('Guides/Index', [
            'guides'  => $guides,
            'filters' => $request->only(['search']),

            'seo' => [
                'title'       => 'Tech Buying Guides — Expert Advice for Smart Purchases',
                'description' => 'Find the best buying guides for smartphones, laptops, earbuds, and more. Our experts break down what to look for so you get the right product.',
                'canonical'   => route('guides.index'),
                'type'        => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $guide   = TechGuide::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $related = TechGuide::where('is_published', true)->where('id', '!=', $guide->id)->latest()->take(3)->get();
        $appUrl   = config('app.url');
        $desc     = Str::limit(strip_tags($guide->content), 155);
        $imgUrl   = $guide->thumbnail ? Storage::url($guide->thumbnail) : null;
        $absImg   = $imgUrl ? (Str::startsWith($imgUrl, 'http') ? $imgUrl : $appUrl . $imgUrl) : null;
        $canonical = route('guides.show', $guide->slug);

        return Inertia::render('Guides/Show', [
            'guide'   => $guide,
            'related' => $related,

            'seo' => [
                'title'        => $guide->title,
                'description'  => $desc,
                'image'        => $absImg,
                'canonical'    => $canonical,
                'type'         => 'article',
                'published_at' => $guide->created_at->toISOString(),
                'modified_at'  => $guide->updated_at->toISOString(),
                'json_ld'      => [
                    '@context'         => 'https://schema.org',
                    '@type'            => 'Article',
                    'headline'         => $guide->title,
                    'description'      => $desc,
                    'image'            => $absImg ? [$absImg] : [],
                    'datePublished'    => $guide->created_at->toISOString(),
                    'dateModified'     => $guide->updated_at->toISOString(),
                    'url'              => $canonical,
                    'author'           => ['@type' => 'Organization', 'name' => config('app.name'), 'url' => $appUrl],
                    'publisher'        => [
                        '@type' => 'Organization',
                        'name'  => config('app.name'),
                        'logo'  => ['@type' => 'ImageObject', 'url' => $appUrl . '/images/og-default.jpg'],
                    ],
                    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonical],
                ],
            ],
        ]);
    }
}
