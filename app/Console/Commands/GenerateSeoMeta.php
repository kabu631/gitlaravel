<?php

namespace App\Console\Commands;

use App\Filament\Resources\BlogPostResource;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\NewsArticle;
use App\Models\PageContent;
use App\Models\Review;
use App\Models\SeoMeta;
use App\Models\TechGuide;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GenerateSeoMeta extends Command
{
    protected $signature = 'seo:generate {--force : Overwrite meta title/description/keywords that are already filled}';

    protected $description = 'Fill meta title, description and keywords for all content and listing pages from their existing content';

    private int $filled = 0;

    public function handle(): int
    {
        Gadget::with(['brand', 'category'])->each(fn (Gadget $g) => $this->apply($g,
            "{$g->name} Price in Nepal — Specs & Review",
            $this->text($g->description)
                ?: "Buy {$g->name} in Nepal at NPR " . number_format((float) $g->price) . ". Check full specifications, features, user reviews and the best price.",
            [$g->name, "{$g->name} price in nepal", "{$g->name} specs", $g->brand?->name, $g->category?->name, $g->category ? "{$g->category->name} price in nepal" : null],
        ));

        Brand::each(fn (Brand $b) => $this->apply($b,
            "{$b->name} Products — Prices & Reviews in Nepal",
            "Browse all {$b->name} smartphones, laptops and accessories available in Nepal. Compare specs, latest prices and read expert reviews.",
            [$b->name, "{$b->name} nepal", "{$b->name} price in nepal", "{$b->name} phones"],
        ));

        Category::each(fn (Category $c) => $this->apply($c,
            "{$c->name} Price in Nepal — Latest Models & Specs",
            "Compare the latest {$c->name} prices in Nepal. Filter by brand and budget, check full specifications and read honest reviews.",
            [$c->name, "{$c->name} price in nepal", "best {$c->name} nepal", "{$c->name} specs"],
        ));

        BlogPost::each(fn (BlogPost $p) => $this->apply($p,
            $p->title,
            $p->meta_description ?: ($p->excerpt ?: $this->text($p->content)),
            array_merge($p->tags ?? [], [BlogPostResource::CATEGORIES[$p->category] ?? $p->category, 'tech blog nepal']),
        ));

        NewsArticle::each(fn (NewsArticle $n) => $this->apply($n,
            $n->title,
            $n->meta_description ?: $this->text($n->content),
            array_merge($this->titleWords($n->title), [Str::headline((string) $n->category), 'tech news nepal']),
        ));

        TechGuide::each(fn (TechGuide $t) => $this->apply($t,
            $t->title,
            $this->text($t->content),
            array_merge($this->titleWords($t->title), [$t->type ? Str::headline($t->type) : 'buying guide', 'tech guide nepal']),
        ));

        Review::with('gadget.brand')->each(fn (Review $r) => $this->apply($r,
            $r->title,
            $this->text($r->verdict) ?: $this->text($r->content)
                ?: "Expert review of {$r->gadget?->name}: performance, camera, battery, pros, cons and final verdict.",
            [$r->gadget?->name, "{$r->gadget?->name} review", $r->gadget?->brand?->name, 'expert review nepal'],
        ));

        PageContent::each(fn (PageContent $p) => $this->apply($p,
            $p->heading ?: Str::headline($p->page),
            $p->meta_description ?: ($p->subheading ?: $this->text($p->body)),
            [config('app.name'), Str::headline($p->page), 'tech platform nepal'],
        ));

        foreach ($this->pageDefaults() as $route => [$title, $description, $keywords]) {
            $meta = SeoMeta::firstOrNew(['route_name' => $route]);
            $this->fill($meta, $title, $description, $keywords);
        }

        $this->info("SEO meta filled/updated for {$this->filled} records.");

        return self::SUCCESS;
    }

    private function apply(Model $model, ?string $title, ?string $description, array $keywords): void
    {
        $meta = $model->seo ?? $model->seo()->make();
        $this->fill($meta, $title, $description, $keywords);
    }

    private function fill(SeoMeta $meta, ?string $title, ?string $description, array $keywords): void
    {
        $force = $this->option('force');
        $changed = false;

        $values = [
            'meta_title'       => $this->shorten(trim((string) $title), 60),
            'meta_description' => Str::limit(trim((string) $description), 157),
            'meta_keywords'    => collect($keywords)->filter()->map(fn ($k) => Str::lower(trim($k)))->unique()->take(10)->implode(','),
        ];

        foreach ($values as $field => $value) {
            if ($value !== '' && ($force || blank($meta->{$field}))) {
                $meta->{$field} = $value;
                $changed = true;
            }
        }

        if ($changed) {
            $meta->save();
            $this->filled++;
        }
    }

    private function shorten(string $text, int $max): string
    {
        if (mb_strlen($text) <= $max) {
            return $text;
        }
        $cut = mb_substr($text, 0, $max);

        return rtrim(mb_substr($cut, 0, mb_strrpos($cut, ' ') ?: $max), ' —-:,|');
    }

    private function text(?string $html): string
    {
        $plain = strip_tags(preg_replace('/<[^>]+>/', ' $0 ', (string) $html));

        return trim(preg_replace('/\s+([.,:;!?])/', '$1', preg_replace('/\s+/', ' ', html_entity_decode($plain))));
    }

    private function titleWords(string $title): array
    {
        $stop = ['the', 'and', 'for', 'with', 'your', 'what', 'how', 'why', 'are', 'you', 'from', 'this', 'that', 'into', 'its', 'new'];

        return collect(preg_split('/[^\p{L}\p{N}]+/u', Str::lower($title)))
            ->filter(fn ($w) => mb_strlen($w) > 2 && ! in_array($w, $stop, true))
            ->take(5)->values()->all();
    }

    private function pageDefaults(): array
    {
        $site = config('app.name');

        return [
            'home'                => ["Nepal's #1 Tech Review, Gadget Prices & Comparison", "Discover the latest smartphones, laptops and accessories in Nepal with honest reviews, live price tracking and spec comparisons.", ['gadgets nepal', 'mobile price in nepal', 'laptop price in nepal', 'tech reviews nepal', $site]],
            'gadgets.index'       => ['Gadgets & Tech Products Price in Nepal', 'Browse smartphones, laptops and accessories with latest prices, full specs and reviews. Filter by brand, category and budget.', ['gadgets price in nepal', 'mobile price in nepal', 'laptop price in nepal', 'accessories nepal']],
            'brands.index'        => ['All Brands — Gadget Prices & Reviews in Nepal', 'Browse every smartphone, laptop and gadget brand available in Nepal with specs, prices and reviews.', ['phone brands nepal', 'laptop brands nepal', 'gadget brands']],
            'news.index'          => ['Tech News — Latest Updates from Nepal & World', 'Latest technology news, product launches, leaks and industry updates, curated by our editorial team.', ['tech news nepal', 'mobile launch nepal', 'gadget news', 'tech rumors']],
            'blog.index'          => ['Blog — Tech Tips, How-Tos & Stories', 'Practical tech tips, how-tos, deals and behind-the-scenes stories from our tech desk.', ['tech blog nepal', 'tech tips', 'how to', 'gadget deals nepal']],
            'reviews.index'       => ['Expert Tech Reviews — Honest Gadget Reviews in Nepal', 'In-depth expert reviews of smartphones, laptops and earbuds with pros, cons, ratings and verdicts.', ['gadget reviews nepal', 'phone review', 'laptop review', 'expert review']],
            'guides.index'        => ['Tech Guides — Expert Buying Advice', 'Buying guides and step-by-step tutorials for smartphones, laptops, earbuds and more.', ['buying guide nepal', 'best phone to buy', 'how to guide', 'tech guide']],
            'compare.index'       => ['Compare Phones & Laptops Side by Side', 'Compare specs, prices and features of smartphones and laptops side by side, with AI buying suggestions.', ['compare phones', 'phone comparison nepal', 'laptop comparison', 'specs comparison']],
            'pcbuilder.index'     => ['PC Builder — Build Your Custom PC in Nepal', 'Pick compatible CPU, GPU, RAM and storage and get a custom PC build recommendation within your budget in Nepal.', ['pc builder nepal', 'custom pc nepal', 'gaming pc build', 'pc parts price nepal']],
            'search.index'        => ['Search Products, Reviews & News', 'Search gadgets, reviews, news and buying guides across all categories.', ['search gadgets', 'find phone', 'tech search']],
            'pages.price-tracker' => ['Price Tracker — Gadget Price Drops in Nepal', 'Track the latest smartphone, laptop and gadget price drops in Nepal with price history and live market analytics.', ['price tracker nepal', 'price drop', 'mobile price history', 'gadget deals nepal']],
            'pages.tech-lab'      => ['Tech Lab — Customs Calculator, 5G Bands & Camera Shootouts', 'MDMS customs duty calculator, NTC/Ncell 5G band checker, blind camera shootouts and more tools for Nepal tech buyers.', ['customs calculator nepal', 'mdms nepal', '5g bands nepal', 'camera shootout']],
            'pages.about'         => ["About Us — Nepal's Trusted Tech Platform", "Learn about the team, mission and values behind Nepal's tech review, gadget comparison and price tracking platform.", ['about ' . Str::lower($site), 'tech platform nepal', 'gadget reviews nepal']],
            'pages.contact'       => ['Contact Us — Get in Touch', "Questions, review requests or partnership proposals? Contact our team and we'll get back to you shortly.", ['contact ' . Str::lower($site), 'tech support nepal', 'partnership']],
            'pages.services'      => ['Our Services — Reviews, Comparisons & Price Tracking', 'Gadget reviews, price comparison, buying guides, sponsored content and more for the Nepal tech community.', ['tech services nepal', 'gadget review service', 'sponsored review']],
            'pages.careers'       => ['Careers — Join Our Tech Team', 'Open positions for tech reviewers, writers and developers in Nepal. Join our team.', ['tech jobs nepal', 'careers', 'writer jobs nepal', 'developer jobs nepal']],
            'pages.terms'         => ['Terms & Conditions', 'Terms and conditions governing the use of our website, products and services.', ['terms and conditions']],
            'pages.privacy'       => ['Privacy Policy', 'How we collect, use and protect your personal information when you use our platform.', ['privacy policy']],
        ];
    }
}
