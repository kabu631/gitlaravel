<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->q ?? '');

        if (!$q) {
            return Inertia::render('Search/Index', [
                'query'    => '',
                'gadgets'  => [],
                'articles' => [],
                'reviews'  => [],
                'total'    => 0,
                'seo'      => [
                    'title'       => 'Search Products, Reviews & News',
                    'description' => 'Search Git Infosys for gadgets, reviews, news, and buying guides across all categories.',
                    'canonical'   => route('search.index'),
                    'noindex'     => true,
                ],
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
            'query'    => $q,
            'gadgets'  => $gadgets,
            'articles' => $articles,
            'reviews'  => $reviews,
            'total'    => $total,
            'seo'      => [
                'title'   => "Search results for \"{$q}\" — {$total} found",
                'noindex' => true,
            ],
        ]);
    }
}
