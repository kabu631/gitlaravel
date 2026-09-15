<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\UpcomingLaunch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->category;

        $articles = NewsArticle::where('is_published', true)
            ->when($category && $category !== 'all', function ($q) use ($category) {
                if ($category === 'rumors') {
                    $q->whereIn('category', ['rumors', 'technology']);
                } elseif ($category === 'technology' || $category === 'tech') {
                    $q->whereIn('category', ['technology', 'tech']);
                } elseif ($category === 'ai' || $category === 'ai-ml') {
                    $q->whereIn('category', ['ai', 'ai-ml']);
                } else {
                    $q->where('category', $category);
                }
            })
            ->when($request->search, fn($q) => $q->where(function ($sub) use ($request) {
                $sub->where('title', 'like', "%{$request->search}%")
                    ->orWhere('meta_description', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $catLabel = $category ? ucwords(str_replace('-', ' ', $category)) . ' ' : '';

        $trendingGadgets = \App\Models\Gadget::with('brand')->where('is_trending', true)->latest()->take(5)->get();

        $upcomingLaunches = UpcomingLaunch::active()->orderBy('sort_order')->get();

        $seoTitle = $category === 'rumors'
            ? 'Nepal Tech Rumors & Upcoming Flagship Releases — Git Infosys'
            : "{$catLabel}Tech News — Latest Updates from Nepal & World";

        $seoDesc = $category === 'rumors'
            ? 'Track leaked specifications, supply-chain rumors, expected launch dates, and estimated NPR prices for upcoming smartphones and laptops in Nepal.'
            : "Stay updated with the latest {$catLabel}technology news, product launches, and industry updates. Curated by the Git Infosys editorial team.";

        return Inertia::render('News/Index', [
            'articles'         => $articles,
            'filters'          => $request->only(['category', 'search']),
            'trendingGadgets'  => $trendingGadgets,
            'upcomingLaunches' => $upcomingLaunches,

            'seo' => [
                'title'       => $seoTitle,
                'description' => $seoDesc,
                'canonical'   => route('news.index', $category ? ['category' => $category] : []),
                'type'        => 'website',
            ],
        ]);
    }

    public function show(string $slug)
    {
        $article = NewsArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $article->increment('views_count');
        $related = NewsArticle::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest()->take(3)->get();

        $recentArticles = NewsArticle::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->latest()->take(5)->get(['id', 'title', 'slug', 'thumbnail', 'category', 'created_at']);

        $categories = NewsArticle::where('is_published', true)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        $trendingGadgets = \App\Models\Gadget::with('brand')
            ->where('is_trending', true)
            ->latest()->take(5)->get(['id', 'name', 'slug', 'image', 'price', 'brand_id']);
        $appUrl   = config('app.url');
        $desc     = Str::limit(strip_tags($article->content), 155);
        $imgUrl   = $article->thumbnail ? Storage::url($article->thumbnail) : null;
        $absImg   = $imgUrl ? (Str::startsWith($imgUrl, 'http') ? $imgUrl : $appUrl . $imgUrl) : null;
        $canonical = route('news.show', $article->slug);

        return Inertia::render('News/Show', [
            'article'         => $article,
            'related'         => $related,
            'recentArticles'  => $recentArticles,
            'categories'      => $categories,
            'trendingGadgets' => $trendingGadgets,

            'seo' => [
                'title'        => $article->title,
                'description'  => $desc,
                'image'        => $absImg,
                'canonical'    => $canonical,
                'type'         => 'article',
                'published_at' => $article->created_at->toISOString(),
                'modified_at'  => $article->updated_at->toISOString(),
                'json_ld'      => [
                    '@context'         => 'https://schema.org',
                    '@type'            => 'NewsArticle',
                    'headline'         => $article->title,
                    'description'      => $desc,
                    'image'            => $absImg ? [$absImg] : [],
                    'datePublished'    => $article->created_at->toISOString(),
                    'dateModified'     => $article->updated_at->toISOString(),
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
