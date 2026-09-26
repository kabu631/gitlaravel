<?php

namespace App\Http\Controllers;

use App\Filament\Pages\SeoSettings;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PageContent;
use App\Models\Review;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\TechGuide;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect();

        // Listing & static pages (search results and legal pages are noindex, so they're left out).
        $static = [
            'home'                => ['1.0', 'daily'],
            'gadgets.index'       => ['0.9', 'daily'],
            'news.index'          => ['0.8', 'daily'],
            'blog.index'          => ['0.7', 'daily'],
            'reviews.index'       => ['0.8', 'weekly'],
            'guides.index'        => ['0.8', 'weekly'],
            'brands.index'        => ['0.6', 'weekly'],
            'pages.price-tracker' => ['0.7', 'daily'],
            'compare.index'       => ['0.6', 'monthly'],
            'pcbuilder.index'     => ['0.6', 'monthly'],
            'pages.tech-lab'      => ['0.5', 'monthly'],
            'pages.about'         => ['0.5', 'monthly'],
            'pages.services'      => ['0.5', 'monthly'],
            'pages.contact'       => ['0.4', 'monthly'],
            'pages.careers'       => ['0.3', 'monthly'],
        ];

        $noindexRoutes = SeoMeta::whereNotNull('route_name')->where('robots', 'like', 'noindex%')->pluck('route_name')
            ->merge(PageContent::whereHas('seo', fn ($q) => $q->where('robots', 'like', 'noindex%'))->pluck('page')->map(fn ($p) => "pages.{$p}"))
            ->all();

        foreach ($static as $name => [$priority, $freq]) {
            if (Route::has($name) && ! in_array($name, $noindexRoutes, true)) {
                $urls->push(['loc' => route($name), 'lastmod' => now()->toDateString(), 'priority' => $priority, 'freq' => $freq]);
            }
        }

        Category::indexable()->select('id', 'slug', 'updated_at')->each(fn ($c) => $urls->push([
            'loc' => route('gadgets.index', ['category' => $c->slug]), 'lastmod' => $c->updated_at?->toDateString(),
            'priority' => '0.8', 'freq' => 'daily',
        ]));

        Gadget::indexable()->select('id', 'name', 'slug', 'image', 'updated_at')->orderByDesc('updated_at')
            ->each(fn ($g) => $urls->push($this->entry(route('gadgets.show', $g->slug), $g, '0.8', 'weekly', $g->image, $g->name)));

        NewsArticle::indexable()->where('is_published', true)->select('id', 'title', 'slug', 'thumbnail', 'updated_at')->orderByDesc('updated_at')
            ->each(fn ($a) => $urls->push($this->entry(route('news.show', $a->slug), $a, '0.7', 'weekly', $a->thumbnail, $a->title)));

        BlogPost::published()->indexable()->select('id', 'title', 'slug', 'cover_image', 'updated_at')->orderByDesc('updated_at')
            ->each(fn ($b) => $urls->push($this->entry(route('blog.show', $b->slug), $b, '0.7', 'weekly', $b->cover_image, $b->title)));

        Review::indexable()->where('is_published', true)->select('id', 'title', 'slug', 'updated_at')->orderByDesc('updated_at')
            ->each(fn ($r) => $urls->push($this->entry(route('reviews.show', $r->slug), $r, '0.7', 'monthly')));

        TechGuide::indexable()->where('is_published', true)->select('id', 'title', 'slug', 'thumbnail', 'updated_at')->orderByDesc('updated_at')
            ->each(fn ($g) => $urls->push($this->entry(route('guides.show', $g->slug), $g, '0.7', 'monthly', $g->thumbnail, $g->title)));

        Brand::indexable()->select('id', 'name', 'slug', 'logo', 'updated_at')
            ->each(fn ($b) => $urls->push($this->entry(route('brands.show', $b->slug), $b, '0.6', 'weekly', $b->logo, "{$b->name} logo")));

        $xml = view('sitemap', ['urls' => $urls->unique('loc')->values()])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $body = SiteSetting::get('seo_discourage_indexing')
            ? "User-agent: *\nDisallow: /"
            : trim((string) (SiteSetting::get('seo_robots_txt') ?: SeoSettings::DEFAULT_ROBOTS_TXT));

        $body .= "\n\nSitemap: " . route('sitemap') . "\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    private function entry(string $loc, $model, string $priority, string $freq, ?string $image = null, ?string $caption = null): array
    {
        return [
            'loc'      => $loc,
            'lastmod'  => $model->updated_at?->toDateString(),
            'priority' => $priority,
            'freq'     => $freq,
            'image'    => $image ? (str_starts_with($image, 'http') ? $image : url(Storage::disk('public')->url($image))) : null,
            'caption'  => $caption,
        ];
    }
}
