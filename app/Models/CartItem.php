<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'session_key', 'gadget_id', 'product_variant_id', 'quantity', 'unit_price', 'variant_info'];

    protected $casts = ['variant_info' => 'array', 'unit_price' => 'decimal:2'];

    public function gadget()         { return $this->belongsTo(Gadget::class); }
    public function user()           { return $this->belongsTo(User::class); }
    public function productVariant() { return $this->belongsTo(ProductVariant::class); }

    public function getSubtotalAttribute(): float
    {
        $price = $this->unit_price ?? $this->gadget->price;
        return $this->quantity * $price;
    }
}
