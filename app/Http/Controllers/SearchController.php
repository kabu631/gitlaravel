<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PriceHistory;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->q ?? '');

        $trending = Gadget::with(['brand', 'category'])->where('is_trending', true)->latest()->take(8)->get();

        $trendingIds     = $trending->pluck('id');
        $priceHistoryMap = PriceHistory::whereIn('gadget_id', $trendingIds)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('gadget_id');

        $priceTracker = $trending->map(function ($g) use ($priceHistoryMap) {
            $history = $priceHistoryMap->get($g->id, collect())->sortByDesc('date');
            $cutoff  = now()->subDays(7);
            $latest  = $history->first();
            $old     = $history->first(fn($h) => $h->date->lte($cutoff)) ?? $history->last();

            return [
                'id'           => $g->id,
                'name'         => $g->name,
                'slug'         => $g->slug,
                'price'        => (float) $g->price,
                'brand'        => $g->brand?->name,
                'category'     => $g->category?->slug,
                'price_change' => ($latest && $old) ? round((float) $latest->price - (float) $old->price) : 0,
            ];
        })->values()->toArray();

        if (!$q) {
            return Inertia::render('Search/Index', [
                'query'        => '',
                'gadgets'      => [],
                'articles'     => [],
                'reviews'      => [],
                'total'        => 0,
                'priceTracker' => $priceTracker,
                'seo'          => Seo::make([
                    'title'       => 'Search Products, Reviews & News',
                    'description' => 'Search Git Infosys for gadgets, reviews, news, and buying guides across all categories.',
                    'canonical'   => route('search.index'),
                    'noindex'     => true,
                ]),
            ]);
        }

        $gadgets = Gadget::with('brand')
            ->where(fn($query) => $query
                ->where('name', 'like', "%{$q}%")
                ->orWhereHas('brand', fn($q2) => $q2->where('name', 'like', "%{$q}%")))
            ->take(12)->get();

        $articles = NewsArticle::where('is_published', true)
            ->where(fn($query) => $query
                ->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%"))
            ->take(9)->get();

        $reviews = Review::with(['gadget.brand'])
            ->where('is_published', true)
            ->where(fn($query) => $query
                ->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%"))
            ->take(6)->get();

        $total = $gadgets->count() + $articles->count() + $reviews->count();

        return Inertia::render('Search/Index', [
            'query'        => $q,
            'gadgets'      => $gadgets,
            'articles'     => $articles,
            'reviews'      => $reviews,
            'total'        => $total,
            'priceTracker' => $priceTracker,
            'seo'          => Seo::make([
                'title'   => "Search results for \"{$q}\" — {$total} found",
                'noindex' => true,
            ]),
        ]);
    }
}
