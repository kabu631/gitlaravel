<?php

namespace App\Support;

use App\Models\Gadget;

/** Devices on sale today for less than their earlier listed price (never estimates or out-of-stock items). */
class PriceDrops
{
    /** The biggest drops, at most two per category so one shelf cannot fill the list. */
    public static function top(int $limit): array
    {
        return Gadget::with(['brand:id,name', 'category:id,slug'])
            ->where('release_status', Gadget::STATUS_AVAILABLE)
            ->where('price_is_estimate', false)
            ->whereNull('out_of_stock_at')
            ->whereNotNull('image')
            ->where('price', '>', 0)
            ->whereColumn('old_price', '>', 'price')
            ->orderByRaw('old_price - price DESC')
            ->get(['id', 'name', 'slug', 'image', 'price', 'old_price', 'brand_id', 'category_id'])
            ->groupBy('category_id')
            ->flatMap(fn ($group) => $group->take(2))
            ->sortByDesc(fn (Gadget $g) => $g->old_price - $g->price)
            ->take($limit)
            ->map(fn (Gadget $g) => [
                'id'           => $g->id,
                'name'         => $g->name,
                'slug'         => $g->slug,
                'image'        => $g->image,
                'price'        => (float) $g->price,
                'old_price'    => (float) $g->old_price,
                'brand'        => $g->brand?->name,
                'category'     => $g->category?->slug,
                'price_change' => (float) $g->price - (float) $g->old_price,
            ])
            ->values()
            ->all();
    }
}
