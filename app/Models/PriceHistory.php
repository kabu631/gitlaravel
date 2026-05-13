<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $fillable = ['gadget_id', 'price', 'date'];

    protected $casts = ['date' => 'date', 'price' => 'decimal:2'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
}
