<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $articles = NewsArticle::where('is_published', true)
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest()->paginate(12)->withQueryString();

        $catLabel = $request->category ? ucwords(str_replace('-', ' ', $request->category)) . ' ' : '';

        $trendingGadgets = \App\Models\Gadget::with('brand')->where('is_trending', true)->latest()->take(5)->get();

        return Inertia::render('News/Index', [
            'articles' => $articles,
            'filters'  => $request->only(['category', 'search']),
            'trendingGadgets' => $trendingGadgets,

            'seo' => [
                'title'       => "{$catLabel}Tech News — Latest Updates from Nepal & World",
                'description' => "Stay updated with the latest {$catLabel}technology news, product launches, and industry updates. Curated by the Git Infosys editorial team.",
                'canonical'   => route('news.index'),
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
        $appUrl   = config('app.url');
        $desc     = Str::limit(strip_tags($article->content), 155);
        $imgUrl   = $article->thumbnail ? Storage::url($article->thumbnail) : null;
        $absImg   = $imgUrl ? (Str::startsWith($imgUrl, 'http') ? $imgUrl : $appUrl . $imgUrl) : null;
        $canonical = route('news.show', $article->slug);

        return Inertia::render('News/Show', [
            'article' => $article,
            'related' => $related,

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
