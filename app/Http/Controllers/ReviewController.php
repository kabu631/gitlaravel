<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\Review;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $filter   = $request->filter;   // 'editors-choice'
        $category = $request->category; // e.g. 'mobile', 'laptop'

        $isEditorsChoice = $filter === 'editors-choice';

        $baseQuery = Review::with(['gadget.brand', 'author'])
            ->where('is_published', true)
            ->when($isEditorsChoice, fn($q) => $q->where('rating', '>=', 8))
            ->when($category, fn($q) => $q->whereHas('gadget', fn($gq) => $gq->whereHas('category', fn($cq) => $cq->where('slug', $category))));

        // Only show "featured" section when no special filter is active
        if (!$isEditorsChoice && !$category) {
            $featuredReviews = (clone $baseQuery)->orderByDesc('rating')->take(3)->get();
            $featuredIds     = $featuredReviews->pluck('id');
        } else {
            $featuredReviews = collect();
            $featuredIds     = collect();
        }

        $reviews = (clone $baseQuery)
            ->whereNotIn('id', $featuredIds)
            ->latest()
            ->paginate(\App\Support\PerPage::resolve($request, 10))
            ->withQueryString();

        $trendingGadgets = Gadget::with('brand')
            ->where('is_trending', true)
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'slug', 'image', 'price', 'brand_id']);

        $sidebarNews = NewsArticle::where('is_published', true)
            ->latest()
            ->take(4)
            ->get(['id', 'title', 'slug', 'thumbnail', 'category', 'created_at']);

        $seoTitle = $isEditorsChoice
            ? "Editor's Choice Reviews — Top-Rated Gadgets in Nepal"
            : 'Expert Tech Reviews — Honest Gadget Reviews in Nepal';

        return Inertia::render('Reviews/Index', [
            'featuredReviews' => $featuredReviews,
            'reviews'         => $reviews,
            'trendingGadgets' => $trendingGadgets,
            'sidebarNews'     => $sidebarNews,
            'filters'         => $request->only(['filter', 'category']),

            'seo' => [
                'title'       => $seoTitle,
                'description' => 'Read in-depth expert reviews of smartphones, laptops, earbuds, and more. Pros, cons, ratings, and verdicts to help you buy smart.',
                'canonical'   => route('reviews.index'),
                'type'        => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $review = Review::with(['gadget.brand', 'gadget.specs', 'author'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $trending = Gadget::with('brand')
            ->where('is_trending', true)
            ->take(5)->get();

        $appUrl   = config('app.url');
        $desc     = Str::limit(strip_tags($review->content ?? $review->verdict ?? ''), 155)
            ?: "Expert review of {$review->gadget->name} — full specs, pros, cons, and final verdict by the Git Infosys team.";
        $gadgetImg = $review->gadget->image ? Storage::url($review->gadget->image) : null;
        $absImg    = $gadgetImg ? (Str::startsWith($gadgetImg, 'http') ? $gadgetImg : $appUrl . $gadgetImg) : null;
        $canonical = route('reviews.show', $review->slug);

        return Inertia::render('Reviews/Show', [
            'review'   => $review,
            'pros'     => $review->getProsList(),
            'cons'     => $review->getConsList(),
            'trending' => $trending,

            'seo' => [
                'title'        => $review->title,
                'description'  => $desc,
                'image'        => $absImg,
                'canonical'    => $canonical,
                'type'         => 'article',
                'published_at' => $review->created_at->toISOString(),
                'modified_at'  => $review->updated_at->toISOString(),
                'json_ld'      => [
                    '@context'     => 'https://schema.org',
                    '@type'        => 'Review',
                    'name'         => $review->title,
                    'description'  => $desc,
                    'url'          => $canonical,
                    'datePublished'=> $review->created_at->toISOString(),
                    'author'       => ['@type' => 'Organization', 'name' => config('app.name'), 'url' => $appUrl],
                    'publisher'    => ['@type' => 'Organization', 'name' => config('app.name')],
                    'itemReviewed' => [
                        '@type'  => 'Product',
                        'name'   => $review->gadget->name,
                        'brand'  => ['@type' => 'Brand', 'name' => $review->gadget->brand?->name ?? ''],
                        'image'  => $absImg ? [$absImg] : [],
                        'offers' => ['@type' => 'Offer', 'price' => (string) $review->gadget->price, 'priceCurrency' => 'NPR'],
                    ],
                    'reviewRating' => [
                        '@type'       => 'Rating',
                        'ratingValue' => (string) $review->rating,
                        'bestRating'  => '10',
                        'worstRating' => '1',
                    ],
                ],
            ],
        ]);
    }

    public function react(Request $request, int $id)
    {
        $request->validate(['reaction' => 'required|in:happy,sad,love,like,funny,angry']);

        $review = Review::findOrFail($id);
        $col    = 'react_' . $request->reaction;
        $review->increment($col);

        return response()->json(['status' => 'success', 'new_count' => $review->$col]);
    }
}
