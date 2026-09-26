<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use App\Models\SeoRedirect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Route;

trait HasSeo
{
    public static function bootHasSeo(): void
    {
        static::deleting(fn ($model) => $model->seo()->delete());

        // Keep old links alive: a changed slug gets a permanent 301 to the new URL.
        static::updated(function ($model) {
            $route = $model->seoRouteName();
            $old   = $model->getOriginal('slug');

            if ($route && Route::has($route) && $model->wasChanged('slug') && filled($old) && filled($model->slug)) {
                SeoRedirect::record(route($route, $old, false), route($route, $model->slug, false));
            }
        });
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /** Named frontend route that shows this record by slug, if any. */
    public function seoRouteName(): ?string
    {
        return match (class_basename(static::class)) {
            'Gadget'      => 'gadgets.show',
            'Brand'       => 'brands.show',
            'BlogPost'    => 'blog.show',
            'NewsArticle' => 'news.show',
            'TechGuide'   => 'guides.show',
            'Review'      => 'reviews.show',
            default       => null,
        };
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->whereDoesntHave('seo', fn ($q) => $q->where('robots', 'like', 'noindex%'));
    }
}
