<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GadgetVariant extends Model
{
    protected $fillable = ['gadget_id', 'variant_type', 'value', 'price', 'stock', 'is_available'];

    protected $casts = ['is_available' => 'boolean', 'price' => 'decimal:2'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
}
