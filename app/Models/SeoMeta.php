<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'route_name',
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'robots', 'robots_directives',
        'og_title', 'og_description', 'og_image', 'og_type', 'image_alt',
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
        'structured_data',
    ];

    protected $casts = [
        'robots_directives' => 'array',
    ];

    /** Frontend routes whose SEO can be managed without a backing record. */
    public const PAGE_ROUTES = [
        'home'                => 'Home',
        'gadgets.index'       => 'Products listing',
        'brands.index'        => 'Brands listing',
        'news.index'          => 'News listing',
        'blog.index'          => 'Blog listing',
        'reviews.index'       => 'Reviews listing',
        'guides.index'        => 'Guides listing',
        'compare.index'       => 'Compare',
        'pcbuilder.index'     => 'PC Builder',
        'search.index'        => 'Search',
        'pages.price-tracker' => 'Price Tracker',
        'pages.tech-lab'      => 'Tech Lab',
        'pages.about'         => 'About',
        'pages.contact'       => 'Contact',
        'pages.services'      => 'Services',
        'pages.careers'       => 'Careers',
        'pages.terms'         => 'Terms & Conditions',
        'pages.privacy'       => 'Privacy Policy',
    ];

    public const ROBOTS_OPTIONS = [
        'index, follow'     => 'index, follow',
        'noindex, follow'   => 'noindex, follow',
        'index, nofollow'   => 'index, nofollow',
        'noindex, nofollow' => 'noindex, nofollow',
    ];

    public const ROBOTS_DIRECTIVES = [
        'noarchive'               => 'noarchive — no cached copy',
        'nosnippet'               => 'nosnippet — no text snippet',
        'noimageindex'            => 'noimageindex — don\'t index images',
        'notranslate'             => 'notranslate — no translation offer',
        'max-image-preview:large' => 'max-image-preview:large',
        'max-snippet:-1'          => 'max-snippet:-1',
        'max-video-preview:-1'    => 'max-video-preview:-1',
    ];

    public const TWITTER_CARDS = [
        'summary_large_image' => 'Summary with large image',
        'summary'             => 'Summary',
    ];

    public const OG_TYPES = [
        'website' => 'website',
        'article' => 'article',
        'product' => 'product',
        'profile' => 'profile',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
