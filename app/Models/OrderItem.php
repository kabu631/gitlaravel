<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'gadget_id', 'product_variant_id', 'price', 'quantity', 'variant_info'];

    protected $casts = ['variant_info' => 'array', 'price' => 'decimal:2'];

    public function order()          { return $this->belongsTo(Order::class); }
    public function gadget()         { return $this->belongsTo(Gadget::class)->withDefault(); }
    public function productVariant() { return $this->belongsTo(ProductVariant::class); }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
