<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'gadget_id', 'sku', 'color', 'ram', 'storage', 'size',
        'price', 'discounted_price', 'stock_quantity', 'variant_image', 'is_active',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'is_active'        => 'boolean',
        'stock_quantity'   => 'integer',
    ];

    public function gadget(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Gadget::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discounted_price ?? $this->price);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    /** Human-readable label e.g. "Silver / 12GB / 256GB" */
    public function getLabelAttribute(): string
    {
        return implode(' / ', array_filter([
            $this->color,
            $this->ram ? $this->ram . ' RAM' : null,
            $this->storage,
            $this->size,
        ]));
    }
}
