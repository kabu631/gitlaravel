<?php

namespace App\Support;

use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves the `seo` Inertia prop consumed by app.blade.php and SeoHead.vue.
 * Precedence (lowest → highest): global SEO settings, controller defaults,
 * Page SEO for the current route (listing/static pages only), the record's own SEO.
 */
class Seo
{
    public static function make(array $defaults = [], ?Model $model = null): array
    {
        $meta = ($model && method_exists($model, 'seo')
            ? ($model->relationLoaded('seo') ? $model->seo : $model->seo()->first())
            : null) ?? static::forCurrentRoute();

        $siteName = config('app.name', 'Git Infosys');
        $sep      = static::setting('seo_title_separator', '|');

        $title       = static::pick($meta?->meta_title, $defaults['title'] ?? null);
        $description = static::clean(static::pick(
            $meta?->meta_description,
            $defaults['description'] ?? null,
            static::setting('seo_default_description'),
        ));
        $keywords = static::pick($meta?->meta_keywords, $defaults['keywords'] ?? null, static::setting('seo_default_keywords'));
        $canonical = static::pick($meta?->canonical_url, $defaults['canonical'] ?? null, request()->url());
        $page = (int) request()->query('page', 1);
        if ($page > 1 && blank($meta?->canonical_url) && ! str_contains($canonical, 'page=')) {
            $canonical .= (str_contains($canonical, '?') ? '&' : '?') . "page={$page}";
        }
        $image = static::absoluteUrl(static::pick(
            $defaults['image'] ?? null,
            static::setting('seo_default_og_image'),
        )) ?? asset(file_exists(public_path('images/og-default.jpg')) ? 'images/og-default.jpg' : 'images/logo_dark.png');
        $type = static::pick($meta?->og_type, $defaults['type'] ?? null, 'website');

        $baseTitle = static::pick($title, static::setting('seo_default_title'));
        $fullTitle = match (true) {
            blank($baseTitle)                    => $siteName,
            Str::contains($baseTitle, $siteName) => $baseTitle,
            default                              => "{$baseTitle} {$sep} {$siteName}",
        };

        $ogTitle       = static::pick($meta?->og_title, $fullTitle);
        $ogDescription = static::clean(static::pick($meta?->og_description, $description));
        $ogImage       = static::absoluteUrl($meta?->og_image) ?? $image;

        $robots   = static::robots($meta, $defaults);
        $imageAlt = static::pick($meta?->image_alt, $defaults['image_alt'] ?? null, $title, $siteName);

        $jsonLd = static::structuredData($meta)
            ?? static::syncJsonLd($defaults['json_ld'] ?? null, [
                $defaults['description'] ?? null => $description,
                $defaults['image'] ?? null       => $ogImage,
                $defaults['title'] ?? null       => $title,
            ])
            ?? static::autoJsonLd($title ?: $baseTitle, $description, $canonical, $ogImage);

        return array_filter([
            'title'               => $title,
            'full_title'          => $fullTitle,
            'description'         => $description,
            'keywords'            => $keywords,
            'canonical'           => $canonical,
            'robots'              => $robots,
            'noindex'             => Str::startsWith($robots, 'noindex'),
            'image'               => $ogImage,
            'image_alt'           => $imageAlt,
            'type'                => $type,
            'og_title'            => $ogTitle,
            'og_description'      => $ogDescription,
            'og_image'            => $ogImage,
            'twitter_card'        => static::pick($meta?->twitter_card, static::setting('seo_twitter_card'), 'summary_large_image'),
            'twitter_site'        => static::setting('seo_twitter_site'),
            'twitter_title'       => static::pick($meta?->twitter_title, $ogTitle),
            'twitter_description' => static::clean(static::pick($meta?->twitter_description, $ogDescription)),
            'twitter_image'       => static::absoluteUrl($meta?->twitter_image) ?? $ogImage,
            'published_at'        => $defaults['published_at'] ?? null,
            'modified_at'         => $defaults['modified_at'] ?? null,
            'json_ld'             => $jsonLd,
        ], fn ($v) => $v !== null && $v !== '');
    }

    /** Replace controller-computed values inside JSON-LD with the admin-overridden ones. */
    private static function syncJsonLd(?array $node, array $replacements): ?array
    {
        if ($node === null) {
            return null;
        }
        $replacements = array_filter($replacements, fn ($to, $from) => filled($from) && filled($to) && $from !== $to, ARRAY_FILTER_USE_BOTH);

        array_walk_recursive($node, function (&$value, $key) use ($replacements) {
            if (is_string($value) && in_array($key, ['description', 'headline', 'name', 'image', 'url'], true) && isset($replacements[$value])) {
                $value = $replacements[$value];
            }
        });

        return $node;
    }

    /** Generic WebPage + breadcrumb schema for pages whose controller doesn't build its own. */
    private static function autoJsonLd(?string $title, ?string $description, string $url, string $image): array
    {
        $home  = rtrim(config('app.url'), '/') . '/';
        $route = request()->route()?->getName() ?? '';
        $type  = match (true) {
            $route === 'pages.about'           => 'AboutPage',
            $route === 'pages.contact'         => 'ContactPage',
            $route === 'search.index'          => 'SearchResultsPage',
            in_array($route, ['gadgets.index', 'brands.index', 'news.index', 'blog.index', 'reviews.index', 'guides.index'], true) => 'CollectionPage',
            default                            => 'WebPage',
        };
        $name = $title ?: config('app.name');

        return [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'              => $type,
                    '@id'                => $url . '#webpage',
                    'url'                => $url,
                    'name'               => $name,
                    'description'        => $description,
                    'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $image],
                    'inLanguage'         => str_replace('_', '-', app()->getLocale()),
                    'isPartOf'           => ['@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => config('app.name')],
                    'breadcrumb'         => ['@id' => $url . '#breadcrumb'],
                ],
                [
                    '@type'           => 'BreadcrumbList',
                    '@id'             => $url . '#breadcrumb',
                    'itemListElement' => array_values(array_filter([
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home],
                        $route !== 'home' ? ['@type' => 'ListItem', 'position' => 2, 'name' => $name, 'item' => $url] : null,
                    ])),
                ],
            ],
        ];
    }

    public static function for(?Model $model, array $defaults = []): array
    {
        return static::make($defaults, $model);
    }

    public static function forCurrentRoute(): ?SeoMeta
    {
        $name = request()->route()?->getName();

        return $name ? SeoMeta::where('route_name', $name)->first() : null;
    }

    private static function robots(?SeoMeta $meta, array $defaults): string
    {
        if (static::setting('seo_discourage_indexing')) {
            return 'noindex, nofollow';
        }

        $base = $meta?->robots
            ?? (! empty($defaults['noindex']) ? 'noindex, nofollow' : null)
            ?? static::setting('seo_robots_default', 'index, follow');

        return collect([$base])->merge($meta?->robots_directives ?? [])->filter()->implode(', ');
    }

    private static function structuredData(?SeoMeta $meta): ?array
    {
        if (blank($meta?->structured_data)) {
            return null;
        }

        $decoded = json_decode($meta->structured_data, true);

        return is_array($decoded) ? $decoded : null;
    }

    private static function absoluteUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        if (Str::startsWith($path, '/')) {
            return url($path);
        }

        return url(Storage::disk('public')->url($path));
    }

    private static function clean(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($text))));
    }

    private static function pick(...$values): mixed
    {
        foreach ($values as $v) {
            if (filled($v)) {
                return $v;
            }
        }

        return null;
    }

    private static function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}
