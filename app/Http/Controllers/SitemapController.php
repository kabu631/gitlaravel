<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\Review;
use App\Models\TechGuide;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect();

        // Static pages
        $static = [
            ['loc' => url('/'),                    'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => route('gadgets.index'),       'priority' => '0.9', 'freq' => 'daily'],
            ['loc' => route('news.index'),          'priority' => '0.8', 'freq' => 'daily'],
            ['loc' => route('reviews.index'),       'priority' => '0.8', 'freq' => 'weekly'],
            ['loc' => route('guides.index'),        'priority' => '0.8', 'freq' => 'weekly'],
            ['loc' => route('compare.index'),       'priority' => '0.6', 'freq' => 'monthly'],
            ['loc' => route('pcbuilder.index'),     'priority' => '0.6', 'freq' => 'monthly'],
            ['loc' => route('search.index'),        'priority' => '0.5', 'freq' => 'monthly'],
            ['loc' => route('pages.about'),         'priority' => '0.5', 'freq' => 'monthly'],
            ['loc' => route('pages.contact'),       'priority' => '0.4', 'freq' => 'monthly'],
            ['loc' => route('pages.services'),      'priority' => '0.5', 'freq' => 'monthly'],
        ];

        foreach ($static as $s) {
            $urls->push($s + ['lastmod' => now()->toDateString()]);
        }

        // Gadgets
        Gadget::select('slug', 'updated_at')->orderByDesc('updated_at')->each(function ($g) use ($urls) {
            $urls->push([
                'loc'      => route('gadgets.show', $g->slug),
                'lastmod'  => $g->updated_at->toDateString(),
                'priority' => '0.8',
                'freq'     => 'weekly',
            ]);
        });

        // News articles
        NewsArticle::select('slug', 'updated_at')->where('is_published', true)->orderByDesc('updated_at')->each(function ($a) use ($urls) {
            $urls->push([
                'loc'      => route('news.show', $a->slug),
                'lastmod'  => $a->updated_at->toDateString(),
                'priority' => '0.7',
                'freq'     => 'weekly',
            ]);
        });

        // Reviews
        Review::select('slug', 'updated_at')->where('is_published', true)->orderByDesc('updated_at')->each(function ($r) use ($urls) {
            $urls->push([
                'loc'      => route('reviews.show', $r->slug),
                'lastmod'  => $r->updated_at->toDateString(),
                'priority' => '0.7',
                'freq'     => 'monthly',
            ]);
        });

        // Tech guides
        TechGuide::select('slug', 'updated_at')->where('is_published', true)->orderByDesc('updated_at')->each(function ($g) use ($urls) {
            $urls->push([
                'loc'      => route('guides.show', $g->slug),
                'lastmod'  => $g->updated_at->toDateString(),
                'priority' => '0.7',
                'freq'     => 'monthly',
            ]);
        });

        // Brands
        Brand::select('slug', 'updated_at')->each(function ($b) use ($urls) {
            $urls->push([
                'loc'      => route('brands.show', $b->slug),
                'lastmod'  => $b->updated_at->toDateString(),
                'priority' => '0.6',
                'freq'     => 'weekly',
            ]);
        });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
